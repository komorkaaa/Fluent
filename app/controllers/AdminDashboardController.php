<?php

namespace App\Controllers;

use App\Models\Application;
use App\Models\Course;
use App\Models\User;

class AdminDashboardController extends AdminController
{
    public function index(): void
    {
        $this->requireAdmin();

        $this->view('admin/index', [
            'title' => 'Панель администратора — Fluent',
            'stats' => [
                'courses' => Course::count(),
                'applications' => Application::count(),
                'users' => User::count(),
            ],
            'statusStats' => Application::countByStatus(),
        ]);
    }
}
