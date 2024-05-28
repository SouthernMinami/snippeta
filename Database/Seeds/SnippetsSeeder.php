<?php

namespace Database\Seeds;

require_once __DIR__ . '/../../vendor/autoload.php';

use Database\AbstractSeeder;
use Database\MySQLWrapper;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

// POSTリクエストを受け取った初回だけ、php console seedを実行
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $output = [];
    $return_var = null;
    exec('php ../../console seed', $output, $return_var);
    // コマンド実行結果を出力して確認
    print_r($output);
    echo "Return status: " . $return_var;
}

class SnippetsSeeder extends AbstractSeeder
{
    protected ?string $tableName = 'snippets';
    protected array $tableColumns = [
        [
            'data_type' => 'string',
            'column_name' => 'title'
        ],
        [
            'data_type' => 'string',
            'column_name' => 'language'
        ],
        [
            'data_type' => 'string',
            'column_name' => 'content'
        ],
        [
            'data_type' => 'string',
            'column_name' => 'url'
        ],
        [
            'data_type' => 'string',
            'column_name' => 'expiration_date'
        ]
    ];

    protected MySQLWrapper $db;
    protected string $data;

    public function getRowCount(): int
    {
        $this->db = new MySQLWrapper();

        $sql = 'SELECT COUNT(*) FROM snippets';
        $result = $this->db->query($sql);
        if (!$result) {
            return 0;
        }

        return $result->fetch_row()[0];
    }

    public function createRowData(): array
    {
        $id = $this->getRowCount() + 1;
        // ここが突破できない
        $input = file_get_contents('php://input') ?: null;
        if ($input === null) {
            return [
                [
                    'Error: No input',
                    'Error: No input',
                    'Error: No input',
                    'Error: No input',
                    '2021-12-31'
                ]
            ];
        }
        $this->data = json_decode($input, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log('JSON decode error');
            return [];
        }

        echo json_encode($this->data, JSON_PRETTY_PRINT);
        $this->data['url'] .= $id;

        return [
            $this->data['title'],
            $this->data['language'],
            $this->data['content'],
            $this->data['url'],
            $this->data['expiration_date'],
        ];

    }
}

