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

        if (YII_ENV_DEV) {
            $this->batchInsert('{{%book}}', ['title', 'author', 'publisher', 'published_date', 'isbn', 'image_url'], [[
                'Sample Book 1',
                'Author 1',
                'Publisher 1',
                '2023-01-01',
                '978-3-16-148410-0',
                '/upload/book/sample1.jpg',
            ], [
                'Sample Book 2',
                'Author 2',
                'Publisher 2',
                '2023-02-01',
                '978-3-16-148410-1',
                '/upload/book/sample2.jpg',
            ], [
                'Sample Book 3',
                'Author 3',
                'Publisher 3',
                '2023-03-01',
                '978-3-16-148410-2',
                '/upload/book/sample3.jpg',
            ]]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropTable('{{%book}}');
    }
}
