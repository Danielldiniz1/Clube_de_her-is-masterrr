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
    $token = $_COOKIE['token'] ?? null;
    if (!$token) {
        return null;
    }
    $jwt = new JWTToken();
    $decoded = $jwt->decode($token);
    if (!$decoded || empty($decoded->data)) {
        return null;
    }
    $data = $decoded->data;
    try {
        $userId = isset($data->id) ? (int)$data->id : 0;
        if ($userId > 0) {
            $conn = \Source\Core\Connect::getInstance();
            $stmt = $conn->prepare("SELECT id, idType, name, email, photo FROM users WHERE id = :id LIMIT 1");
            $stmt->bindValue(":id", $userId, \PDO::PARAM_INT);
            $stmt->execute();
            $fresh = $stmt->fetch();
            if ($fresh) {
                $merged = (object) [
                    'id' => (int)$fresh->id,
                    'idType' => (int)$fresh->idType,
                    'name' => $fresh->name,
                    'email' => $fresh->email,
                    'photo' => $fresh->photo
                ];
                return $merged;
            }
        }
    } catch (\Throwable $e) {}
    return $data;
}

function user_has_active_subscription(int $user_id): bool
{
    try {
        $conn = \Source\Core\Connect::getInstance();
        $stmt = $conn->prepare("SELECT 1 FROM subscriptions WHERE user_id = :uid AND status = 'active' LIMIT 1");
        $stmt->bindValue(":uid", $user_id, \PDO::PARAM_INT);
        $stmt->execute();
        return (bool)$stmt->fetch();
    } catch (\Throwable $e) {
        return false;
    }
}
