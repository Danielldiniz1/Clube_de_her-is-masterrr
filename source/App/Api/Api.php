<?php

namespace Source\App\Api;

use Source\Core\JWTToken;


class Api
{
    protected $headers;
    protected $response;
    /** @var object|false */
    protected $userAuth = false;

    public function __construct()
    {
        header('Content-Type: application/json; charset=UTF-8');
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Cache-Control: post-check=0, pre-check=0', false);
        header('Pragma: no-cache');
        $this->headers = getallheaders();
    }

    protected function call (int $code, string $status = null, string $message = null, $type = null): Api
    {
        http_response_code($code);
        if(!empty($status)){
            $this->response = [
                "code" => $code,
                "type" => $type,
                "status" => $status,
                "message" => (!empty($message) ? $message : null)
            ];
        }
        return $this;
    }

    protected function back(array $data = null): Api
    {
        if ($data) {
            $this->response["data"] = $data;
        }
        echo json_encode($this->response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        return $this;
    }

    protected function auth(): void
    {
        $authHeader = $this->headers['Authorization'] ?? null;

        // Fallback for servers that don't pass the Authorization header properly
        if (!$authHeader && isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
        }

        $token = null;

        if ($authHeader && preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            // Padrão da web: "Authorization: Bearer <token>"
            $token = $matches[1];
        } elseif (!empty($this->headers['token'])) {
            // Fallback para o cabeçalho customizado 'token' para manter compatibilidade
            $token = $this->headers['token'];
        }

        if (empty($token)) {
            $this->call(401, "unauthorized", "Token não fornecido", "error")->back();
            exit();
        }

        $jwt = new JWTToken();
        $decoded = $jwt->decode($token);

        if (!$decoded) {
            $this->call(401, "unauthorized", "Token inválido ou expirado", "error")->back();
            exit();
        }

        $this->userAuth = $decoded->data;}
}