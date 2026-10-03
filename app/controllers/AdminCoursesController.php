<?php

namespace App\Controllers;

use App\Models\Course;

class AdminCoursesController extends AdminController {
    public function index(): void {
        $this->requireAdmin();

        $courses = Course::all();

        $this->view('admin/courses/index', [
            'title' => 'Управление курсами — Fluent',
            'courses' => $courses,
        ]);
    }

    public function create(): void {
        $this->requireAdmin();

        $this->view('admin/courses/create', [
            'title' => 'Добавление курса — Fluent',
        ]);
    }

    public function store(): void {
        $this->requireAdmin();

        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $price = trim($_POST['price'] ?? '');
        $level = trim($_POST['level'] ?? '');
        $format = trim($_POST['format'] ?? '');
        $language = trim($_POST['language'] ?? '');
        $image = trim($_POST['image'] ?? '');

        $errors = $this->validate(
            $name,
            $description,
            $price,
            $level,
            $format,
            $language,
            $image
        );

        if ($errors !== []) {
            $this->view('admin/courses/create', [
                'title' => 'Добавление курса — Fluent',
                'errors' => $errors,
                'old' => [
                    'name' => $name,
                    'description' => $description,
                    'price' => $price,
                    'level' => $level,
                    'format' => $format,
                    'language' => $language,
                    'image' => $image,
                ],
            ]);

            return;
        }

        Course::create(
            $name,
            $description,
            (float) $price,
            $level,
            $format,
            $language,
            $image !== '' ? $image : null
        );

        header('Location: /admin/courses');
        exit;
    }

    public function edit(int $id): void {
        $this->requireAdmin();

        $course = Course::find($id);

        if ($course === null) {
            http_response_code(404);

            $this->view('errors/404', [
                'title' => 'Курс не найден — Fluent',
            ]);

            return;
        }

        $this->view('admin/courses/edit', [
            'title' => 'Редактирование курса — Fluent',
            'course' => $course,
        ]);
    }

    public function update(int $id): void {
        $this->requireAdmin();

        $course = Course::find($id);

        if ($course === null) {
            http_response_code(404);

            $this->view('errors/404', [
                'title' => 'Курс не найден — Fluent',
            ]);

            return;
        }

        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $price = trim($_POST['price'] ?? '');
        $level = trim($_POST['level'] ?? '');
        $format = trim($_POST['format'] ?? '');
        $language = trim($_POST['language'] ?? '');
        $image = trim($_POST['image'] ?? '');

        $errors = $this->validate(
            $name,
            $description,
            $price,
            $level,
            $format,
            $language,
            $image
        );

        if ($errors !== []) {
            $this->view('admin/courses/edit', [
                'title' => 'Редактирование курса — Fluent',
                'course' => [
                    'id' => $id,
                    'name' => $name,
                    'description' => $description,
                    'price' => $price,
                    'level' => $level,
                    'format' => $format,
                    'language' => $language,
                    'image' => $image,
                ],
                'errors' => $errors,
            ]);

            return;
        }

        Course::update(
            $id,
            $name,
            $description,
            (float) $price,
            $level,
            $format,
            $language,
            $image !== '' ? $image : null
        );

        header('Location: /admin/courses');
        exit;
    }

    public function delete(int $id): void {
        $this->requireAdmin();

        $course = Course::find($id);

        if ($course === null) {
            http_response_code(404);

            $this->view('errors/404', [
                'title' => 'Курс не найден — Fluent',
            ]);

            return;
        }

        Course::delete($id);

        header('Location: /admin/courses');
        exit;
    }

    private function validate(
        string $name,
        string $description,
        string $price,
        string $level,
        string $format,
        string $language,
        string $image
    ): array {
        $errors = [];

        if ($name === '') {
            $errors[] = 'Введите название курса.';
        } elseif (mb_strlen($name) > 255) {
            $errors[] = 'Название курса не должно превышать 255 символов.';
        }

        if ($description === '') {
            $errors[] = 'Введите описание курса.';
        }

        if ($price === '') {
            $errors[] = 'Введите цену.';
        } elseif (!is_numeric($price) || (float) $price < 0) {
            $errors[] = 'Цена должна быть неотрицательным числом.';
        }

        if ($level === '') {
            $errors[] = 'Укажите уровень.';
        } elseif (mb_strlen($level) > 50) {
            $errors[] = 'Уровень не должен превышать 50 символов.';
        }

        if ($format === '') {
            $errors[] = 'Укажите формат.';
        } elseif (mb_strlen($format) > 50) {
            $errors[] = 'Формат не должен превышать 50 символов.';
        }

        if ($language === '') {
            $errors[] = 'Укажите язык.';
        } elseif (mb_strlen($language) > 100) {
            $errors[] = 'Название языка не должно превышать 100 символов.';
        }

        if ($image !== '' && mb_strlen($image) > 500) {
            $errors[] = 'Путь к изображению не должен превышать 500 символов.';
        }

        return $errors;
    }
}