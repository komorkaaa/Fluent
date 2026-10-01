<?php

use App\Controllers\HomeController;
use App\Controllers\CoursesController;
use App\Controllers\ApplicationsController;

$router->get('/', [HomeController::class, 'index']);

$router->get('/courses', [CoursesController::class, 'index']);

$router->get('/courses/{id}', [CoursesController::class, 'show']);

$router->get('/courses/{id}/apply', [ApplicationsController::class, 'create']);

$router->post('/courses/{id}/apply', [ApplicationsController::class, 'store']);