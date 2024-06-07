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
    public static function getSnippets(): array
    {
        $db = new MySQLWrapper();

        $query = 'SELECT * FROM snippets';
        $stmt = $db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        $i = 0;
        $snippets = [];
        while ($row = $result->fetch_assoc()) {
            $snippets[$i] = [
                'title' => $row['title'],
                'language' => $row['language'],
                'content' => $row['content'],
                'path' => $row['path'],
                'expiration_date' => $row['expiration_date'],
                'created_at' => $row['created_at'],
                'updated_at' => $row['updated_at']
            ];
            $i++;
        }

        return $snippets;
    }
}