<?php

namespace Commands\Programs;

use Commands\AbstractCommand;
use Commands\Argument;
use Database\MySQLWrapper;
use Database\Seeder;

class Seed extends AbstractCommand
{
    protected static ?string $alias = 'seed';

    public static function getArgs(): array
    {
        return [
            (new Argument('data'))->description('Data to seed')->required(true)->allowAsShort(true)
        ];
    }

    public function execute(): int
    {
        $dataStr = $this->getArgValue('data');
        $this->runAllSeeds($dataStr);
        return 0;
    }

    function runAllSeeds(string $dataStr): void
    {
        $directory_path = __DIR__ . '/../../Database/Seeds';

        // Seedsディレクトリ内のファイルを取得
        $files = scandir($directory_path);

        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                // クラス名をファイル名から取得
                $class_name = 'Database\Seeds\\' . pathinfo($file, PATHINFO_FILENAME);

                // シードファイルを読み込む
                include_once $directory_path . '/' . $file;

                if (class_exists($class_name) && is_subclass_of($class_name, Seeder::class)) {
                    $seeder = new $class_name(new MySQLWrapper());
                    $seeder->seed($dataStr);
                } else
                    throw new \Exception('Seeder must be a class that subclasses the seeder interface');
            }
        }
    }
}