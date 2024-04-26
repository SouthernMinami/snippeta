<?php

namespace Helpers;

use Exceptions\ReadAndParseEnvException;

class Settings
{
    private const ENV_PATH = '.env';

    public static function env(string $pair): string
    {
        $config = parse_ini_file(dirname(__FILE__, 2) . '/' . self::ENV_PATH);

        // envファイル読み込みに失敗した場合の例外
        if ($config === false) {
            throw new ReadAndParseEnvException();
        }

        return $config[$pair];
    }
}
