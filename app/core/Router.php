<?php

namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $path, callable|array $handler): void {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, callable|array $handler): void {
        $this->routes['POST'][$path] = $handler;
    }

    public function dispatch(string $method, string $uri): void {
        $path = parse_url($uri, PHP_URL_PATH);

        foreach ($this->routes[$method] ?? [] as $route => $handler) {
            $parameters = $this->matchRoute($route, $path);

            if ($parameters === null) {
                continue;
            }

            $this->callHandler($handler, $parameters);
            return;
        }

        http_response_code(404);

        $controller = new \App\Controllers\ErrorController();
        $controller->notFound();
        exit;
    }

    private function matchRoute(string $route, string $path): ?array {
        $pattern = preg_replace(
            '/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/',
            '(?P<$1>[^/]+)',
            $route
        );

        $pattern = '#^' . $pattern . '$#';

        if (!preg_match($pattern, $path, $matches)) {
            return null;
        }

        $parameters = [];

        foreach ($matches as $key => $value) {
            if (is_string($key)) {
                $parameters[$key] = $value;
            }
        }

        return $parameters;
    }

    private function callHandler(callable|array $handler, array $parameters): void {
        if (is_callable($handler)) {
            $handler(...array_values($parameters));
            return;
        }

        [$controller, $action] = $handler;

        $controllerInstance = new $controller();

        $controllerInstance->$action(...array_values($parameters));
    }
}