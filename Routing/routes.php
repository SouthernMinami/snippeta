<?php

use Response\HTTPRenderer;
use Response\Render\HTMLRenderer;
use Response\Render\JSONRenderer;
use Helpers\ValidationHelper;

return [
    'new' => function (): HTMLRenderer {
        return new HTMLRenderer('new');
    },
];