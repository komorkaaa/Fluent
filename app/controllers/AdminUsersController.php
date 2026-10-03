<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Models\User;

class AdminUsersController extends AdminController {
    private const ROLES = [
        'user',
        'admin',
    ];

    public function index(): void {
        $this->requireAdmin();

        $users = User::all();

        $this->view('admin/users/index', [
            'title' => 'Пользователи — Fluent',
            'users' => $users,
            'roles' => self::ROLES,
        ]);
    }

    public function updateRole(int $id): void {
        $this->requireAdmin();

        $currentUser = Auth::user();

        $user = null;

        foreach (User::all() as $item) {
            if ((int) $item['id'] === $id) {
                $user = $item;
                break;
            }
        }

        if ($user === null) {
            http_response_code(404);

            $this->view('errors/404', [
                'title' => 'Пользователь не найден — Fluent',
            ]);

            return;
        }

        if (
            $currentUser !== null &&
            (int) $currentUser['id'] === $id
        ) {
            http_response_code(400);

            $this->view('errors/403', [
                'title' => 'Нельзя изменить свою роль — Fluent',
            ]);

            return;
        }

        $role = trim($_POST['role'] ?? '');

        if (!in_array($role, self::ROLES, true)) {
            http_response_code(400);

            echo 'Некорректная роль';

            return;
        }

        User::updateRole($id, $role);

        header('Location: /admin/users');
        exit;
    }
}