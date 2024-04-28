<?php

// マイグレーションの実行、ロールバック、新しいスキーマインストールを行う
namespace Commands\Programs;

use Commands\AbstractCommand;
use Commands\Argument;
use Database\MySQLWrapper;

class Migrate extends AbstractCommand
{
    // 使用するコマンド名
    protected static ?string $alias = 'migrate';

    // 引数の割当
    public static function getArgs(): array
    {
        return [
            (new Argument('rollback'))->description('マイグレーションをロールバックします。ロールバック回数を指定することもできます。')->required(false)->allowAsShort(true),
            (new Argument('init'))->description('新しいマイグレーションテーブルを作成します。')->required(false)->allowAsShort(true),
        ];
    }

    public function execute(): int
    {
        $rollback = $this->getArgValue('rollback');

        if ($this->getArgValue('init')) {
            $this->createMigrationsTable();
        }

        if (!$rollback) {
            $this->log("マイグレーションを開始します。");
            $this->migrate();
        } else {
            $rollback = $rollback === true ? 1 : (int) $rollback;
            $this->log("マイグレーションをロールバックしています。");
            for ($i = 0; $i < $rollback; $i++) {
                $this->rollback();
            }
        }

        return 0;
    }

    private function createMigrationsTable(): void
    {
        $this->log("マイグレーションテーブルを作成します。");

        $mysqli = new MySQLWrapper();

        $result = $mysqli->query("
            CREATE TABLE IF NOT EXISTS migrations (
                id INT AUTO_INCREMENT PRIMARY KEY,
                filename VARCHAR(255) NOT NULL
            );
        ");

        if (!$result) {
            throw new \Exception("マイグレーションテーブルの作成に失敗しました。");
        }

        $this->log("マイグレーションテーブルの作成が完了しました。");
    }

    private function migrate(): void
    {
        $this->log("Migrating...");

        $last_migration = $this->getLastMigration();
        // 日付順にファイルをソート
        $all_migrations = $this->getAllMigrationFiles();
        $start_index = ($last_migration) ? array_search($last_migration, $all_migrations) + 1 : 0;

        for ($i = $start_index; $i < count($all_migrations); $i++) {
            $filename = $all_migrations[$i];

            // マイグレーションファイルを読み込む
            include_once ($filename);

            $migration_class = $this->getClassnameFromMigrationFilename($filename);
            $migration = new $migration_class();

            // マイグレーションを実行
            $this->log(sprintf("%sのマイグレーションを実行しています。", $migration_class));
            $queries = $migration->up();
            if (empty($queries)) {
                throw new \Exception("マイグレーションファイルのクエリが空です。");
            }

            // クエリを実行
            $this->processQueries($queries);
            $this->insertMigration($filename);
        }

        $this->log("マイグレーションが完了しました。\n");
    }

    // マイグレーションファイルからクラス名を取得する関数
    private function getClassnameFromMigrationFilename(string $filename): string
    {
        // 正規表現でクラス名を取得
        // / ... 正規表現の開始
        // () ... グループ
        // [] ... 文字クラス
        // ^ ... 特定の文字以外
        // + ... 直前の文字が1文字以上
        // ([^_]+) ... アンダースコア以外の文字が1文字以上
        if (preg_match('/([^_]+)\.php$/', $filename, $matches)) {
            return sprintf("%s\%s", 'Database\Migrations', $matches[1]);
        } else {
            throw new \Exception("クラス名の取得に失敗しました。");
        }
    }

    // 最後に行ったマイグレーションを取得する関数
    private function getLastMigration(): ?string
    {
        $mysqli = new MySQLWrapper();
        $query = "SELECT filename FROM migrations ORDER BY id DESC LIMIT 1";
        $result = $mysqli->query($query);

        // カラムが存在する場合は、ファイル名を返す
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['filename'];
        }
        return null;
    }

    // マイグレーションファイルを全て取得する関数
    private function getAllMigrationFiles(string $order = 'asc'): array
    {
        $directory = sprintf("%s/../../Database/Migrations", __DIR__);
        $this->log($directory);

        // glob ... ワイルドカード文字列と一致するファイルを取得
        $all_files = glob($directory . "/*.php");

        // デフォルトは降順でファイルをソート
        usort($all_files, function ($a, $b) use ($order) {
            $compare_result = strcmp($a, $b);
            return ($order === 'desc') ? -$compare_result : $compare_result;
        });

        return $all_files;
    }

    private function processQueries(array $queries): void
    {
        $mysqli = new MySQLWrapper();

        foreach ($queries as $query) {
            $result = $mysqli->query($query);
            if (!$result) {
                throw new \Exception("クエリの実行に失敗しました。");
            } else {
                $this->log("クエリの実行が完了しました。");
            }
        }
    }

    private function insertMigration(string $filename): void
    {
        $mysqli = new MySQLWrapper();

        $statement = $mysqli->prepare("INSERT INTO migrations (filename) VALUES (?)");
        if (!$statement) {
            throw new \Exception("クエリの準備に失敗しました。");
        }

        // 準備されたクエリに実際のファイル名を挿入
        $statement->bind_param('s', $filename);

        // ステートメントの実行
        if (!$statement->execute()) {
            throw new \Exception("クエリの実行に失敗しました。");
        }

        // ステートメントを閉じる
        $statement->close();
    }

    private function rollback(int $n = 1): void
    {
        $this->log("Rolling back {$n} migration(s)...");

        $last_migration = $this->getLastMigration();
        $all_migrations = $this->getAllMigrationFiles();

        $last_migration_index = array_search($last_migration, $all_migrations);

        if (!$last_migration_index) {
            $this->log("最後に実行したマイグレーションが見つかりませんでした。");
            return;
        }

        $count = 0;
        // 最後に実行したマイグレーションからn個分のマイグレーションをロールバック
        for ($i = $last_migration_index; $count < $n; $i--) {
            $filename = $all_migrations[$i];
            $this->log("Rolling back {$filename}...");

            include_once ($filename);

            $migration_class = $this->getClassnameFromMigrationFilename($filename);
            $migration = new $migration_class();

            $queries = $migration->down();
            if (empty($queries)) {
                throw new \Exception("マイグレーションファイルのクエリが空です。");
            }

            $this->processQueries($queries);
            $this->removeMigration($filename);
            $count++;
        }

        $this->log("ロールバックが完了しました。\n");
    }

    private function removeMigration(string $filename): void
    {
        $mysqli = new MySQLWrapper();
        $statement = $mysqli->prepare("DELETE FROM migrations WHERE filename = ?");

        if (!$statement) {
            throw new \Exception("クエリの準備に失敗しました。(" . $mysqli->errno . ")" . $mysqli->error);
        }

        $statement->bind_param('s', $filename);
        if (!$statement->execute()) {
            throw new \Exception("クエリの実行に失敗しました。(" . $mysqli->errno . ")" . $mysqli->error);
        }

        $statement->close();
    }
}