<?php

namespace App\Controllers;

use App\Models\Application;
use App\Models\User;

class AdminApplicationsController extends AdminController {
    public function index(): void {
        $this->requireAdmin();

        $filters = [
            'user_id' => $this->intOrNull($this->input($_GET, 'user_id')),
            'status_id' => $this->intOrNull($this->input($_GET, 'status_id')),
            'date_from' => $this->validDate($this->input($_GET, 'date_from')),
            'date_to' => $this->validDate($this->input($_GET, 'date_to')),
        ];

        if (
            $filters['date_from'] !== ''
            && $filters['date_to'] !== ''
            && $filters['date_from'] > $filters['date_to']
        ) {
            [$filters['date_from'], $filters['date_to']] = [
                $filters['date_to'],
                $filters['date_from'],
            ];
        }

        $this->view('admin/applications/index', [
            'title' => 'Заявки — Fluent',
            'applications' => Application::all($filters),
            'statuses' => Application::statuses(),
            'users' => User::all(),
            'filters' => $filters,
        ]);
    }

    public function show(int $id): void {
        $this->requireAdmin();

        $this->view('admin/applications/show', [
            'title' => 'Заявка №' . $id . ' — Fluent',
            'application' => $this->findOrFail($id),
            'statuses' => Application::statuses(),
        ]);
    }

    public function updateStatus(int $id): void {
        $this->verifyCsrf();
        $this->requireAdmin();

        $this->findOrFail($id);

        $statusId = $this->intOrNull($this->input($_POST, 'status_id'));

        if ($statusId === null || !Application::statusExists($statusId)) {
            $this->flash('danger', 'Выберите корректный статус.');
            $this->redirect('/admin/applications/' . $id);
        }

        Application::updateStatus($id, $statusId);

        $this->flash('success', 'Статус заявки №' . $id . ' обновлён.');
        $this->redirect('/admin/applications/' . $id);
    }

    public function delete(int $id): void {
        $this->verifyCsrf();
        $this->requireAdmin();

        $this->findOrFail($id);

        Application::delete($id);

        $this->flash('success', 'Заявка №' . $id . ' удалена.');
        $this->redirect('/admin/applications');
    }

    private function findOrFail(int $id): array {
        $application = Application::find($id);

        if ($application === null) {
            $this->abortNotFound('Заявка не найдена — Fluent');
        }

        return $application;
    }

    private function validDate(string $date): string {
        if ($date === '') {
            return '';
        }

        $parsed = \DateTime::createFromFormat('!Y-m-d', $date);

        return $parsed !== false && $parsed->format('Y-m-d') === $date
            ? $date
            : '';
    }
}
