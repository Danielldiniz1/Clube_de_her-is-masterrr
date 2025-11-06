<?php

namespace Source\Models;

use PDOException;
use Source\Core\Connect;
use Source\Core\Model;

class CartItem extends Model {
    protected $entity = "cart_items";

    private $id;
    private $user_id;
    private $product_id;
    private $quantity;
    private $created_at;
    private $message;

    public function __construct(
        int $id = null,
        int $user_id = null,
        int $product_id = null,
        int $quantity = 1,
        string $created_at = null
    ) {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->product_id = $product_id;
        $this->quantity = $quantity;
        $this->created_at = $created_at;
        $this->entity = "cart_items";
    }

    public function getMessage(): ?string { return $this->message; }

    public function addOrIncrement(int $user_id, int $product_id, int $quantity = 1): bool
    {
        $conn = Connect::getInstance();

        // Verifica se já existe item do produto no carrinho do usuário
        $check = $conn->prepare("SELECT id, quantity FROM cart_items WHERE user_id = :uid AND product_id = :pid");
        $check->bindParam(":uid", $user_id);
        $check->bindParam(":pid", $product_id);
        $check->execute();
        $existing = $check->fetch();

        try {
            if ($existing) {
                $newQty = ((int)$existing->quantity) + max(1, $quantity);
                $upd = $conn->prepare("UPDATE cart_items SET quantity = :q WHERE id = :id");
                $upd->bindParam(":q", $newQty, \PDO::PARAM_INT);
                $upd->bindParam(":id", $existing->id, \PDO::PARAM_INT);
                $upd->execute();
            } else {
                $ins = $conn->prepare("INSERT INTO cart_items (user_id, product_id, quantity) VALUES (:uid, :pid, :q)");
                $qty = max(1, $quantity);
                $ins->bindParam(":uid", $user_id, \PDO::PARAM_INT);
                $ins->bindParam(":pid", $product_id, \PDO::PARAM_INT);
                $ins->bindParam(":q", $qty, \PDO::PARAM_INT);
                $ins->execute();
            }
            $this->message = "Item adicionado/atualizado no carrinho";
            return true;
        } catch (PDOException $e) {
            $this->message = "Erro ao salvar item no carrinho: " . $e->getMessage();
            return false;
        }
    }

    public function setQuantity(int $user_id, int $product_id, int $quantity): bool
    {
        $conn = Connect::getInstance();
        try {
            $qty = max(1, $quantity);
            $stmt = $conn->prepare("UPDATE cart_items SET quantity = :q WHERE user_id = :uid AND product_id = :pid");
            $stmt->bindParam(":q", $qty, \PDO::PARAM_INT);
            $stmt->bindParam(":uid", $user_id, \PDO::PARAM_INT);
            $stmt->bindParam(":pid", $product_id, \PDO::PARAM_INT);
            $stmt->execute();
            $this->message = "Quantidade atualizada";
            return true;
        } catch (PDOException $e) {
            $this->message = "Erro ao atualizar quantidade: " . $e->getMessage();
            return false;
        }
    }

    public function removeItem(int $user_id, int $product_id): bool
    {
        $conn = Connect::getInstance();
        try {
            $stmt = $conn->prepare("DELETE FROM cart_items WHERE user_id = :uid AND product_id = :pid");
            $stmt->bindParam(":uid", $user_id, \PDO::PARAM_INT);
            $stmt->bindParam(":pid", $product_id, \PDO::PARAM_INT);
            $stmt->execute();
            $this->message = "Item removido";
            return true;
        } catch (PDOException $e) {
            $this->message = "Erro ao remover item: " . $e->getMessage();
            return false;
        }
    }

    public function clear(int $user_id): bool
    {
        $conn = Connect::getInstance();
        try {
            $stmt = $conn->prepare("DELETE FROM cart_items WHERE user_id = :uid");
            $stmt->bindParam(":uid", $user_id, \PDO::PARAM_INT);
            $stmt->execute();
            $this->message = "Carrinho limpo";
            return true;
        } catch (PDOException $e) {
            $this->message = "Erro ao limpar carrinho: " . $e->getMessage();
            return false;
        }
    }

    public function listItemsWithProducts(int $user_id): array
    {
        $conn = Connect::getInstance();
        $query = "SELECT ci.product_id, ci.quantity,
                         p.name, p.price,
                         (SELECT image_path FROM product_images WHERE product_id = ci.product_id AND is_primary = 1 LIMIT 1) AS image_path
                  FROM cart_items ci
                  INNER JOIN products p ON p.id = ci.product_id
                  WHERE ci.user_id = :uid";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":uid", $user_id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll() ?: [];
    }
}