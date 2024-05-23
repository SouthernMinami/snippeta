<?php

namespace Database\Seeds;

require_once 'vendor/autoload.php';

use Database\AbstractSeeder;
use Database\MySQLWrapper;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

exec('php console seed');

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
            'data_type' => 'timestamp',
            'column_name' => 'expiration_date'
        ],
        [
            'data_type' => 'timestamp',
            'column_name' => 'created_at'
        ],
        [
            'data_type' => 'timestamp',
            'column_name' => 'updated_at'
        ],
    ];

    protected MySQLWrapper $db = new MySQLWrapper();
    protected array $data = $_POST;

    public function getRowCount(): int
    {
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
        $this->data['url'] .= $id;

        return [
            $this->data['title'],
            $this->data['language'],
            $this->data['content'],
            $this->data['url'],
            $this->data['expiration_date'],
            date('Y-m-d H:i:s'),
            date('Y-m-d H:i:s')
        ];

    }
}

