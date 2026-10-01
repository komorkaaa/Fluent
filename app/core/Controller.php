<?php

namespace App\Core;

class Controller {
    protected function view(string $view, array $data = []): void {
        extract($data);

        $viewPath = __DIR__ . '/../views/' . $view . '.php';

        if (!file_exists($viewPath)) {
            throw new \RuntimeException("View not found: {$view}");
        }

        require $viewPath;
    }
}