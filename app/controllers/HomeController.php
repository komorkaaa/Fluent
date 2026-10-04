<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Course;
use App\Models\Lookup;

class HomeController extends Controller
{
    public function index(): void
    {
        $this->view('home', [
            'title' => 'Fluent — школа иностранных языков',
            'featuredCourses' => Course::latest(3),
            'languageStats' => Lookup::languageStats(),
        ]);
    }
}
