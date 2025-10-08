<?php

namespace Source\Models;

use PDOException;
use Source\Core\Connect;
use Source\Core\Model;

class ProductImage extends Model {
    private $id;
    private $product_id;
    private $image_path;
    private $is_primary;
    private $display_order;
    private $created_at;
    private $message;

    public function __construct(
        int $id = null,
        int $product_id = null,
        string $image_path = null,
        bool $is_primary = false,
        int $display_order = 0,
        string $created_at = null
    )
    {
        $this->id = $id;
        $this->product_id = $product_id;
        $this->image_path = $image_path;
        $this->is_primary = $is_primary;
        $this->display_order = $display_order;
        $this->created_at = $created_at;
        $this->entity = "product_images";
    }

    // Getters
    public function getId(): ?int { return $this->id; }
    public function getProductId(): ?int { return $this->product_id; }
    public function getImagePath(): ?string { return $this->image_path; }
    public function getIsPrimary(): ?bool { return $this->is_primary; }
    public function getDisplayOrder(): ?int { return $this->display_order; }
    public function getCreatedAt(): ?string { return $this->created_at; }
    public function getMessage(): ?string { return $this->message; }

    // Setters
    public function setId(?int $id): void { $this->id = $id; }
    public function setProductId(?int $product_id): void { $this->product_id = $product_id; }
    public function setImagePath(?string $image_path): void { $this->image_path = $image_path; }
    public function setIsPrimary(?bool $is_primary): void { $this->is_primary = $is_primary; }
    public function setDisplayOrder(?int $display_order): void { $this->display_order = $display_order; }
    public function setCreatedAt(?string $created_at): void { $this->created_at = $created_at; }

    public function insert(): ?int
    {
        $conn = Connect::getInstance();

        if(empty($this->product_id)){
            $this->message = "ID do produto é obrigatório!";
            return false;
        }

        if(empty($this->image_path)){
            $this->message = "Caminho da imagem é obrigatório!";
            return false;
        }

        // Se esta imagem for marcada como primária, desmarcar outras como primárias
        if($this->is_primary) {
            $this->unsetOtherPrimaryImages();
        }

        $query = "INSERT INTO product_images (product_id, image_path, is_primary, display_order) 
                  VALUES (:product_id, :image_path, :is_primary, :display_order)";

        $stmt = $conn->prepare($query);
        $stmt->bindParam(":product_id", $this->product_id);
        $stmt->bindParam(":image_path", $this->image_path);
        $stmt->bindParam(":is_primary", $this->is_primary);
        $stmt->bindParam(":display_order", $this->display_order);

        try {
            $stmt->execute();
            $this->message = "Imagem adicionada com sucesso!";
            return $conn->lastInsertId();
        } catch (PDOException $exception) {
            $this->message = "Erro ao adicionar imagem: {$exception->getMessage()}";
            return false;
        }
    }

    public function getByProductId(int $product_id): ?array
    {
        $conn = Connect::getInstance();
        $query = "SELECT * FROM product_images WHERE product_id = :product_id ORDER BY is_primary DESC, display_order ASC";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":product_id", $product_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getPrimaryImageByProductId(int $product_id): ?array
    {
        $conn = Connect::getInstance();
        $query = "SELECT * FROM product_images WHERE product_id = :product_id AND is_primary = 1 LIMIT 1";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":product_id", $product_id);
        $stmt->execute();
        return $stmt->fetch();
    }

    private function unsetOtherPrimaryImages(): void
    {
        $conn = Connect::getInstance();
        $query = "UPDATE product_images SET is_primary = 0 WHERE product_id = :product_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":product_id", $this->product_id);
        $stmt->execute();
    }

    public function setPrimary(): bool
    {
        $conn = Connect::getInstance();
        
        // Primeiro, desmarcar outras imagens como primárias
        $this->unsetOtherPrimaryImages();
        
        // Depois, marcar esta como primária
        $query = "UPDATE product_images SET is_primary = 1 WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":id", $this->id);

        try {
            $stmt->execute();
            $this->is_primary = true;
            $this->message = "Imagem definida como principal!";
            return true;
        } catch (PDOException $exception) {
            $this->message = "Erro ao definir imagem como principal: {$exception->getMessage()}";
            return false;
        }
    }

    public function delete(): bool
    {
        $conn = Connect::getInstance();
        
        // Se a imagem a ser deletada for a primária, definir outra como primária
        if($this->is_primary) {
            $query = "SELECT id FROM product_images WHERE product_id = :product_id AND id != :id LIMIT 1";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(":product_id", $this->product_id);
            $stmt->bindParam(":id", $this->id);
            $stmt->execute();
            
            $nextImage = $stmt->fetch();
            if($nextImage) {
                $updateQuery = "UPDATE product_images SET is_primary = 1 WHERE id = :next_id";
                $updateStmt = $conn->prepare($updateQuery);
                $updateStmt->bindParam(":next_id", $nextImage['id']);
                $updateStmt->execute();
            }
        }
        
        $query = "DELETE FROM product_images WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":id", $this->id);

        try {
            $stmt->execute();
            $this->message = "Imagem removida com sucesso!";
            return true;
        } catch (PDOException $exception) {
            $this->message = "Erro ao remover imagem: {$exception->getMessage()}";
            return false;
        }
    }

    public function updateDisplayOrder(int $new_order): bool
    {
        $conn = Connect::getInstance();
        
        $query = "UPDATE product_images SET display_order = :display_order WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":display_order", $new_order);
        $stmt->bindParam(":id", $this->id);

        try {
            $stmt->execute();
            $this->display_order = $new_order;
            $this->message = "Ordem da imagem atualizada!";
            return true;
        } catch (PDOException $exception) {
            $this->message = "Erro ao atualizar ordem da imagem: {$exception->getMessage()}";
            return false;
        }
    }

    public function deleteByProductId(int $product_id): bool
    {
        $conn = Connect::getInstance();
        
        $query = "DELETE FROM product_images WHERE product_id = :product_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":product_id", $product_id);

        try {
            $stmt->execute();
            $this->message = "Imagens do produto removidas com sucesso!";
            return true;
        } catch (PDOException $exception) {
            $this->message = "Erro ao remover imagens do produto: {$exception->getMessage()}";
            return false;
        }
    }
}