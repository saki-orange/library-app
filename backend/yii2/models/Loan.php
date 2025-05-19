<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

class Loan extends ActiveRecord {
  const STATUS_INACTIVE = 0;
  const STATUS_ACTIVE = 1;

  /**
   * @inheritdoc
   */
  public static function tableName() {
    return '{{loan}}';
  }

  /**
   * @inheritdoc
   */
  public function fields() {
    return [
      'id' => 'string',
      'sku_id' => 'string',
      'user_id' => 'string',
      'return_date' => 'string',
      'created_at' => 'string',
      'updated_at' => 'string',
    ];
  }

  /**
   * @inheritdoc
   */
  public function rules() {
    return [
      [['sku_id', 'user_id'], 'required'],
      // 同じ本を2人以上に貸出してはいけない
      [['sku_id'], 'unique'],
      // 貸出冊数制限
      [['user_id'], 'validateUserBookLoanLimit'],
      [['sku_id', 'user_id'], 'thamtech\uuid\validators\UuidValidator'],
      ['return_date', 'date', 'format' => 'php:Y-m-d'],
      ['sku_id', 'exist', 'targetClass' => BookSku::class, 'targetAttribute' => 'id'],
      ['user_id', 'exist', 'targetClass' => User::class, 'targetAttribute' => 'id'],
      // 予約中/取置処理中/取置中の本は貸出できない
      ['sku_id', function ($attribute, $params, $validator) {
        if (Reservation::find()->where([$attribute => $this->$attribute])->exists()) {
          $this->addError($attribute, '予約されている本は貸出できません。');
          return;
        }
        if (Hold::find()->where([$attribute => $this->$attribute])->exists()) {
          $this->addError($attribute, '取置処理中/取置中の本は貸出できません。');
          return;
        }
      }],
    ];
  }

  /**
   * @inheritdoc
   */
  public function behaviors() {
    return [
      /**
       * return_dateを現在の日付から貸出期間を加算した値で初期化
       */
      [
        'class' => \yii\behaviors\AttributeBehavior::class,
        'attributes' => [
          ActiveRecord::EVENT_BEFORE_INSERT => 'return_date',
        ],
        'value' => function ($event) {
          $loanPeriod = Yii::$app->params['library.loanPeriodDays'];
          return date('Y-m-d', strtotime("+{$loanPeriod} days"));
        },
      ],

      /**
       * updated_atの自動更新
       * @see https://www.yiiframework.com/doc/api/2.0/yii-behaviors-timestampbehavior
       */
      [
        'class' => TimestampBehavior::class,
        'updatedAtAttribute' => 'updated_at',
        // PostgreSQL依存
        'value' => new Expression('CURRENT_TIMESTAMP'),
      ],
    ];
  }

  /**
   * 貸出冊数制限
   * @param string $attribute
   * @param array $params
   */
  public function validateUserBookLoanLimit($attribute, $params) {
    $maxLoan = Yii::$app->params['library.maxLoanItems'];
    $count = self::find()
      ->where([$attribute => $this->$attribute])
      // パフォーマンス向上のため検索件数上限を指定
      ->limit($maxLoan + 1)
      ->count();

    if ($count > $maxLoan) {
      Yii::error("貸出冊数の上限を超えているユーザーがいます user_id={$this->$attribute} count={$count} maxLoan={$maxLoan}");
    }

    if ($count >= $maxLoan) {
      $this->addError($attribute, '貸出冊数の上限を超えるため、貸出できません。');
    }
  }


  /**
   * 貸出処理
   * @return bool
   * @throws \Throwable
   */
  public function lendBook() {
    $transaction = Yii::$app->db->beginTransaction();
    try {
      // ユーザーの取置中の本の場合は取置から削除する
      if ($hold_id = Hold::find()
        ->where(['user_id' => $this->user_id, 'sku_id' => $this->sku_id, 'status' => Hold::STATUS_READY])
        ->one()
      ) {
        if ($hold_id->delete() === false) {
          throw new \RuntimeException('取置中の本を削除できませんでした');
        }
      }

      // 貸出
      if (!$this->validate()) {
        return false;
      }
      if (!$this->save()) {
        throw new \RuntimeException('貸出処理に失敗しました');
      }

      $transaction->commit();
    } catch (\Throwable $e) {
      $transaction->rollBack();
      Yii::error("トランザクション失敗: " . $e->getMessage(), __METHOD__);
      throw $e;
    }

    return true;
  }


  /**
   * 貸出延長処理
   * @return bool
   * @throws \RuntimeException
   */
  public function extendReturnDate() {
    // 予約中の本は延長できない
    if (Reservation::find()->where(['sku_id' => $this->sku_id])->exists()) {
      $this->addError('sku_id', '予約が入っているため延長できません。');
      return false;
    }

    $loanPeriod = Yii::$app->params['library.loanPeriodDays'];
    $this->return_date = date('Y-m-d', strtotime($this->return_date . " +{$loanPeriod} days"));
    if (!$this->save()) {
      Yii::error("貸出延長に失敗: " . json_encode($this->getErrors()), __METHOD__);
      throw new \RuntimeException('貸出延長に失敗しました');
    }

    return true;
  }


  /**
   * 返却処理
   * @return bool
   * @throws \Throwable
   */
  public function returnBook() {
    $transaction = Yii::$app->db->beginTransaction();
    try {
      $skuId = $this->sku_id;
      if ($this->delete() === false) {
        throw new \RuntimeException('貸出中の本を削除できませんでした');
      }

      // 予約中の人がいる場合は予約日時が最も古いものを取置処理中にする
      if ($oldestReservation = Reservation::find()
        ->where(['sku_id' => $skuId])
        ->orderBy(['created_at' => SORT_ASC])
        ->one()
      ) {
        $userId = $oldestReservation->user_id;
        if ($oldestReservation->delete() === false) {
          throw new \RuntimeException('予約中の本を削除できませんでした');
        }

        $hold = new Hold();
        $hold->sku_id = $skuId;
        $hold->user_id = $userId;
        if (!$hold->save()) {
          throw new \RuntimeException('予約中の本を取置処理中にできませんでした');
        }
      }

      $transaction->commit();
    } catch (\Throwable $e) {
      $transaction->rollBack();
      Yii::error("トランザクション失敗: " . $e->getMessage(), __METHOD__);
      throw $e;
    }

    return true;
  }
}
