<?php

spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';

    if (!str_starts_with($class, $prefix)) {
        return;
    }

    $relativeClass = substr($class, strlen($prefix));

    $directories = [
        'Core' => __DIR__,
        'Controllers' => __DIR__ . '/../controllers',
        'Models' => __DIR__ . '/../models',
    ];

    foreach ($directories as $namespace => $directory) {
        $namespacePrefix = $namespace . '\\';

        if (!str_starts_with($relativeClass, $namespacePrefix)) {
            continue;
        }

        $className = substr($relativeClass, strlen($namespacePrefix));

        $file = $directory . '/' . $className . '.php';

        if (file_exists($file)) {
            require_once $file;
        }

        return;
    }
});