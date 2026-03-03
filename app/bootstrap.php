<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('APP_PATH', __DIR__);
define('ROOT_PATH', dirname(__DIR__));

spl_autoload_register(function ($className) {
    $prefix = 'App\\';

    if (strpos($className, $prefix) !== 0) {
        return;
    }

    $relativeClass = substr($className, strlen($prefix));
    $relativePath = str_replace('\\', '/', $relativeClass);

    $segments = explode('/', $relativePath);
    $lowerSegments = array_map('strtolower', $segments);

    $candidates = [
        APP_PATH . '/' . $relativePath . '.php',
        APP_PATH . '/' . implode('/', $lowerSegments) . '.php',
    ];

    foreach ($candidates as $file) {
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});
