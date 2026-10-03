<?php

namespace App\Controllers;

use App\Models\Application;

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

        $applications = Application::all();

        $this->view('admin/applications/index', [
            'title' => 'Заявки — Fluent',
            'applications' => $applications,
            'statuses' => self::STATUSES,
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