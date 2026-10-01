<?php

use App\Controllers\HomeController;

$router->get('/', [HomeController::class, 'index']);