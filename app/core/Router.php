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
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';
        $path = rawurldecode($path);

        // /courses/ и /courses — один и тот же маршрут
        if ($path !== '/') {
            $path = rtrim($path, '/');
        }

        if ($method === 'HEAD') {
            $method = 'GET';
        }

        foreach ($this->routes[$method] ?? [] as $route => $handler) {
            $parameters = $this->matchRoute($route, $path);

            if ($parameters === null) {
                continue;
            }

            $this->callHandler($handler, $parameters);
            return;
        }

        (new \App\Controllers\ErrorController())->notFound();
    }

    private function matchRoute(string $route, string $path): ?array {
        // {id} — только число; остальные параметры — любой сегмент пути
        $pattern = preg_replace_callback(
            '/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/',
            static fn (array $m): string => $m[1] === 'id'
                ? '(?P<id>\d{1,18})'
                : '(?P<' . $m[1] . '>[^/]+)',
            $route
        );

        $pattern = '#^' . $pattern . '$#u';

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
