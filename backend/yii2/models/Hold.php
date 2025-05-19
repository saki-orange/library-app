<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

class Hold extends ActiveRecord {
  const STATUS_IN_PROGRESS = 'in_progress';
  const STATUS_READY = 'ready';

  /**
   * @inheritdoc
   */
  public static function tableName() {
    return '{{hold}}';
  }

  /**
   * @inheritdoc
   */
  public function fields() {
    return [
      'id' => 'string',
      'sku_id' => 'string',
      'user_id' => 'string',
      'status' => 'string',
      'expire_date' => 'string',
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
      // 同じ本を2人以上が取置してはいけない
      [['sku_id'], 'unique'],
      // 取置冊数制限
      [['user_id'], 'validateUserBookHoldLimit'],
      [['sku_id', 'user_id'], 'thamtech\uuid\validators\UuidValidator'],
      ['expire_date', 'date', 'format' => 'php:Y-m-d'],
      ['status', 'default', ['value' => Hold::STATUS_IN_PROGRESS]],
      ['status', 'in', ['range' => [Hold::STATUS_IN_PROGRESS, Hold::STATUS_READY]]],
      ['sku_id', 'exist', 'targetClass' => BookSku::class, 'targetAttribute' => 'id'],
      ['user_id', 'exist', 'targetClass' => User::class, 'targetAttribute' => 'id'],
      // 貸出中の本は取置できない
      ['sku_id', function ($attribute, $params, $validator) {
        if (Loan::find()->where([$attribute => $this->$attribute])->exists()) {
          $this->addError($attribute, '貸出中の本は取置できません。');
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
       * expired_dateを現在の日付から取置期限を加算した値で初期化
       */
      [
        'class' => \yii\behaviors\AttributeBehavior::class,
        'attributes' => [
          ActiveRecord::EVENT_BEFORE_INSERT => 'expire_date',
        ],
        'value' => function ($event) {
          $holdPeriod = Yii::$app->params['library.holdPeriodDays'];
          return date('Y-m-d', strtotime("+{$holdPeriod} days"));
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
   * 取置冊数制限
   * @param string $attribute
   * @param array $params
   */
  public function validateUserBookHoldLimit($attribute, $params) {
    $maxHold = Yii::$app->params['library.maxHoldLimit'];
    $count = self::find()
      ->where([$attribute => $this->$attribute])
      // パフォーマンス向上のため検索件数上限を指定
      ->limit($maxHold + 1)
      ->count();

    if ($count > $maxHold) {
      Yii::error("取置冊数の上限を超えているユーザーがいます user_id={$this->$attribute} count={$count} maxHold={$maxHold}");
    }

    if ($count >= $maxHold) {
      $this->addError($attribute, '取置冊数の上限を超えるため、貸出できません。');
    }
  }
}
