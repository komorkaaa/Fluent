<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Application;
use App\Models\Course;

class ApplicationsController extends Controller
{
    public function create(int $courseId): void
    {
        $course = Course::find($courseId);

        if ($course === null) {
            http_response_code(404);

            $this->view('errors/404', [
                'title' => 'Курс не найден — Fluent',
            ]);

            return;
        }

        $user = Auth::user();

        $this->view('applications/create', [
            'title' => 'Запись на курс — Fluent',
            'course' => $course,
            'user' => $user,
        ]);
    }

    public function store(int $courseId): void
    {
        $course = Course::find($courseId);
        $user = Auth::user();

        if ($course === null) {
            http_response_code(404);

            $this->view('errors/404', [
                'title' => 'Курс не найден — Fluent',
            ]);

            return;
        }

        $phone = trim($_POST['phone'] ?? '');
        $comment = trim($_POST['comment'] ?? '');

        $errors = [];

        if ($user !== null) {
            $name = $user['name'];
            $email = $user['email'];
        } else {
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');

            if ($name === '') {
                $errors[] = 'Введите имя.';
            }

            if ($email === '') {
                $errors[] = 'Введите email.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Введите корректный email.';
            }
        }

        if ($phone === '') {
            $errors[] = 'Введите номер телефона.';
        }

        if ($errors !== []) {
            $this->view('applications/create', [
                'title' => 'Запись на курс — Fluent',
                'course' => $course,
                'user' => $user,
                'errors' => $errors,
                'old' => [
                    'name' => $name,
                    'phone' => $phone,
                    'email' => $email,
                    'comment' => $comment,
                ],
            ]);

            return;
        }

        Application::create(
            $courseId,
            $name,
            $phone,
            $email,
            $comment !== '' ? $comment : null,
            $user['id'] ?? null
        );

        $this->view('applications/success', [
            'title' => 'Заявка отправлена — Fluent',
            'course' => $course,
        ]);
    }
}