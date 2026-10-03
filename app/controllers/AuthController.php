<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Core\Auth;

class AuthController extends Controller {
    public function register(): void {
        $this->view('auth/register', [
            'title' => 'Регистрация — Fluent',
        ]);
    }

    public function storeRegister(): void {
        $this->verifyCsrf();

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $passwordConfirmation = $_POST['password_confirmation'] ?? '';

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
        } elseif (strlen($password) < 6) {
            $errors[] = 'Пароль должен содержать минимум 6 символов.';
        }

        if ($password !== $passwordConfirmation) {
            $errors[] = 'Пароли не совпадают.';
        }

        if (User::findByEmail($email) !== null) {
            $errors[] = 'Пользователь с таким email уже зарегистрирован.';
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

        User::create(
            $name,
            $email,
            $password
        );

        header('Location: /login');
        exit;
    }

    public function login(): void
    {
        $this->view('auth/login', [
            'title' => 'Вход — Fluent',
        ]);
    }

    public function storeLogin(): void {
        $this->verifyCsrf();

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

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
                $errors[] = 'Ваша учетная запись заблокирована.';
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

        header('Location: /');
        exit;
    }

    public function logout(): void {
        $this->verifyCsrf();

        Auth::logout();

        header('Location: /');
        exit;
    }
}