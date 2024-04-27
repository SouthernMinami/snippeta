<?php

// コード生成のコマンド

namespace Commands\Programs;

use Commands\AbstractCommand;
use Commands\Argument;

class CodeGeneration extends AbstractCommand
{
    // 使用するコマンド名
    protected static ?string $alias = 'code-gen';
    protected static bool $requiredCommandValue = true;

    // 引数の割当
    public static function getArgs(): array
    {
        return [(new Argument('name'))->description('生成されるファイル名。')->required(false)];
    }

    public function execute(): int
    {
        $codeGenType = $this->getCommandValue();

        switch ($codeGenType) {
            case 'command':
                $this->generateCommand(readline("コマンド名を入力してください: "));
                break;
            case 'migration':
                $migrationName = $this->getArgValue('name');
                $this->log(sprintf("マイグレーションファイル %s を生成します。", $migrationName));
                $this->generateMigrationFile($migrationName);
            default:
                $this->log('Invalid code generation type.');
                break;
        }
        return 0;
    }

    // マイグレーションファイルを生成する関数
    private function generateMigrationFile(string $migrationName): void
    {
        // {YYYY-MM-DD}{UNIXTIME}{ClassName}.phpのフォーマットでマイグレーションファイルを生成
        $filename = sprintf(
            '%s_%s_%s.php',
            date('Y-m-d'),
            time(),
            $migrationName
        );

        $migrationContent = $this->getMigrationContent($migrationName);

        // 移行先
        $path = sprintf("%s/../../Database/Migrations/%s", __DIR__, $filename);

        file_put_contents($path, $migrationContent);
        $this->log(sprintf("マイグレーションファイル %s が作成されました。", $filename));
    }

    // マイグレーションファイルの内容を取得する関数
    private function getMigrationContent(string $migrationName): string
    {
        $className = $this->pascalCase($migrationName);

        return <<<MIGRATION
<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class {$className} implements SchemaMigration
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
MIGRATION;
    }

    // スネークケースをパスカルケースに変換する関数
    private function pascalCase(string $snakeCase): string
    {
        return str_replace(' ', '', ucwords(str_replace('_', ' ', $snakeCase)));
    }

    // 新しいコマンドファイルを生成してProgramsに追加する関数
    private function generateCommand(string $name): void
    {
        $capitalized_name = ucfirst($name);
        // 空白区切りで引数を取得
        $args = explode(' ', readline("コマンドで利用するオプションをスペース区切りで入力してください:"));
        $exec_code = readline("コマンドの実行コードを入力してください: ");

        $file_path = "Commands/Programs/" . $capitalized_name . ".php";
        $content = "<?php
            namespace Commands\Programs;
            
            use Commands\AbstractCommand;
            use Commands\Argument;

            class $capitalized_name extends AbstractCommand
            {
                protected static ?string \$alias = '$name';

                public static function getArgs(): array
                {

                    return [
                        " . implode(",\n", array_map(function ($arg) {
            return "(new Argument('$arg'))->description('')->required(false)->allowAsShort(true)";
        }, $args)) . "
                    ];
                }

                public function execute(): int
                {
                    $exec_code
                    return 0;
                }
            }
        ";

        file_put_contents($file_path, $content);
        // registry.phpに新しいコマンドを追加
        $registry_path = "Commands/registry.php";
        $registry_content = file_get_contents($registry_path);
        $registry_content = str_replace("return [", "return [\n    Commands\Programs\\$capitalized_name::class,", $registry_content);
        file_put_contents($registry_path, $registry_content);
    }
}
