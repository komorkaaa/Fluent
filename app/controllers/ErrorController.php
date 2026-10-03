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
}