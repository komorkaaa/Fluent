<?php

use App\Controllers\HomeController;
use App\Controllers\CoursesController;

$router->get('/', [HomeController::class, 'index']);

$router->get('/courses', [CoursesController::class, 'index']);

$router->get('/courses/{id}', [CoursesController::class, 'show']);