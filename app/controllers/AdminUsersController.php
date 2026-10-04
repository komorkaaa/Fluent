<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Models\Application;
use App\Models\User;

class AdminUsersController extends AdminController {
    private const ROLES = [
        'user' => 'Пользователь',
        'admin' => 'Администратор',
    ];

    public function index(): void {
        $this->requireAdmin();

        $this->view('admin/users/index', [
            'title' => 'Пользователи — Fluent',
            'users' => User::all(),
            'currentUser' => Auth::user(),
        ]);
    }

    public function show(int $id): void {
        $this->requireAdmin();

        $user = $this->findOrFail($id);

        $this->view('admin/users/show', [
            'title' => $user['name'] . ' — Fluent',
            'account' => $user,
            'applications' => Application::findByUserId($id),
            'roles' => self::ROLES,
            'currentUser' => Auth::user(),
        ]);
    }

    public function updateRole(int $id): void {
        $this->verifyCsrf();
        $this->requireAdmin();

        $this->findOrFail($id);
        $this->denyForSelf($id, 'Нельзя изменить свою роль — Fluent');

        $role = $this->input($_POST, 'role');

        if (!array_key_exists($role, self::ROLES)) {
            $this->flash('danger', 'Выберите корректную роль.');
            $this->redirect('/admin/users/' . $id);
        }

        User::updateRole($id, $role);

        $this->flash('success', 'Роль пользователя изменена.');
        $this->redirect('/admin/users/' . $id);
    }

    public function block(int $id): void {
        $this->verifyCsrf();
        $this->requireAdmin();

        $this->findOrFail($id);
        $this->denyForSelf($id, 'Нельзя заблокировать себя — Fluent');

        User::setBlocked($id, true);

        $this->flash('success', 'Пользователь заблокирован.');
        $this->redirect($this->backTo($id));
    }

    public function unblock(int $id): void {
        $this->verifyCsrf();
        $this->requireAdmin();

        $this->findOrFail($id);

        User::setBlocked($id, false);

        $this->flash('success', 'Пользователь разблокирован.');
        $this->redirect($this->backTo($id));
    }

    public function delete(int $id): void {
        $this->verifyCsrf();
        $this->requireAdmin();

        $this->findOrFail($id);
        $this->denyForSelf($id, 'Нельзя удалить себя — Fluent');

        User::delete($id);

        $this->flash('success', 'Пользователь удалён.');
        $this->redirect('/admin/users');
    }

    private function findOrFail(int $id): array {
        $user = User::find($id);

        if ($user === null) {
            $this->abortNotFound('Пользователь не найден — Fluent');
        }

        return $user;
    }

    private function denyForSelf(int $id, string $title): void {
        $currentUser = Auth::user();

        if ($currentUser !== null && (int) $currentUser['id'] === $id) {
            $this->abortForbidden($title);
        }
    }

    /** Возврат на карточку пользователя, если действие выполнялось оттуда. */
    private function backTo(int $id): string {
        return ($_POST['back'] ?? '') === 'show'
            ? '/admin/users/' . $id
            : '/admin/users';
    }
}
