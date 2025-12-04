<?php

namespace Source\Models;

use Source\Core\Connect;

class Subscription
{
    public function hasActive(int $userId): bool
    {
        $conn = Connect::getInstance();
        $stmt = $conn->prepare("SELECT 1 FROM subscriptions WHERE user_id = :uid AND status = 'active' LIMIT 1");
        $stmt->bindValue(':uid', $userId, \PDO::PARAM_INT);
        $stmt->execute();
        return (bool)$stmt->fetch();
    }

    public function activateForUser(int $userId, int $clubId): bool
    {
        $conn = Connect::getInstance();
        $stmt = $conn->prepare("INSERT INTO subscriptions (user_id, club_id, status) VALUES (:uid, :cid, 'active')");
        $stmt->bindValue(':uid', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':cid', $clubId, \PDO::PARAM_INT);
        return (bool)$stmt->execute();
    }
}