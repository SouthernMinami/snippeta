<?php

namespace Database\Seeds;

require_once 'vendor/autoload.php';

use Database\AbstractSeeder;

class TestSeeder extends AbstractSeeder
{
    protected ?string $tableName = 'test';
    protected array $tableColumns = [
        [
            'data_type' => 'string',
            'column_name' => 'username'
        ],
        [
            'data_type' => 'string',
            'column_name' => 'email'
        ],
        [
            'data_type' => 'string',
            'column_name' => 'password'
        ],
    ];

    public function createRowData(): array
    {
        return [
            ...array_map(function () {
                return [
                    \Faker\Factory::create()->name,
                    \Faker\Factory::create()->email,
                    \Faker\Factory::create()->password
                ];

            }, range(0, 9))
        ];
    }
}
