<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

class User extends ActiveRecord {
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{user}}';
    }

    /**
     * @inheritdoc
     */
    public function fields() {
        return [
            'id',
            'name',
            'email',
            'password',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name', 'email', 'password'], 'required'],
            [['name', 'email', 'password'], 'string', 'max' => 255],
            ['email', 'email'],
            ['email', 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
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
}
