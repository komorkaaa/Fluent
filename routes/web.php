<?php

use App\Controllers\HomeController;
use App\Controllers\CoursesController;
use App\Controllers\ApplicationsController;
use App\Controllers\AuthController;
use App\Controllers\ProfileController;
use App\Controllers\AdminDashboardController;
use App\Controllers\AdminCoursesController;
use App\Controllers\AdminApplicationsController;
use App\Controllers\AdminUsersController;

$router->get('/', [HomeController::class, 'index']);

// ====== courses ======
$router->get('/courses', [CoursesController::class, 'index']);
$router->get('/courses/{id}', [CoursesController::class, 'show']);
$router->get('/courses/{id}/apply', [ApplicationsController::class, 'create']);
$router->post('/courses/{id}/apply', [ApplicationsController::class, 'store']);


// ====== auth ======
$router->get('/register', [AuthController::class, 'register']);
$router->post('/register', [AuthController::class, 'storeRegister']);
$router->get('/login', [AuthController::class, 'login']);
$router->post('/login', [AuthController::class, 'storeLogin']);
$router->post('/logout', [AuthController::class, 'logout']);


// ====== profile ======
$router->get('/profile', [ProfileController::class, 'index']);
$router->post('/profile', [ProfileController::class, 'update']);

// ====== admin ======
$router->get('/admin', [AdminDashboardController::class, 'index']);
$router->get('/admin/courses', [AdminCoursesController::class, 'index']);
$router->get('/admin/courses/create', [AdminCoursesController::class, 'create']);
$router->post('/admin/courses/create', [AdminCoursesController::class, 'store']);
$router->get('/admin/courses/{id}/edit', [AdminCoursesController::class, 'edit']);
$router->post('/admin/courses/{id}/edit', [AdminCoursesController::class, 'update']);
$router->post('/admin/courses/{id}/delete', [AdminCoursesController::class, 'delete']);
$router->get('/admin/applications', [AdminApplicationsController::class, 'index']);
$router->post('/admin/applications/{id}/status', [AdminApplicationsController::class, 'updateStatus']);
$router->post('/admin/applications/{id}/delete', [AdminApplicationsController::class, 'delete']);
$router->get('/admin/users', [AdminUsersController::class, 'index']);
$router->post('/admin/users/{id}/role', [AdminUsersController::class, 'updateRole']);