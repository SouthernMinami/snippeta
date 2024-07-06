<?php

namespace Helpers;

require_once '../vendor/autoload.php';

use Helpers\ValidationHelper;

$input = json_decode(file_get_contents('php://input'), true);

$title = ValidationHelper::string($input['title'] !== null ? $input['title'] : 'untitled');
$language = ValidationHelper::string($input['language'] !== null ? $input['language'] : 'plaintext');
// 特殊文字エスケープのために、シングルクォートで囲む
$content = "'" . ValidationHelper::code($input['content'] ?? null) . "'";
$date = ValidationHelper::string(date('Y-m-d H:i:s'));
$path = ValidationHelper::string(hash('md5', $date));
$expirationDate = ValidationHelper::string($input['expirationDate'] ?? null);

$dataStr = implode(',', [$title, $language, $content, $path, $expirationDate]);

$command = sprintf('php ../console seed --data %s', $dataStr);
exec($command, $output, $return_var);

if ($return_var !== 0) {
    throw new \Exception('Failed to seed snippet data.');
}