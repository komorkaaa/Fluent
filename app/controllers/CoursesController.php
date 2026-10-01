<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Course;

class CoursesController extends Controller {
    public function index(): void {
        $courses = Course::all();

        $this->view('courses/index', [
            'title' => 'Курсы – Fluent',
            'courses' => $courses,
        ]);
    }

    public function show(int $id): void {
        $course = Course::find($id);

        if ($course === null) {
            http_response_code(404);

            $this->view('errors/404', [
                'title' => 'Курс не найден — Fluent',
            ]);

            return;
        }

        $this->view('courses/show', [
            'title' => $course['name'] . ' — Fluent',
            'course' => $course,
        ]);
    }
}