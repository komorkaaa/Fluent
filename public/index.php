<?php

require_once __DIR__ . '/../app/core/Router.php';
require_once __DIR__ . '/../app/core/Controller.php';
require_once __DIR__ . '/../app/controllers/HomeController.php';

use App\Core\Router;
use App\Controllers\HomeController;

$router = new Router();

$router->get('/', [HomeController::class, 'index']);

$router->dispatch(
    $_SERVER['REQUEST_METHOD'],
    $_SERVER['REQUEST_URI']
);