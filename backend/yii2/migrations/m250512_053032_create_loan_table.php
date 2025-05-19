<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%loan}}`.
 */
class m250512_053032_create_loan_table extends Migration {
    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->createTable('{{%loan}}', [
            // PostgreSQL依存
            'id' => 'UUID primary key default gen_random_uuid()',
            'sku_id' => 'UUID not null unique',
            'user_id' => 'UUID not null',
            'return_date' => $this->date()->notNull(),
            'created_at' => 'TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'updated_at' => 'TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP',
        ]);

        $this->addForeignKey(
            'fk-loan-sku_id',
            '{{%loan}}',
            'sku_id',
            '{{%book_sku}}',
            'id',
            'NO ACTION',
            'NO ACTION'
        );

        $this->addForeignKey(
            'fk-loan-user_id',
            '{{%loan}}',
            'user_id',
            '{{%user}}',
            'id',
            'NO ACTION',
            'NO ACTION'
        );

        if (YII_ENV_DEV) {
            // bookテーブルからIDを取得
            $bookSkus = (new \yii\db\Query())
                ->select('id')
                ->from('{{%book_skus}}')
                ->all();
            $users = (new \yii\db\Query())
                ->select('id')
                ->from('{{%user}}')
                ->all();

            // book_skuテーブルにデータを挿入
            foreach ($bookSkus as $book) {
                $this->insert('{{%book_sku}}', [
                    'book_id' => $book['id'],
                ]);
                $this->insert('{{%book_sku}}', [
                    'book_id' => $book['id'],
                ]);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropForeignKey(
            'fk-loan-sku_id',
            '{{%loan}}'
        );
        $this->dropForeignKey(
            'fk-loan-user_id',
            '{{%loan}}'
        );
        $this->dropTable('{{%loan}}');
    }
}
