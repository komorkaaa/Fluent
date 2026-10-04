<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Application;
use App\Models\User;

class ProfileController extends Controller {
    public function index(): void {
        $user = $this->requireAuth();

        $this->renderProfile($user);
    }

    public function update(): void {
        $this->verifyCsrf();

        $user = $this->requireAuth();

        $name = $this->input($_POST, 'name');
        $email = mb_strtolower($this->input($_POST, 'email'));

        $errors = [];

        if ($name === '') {
            $errors[] = 'Введите имя.';
        } elseif (mb_strlen($name) > 255) {
            $errors[] = 'Имя не должно превышать 255 символов.';
        }

        if ($email === '') {
            $errors[] = 'Введите email.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Введите корректный email.';
        } elseif (mb_strlen($email) > 255) {
            $errors[] = 'Email не должен превышать 255 символов.';
        } else {
            $existingUser = User::findByEmail($email);

            if (
                $existingUser !== null &&
                (int) $existingUser['id'] !== (int) $user['id']
            ) {
                $errors[] = 'Пользователь с таким email уже существует.';
            }
        }

        if ($errors === []) {
            try {
                User::update((int) $user['id'], $name, $email);
            } catch (\PDOException $exception) {
                if ($exception->getCode() !== '23505') {
                    throw $exception;
                }

                $errors[] = 'Пользователь с таким email уже существует.';
            }
        }

        if ($errors !== []) {
            $this->renderProfile($user, $errors, ['name' => $name, 'email' => $email]);

            return;
        }

        $_SESSION['user']['name'] = $name;
        $_SESSION['user']['email'] = $email;

        $this->flash('success', 'Данные профиля сохранены.');
        $this->redirect('/profile');
    }

    public function application(int $id): void {
        $user = $this->requireAuth();

        // Только собственные заявки: чужой id даёт 404, а не утечку данных
        $application = Application::findForUser($id, (int) $user['id']);

        if ($application === null) {
            $this->abortNotFound('Заявка не найдена — Fluent');
        }

        $this->view('profile/application', [
            'title' => 'Заявка №' . $application['id'] . ' — Fluent',
            'application' => $application,
        ]);
    }

    private function renderProfile(array $user, array $errors = [], array $old = []): void {
        $this->view('profile/index', [
            'title' => 'Личный кабинет — Fluent',
            'user' => $user,
            'applications' => Application::findByUserId((int) $user['id']),
            'errors' => $errors,
            'old' => $old,
        ]);
    }
}
