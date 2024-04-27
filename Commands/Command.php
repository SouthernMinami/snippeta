<?php

// すべてのコマンドが持っているメソッドを定義するインタフェース

namespace Commands;

interface Command
{
    // コマンドのエイリアスを取得
    public static function getAlias(): string;
    // コマンドの引数の配列を取得
    /** @return Argument[] */
    public static function getArgs(): array;
    // コマンドに関するヘルプを取得
    public static function getHelp(): string;
    // 値が必要かどうかを取得
    public static function isCommandValueRequired(): bool;

    // 引数の値を取得
    /** @return bool | string */
    public function getArgValue(string $arg): bool|string;
    // コマンドを実行
    public function execute(): int;
}