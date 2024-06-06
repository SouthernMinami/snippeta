<?php

namespace Database\Seeds;

require_once __DIR__ . '/../../vendor/autoload.php';

use Database\AbstractSeeder;
use Database\MySQLWrapper;

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
            'column_name' => 'path'
        ],
        [
            'data_type' => 'string',
            'column_name' => 'expiration_date'
        ]
    ];

    protected MySQLWrapper $db;

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

    public function createRowData(string $dataStr): array
    {
        $data_array = explode(',', $dataStr);

        return [
            $data_array
        ];
    }
}