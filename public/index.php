<?php

require_once __DIR__ . '/../app/core/autoload.php';

use App\Controllers\ErrorController;
use App\Core\Router;

set_exception_handler(function (Throwable $error): void {
    error_log((string) $error);

    while (ob_get_level() > 0) {
        ob_end_clean();
    }

    (new ErrorController())->serverError($error);
});

$router = new Router();

require_once __DIR__ . '/../routes/web.php';

$router->dispatch(
    $_SERVER['REQUEST_METHOD'],
    $_SERVER['REQUEST_URI']
);
