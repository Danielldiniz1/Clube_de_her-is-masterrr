<?php

function url(string $path = null): string
{
    // Detecta dinamicamente esquema, host e subdiretório base do projeto
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
    $scheme = $isHttps ? 'https' : 'http';
    $script = $_SERVER['SCRIPT_NAME'] ?? '/';
    $dir = str_replace('\\', '/', dirname($script));
    $dir = rtrim($dir, '/');
    if ($dir === '/') { $dir = ''; }
    $base = $scheme . '://' . $host . $dir;

    if ($path) {
        return $base . '/' . ($path[0] == '/' ? mb_substr($path, 1) : $path);
    }
    return $base;
}

use Source\Core\JWTToken;

function current_user(): ?object
{
    // Lê JWT do cookie 'token' (gravado no login)
    $token = $_COOKIE['token'] ?? null;
    if (!$token) {
        return null;
    }
    $jwt = new JWTToken();
    $decoded = $jwt->decode($token);
    if (!$decoded || empty($decoded->data)) {
        return null;
    }
    return $decoded->data; // objeto com propriedades do usuário (ex.: id, name, email)
}