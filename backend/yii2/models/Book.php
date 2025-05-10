<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

class Book extends ActiveRecord {
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{book}}';
    }

    /**
     * @inheritdoc
     */
    public function fields() {
        return [
            'id',
            'title',
            'author',
            'publisher',
            'published_date',
            'isbn',
            'image_url'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['title', 'author', 'publisher', 'published_date', 'isbn'], 'required'],
            [['title', 'author', 'publisher', 'isbn', "image_url"], 'string', 'max' => 255],
            ['published_date', 'date'],
            ['isbn', 'unique'],
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
