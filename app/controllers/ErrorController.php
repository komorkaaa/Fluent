<?php

namespace App\Controllers;

use App\Core\Controller;

class ErrorController extends Controller
{
    public function notFound(): void
    {
        http_response_code(404);

        $this->view('errors/404', [
            'title' => 'Страница не найдена — Fluent',
        ]);
    }

    public function serverError(?\Throwable $error = null): void
    {
        http_response_code(500);

        try {
            $this->view('errors/500', [
                'title' => 'Ошибка сервера — Fluent',
                'details' => getenv('APP_DEBUG') === '1' && $error !== null
                    ? (string) $error
                    : null,
            ]);
        } catch (\Throwable) {
            // если не работает даже шаблон (например, нет базы) — простой ответ
            echo '<!DOCTYPE html><meta charset="utf-8"><title>Ошибка сервера</title>'
                . '<h1>Ошибка сервера</h1><p>Попробуйте зайти позже.</p>';
        }
    }
}
