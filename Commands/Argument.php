<?php

// コマンドが使える引数を定義する
// 必要に応じてオプション引数も追加

namespace Commands;

class Argument
{
    private string $arg;
    private string $description = '';
    private bool $required = true;
    private bool $allowAsShort = false;

    public function __construct(string $arg)
    {
        $this->arg = $arg;
    }

    public function getArg(): string
    {
        return $this->arg;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    // descriptionを設定し、引数自身を返す
    public function description(string $description): Argument
    {
        $this->description = $description;
        return $this;
    }

    // 引数が必須かどうかを返す
    public function isRequired(): bool
    {
        return $this->required;
    }

    // 引数が必須かどうかを設定し、引数自身を返す
    public function required(bool $required): Argument
    {
        $this->required = $required;
        return $this;
    }

    // 短縮形式の引数を許可するかどうかを返す
    public function isShortAllowed(): bool
    {
        return $this->allowAsShort;
    }

    // 短縮形式の引数を許可するかどうかを設定し、引数自身を返す
    public function allowAsShort(bool $allowAsShort): Argument
    {
        $this->allowAsShort = $allowAsShort;
        return $this;
    }
}