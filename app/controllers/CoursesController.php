<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Course;
use App\Models\Lookup;

class CoursesController extends Controller {
    public function index(): void
    {
        $sort = $this->input($_GET, 'sort');

        if (!array_key_exists($sort, Course::SORTS)) {
            $sort = 'date_desc';
        }

        $priceMin = $this->parsePrice($this->input($_GET, 'price_min'));
        $priceMax = $this->parsePrice($this->input($_GET, 'price_max'));

        if ($priceMin !== null && $priceMax !== null && $priceMin > $priceMax) {
            [$priceMin, $priceMax] = [$priceMax, $priceMin];
        }

        $filters = [
            'q' => mb_substr($this->input($_GET, 'q'), 0, 100),
            'language_id' => $this->intOrNull($this->input($_GET, 'language')),
            'level_id' => $this->intOrNull($this->input($_GET, 'level')),
            'format_id' => $this->intOrNull($this->input($_GET, 'format')),
            'price_min' => $priceMin,
            'price_max' => $priceMax,
        ];

        $this->view('courses/index', [
            'title' => 'Курсы — Fluent',
            'courses' => Course::all($filters, $sort),
            'languages' => Lookup::languages(),
            'levels' => Lookup::levels(),
            'formats' => Lookup::formats(),
            'filters' => $filters,
            'sort' => $sort,
            'sorts' => Course::SORTS,
        ]);
    }

    public function show(int $id): void {
        $course = Course::find($id);

        if ($course === null) {
            $this->abortNotFound('Курс не найден — Fluent');
        }

        $this->view('courses/show', [
            'title' => $course['name'] . ' — Fluent',
            'course' => $course,
            'similarCourses' => Course::similar(
                (int) $course['id'],
                (int) $course['language_id'],
                (int) $course['level_id']
            ),
        ]);
    }

    private function parsePrice(string $value): ?float {
        $value = str_replace(',', '.', $value);

        if ($value === '' || !is_numeric($value) || (float) $value < 0) {
            return null;
        }

        return min((float) $value, 99999999.99);
    }
}
