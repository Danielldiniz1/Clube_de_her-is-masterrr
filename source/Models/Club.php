<?php

namespace Source\Models;

use PDOException;
use Source\Core\Connect;
use Source\Core\Model;

class Club extends Model {
    private $id;
    private $user_id;
    private $club_name;
    private $description;
    private $is_active;
    private $created_at;
    private $message;

    public function __construct(
        int $id = null,
        int $user_id = null,
        string $club_name = null,
        string $description = null,
        bool $is_active = true,
        string $created_at = null
    )
    {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->club_name = $club_name;
        $this->description = $description;
        $this->is_active = $is_active;
        $this->created_at = $created_at;
        $this->entity = "clubs";
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(?int $user_id): void
    {
        $this->user_id = $user_id;
    }

    public function getClubName(): ?string
    {
        return $this->club_name;
    }

    public function setClubName(?string $club_name): void
    {
        $this->club_name = $club_name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getIsActive(): ?bool
    {
        return $this->is_active;
    }

    public function setIsActive(?bool $is_active): void
    {
        $this->is_active = $is_active;
    }

    public function getCreatedAt(): ?string
    {
        return $this->created_at;
    }

    public function setCreatedAt(?string $created_at): void
    {
        $this->created_at = $created_at;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function insert(): ?int
    {
        $conn = Connect::getInstance();

        if(empty($this->club_name)){
            $this->message = "Nome do clube é obrigatório!";
            return false;
        }

        if(empty($this->user_id)){
            $this->message = "ID do usuário é obrigatório!";
            return false;
        }

        // Verificar se o usuário existe
        $query = "SELECT * FROM users WHERE id = :user_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->execute();

        if($stmt->rowCount() == 0) {
            $this->message = "Usuário não encontrado!";
            return false;
        }

        $query = "INSERT INTO clubs (user_id, club_name, description, is_active) 
                  VALUES (:user_id, :club_name, :description, :is_active)";

        $stmt = $conn->prepare($query);
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":club_name", $this->club_name);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":is_active", $this->is_active);

        try {
            $stmt->execute();
            $this->message = "Clube cadastrado com sucesso!";
            return $conn->lastInsertId();
        } catch (PDOException $exception) {
            $this->message = "Erro ao cadastrar clube: {$exception->getMessage()}";
            return false;
        }
    }

    public function update(): bool
    {
        $conn = Connect::getInstance();

        if(empty($this->club_name)){
            $this->message = "Nome do clube é obrigatório!";
            return false;
        }

        $query = "UPDATE clubs 
                  SET club_name = :club_name, description = :description, is_active = :is_active
                  WHERE id = :id";

        $stmt = $conn->prepare($query);
        $stmt->bindParam(":club_name", $this->club_name);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":is_active", $this->is_active);
        $stmt->bindParam(":id", $this->id);

        try {
            $stmt->execute();
            $this->message = "Clube atualizado com sucesso!";
            return true;
        } catch (PDOException $exception) {
            $this->message = "Erro ao atualizar clube: {$exception->getMessage()}";
            return false;
        }
    }

    public function selectByUserId(int $user_id): ?array
    {
        $conn = Connect::getInstance();
        $query = "SELECT * FROM clubs WHERE user_id = :user_id AND is_active = 1";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function delete(): bool
    {
        $conn = Connect::getInstance();
        
        $query = "DELETE FROM clubs WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":id", $this->id);

        try {
            $stmt->execute();
            $this->message = "Clube removido com sucesso!";
            return true;
        } catch (PDOException $exception) {
            $this->message = "Erro ao remover clube: {$exception->getMessage()}";
            return false;
        }
    }
}
