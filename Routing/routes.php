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
    '404' => function (): HTMLRenderer {
        return new HTMLRenderer('404');
    },
    'api/snippet' => function (): JSONRenderer {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $hash = ValidationHelper::string(ltrim($path, '/api/snippet/'));
        $snippetInfo = DatabaseHelper::getSnippet($hash);

        return new JSONRenderer($snippetInfo);
    },
    'api/shared_snippets' => function (): JSONRenderer {
        $snippets = DatabaseHelper::getSnippets();

        return new JSONRenderer($snippets);
    },
    'api/404' => function (): JSONRenderer {
        return new JSONRenderer(['error' => 'Not Found']);
    },
];