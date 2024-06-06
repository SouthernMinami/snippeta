<?php

namespace Helpers;

use \Database\MySQLWrapper;

class DatabaseHelper
{
    public static function getSnippet(string $path): array
    {
        $db = new MySQLWrapper();

        $query = "SELECT * FROM snippets WHERE path = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param('s', $path);
        $stmt->execute();
        $result = $stmt->get_result();
        $snippetInfo = $result->fetch_assoc();

        if (!$snippetInfo)
            throw new \InvalidArgumentException(sprintf('pathが %s のデータは見つかりませんでした。', $path));

        return $snippetInfo;
    }
}