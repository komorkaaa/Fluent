<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Course;

class CoursesController extends Controller {
    public function index(): void
    {
        $language = $_GET['language'] ?? null;
        $level = $_GET['level'] ?? null;
        $format = $_GET['format'] ?? null;
        $sort = $_GET['sort'] ?? 'date_desc';

        $courses = Course::all(
            $language,
            $level,
            $format,
            $sort
        );

        $this->view('courses/index', [
            'title' => 'Курсы — Fluent',
            'courses' => $courses,
            'language' => $language,
            'level' => $level,
            'format' => $format,
            'sort' => $sort,
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