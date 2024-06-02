<?php

// このクラスを拡張してコマンドを作成する
// 子クラスに stdout に出力する log() メソッドや引数オプションを取得する方法などのヘルパーメソッドを含む
// シェルから渡された引数を解析し、引数のマップを作成するのが目的

namespace Commands;

use Exception;

abstract class AbstractCommand implements Command
{
    protected ?string $value;
    protected array $argsMap = [];
    protected static ?string $alias = null;

    protected static bool $commandValueRequired = false;

    /**
     * @throws Exception
     */

    public function __construct()
    {
        $this->setUpArgsMap();
    }

    // シェルから引数を読み込み、引数のハッシュマップをセットアップする
    private function setUpArgsMap(): void
    {
        // グローバルスコープの引数の配列を取得
        $args = $GLOBALS['argv'];
        // エイリアスのインデックスを検索
        $startIndex = array_search($this->getAlias(), $args); // argsの2番目：コマンド名にあたる

        // エイリアスが見つからない場合は例外をスロー
        // それ以外の場合は、エイリアスのインデックスをインクリメント
        if ($startIndex === false)
            throw new Exception(sprintf("%sというエイリアスが見つかりませんでした。", $this->getAlias()));
        else
            $startIndex++; // argsの3番目

        $shellArgs = [];

        // コマンド名の次に引数が存在しないか、次の引数がオプション(-)の場合で、
        // コマンド名の次にオプションでない値が必須な場合は例外をスロー
        // それ以外の場合は、エイリアスの次の引数を引数マップに追加し、インデックスをインクリメント
        if (!isset($args[$startIndex]) || $args[$startIndex][0] === '-') {
            if ($this->isCommandValueRequired()) {
                throw new Exception(sprintf("%sコマンドを実行するには値を入力してください。", $this->getAlias()));
            }
        } else {
            $this->argsMap[$this->getAlias()] = $args[$startIndex];
            $startIndex++;
        }

        // すべての引数を$argsに格納
        for ($i = $startIndex; $i < count($args); $i++) {
            $arg = $args[$i];

            // ハイフンがある場合、ハイフンをキーとして扱う
            if ($arg[0] . $arg[1] === '--')
                $key = substr($arg, 2);
            else if ($arg[0] === '-')
                $key = substr($arg, 1);
            else
                throw new Exception('オプションは-か--で始まる必要があります。');

            $shellArgs[$key] = true;

            // 次のargsエントリがオプション(-)でない場合は引数値として扱う
            if (isset($args[$i + 1]) && $args[$i + 1] !== '-') {
                $shellArgs[$key] = $args[$i + 1]; // '--' => $args[$i + 1]
                $i++;
            }
        }

        // コマンドの引数マップを設定
        foreach ($this->getArgs() as $arg) {
            $argString = $arg->getArg();
            $value = null;

            if ($arg->isShortAllowed() && isset($shellArgs[$argString[0]]))
                $value = $shellArgs[$argString[0]];
            else if (isset($shellArgs[$argString]))
                $value = $shellArgs[$argString];

            if ($value === null) {
                if ($arg->isRequired())
                    throw new Exception(sprintf("必要な引数%sが見つかりませんでした。", $argString));
                else
                    $this->argsMap[$argString] = false;
            } else
                $this->argsMap[$argString] = $value;
        }

        // マップをログに出力
        $this->log(json_encode($this->argsMap));
    }

    public static function getHelp(): string
    {
        $helpString = "Command: " . static::getAlias() . (static::isCommandValueRequired() ? " {value}" : "") . PHP_EOL;

        $args = static::getArgs();
        if (empty($args))
            return $helpString;

        $helpString .= "Arguments: " . PHP_EOL;

        foreach ($args as $arg) {
            $helpString .= " --" . $arg->getArg();

            if ($arg->isShortAllowed()) {
                $helpString .= " (-" . $arg->getArg()[0] . ")";
            }
            $helpString .= ": " . $arg->isRequired() ? " (Required)" : " (Optional)";
            $helpString .= PHP_EOL;
        }

        return $helpString;
    }

    public static function getAlias(): string
    {
        // エイリアスが設定されてない場合はクラス名を返す
        return static::$alias != null ? static::$alias : static::class;
    }

    public static function isCommandValueRequired(): bool
    {
        return static::$commandValueRequired;
    }

    public function getCommandValue(): string
    {
        return $this->argsMap[static::getAlias()] ?? "";
    }

    public function getArgValue(string $arg): bool|string
    {
        return $this->argsMap[$arg];
    }

    protected function log(string $info): void
    {
        fwrite(STDOUT, $info . PHP_EOL);
    }

    /** @return Argument[] */
    public abstract static function getArgs(): array;
    public abstract function execute(): int;
}