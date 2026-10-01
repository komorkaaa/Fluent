<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Application;
use App\Models\Course;

class ApplicationsController extends Controller {
    public function create(int $courseId): void {
        $course = Course::find($courseId);

        if ($course === null) {
            http_response_code(404);

            $this->view('errors/404', [
                'title' => 'Курс не найден — Fluent',
            ]);

            return;
        }

        $this->view('applications/create', [
            'title' => 'Запись на курс — Fluent',
            'course' => $course,
        ]);
    }

    public function store(int $courseId): void {
        $course = Course::find($courseId);

        if ($course === null) {
            http_response_code(404);

            $this->view('errors/404', [
                'title' => 'Курс не найден — Fluent',
            ]);

            return;
        }

        $name = trim($_POST['name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $comment = trim($_POST['comment'] ?? '');

        $errors = [];

        if ($name === '') {
            $errors[] = 'Введите имя.';
        }

        if ($phone === '') {
            $errors[] = 'Введите номер телефона.';
        }

        if ($email === '') {
            $errors[] = 'Введите email.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Введите корректный email.';
        }

        if ($errors !== []) {
            $this->view('applications/create', [
                'title' => 'Запись на курс — Fluent',
                'course' => $course,
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
            $comment !== '' ? $comment : null
        );

        $this->view('applications/success', [
            'title' => 'Заявка отправлена — Fluent',
            'course' => $course,
        ]);
    }
}