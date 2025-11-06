<?php

function url(string $path = null): string
{
    $host = $_SERVER['HTTP_HOST'] ?? '';
    $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
    $scheme = $isHttps ? 'https' : 'http';

    // Quando rodando com o servidor embutido do PHP (ex.: localhost:8000), use o host atual
    if (strpos($host, ':8000') !== false) {
        $base = $scheme . '://' . $host;
    } else if (strpos($host, 'localhost') !== false) {
        // Ambiente local padrão (WAMP/Apache) mantendo subpasta configurada
        $base = CONF_URL_TEST;
    } else {
        // Produção
        $base = CONF_URL_BASE;
    }

    if ($path) {
        return $base . "/" . ($path[0] == "/" ? mb_substr($path, 1) : $path);
    }
    return $base;
}