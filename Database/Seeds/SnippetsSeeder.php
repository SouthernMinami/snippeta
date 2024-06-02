<?php

namespace Database\Seeds;

require_once __DIR__ . '/../../vendor/autoload.php';

use Database\AbstractSeeder;
use Database\MySQLWrapper;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

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
        // $id = $this->getRowCount() + 1;

        // $input_json = file_get_contents('php://input');
        // $data = json_decode($input_json, true);
        // // ここが突破できない
        // if ($data === null) {
        //     return [
        //         [
        //             'Error: No input',
        //             'Error: No input',
        //             'Error: No input',
        //             'Error: No input',
        //             '2021-12-31'
        //         ]
        //     ];
        // }

        // if (json_last_error() !== JSON_ERROR_NONE) {
        //     error_log('JSON decode error');
        //     return [];
        // }

        // echo json_encode($data, JSON_PRETTY_PRINT);
        // $data['url'] .= $id;

        $data_array = explode(',', $dataStr);

        return [
            $data_array
        ];
    }
}