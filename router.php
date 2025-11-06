<?php
// Simple router for PHP built-in server to route all requests to index.php
// Serves existing static files directly; otherwise boots the app router.
if (php_sapi_name() === 'cli-server') {
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $fullPath = __DIR__ . $path;

    if ($path !== '/' && file_exists($fullPath) && is_file($fullPath)) {
        return false; // Serve the requested resource as-is.
    }
}

require __DIR__ . '/index.php';