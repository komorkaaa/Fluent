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

        $errors = $this->validate(
            $name,
            $description,
            $price,
            $level,
            $format,
            $language
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
            $language
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

        $errors = $this->validate(
            $name,
            $description,
            $price,
            $level,
            $format,
            $language
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
            $language
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
        string $language
    ): array {
        $errors = [];

        if ($name === '') {
            $errors[] = 'Введите название курса.';
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
        }

        if ($format === '') {
            $errors[] = 'Укажите формат.';
        }

        if ($language === '') {
            $errors[] = 'Укажите язык.';
        }

        return $errors;
    }
}