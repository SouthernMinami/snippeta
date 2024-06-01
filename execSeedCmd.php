<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$input_json = file_get_contents('php://input');

// POSTリクエストを受け取ったときだけ、php console seedを実行
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    echo json_encode($input_json);

    $output = [];
    $return_var = null;
    exec('php ../../console seed', $output, $return_var);
    // コマンド実行結果を出力して確認
    print_r($output);
    echo "Return status: " . $return_var;
}