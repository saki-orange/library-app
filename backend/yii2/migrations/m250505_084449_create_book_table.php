<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%book}}`.
 */
class m250505_084449_create_book_table extends Migration {
    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->createTable('{{%book}}', [
            // PostgreSQL依存
            'id' => 'UUID primary key default gen_random_uuid()',
            'title' => $this->string()->notNull(),
            'author' => $this->string()->notNull(),
            'publisher' => $this->string()->notNull(),
            'published_date' => $this->date()->notNull(),
            'isbn' => $this->string()->unique(),
            'image_url' => $this->string(),
            'created_at' => 'TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'updated_at' => 'TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropTable('{{%book}}');
    }
}
