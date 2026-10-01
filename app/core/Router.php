<?php

namespace App\Core;

class Router {
    private array $routes = [];

    public function get(string $path, callable|array $handler): void {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, callable|array $handler): void {
        $this->routes['POST'][$path] = $handler;
    }

    public function dispatch(string $method, string $uri): void {
        $path = parse_url($uri, PHP_URL_PATH);

        $handler = $this->routes[$method][$path] ?? null;

        if ($handler === null) {
            http_response_code(404);
            echo '404 Not Found';
            return;
        }

        if (is_callable($handler)) {
            $handler();
            return;
        }

        [$controller, $action] = $handler;

        $controllerInstance = new $controller();
        $controllerInstance->$action();
    }
}