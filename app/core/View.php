<?php

namespace App\Core;

class View
{
    public static function render(string $view, array $data = []): void
    {
        extract($data, EXTR_SKIP);
        require APP_PATH . '/views/' . $view . '.php';
    }

    public static function renderWithLayout(string $layout, string $view, array $data = []): void
    {
        extract($data, EXTR_SKIP);
        ob_start();
        require APP_PATH . '/views/' . $view . '.php';
        $content = ob_get_clean();
        require APP_PATH . '/views/layouts/' . $layout . '.php';
    }
}
