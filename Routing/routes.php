<?php

use Response\HTTPRenderer;
use Response\Render\HTMLRenderer;
use Response\Render\JSONRenderer;
use Helpers\ValidationHelper;
use Helpers\DatabaseHelper;

return [
    'new' => function (): HTMLRenderer {
        return new HTMLRenderer('new');
    },
    'snippet' => function (): HTMLRenderer {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        // /snippet/以下のパスを取得
        $hash = ValidationHelper::string(ltrim($path, '/snippet/'));
        $snippetInfo = DatabaseHelper::getSnippet($hash);

        return new HTMLRenderer('snippet', ['snippet' => $snippetInfo]);
    },
    'shared_snippets' => function (): HTMLRenderer {
        $snippets = DatabaseHelper::getSnippets();

        return new HTMLRenderer('shared_snippets', ['snippets' => $snippets]);
    },
];