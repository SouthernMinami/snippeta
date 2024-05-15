<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class CreateSnippetsTable implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーション処理を書く
        return [
            'CREATE TABLE snippets (
                id INT AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(255) NOT NULL,
                language VARCHAR(255) NOT NULL,
                content TEXT NOT NULL,
                url VARCHAR(255) NOT NULL,
                expiration_date TIMESTAMP NOT NULL,
                created_at TIMESTAMP NOT NULL,
                updated_at TIMESTAMP NOT NULL
            )'
        ];
    }

    public function down(): array
    {
        // ロールバック処理を書く
        return [
            'DROP TABLE snippets'
        ];
    }
}