<?php

use App\Controllers\HomeController;
use App\Controllers\CoursesController;
use App\Controllers\ApplicationsController;
use App\Controllers\AuthController;

$router->get('/', [HomeController::class, 'index']);

$router->get('/courses', [CoursesController::class, 'index']);

$router->get('/courses/{id}', [CoursesController::class, 'show']);

$router->get('/courses/{id}/apply', [ApplicationsController::class, 'create']);

$router->post('/courses/{id}/apply', [ApplicationsController::class, 'store']);

$router->get('/register', [AuthController::class, 'register']);

$router->post('/register', [AuthController::class, 'storeRegister']);

$router->get('/login', [AuthController::class, 'login']);

$router->post('/login', [AuthController::class, 'storeLogin']);

$router->post('/logout', [AuthController::class, 'logout']);