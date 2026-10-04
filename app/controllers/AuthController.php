<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\User;

class AuthController extends Controller {
    public function register(): void {
        $this->redirectIfAuthenticated();

        $this->view('auth/register', [
            'title' => 'Регистрация — Fluent',
        ]);
    }

    public function storeRegister(): void {
        $this->verifyCsrf();
        $this->redirectIfAuthenticated();

        $name = $this->input($_POST, 'name');
        $email = mb_strtolower($this->input($_POST, 'email'));
        $password = is_string($_POST['password'] ?? null) ? $_POST['password'] : '';
        $passwordConfirmation = is_string($_POST['password_confirmation'] ?? null)
            ? $_POST['password_confirmation']
            : '';

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
        }

        if ($password === '') {
            $errors[] = 'Введите пароль.';
        } elseif (mb_strlen($password) < 6) {
            $errors[] = 'Пароль должен содержать минимум 6 символов.';
        } elseif (strlen($password) > 72) {
            // bcrypt учитывает только первые 72 байта пароля
            $errors[] = 'Пароль не должен быть длиннее 72 байт.';
        }

        if ($password !== $passwordConfirmation) {
            $errors[] = 'Пароли не совпадают.';
        }

        if ($errors === [] && User::findByEmail($email) !== null) {
            $errors[] = 'Пользователь с таким email уже зарегистрирован.';
        }

        if ($errors === []) {
            try {
                User::create($name, $email, $password);
            } catch (\PDOException $exception) {
                // 23505 — нарушение уникальности (одновременная регистрация того же email)
                if ($exception->getCode() !== '23505') {
                    throw $exception;
                }

                $errors[] = 'Пользователь с таким email уже зарегистрирован.';
            }
        }

        if ($errors !== []) {
            $this->view('auth/register', [
                'title' => 'Регистрация — Fluent',
                'errors' => $errors,
                'old' => [
                    'name' => $name,
                    'email' => $email,
                ],
            ]);

            return;
        }

        $this->flash('success', 'Аккаунт создан. Теперь можно войти.');
        $this->redirect('/login');
    }

    public function login(): void {
        $this->redirectIfAuthenticated();

        $this->view('auth/login', [
            'title' => 'Вход — Fluent',
        ]);
    }

    public function storeLogin(): void {
        $this->verifyCsrf();
        $this->redirectIfAuthenticated();

        $email = $this->input($_POST, 'email');
        $password = is_string($_POST['password'] ?? null) ? $_POST['password'] : '';

        $errors = [];

        if ($email === '') {
            $errors[] = 'Введите email.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Введите корректный email.';
        }

        if ($password === '') {
            $errors[] = 'Введите пароль.';
        }

        $user = null;

        if ($errors === []) {
            $user = User::findByEmail($email);

            if (
                $user === null ||
                !password_verify($password, $user['password'])
            ) {
                $errors[] = 'Неверный email или пароль.';
            } elseif ((bool) $user['is_blocked']) {
                $errors[] = 'Ваша учётная запись заблокирована.';
            }
        }

        if ($errors !== []) {
            $this->view('auth/login', [
                'title' => 'Вход — Fluent',
                'errors' => $errors,
                'old' => [
                    'email' => $email,
                ],
            ]);

            return;
        }

        Auth::login($user);

        $this->redirect($user['role'] === 'admin' ? '/admin' : '/profile');
    }

    public function logout(): void {
        $this->verifyCsrf();

        Auth::logout();

        $this->redirect('/');
    }

    private function redirectIfAuthenticated(): void {
        if (Auth::check()) {
            $this->redirect('/profile');
        }
    }
}
