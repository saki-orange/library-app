<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Reservation extends ActiveRecord {
  const STATUS_INACTIVE = 0;
  const STATUS_ACTIVE = 1;

  /**
   * @inheritdoc
   */
  public static function tableName() {
    return '{{reservation}}';
  }

  /**
   * @inheritdoc
   */
  public function fields() {
    return [
      'id' => 'string',
      'sku_id' => 'string',
      'user_id' => 'string',
      'created_at' => 'string',
    ];
  }

  /**
   * @inheritdoc
   */
  public function rules() {
    return [
      [['sku_id', 'user_id'], 'required'],
      // 同じ人が同じ本を2冊以上予約することはできない
      ['sku_id', 'unique', 'targetAttribute' => ['sku_id', 'user_id']],
      // 予約冊数制限
      [['user_id'], 'validateUserBookReservationLimit'],
      [['sku_id', 'user_id'], 'thamtech\uuid\validators\UuidValidator'],
      ['sku_id', 'exist', 'targetClass' => BookSku::class, 'targetAttribute' => 'id'],
      ['user_id', 'exist', 'targetClass' => User::class, 'targetAttribute' => 'id'],
      // 在庫ありの本は予約できない
      ['sku_id', function ($attribute, $params, $validator) {
        if (Loan::find()->where([$attribute => $this->$attribute])->exists()) {
          // 貸出中は予約できる
          return;
        }
        if (Hold::find()->where([$attribute => $this->$attribute])->exists()) {
          // 取置処理中/取置中は予約できる
          return;
        }
        $this->addError($attribute, '在庫ありの本は予約できません。');
      }],
    ];
  }

  /**
   * @inheritdoc
   */
  public function behaviors() {
    return [];
  }

  public function validateUserBookReservationLimit($attribute, $params) {
    $maxReserve = Yii::$app->params['library.maxReserveItems'];
    $count = self::find()
      ->where([$attribute => $this->$attribute])
      // パフォーマンス向上のため検索件数上限を指定
      ->limit($maxReserve + 1)
      ->count();

    if ($count > $maxReserve) {
      Yii::error("予約冊数の上限を超えているユーザーがいます user_id={$this->$attribute} count={$count} maxReserve={$maxReserve}");
    }

    if ($count >= $maxReserve) {
      $this->addError($attribute, '予約冊数の上限を超えるため、予約できません。');
    }
  }
}
