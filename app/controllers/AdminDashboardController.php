<?php

namespace App\Controllers;

class AdminDashboardController extends AdminController
{
    public function index(): void
    {
        $this->requireAdmin();

        $this->view('admin/index', [
            'title' => 'Панель администратора — Fluent',
        ]);
    }
}