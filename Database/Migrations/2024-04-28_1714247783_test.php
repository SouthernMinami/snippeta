<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class Test implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーション処理を書く
        return [
            "CREATE TABLE test (
                id BIGINT PRIMARY KEY AUTO_INCREMENT,
                username VARCHAR(255) NOT NULL,
            )"
        ];
    }

    public function down(): array
    {
        // ロールバック処理を書く
        return [
            "DROP TABLE test"
        ];
    }
}