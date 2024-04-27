<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class Test implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーション処理を書く
        return [];
    }

    public function down(): array
    {
        // ロールバック処理を書く
        return [];
    }
}