<?php

namespace Response\Render;

use Response\HTTPRenderer;

class HTMLRenderer implements HTTPRenderer
{
    private string $viewFile;
    private array $data;

    public function __construct(string $viewFile, array $data = [])
    {
        $this->viewFile = $viewFile;
        $this->data = $data;
    }

    // レスポンスヘッダーを返す関数
    public function getFields(): array
    {
        return [
            'Content-Type' => 'text/html; charset=UTF-8',
        ];
    }

    // レスポンスボディを返す関数
    public function getContent(): string
    {
        $viewPath = $this->getViewPath($this->viewFile);

        if (!file_exists($viewPath)) {
            throw new \Exception("View file {$viewPath} does not exist.");
        }

        // ob_start() ... output buffering すべての出力をバッファに保存する
        // このバッファはob_get_clean()で取得とクリアができる
        ob_start();
        // extract() ... キーを変数名、値を変数の値として配列を展開し、変数を作成する
        extract($this->data);

        require $viewPath;
        return $this->getHeader() . ob_get_clean() . $this->getFooter();
    }

    // ヘッダーレイアウトを返す関数
    private function getHeader(): string
    {
        ob_start();

        require $this->getViewPath('layout/header');
        return ob_get_clean();
    }

    // フッターレイアウトを返す関数
    private function getFooter(): string
    {
        ob_start();

        require $this->getViewPath('layout/footer');
        return ob_get_clean();
    }

    // ビューファイルのパスを返す関数
    private function getViewPath(string $path): string
    {
        return sprintf("%s/%s/Views/%s.php", __DIR__, '../..', $path);
    }
}