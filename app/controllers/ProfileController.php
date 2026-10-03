<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Application;
use App\Models\User;

class ProfileController extends Controller {
    public function index(): void {
        Auth::start();

        if (!Auth::check()) {
            header('Location: /login');
            exit;
        }

        $user = Auth::user();

        $applications = Application::findByUserId(
            (int) $user['id']
        );

        $this->view('profile/index', [
            'title' => 'Личный кабинет — Fluent',
            'user' => $user,
            'applications' => $applications,
        ]);
    }

    public function update(): void {
        $this->verifyCsrf();

        Auth::start();

        if (!Auth::check()) {
            header('Location: /login');
            exit;
        }

        $user = Auth::user();

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');

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

        $existingUser = User::findByEmail($email);

        if (
            $existingUser !== null &&
            (int) $existingUser['id'] !== (int) $user['id']
        ) {
            $errors[] = 'Пользователь с таким email уже существует.';
        }

        if ($errors !== []) {
            $applications = Application::findByUserId(
                (int) $user['id']
            );

            $this->view('profile/index', [
                'title' => 'Личный кабинет — Fluent',
                'user' => $user,
                'applications' => $applications,
                'errors' => $errors,
            ]);

            return;
        }

        User::update(
            (int) $user['id'],
            $name,
            $email
        );

        $_SESSION['user']['name'] = $name;
        $_SESSION['user']['email'] = $email;

        header('Location: /profile');
        exit;
    }
}