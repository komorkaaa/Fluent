<?php

namespace App\Core;

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

    protected function redirect(string $location): never {
        header('Location: ' . $location);
        exit;
    }

    protected function flash(string $type, string $message): void {
        Auth::flash($type, $message);
    }

    /** Требует вход; возвращает данные текущего пользователя. */
    protected function requireAuth(): array {
        $user = Auth::user();

        if ($user === null) {
            $this->redirect('/login');
        }

        return $user;
    }

    protected function abortNotFound(string $title = 'Страница не найдена — Fluent'): never {
        http_response_code(404);

        $this->view('errors/404', ['title' => $title]);

        exit;
    }

    protected function abortForbidden(string $title = 'Доступ запрещён — Fluent'): never {
        http_response_code(403);

        $this->view('errors/403', ['title' => $title]);

        exit;
    }

    protected function verifyCsrf(): void {
        if (!Auth::verifyCsrfToken($_POST['csrf_token'] ?? null)) {
            $this->abortForbidden();
        }
    }

    /** Строковое значение из $_GET/$_POST (массивы и мусор отбрасываются). */
    protected function input(array $source, string $key): string {
        $value = $source[$key] ?? '';

        return is_string($value) ? trim($value) : '';
    }

    protected function intOrNull(string $value): ?int {
        return ctype_digit($value) && strlen($value) <= 18 ? (int) $value : null;
    }
}
