<?php

namespace Source\Models;

use PDOException;
use Source\Core\Connect;

class Order
{
    public function ensureTables(): void
    {
        $conn = Connect::getInstance();
        // orders table
        $conn->exec(
            "CREATE TABLE IF NOT EXISTS orders (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                order_number VARCHAR(64) NOT NULL,
                status VARCHAR(32) NOT NULL DEFAULT 'completed',
                total DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                INDEX(user_id),
                UNIQUE KEY(order_number)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
        );

        // order_items table
        $conn->exec(
            "CREATE TABLE IF NOT EXISTS order_items (
                id INT AUTO_INCREMENT PRIMARY KEY,
                order_id INT NOT NULL,
                product_id INT NOT NULL,
                name VARCHAR(255) NOT NULL,
                price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                quantity INT NOT NULL DEFAULT 1,
                subtotal DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                INDEX(order_id),
                INDEX(product_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
        );
    }

    /**
     * Cria um pedido a partir dos itens do carrinho
     * @param int $userId
     * @param array $items Cada item deve conter: product_id, name, price, quantity
     * @return array{order_id:int, order_number:string, total:float}|null
     */
    public function createFromCart(int $userId, array $items): ?array
    {
        if (empty($items)) {
            return null;
        }
        $conn = Connect::getInstance();

        $orderNumber = date('Ymd-His') . '-' . random_int(100, 999);
        $total = 0.0;
        foreach ($items as $item) {
            $qty = (int)($item->quantity ?? 1);
            $price = (float)($item->price ?? 0);
            $total += ($qty * $price);
        }

        try {
            // Begin transaction
            $conn->beginTransaction();
            // Insert order
            $stmt = $conn->prepare("INSERT INTO orders (user_id, order_number, status, total) VALUES (:uid, :onum, 'completed', :total)");
            $stmt->bindValue(':uid', $userId, \PDO::PARAM_INT);
            $stmt->bindValue(':onum', $orderNumber, \PDO::PARAM_STR);
            $stmt->bindValue(':total', $total);
            $stmt->execute();
            $orderId = (int)$conn->lastInsertId();

            // Insert items
            $itemStmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, name, price, quantity, subtotal) VALUES (:oid, :pid, :name, :price, :qty, :sub)");
            foreach ($items as $item) {
                $pid = (int)($item->product_id ?? 0);
                $name = (string)($item->name ?? 'Produto');
                $qty = max(1, (int)($item->quantity ?? 1));
                $price = (float)($item->price ?? 0);
                $sub = $qty * $price;
                $itemStmt->bindValue(':oid', $orderId, \PDO::PARAM_INT);
                $itemStmt->bindValue(':pid', $pid, \PDO::PARAM_INT);
                $itemStmt->bindValue(':name', $name, \PDO::PARAM_STR);
                $itemStmt->bindValue(':price', $price);
                $itemStmt->bindValue(':qty', $qty, \PDO::PARAM_INT);
                $itemStmt->bindValue(':sub', $sub);
                $itemStmt->execute();
            }

            $conn->commit();
            return [
                'order_id' => $orderId,
                'order_number' => $orderNumber,
                'total' => $total
            ];
        } catch (PDOException $e) {
            if ($conn->inTransaction()) {
                $conn->rollBack();
            }
            return null;
        }
    }

    /**
     * Lista pedidos do usuário com agregados
     */
    public function listByUser(int $userId): array
    {
        $conn = Connect::getInstance();
        $stmt = $conn->prepare("SELECT id, order_number, status, total, created_at FROM orders WHERE user_id = :uid ORDER BY id DESC");
        $stmt->bindValue(':uid', $userId, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll() ?: [];
    }

    /**
     * Lista itens de um pedido
     */
    public function listItems(int $orderId): array
    {
        $conn = Connect::getInstance();
        $stmt = $conn->prepare("SELECT product_id, name, price, quantity, subtotal FROM order_items WHERE order_id = :oid");
        $stmt->bindValue(':oid', $orderId, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll() ?: [];
    }
}