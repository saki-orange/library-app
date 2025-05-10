<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m250505_071716_create_user_table extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->createTable('{{%user}}', [
            // PostgreSQL依存
            'id' => 'UUID primary key default gen_random_uuid()',
            'name' => $this->string()->notNull(),
            'email' => $this->string()->notNull()->unique(),
            'password' => $this->string()->notNull(),
            'created_at' => 'TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'updated_at' => 'TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP'
        ]);

        if (YII_ENV_DEV) {
            $this->batchInsert('{{%user}}', ['name', 'email', 'password'], [[
                'test1',
                'test1@example.com',
                password_hash('test1', PASSWORD_BCRYPT),
            ], [
                'test2',
                'test2@example.com',
                password_hash('test2', PASSWORD_BCRYPT),
            ], [
                'test3',
                'test3@example.com',
                password_hash('test3', PASSWORD_BCRYPT),
            ]]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropTable('{{%user}}');
    }
}
