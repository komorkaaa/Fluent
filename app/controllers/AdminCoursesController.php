<?php

namespace App\Controllers;

use App\Models\Course;
use App\Models\Lookup;

class AdminCoursesController extends AdminController {
    public function index(): void {
        $this->requireAdmin();

        $this->view('admin/courses/index', [
            'title' => 'Управление курсами — Fluent',
            'courses' => Course::all(),
        ]);
    }

    public function create(): void {
        $this->requireAdmin();

        $this->renderForm('create', 'Добавление курса — Fluent', [
            'name' => '', 'description' => '', 'price' => '',
            'language_id' => '', 'level_id' => '', 'format_id' => '', 'image' => '',
        ]);
    }

    public function store(): void {
        $this->verifyCsrf();
        $this->requireAdmin();

        [$data, $errors] = $this->collect();

        if ($errors !== []) {
            $this->renderForm('create', 'Добавление курса — Fluent', $this->old(), $errors);

            return;
        }

        Course::create($data);

        $this->flash('success', 'Курс «' . $data['name'] . '» добавлен.');
        $this->redirect('/admin/courses');
    }

    public function edit(int $id): void {
        $this->requireAdmin();

        $course = $this->findOrFail($id);

        $this->renderForm('edit', 'Редактирование курса — Fluent', $course, [], $id);
    }

    public function update(int $id): void {
        $this->verifyCsrf();
        $this->requireAdmin();

        $this->findOrFail($id);

        [$data, $errors] = $this->collect();

        if ($errors !== []) {
            $this->renderForm('edit', 'Редактирование курса — Fluent', $this->old(), $errors, $id);

            return;
        }

        Course::update($id, $data);

        $this->flash('success', 'Изменения курса сохранены.');
        $this->redirect('/admin/courses');
    }

    public function delete(int $id): void {
        $this->verifyCsrf();
        $this->requireAdmin();

        $course = $this->findOrFail($id);

        try {
            Course::delete($id);
            $this->flash('success', 'Курс «' . $course['name'] . '» удалён.');
        } catch (\PDOException $exception) {
            // 23503 — на курс ссылаются заявки (внешний ключ ON DELETE RESTRICT)
            if ($exception->getCode() !== '23503') {
                throw $exception;
            }

            $this->flash(
                'danger',
                'Курс «' . $course['name'] . '» нельзя удалить: на него есть заявки. '
                . 'Сначала удалите или завершите связанные заявки.'
            );
        }

        $this->redirect('/admin/courses');
    }

    private function findOrFail(int $id): array {
        $course = Course::find($id);

        if ($course === null) {
            $this->abortNotFound('Курс не найден — Fluent');
        }

        return $course;
    }

    private function renderForm(
        string $view,
        string $title,
        array $course,
        array $errors = [],
        ?int $id = null
    ): void {
        $this->view('admin/courses/' . $view, [
            'title' => $title,
            'course' => $course + ['id' => $id],
            'errors' => $errors,
            'languages' => Lookup::languages(),
            'levels' => Lookup::levels(),
            'formats' => Lookup::formats(),
        ]);
    }

    /** Значения формы для повторного показа после ошибки. */
    private function old(): array {
        return [
            'name' => $this->input($_POST, 'name'),
            'description' => $this->input($_POST, 'description'),
            'price' => $this->input($_POST, 'price'),
            'language_id' => $this->input($_POST, 'language_id'),
            'level_id' => $this->input($_POST, 'level_id'),
            'format_id' => $this->input($_POST, 'format_id'),
            'new_language' => $this->input($_POST, 'new_language'),
            'image' => $this->input($_POST, 'image'),
        ];
    }

    /** @return array{0: array, 1: string[]} данные для сохранения и ошибки */
    private function collect(): array {
        $old = $this->old();
        $errors = [];

        if ($old['name'] === '') {
            $errors[] = 'Введите название курса.';
        } elseif (mb_strlen($old['name']) > 255) {
            $errors[] = 'Название курса не должно превышать 255 символов.';
        }

        if ($old['description'] === '') {
            $errors[] = 'Введите описание курса.';
        } elseif (mb_strlen($old['description']) > 10000) {
            $errors[] = 'Описание не должно превышать 10 000 символов.';
        }

        $price = str_replace(',', '.', $old['price']);

        if ($price === '') {
            $errors[] = 'Введите цену.';
        } elseif (!is_numeric($price) || (float) $price < 0) {
            $errors[] = 'Цена должна быть неотрицательным числом.';
        } elseif ((float) $price > 99999999.99) {
            $errors[] = 'Цена не должна превышать 99 999 999,99 ₽.';
        }

        $languageId = $this->intOrNull($old['language_id']);

        if ($old['new_language'] !== '') {
            if (mb_strlen($old['new_language']) > 100) {
                $errors[] = 'Название языка не должно превышать 100 символов.';
            }
        } elseif ($languageId === null || !Lookup::exists('languages', $languageId)) {
            $errors[] = 'Выберите язык.';
        }

        $levelId = $this->intOrNull($old['level_id']);

        if ($levelId === null || !Lookup::exists('levels', $levelId)) {
            $errors[] = 'Выберите уровень.';
        }

        $formatId = $this->intOrNull($old['format_id']);

        if ($formatId === null || !Lookup::exists('formats', $formatId)) {
            $errors[] = 'Выберите формат.';
        }

        if ($old['image'] !== '') {
            if (mb_strlen($old['image']) > 500) {
                $errors[] = 'Путь к изображению не должен превышать 500 символов.';
            } elseif (!preg_match('#^(/[A-Za-z0-9_\-./]+|https?://[^\s"\'<>]+)$#', $old['image'])) {
                $errors[] = 'Изображение: укажите путь вида /images/courses/file.svg или ссылку http(s)://…';
            }
        }

        if ($errors !== []) {
            return [[], $errors];
        }

        if ($old['new_language'] !== '') {
            $languageId = Lookup::languageIdByName($old['new_language']);
        }

        return [[
            'name' => $old['name'],
            'description' => $old['description'],
            'price' => (float) $price,
            'language_id' => $languageId,
            'level_id' => $levelId,
            'format_id' => $formatId,
            'image' => $old['image'] !== '' ? $old['image'] : null,
        ], []];
    }
}
