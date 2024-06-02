<?php

namespace Database;

interface Seeder
{
    public function seed(string $dataStr): void;

    public function createRowData(string $dataStr): array;
}
