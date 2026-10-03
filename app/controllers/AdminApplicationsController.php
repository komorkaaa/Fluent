<?php

namespace App\Controllers;

use App\Models\Application;
use App\Models\User;

class AdminApplicationsController extends AdminController {
    private const STATUSES = [
        'Новая',
        'В обработке',
        'Подтверждена',
        'Отклонена',
        'Завершена',
    ];

    public function index(): void {
        $this->requireAdmin();

        $filters = [
            'user_id' => trim($_GET['user_id'] ?? ''),
            'status' => trim($_GET['status'] ?? ''),
            'date_from' => trim($_GET['date_from'] ?? ''),
            'date_to' => trim($_GET['date_to'] ?? ''),
        ];

        if (!in_array($filters['status'], self::STATUSES, true)) {
            $filters['status'] = '';
        }

        if (
            $filters['date_from'] !== ''
            && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $filters['date_from'])
        ) {
            $filters['date_from'] = '';
        }

        if (
            $filters['date_to'] !== ''
            && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $filters['date_to'])
        ) {
            $filters['date_to'] = '';
        }

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

        $users = User::all();
        $applications = Application::all($filters);

        $this->view('admin/applications/index', [
            'title' => 'Заявки — Fluent',
            'applications' => $applications,
            'statuses' => self::STATUSES,
            'users' => $users,
            'filters' => $filters,
        ]);
    }

    public function updateStatus(int $id): void {
        $this->requireAdmin();

        $application = Application::find($id);

        if ($application === null) {
            http_response_code(404);

            $this->view('errors/404', [
                'title' => 'Заявка не найдена — Fluent',
            ]);

            return;
        }

        $status = trim($_POST['status'] ?? '');

        if (!in_array($status, self::STATUSES, true)) {
            http_response_code(400);

            echo 'Некорректный статус';

            return;
        }

        Application::updateStatus($id, $status);

        header('Location: /admin/applications');
        exit;
    }

    public function delete(int $id): void {
        $this->requireAdmin();

        $application = Application::find($id);

        if ($application === null) {
            http_response_code(404);

            $this->view('errors/404', [
                'title' => 'Заявка не найдена — Fluent',
            ]);

            return;
        }

        Application::delete($id);

        header('Location: /admin/applications');
        exit;
    }
}