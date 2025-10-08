<?php

namespace Source\App\Api;

use Source\Core\Email; // Adicione esta linha
use Source\Core\JWTToken;
use Source\Models\User;
use Exception;

class Users extends Api
{
    public function __construct()
    {
        parent::__construct();
    }

    // ... (outros métodos como listUsers, insertUser, loginUser permanecem iguais) ...
    
    public function listUsers()
    {
        $user = new User();
        $this->call(200, "success", "Lista de usuários recuperada", "success")->back([
            "users" => $user->selectAll()
        ]);
    }

    public function insertUser(array $data)
    {
        if (empty($data["name"]) || empty($data["email"]) || empty($data["password"])) {
            $this->call(400, "error", "Nome, e-mail e senha são obrigatórios", "error")->back();
            return;
        }

        $idType = $data["idType"] ?? 2;

        $user = new User(
            null,
            (int)$idType,
            $data["name"],
            $data["email"],
            $data["password"],
            $data["photo"] ?? null
        );

        $insert = $user->insert();

        if (!$insert) {
            $this->call(400, "error", $user->getMessage() ?? "Erro na requisição", "error")->back();
            return;
        }

        // ** Início da nova funcionalidade **
        try {
            $body = "<h1>Bem-vindo ao Clube de Heróis!</h1><p>Olá, {$data['name']}! Seu cadastro foi um sucesso.</p><p>Explore nosso universo de colecionáveis!</p>";
            $email = new Email();
            $email->sendEmail($data['email'], 'Bem-vindo ao Clube de Heróis', $body);
        } catch (Exception $e) {
            error_log("Erro ao enviar e-mail de boas-vindas: " . $e->getMessage());
        }
        // ** Fim da nova funcionalidade **

        $this->call(201, "success", "Usuário cadastrado com sucesso", "success")->back([
            "user" => [
                "id" => $insert,
                "idType" => $user->getIdType(),
                "name" => $user->getName(),
                "email" => $user->getEmail(),
                "photo" => $user->getPhoto()
            ]
        ]);
    }


    public function loginUser(array $data)
    {
        $user = new User();

        if (!$user->login($data["email"], $data["password"])) {
            $this->call(401, "error", $user->getMessage() ?? "Credenciais inválidas", "error")->back();
            return;
        }

        $token = new JWTToken();
        $this->call(200, "success", $user->getMessage(), "success")->back([
            "user" => [
                "id" => $user->getId(),
                "idType" => $user->getIdType(),
                "name" => $user->getName(),
                "email" => $user->getEmail(),
                "photo" => $user->getPhoto(),
                "token" => $token->create([
                    "id" => $user->getId(),
                    "name" => $user->getName(),
                    "email" => $user->getEmail(),
                    "idType" => $user->getIdType()
                ])
            ]
        ]);
    }

    /**
     * ATUALIZAÇÃO DO PERFIL DO USUÁRIO LOGADO
     */
    public function updateProfile(array $data): void
    {
        // O método auth() interrompe a execução se a autenticação falhar.
        $this->auth();

        // Valida se os campos necessários foram enviados
        if (empty($data["name"]) || empty($data["email"])) {
            $this->call(400, "error", "Nome e e-mail são obrigatórios.", "validation_error")->back();
            return;
        }

        // Cria uma instância do usuário com o ID obtido do token autenticado
        $user = new User($this->userAuth->id);

        // Chama o método do modelo para atualizar o perfil
        if (!$user->updateProfile($data["name"], $data["email"], $data["idType"] ?? null)) {
            // Se a atualização falhar, retorna a mensagem de erro específica do modelo
            $this->call(400, "error", $user->getMessage(), "update_failed")->back();
            return;
        }

        // Se a atualização for bem-sucedida, retorna a mensagem de sucesso
        $this->call(200, "success", $user->getMessage(), "success")->back([
            "user" => [
                "name" => $user->getName(),
                "email" => $user->getEmail(),
                "idType" => $user->getIdType()
            ]
        ]);
    }
    
    public function setPassword(array $data): void
    {
        // O método auth() interrompe a execução se a autenticação falhar.
        $this->auth();

        // Valida se os campos necessários foram enviados
        $required = ["password", "newPassword", "confirmNewPassword"];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                $this->call(400, "validation_error", "O campo {$field} é obrigatório.", "error")->back();
                return;
            }
        }

        // Valida se a nova senha e a confirmação são iguais
        if ($data["newPassword"] !== $data["confirmNewPassword"]) {
            $this->call(400, "validation_error", "A nova senha e a confirmação não correspondem.", "error")->back();
            return;
        }

        // Cria uma instância do usuário com o ID obtido do token autenticado
        $user = new User($this->userAuth->id);

        // Chama o método do modelo para alterar a senha
        if (!$user->changePassword($data["password"], $data["newPassword"])) {
            // Se a alteração falhar, retorna a mensagem de erro específica do modelo
            $this->call(400, "update_failed", $user->getMessage(), "error")->back();
            return;
        }

        // Se a alteração for bem-sucedida, retorna a mensagem de sucesso
        $this->call(200, "success", $user->getMessage(), "success")->back();
    }

    public function updateUser(array $data): void
    {
        // Idealmente, adicionar uma verificação de permissão de administrador aqui
        // $this->auth();
        
        if (empty($data['id'])) {
            $this->call(400, "error", "O ID do usuário é obrigatório.", "error")->back();
            return;
        }

        $updateData = $this->filterData($data, ['name', 'email', 'idType', 'password', 'photo']);
        
        // Não atualiza a senha se o campo estiver vazio
        if (empty($updateData['password'])) {
            unset($updateData['password']);
        }

        $user = new User((int)$data['id']);
        if (!$user->findById((int)$data['id'])) {
            $this->call(404, "error", "Usuário não encontrado.", "error")->back();
            return;
        }

        if (!$user->update($updateData)) {
            $this->call(400, "error", $user->getMessage(), "error")->back();
            return;
        }

        $this->call(200, "success", "Usuário atualizado com sucesso.", "success")->back();
    }

    public function deleteUser(array $data): void
    {
        // Idealmente, adicionar uma verificação de permissão de administrador aqui
        // $this->auth();

        if (empty($data['id'])) {
            $this->call(400, "error", "O ID do usuário é obrigatório.", "error")->back();
            return;
        }

        $user = new User((int)$data['id']);
        if (!$user->delete()) {
            $this->call(404, "error", $user->getMessage(), "error")->back();
            return;
        }

        $this->call(200, "success", "Usuário removido com sucesso.", "success")->back();
    }

    private function filterData(array $data, array $fields): array
    {
        $filteredData = [];
        foreach ($fields as $field) {
            if (isset($data[$field])) {
                $filteredData[$field] = filter_var($data[$field], FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        return $filteredData;
    }

    // ... (outros métodos como getUserById, etc., permanecem iguais) ...
    public function getUserById(array $data)
    {
        if (empty($data['id']) || !is_numeric($data['id'])) {
            $this->call(400, "error", "O ID do usuário é inválido ou não foi fornecido.", "error")->back();
            return;
        }

        $userId = (int)$data['id'];
        $user = new User();
        
        if (!$user->findById($userId)) {
            $this->call(404, "error", $user->getMessage() ?? "Usuário não encontrado.", "error")->back();
            return;
        }

        $this->call(200, "success", "Usuário recuperado com sucesso", "success")->back([
            "user" => [
                "id" => $user->getId(),
                "idType" => $user->getIdType(),
                "name" => $user->getName(),
                "email" => $user->getEmail(),
                "photo" => $user->getPhoto()
            ]
        ]);
    }

    public function updatePhoto(array $data): void
    {
        $this->auth();

        if (empty($_FILES['photo'])) {
            $this->call(400, "bad_request", "Nenhum arquivo de foto enviado.", "error")->back();
            return;
        }

        $user = new User();
        if (!$user->findById($this->userAuth->id)) {
            $this->call(404, "not_found", "Usuário não encontrado.", "error")->back();
            return;
        }

        // O método `uploadPhoto` no modelo User lidará com a lógica de upload e atualização do banco de dados.
        // Precisaremos criá-lo a seguir.
        if (!$user->uploadPhoto($_FILES['photo'])) {
            $this->call(500, "internal_server_error", $user->getMessage(), "error")->back();
            return;
        }

        $this->call(200, "success", "Foto atualizada com sucesso!", "success")->back([
            "user" => [
                "id" => $user->getId(),
                "photo" => $user->getPhoto()
            ]
        ]);
    }

}