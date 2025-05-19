<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%book_sku}}`.
 */
class m250510_083755_create_book_sku_table extends Migration {
    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->createTable('{{%book_sku}}', [
            // PostgreSQL依存
            'id' => 'UUID primary key default gen_random_uuid()',
            'book_id' => 'UUID not null',
            'created_at' => 'TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP',
        ]);

        $this->addForeignKey(
            'fk-book_sku-book_id',
            '{{%book_sku}}',
            'book_id',
            '{{%book}}',
            'id',
            'NO ACTION',
            'NO ACTION'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropForeignKey(
            'fk-book_sku-book_id',
            '{{%book_sku}}'
        );
        $this->dropTable('{{%book_sku}}');
    }
}
