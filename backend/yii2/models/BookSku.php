<?php

namespace app\models;

use yii\db\ActiveRecord;

class BookSku extends ActiveRecord {
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{book_sku}}';
    }

    /**
     * @inheritdoc
     */
    public function fields() {
        return [
            'id',
            'book_id',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['book_id'], 'required'],
            [['book_id'], 'thamtech\uuid\validators\UuidValidator'],
            ['book_id', 'exist', 'targetClass' => Book::class, 'targetAttribute' => 'id'],
        ];
    }
}
