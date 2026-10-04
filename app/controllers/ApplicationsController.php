<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Application;
use App\Models\Course;

class ApplicationsController extends Controller {
    public function create(int $courseId): void {
        $course = Course::find($courseId);

        if ($course === null) {
            $this->abortNotFound('Курс не найден — Fluent');
        }

        $this->view('applications/create', [
            'title' => 'Запись на курс — Fluent',
            'course' => $course,
            'user' => Auth::user(),
        ]);
    }

    public function store(int $courseId): void {
        $this->verifyCsrf();

        $course = Course::find($courseId);

        if ($course === null) {
            $this->abortNotFound('Курс не найден — Fluent');
        }

        $user = Auth::user();

        $phone = $this->input($_POST, 'phone');
        $comment = $this->input($_POST, 'comment');

        $errors = [];

        if ($user !== null) {
            $name = $user['name'];
            $email = $user['email'];
        } else {
            $name = $this->input($_POST, 'name');
            $email = $this->input($_POST, 'email');

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
        }

        if ($phone === '') {
            $errors[] = 'Введите номер телефона.';
        } elseif (mb_strlen($phone) > 50 || !preg_match('/^[0-9+()\-\s.]+$/', $phone)) {
            $errors[] = 'Введите корректный номер телефона.';
        } else {
            $digits = preg_replace('/\D+/', '', $phone);

            if (strlen($digits) < 10 || strlen($digits) > 15) {
                $errors[] = 'Введите корректный номер телефона.';
            }
        }

        if (mb_strlen($comment) > 5000) {
            $errors[] = 'Комментарий не должен превышать 5000 символов.';
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

        $applicationId = Application::create(
            $courseId,
            $name,
            $phone,
            $email,
            $comment !== '' ? $comment : null,
            $user['id'] ?? null
        );

        Auth::start();
        $_SESSION['application_success'] = [
            'id' => $applicationId,
            'course_id' => (int) $course['id'],
            'course_name' => $course['name'],
        ];

        // Post/Redirect/Get: повторная отправка при обновлении страницы невозможна
        $this->redirect('/applications/success');
    }

    public function success(): void {
        Auth::start();

        $result = $_SESSION['application_success'] ?? null;
        unset($_SESSION['application_success']);

        if ($result === null) {
            $this->redirect('/courses');
        }

        $this->view('applications/success', [
            'title' => 'Заявка отправлена — Fluent',
            'result' => $result,
            'user' => Auth::user(),
        ]);
    }
}
