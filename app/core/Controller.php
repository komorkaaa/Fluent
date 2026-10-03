<?php

namespace App\Core;

use App\Core\Auth;

class Controller {
    protected function view(string $view, array $data = []): void {
        extract($data);

        $viewPath = __DIR__ . '/../views/' . $view . '.php';

        if (!file_exists($viewPath)) {
            throw new \RuntimeException("View not found: {$view}");
        }

        ob_start();

        require $viewPath;

        $content = ob_get_clean();

        require __DIR__ . '/../views/layout.php';
    }

    protected function verifyCsrf(): void {
        if (!Auth::verifyCsrfToken($_POST['csrf_token'] ?? null)) {
            http_response_code(403);

            $this->view('errors/403', [
                'title' => 'Доступ запрещён — Fluent',
            ]);

            exit;
        }
    }
}