<?php

namespace Source\Models;

use SorFabioSantos\Uploader\Uploader;
use PDOException;
use Source\Core\Connect;
use Source\Core\Model;

class User extends Model {
    // ... (propriedades e construtor permanecem iguais) ...
    private $id;
    private $idType;
    private $name;
    private $email;
    private $password;
    private $photo;
    private $message;

    public function __construct(
        int $id = null,
        int $idType = null,
        string $name = null,
        string $email = null,
        string $password = null,
        string $photo = null
    )
    {
        $this->id = $id;
        $this->idType = $idType;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->photo = $photo;
        $this->entity = "users";
    }

    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): void { $this->id = $id; }

    public function getIdType(): ?int { return $this->idType; }
    public function setIdType(?int $idType): void { $this->idType = $idType; }

    public function getName(): ?string { return $this->name; }
    public function setName(?string $name): void { $this->name = $name; }

    public function getEmail(): ?string { return $this->email; }
    public function setEmail(?string $email): void { $this->email = $email; }

    public function getPassword(): ?string { return $this->password; }
    public function setPassword(?string $password): void { $this->password = $password; }

    public function getPhoto(): ?string { return $this->photo; }
    public function setPhoto(?string $photo): void { $this->photo = $photo; }

    public function getMessage(): ?string { return $this->message; }
    // ... (métodos como insert, login, etc., permanecem iguais) ...
    public function insert(): ?int
    {
        $conn = Connect::getInstance();

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $this->message = "E-mail inválido!";
            return null;
        }

        $stmt = $conn->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->bindParam(":email", $this->email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $this->message = "E-mail já cadastrado!";
            return null;
        }

        $this->password = password_hash($this->password, PASSWORD_DEFAULT);

        $query = "INSERT INTO users (idType, name, email, password, photo) 
                  VALUES (:idType, :name, :email, :password, :photo)";
        
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":idType", $this->idType);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":photo", $this->photo);

        try {
            $stmt->execute();
            $this->id = $conn->lastInsertId();
            $this->message = "Usuário cadastrado com sucesso!";
            return $this->id;
        } catch (PDOException $e) {
            $this->message = "Erro ao cadastrar usuário: " . $e->getMessage();
            return null;
        }
    }
    
    public function login(string $email, string $password): bool
    {
        $query = "SELECT * FROM users WHERE email = :email";
        $conn = Connect::getInstance();
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        $result = $stmt->fetch();

        if (!$result) {
            $this->message = "E-mail não cadastrado!";
            return false;
        }

        if (!password_verify($password, $result->password)) {
            $this->message = "Senha incorreta!";
            return false;
        }

        $this->id = $result->id;
        $this->idType = $result->idType;
        $this->name = $result->name;
        $this->email = $result->email;
        $this->photo = $result->photo;
        $this->message = "Usuário logado com sucesso!";
        return true;
    }

    public function uploadPhoto(array $file): bool
    {
        // 1. Validação do arquivo
        if (empty($file['name']) || $file['error'] !== UPLOAD_ERR_OK) {
            $this->message = "Nenhum arquivo de imagem válido foi enviado.";
            return false;
        }

        // 2. Instancia o Uploader
        $uploader = new Uploader();

        // 3. Realiza o upload
        $newPhotoPath = $uploader->Image($file);

        if (!$newPhotoPath) {
            $this->message = $uploader->getMessage();
            return false;
        }

        // 4. Deleta a foto antiga, se existir
        if ($this->photo) {
            $oldPhotoFullPath = dirname(__DIR__, 2) . $this->photo;
            if (file_exists($oldPhotoFullPath)) {
                unlink($oldPhotoFullPath);
            }
        }

        // 5. Atualiza o caminho da foto no objeto e no banco de dados
        $this->setPhoto($newPhotoPath);

        try {
            $conn = Connect::getInstance();
            $stmt = $conn->prepare("UPDATE users SET photo = :photo WHERE id = :id");
            $stmt->bindParam(':photo', $this->photo);
            $stmt->bindParam(':id', $this->id);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $this->message = "Foto atualizada com sucesso!";
                return true;
            } else {
                $this->message = "Nenhuma alteração foi necessária.";
                return true;
            }
        } catch (PDOException $e) {
            $newPhotoFullPath = dirname(__DIR__, 2) . $newPhotoPath;
            if (file_exists($newPhotoFullPath)) {
                unlink($newPhotoFullPath);
            }
            $this->message = "Erro ao atualizar a foto no banco de dados.";
            error_log($e->getMessage());
            return false;
        }
    }

    public function findById(int $id): bool
    {
        $conn = Connect::getInstance();
        $query = "SELECT id, idType, name, email, photo FROM users WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":id", $id, \PDO::PARAM_INT);

        try {
            $stmt->execute();
            $result = $stmt->fetch();

            if (!$result) {
                $this->message = "Usuário não encontrado!";
                return false;
            }

            $this->id = (int)$result->id;
            $this->idType = (int)$result->idType;
            $this->name = $result->name;
            $this->email = $result->email;
            $this->photo = $result->photo;
            
            return true;
        } catch (PDOException $e) {
            $this->message = "Erro ao buscar usuário: " . $e->getMessage();
            return false;
        }
    }
    /**
     * ATUALIZA O PERFIL DE UM USUÁRIO EXISTENTE
     */
    public function updateProfile(string $name, string $email, ?int $idType = null): bool
    {
        // O ID do usuário já deve ter sido definido no construtor
        if (!$this->id) {
            $this->message = "ID do usuário não fornecido para atualização.";
            return false;
        }
        
        // Carrega os dados atuais do usuário para ter os valores de fallback (como idType)
        if (!$this->findById($this->id)) {
            // A mensagem de erro já é definida por findById
            return false;
        }

        $conn = Connect::getInstance();

        // 1. Verifica se o e-mail já está em uso por OUTRO usuário
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = :email AND id != :id");
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $this->message = "Este e-mail já está sendo usado por outra conta.";
            return false;
        }

        // 2. Prepara e executa a query de atualização
        try {
            $query = "UPDATE users SET name = :name, email = :email, idType = :idType WHERE id = :id";
            $stmt = $conn->prepare($query);
            $stmt->bindValue(":name", $name);
            $stmt->bindValue(":email", $email);
            $stmt->bindValue(":idType", $idType ?? $this->idType); // Usa o novo idType ou mantém o antigo
            $stmt->bindValue(":id", $this->id);
            $stmt->execute();

            // 3. Verifica se a atualização teve efeito
            if ($stmt->rowCount() > 0) {
                // Atualiza as propriedades do objeto
                $this->name = $name;
                $this->email = $email;
                $this->idType = $idType ?? $this->idType;
                $this->message = "Perfil atualizado com sucesso!";
                return true;
            }
            
            // Se rowCount for 0, significa que o usuário clicou em salvar sem alterar os dados.
            // Isso não é um erro.
            $this->message = "Nenhuma alteração foi detectada.";
            return true; 
            
        } catch (PDOException $exception) {
            // Captura qualquer erro do banco de dados
            $this->message = "Erro no servidor ao tentar atualizar o perfil.";
            // Para debug: error_log($exception->getMessage());
            return false;
        }
    }

    public function changePassword(string $currentPassword, string $newPassword): bool
    {
        if (!$this->id) {
            $this->message = "ID do usuário não fornecido para alterar a senha.";
            return false;
        }

        $conn = Connect::getInstance();

        $stmt = $conn->prepare("SELECT password FROM users WHERE id = :id");
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        $result = $stmt->fetch();

        if (!$result || !password_verify($currentPassword, $result->password)) {
            $this->message = "Senha atual incorreta!";
            return false;
        }

        $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("UPDATE users SET password = :password WHERE id = :id");
        $stmt->bindParam(":password", $newPasswordHash);
        $stmt->bindParam(":id", $this->id);

        try {
            $stmt->execute();
            $this->message = "Senha atualizada com sucesso!";
            return true;
        } catch (PDOException $exception) {
            $this->message = "Erro ao atualizar senha: " . $exception->getMessage();
            return false;
        }
    }

    /**
     * ATUALIZA UM USUÁRIO (USO GERAL/ADMIN)
     */
    public function update(array $data): bool
    {
        if (!$this->id) {
            $this->message = "ID do usuário não fornecido para atualização.";
            return false;
        }

        $conn = Connect::getInstance();

        // Verifica a unicidade do e-mail se ele estiver sendo alterado
        if (!empty($data['email'])) {
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = :email AND id != :id");
            $stmt->bindParam(":email", $data['email']);
            $stmt->bindParam(":id", $this->id);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $this->message = "Este e-mail já está sendo usado por outra conta.";
                return false;
            }
        }

        // Constrói a query dinamicamente
        $setClauses = [];
        if (isset($data['name'])) $setClauses[] = "name = :name";
        if (isset($data['email'])) $setClauses[] = "email = :email";
        if (isset($data['idType'])) $setClauses[] = "idType = :idType";
        if (!empty($data['password'])) $setClauses[] = "password = :password";
        if (isset($data['photo'])) $setClauses[] = "photo = :photo";

        if (empty($setClauses)) {
            $this->message = "Nenhum dado para atualizar.";
            return true; // Não é um erro
        }

        $query = "UPDATE users SET " . implode(", ", $setClauses) . " WHERE id = :id";
        $stmt = $conn->prepare($query);

        // Bind dos parâmetros
        if (isset($data['name'])) $stmt->bindValue(":name", $data['name']);
        if (isset($data['email'])) $stmt->bindValue(":email", $data['email']);
        if (isset($data['idType'])) $stmt->bindValue(":idType", $data['idType']);
        if (!empty($data['password'])) {
            $stmt->bindValue(":password", password_hash($data['password'], PASSWORD_DEFAULT));
        }
        if (isset($data['photo'])) $stmt->bindValue(":photo", $data['photo']);
        $stmt->bindValue(":id", $this->id);

        try {
            $stmt->execute();
            $this->message = "Usuário atualizado com sucesso!";
            return true;
        } catch (PDOException $e) {
            $this->message = "Erro ao atualizar usuário: " . $e->getMessage();
            return false;
        }
    }

    public function delete(): bool
    {
        if (!$this->id) {
            $this->message = "ID do usuário não fornecido para exclusão.";
            return false;
        }

        $conn = Connect::getInstance();
        $stmt = $conn->prepare("DELETE FROM users WHERE id = :id");
        $stmt->bindParam(":id", $this->id);

        try {
            $stmt->execute();
            $this->message = "Usuário removido com sucesso!";
            return true;
        } catch (PDOException $e) {
            $this->message = "Erro ao remover usuário: " . $e->getMessage();
            return false;
        }
    }
}