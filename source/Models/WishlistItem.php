<?php

namespace Source\Models;

use PDOException;
use Source\Core\Connect;

class WishlistItem
{
    public function add(int $user_id, int $product_id): bool
    {
        $conn = Connect::getInstance();
        // evita duplicados
        $check = $conn->prepare("SELECT id FROM wishlist_items WHERE user_id = :uid AND product_id = :pid");
        $check->bindValue(':uid', $user_id, \PDO::PARAM_INT);
        $check->bindValue(':pid', $product_id, \PDO::PARAM_INT);
        $check->execute();
        if ($check->fetch()) {
            return true;
        }
        try {
            $stmt = $conn->prepare("INSERT INTO wishlist_items (user_id, product_id) VALUES (:uid, :pid)");
            $stmt->bindValue(':uid', $user_id, \PDO::PARAM_INT);
            $stmt->bindValue(':pid', $product_id, \PDO::PARAM_INT);
            return (bool)$stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function remove(int $user_id, int $product_id): bool
    {
        $conn = Connect::getInstance();
        try {
            $stmt = $conn->prepare("DELETE FROM wishlist_items WHERE user_id = :uid AND product_id = :pid");
            $stmt->bindValue(':uid', $user_id, \PDO::PARAM_INT);
            $stmt->bindValue(':pid', $product_id, \PDO::PARAM_INT);
            return (bool)$stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function listItemsWithProducts(int $user_id): array
    {
        $conn = Connect::getInstance();
        $query = "SELECT w.product_id,
                         p.name, p.price,
                         (SELECT image_path FROM product_images WHERE product_id = w.product_id AND is_primary = 1 LIMIT 1) AS image_path
                  FROM wishlist_items w
                  INNER JOIN products p ON p.id = w.product_id
                  WHERE w.user_id = :uid";
        $stmt = $conn->prepare($query);
        $stmt->bindValue(':uid', $user_id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll() ?: [];
    }

    /**
     * Cria tabela se não existir
     */
    public function ensureTable(): void
    {
        $conn = Connect::getInstance();
        $conn->exec("CREATE TABLE IF NOT EXISTS wishlist_items (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            product_id INT NOT NULL,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            INDEX(user_id), INDEX(product_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    }
}