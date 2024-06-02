<?php

namespace Helpers;

require_once '../vendor/autoload.php';

use Helpers\ValidationHelper;

// $input_json = file_get_contents('php://input');

// // POSTリクエストを受け取ったときだけ、php console seedを実行
// if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
//     echo json_encode($input_json);

//     $output = [];
//     $return_var = null;
//     exec('php ../../console seed', $output, $return_var);
//     // コマンド実行結果を出力して確認
//     print_r($output);
//     echo "Return status: " . $return_var;
// }

// $data['title'],
//             $data['language'],
//             $data['content'],
//             $data['url'],
//             $data['expiration_date'],

$input = json_decode(file_get_contents('php://input'), true);

// $title = ValidationHelper::string($_POST['title'] !== null ? $_POST['title'] : 'untitled');
// $language = ValidationHelper::string($_POST['language'] ?? null);
// $content = ValidationHelper::string($_POST['content'] ?? null);
// $date = ValidationHelper::string(date('Y-m-d H:i:s'));
// $path = ValidationHelper::string(hash('md5', $date));
// $expiration_date = ValidationHelper::string($_POST['expiration_date'] ?? null);

$title = ValidationHelper::string($input['title'] !== null ? $input['title'] : 'untitled');
$language = ValidationHelper::string($input['language'] !== null ? $input['language'] : 'plaintext');
// 特殊文字エスケープのために、シングルクォートで囲む
$content = "'" . ValidationHelper::string($input['content'] ?? null) . "'";
$date = ValidationHelper::string(date('Y-m-d H:i:s'));
$path = ValidationHelper::string(hash('md5', $date));
$expirationDate = ValidationHelper::string($input['expirationDate'] ?? null);

$dataStr = implode(',', [$title, $language, $content, $path, $expirationDate]);

echo $dataStr;

$command = sprintf('php ../console seed --data %s', $dataStr);
exec($command, $output, $return_var);

// header('Location: /snippet/' . $path);