<?php
// Simple router for PHP built-in server to support pretty URLs
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$file = __DIR__ . $requestUri;

if ($requestUri !== '/' && is_file($file)) {
    // Serve static files directly
    return false;
}

// Fallback to index.php for app routing
require __DIR__ . '/index.php';