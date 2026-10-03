<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;

class AdminController extends Controller {
    protected function requireAdmin(): void {
        if (!Auth::isAdmin()) {
            http_response_code(403);

            $this->view('errors/403', [
                'title' => 'Доступ запрещён — Fluent',
            ]);

            exit;
        }
    }
}