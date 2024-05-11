<?php

spl_autoload_extensions('.php');
spl_autoload_register(function ($class) {
    $namespace = explode('\\', $class);
    $file = __DIR__ . '/' . implode('/', $namespace) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

$DEBUG = true;

// ルーティング
$routes = include ('Routing/routes.php');

// リクエストURLからパスの部分を取得
// 例: /new -> new
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = ltrim($path, '/');

// デフォルトでnewページを表示
if ($path === '') {
    header('Location: /new');
    exit();
}

// ルートパスの一致を確認
if (isset($routes[$path])) {
    // ルートパスをキーとして、コールバック関数を取得
    $renderer = $routes[$path]();

    try {
        // ヘッダーフィールドを設定
        foreach ($renderer->getFields() as $name => $value) {
            // ヘッダーに設定する値を無害なものにサニタイズ
            // FILTER_SANITIZE_STRING ... 文字列に変換
            $sanitized_value = filter_var($value, FILTER_SANITIZE_STRING);

            // サニタイズされた値がもとの値と一致する場合、ヘッダーに設定
            if ($sanitized_value === $value) {
                header("{$name}: {$value}");
            } else {
                // 一致しない場合、ログに記録するか処理する
                // エラー処理によっては例外をスローするか、デフォルトのまま続行
                http_response_code(500);
                if ($DEBUG) {
                    print ("Failed setting header - original value: '{$value}', sanitized value: '{$sanitized_value}'");
                }
                exit();
            }
        }

        print ($renderer->getContent());
    } catch (Exception $e) {
        http_response_code(500);
        print ("Internal error, please contact the admin. <br>");

        if ($DEBUG) {
            print ($e->getMessage());
        }
    }
} else {
    // 一致するルートがない場合、404 Not Found
    http_response_code(404);
    print ("404 Not Found: The requested URL was not found on this server.");
}