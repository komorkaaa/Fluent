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
            'currentUser' => Auth::user(),
        ]);
    }

    public function updateRole(int $id): void {
        $this->verifyCsrf();
        $this->requireAdmin();

        $currentUser = Auth::user();

        $user = $this->findUser($id);

        if ($user === null) {
            $this->showNotFound();

            return;
        }

        if ($this->isCurrentUser($currentUser, $id)) {
            $this->showForbidden('Нельзя изменить свою роль — Fluent');

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

    public function block(int $id): void {
        $this->verifyCsrf();
        $this->requireAdmin();

        $currentUser = Auth::user();

        $user = $this->findUser($id);

        if ($user === null) {
            $this->showNotFound();

            return;
        }

        if ($this->isCurrentUser($currentUser, $id)) {
            $this->showForbidden('Нельзя заблокировать себя — Fluent');

            return;
        }

        User::setBlocked($id, true);

        header('Location: /admin/users');
        exit;
    }

    public function unblock(int $id): void {
        $this->verifyCsrf();
        $this->requireAdmin();

        $user = $this->findUser($id);

        if ($user === null) {
            $this->showNotFound();

            return;
        }

        User::setBlocked($id, false);

        header('Location: /admin/users');
        exit;
    }

    public function delete(int $id): void {
        $this->verifyCsrf();
        $this->requireAdmin();

        $currentUser = Auth::user();

        $user = $this->findUser($id);

        if ($user === null) {
            $this->showNotFound();

            return;
        }

        if ($this->isCurrentUser($currentUser, $id)) {
            $this->showForbidden('Нельзя удалить себя — Fluent');

            return;
        }

        User::delete($id);

        header('Location: /admin/users');
        exit;
    }

    private function findUser(int $id): ?array {
        foreach (User::all() as $user) {
            if ((int) $user['id'] === $id) {
                return $user;
            }
        }

        return null;
    }

    private function isCurrentUser(
        ?array $currentUser,
        int $id
    ): bool {
        return $currentUser !== null
            && (int) $currentUser['id'] === $id;
    }

    private function showNotFound(): void {
        http_response_code(404);

        $this->view('errors/404', [
            'title' => 'Пользователь не найден — Fluent',
        ]);
    }

    private function showForbidden(string $title): void {
        http_response_code(403);

        $this->view('errors/403', [
            'title' => $title,
        ]);
    }
}