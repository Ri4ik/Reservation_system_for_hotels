<?php

namespace App\Models;

use App\Core\DB\Connection;
use PDO;

class Review
{
    public static function getAll(): array
    {
        $stmt = Connection::connect()->prepare("
            SELECT r.*, u.name AS user_name
            FROM reviews r
            JOIN users u ON r.user_id = u.id
            ORDER BY r.created_at DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById(int $id): ?array
    {
        $stmt = Connection::connect()->prepare("SELECT * FROM reviews WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public static function create(int $user_id, string $content, int $rating): bool
    {
        $stmt = Connection::connect()->prepare("
        INSERT INTO reviews (user_id, comment, rating, created_at)
        VALUES (:user_id, :comment, :rating, NOW())
    ");
        return $stmt->execute([
            'user_id' => $user_id,
            'comment' => $content,
            'rating' => $rating
        ]);
    }

    public static function update(int $id, string $content): bool
    {
        $stmt = Connection::connect()->prepare("
            UPDATE reviews SET comment = :comment  WHERE id = :id
        ");
        return $stmt->execute([
            'id' => $id,
            'comment' => $content
        ]);
    }
    public static function search(?string $author, ?string $date): array
    {
        $pdo = \App\Core\DB\Connection::connect();

        $sql = "SELECT r.*, u.name AS user_name 
            FROM reviews r 
            JOIN users u ON r.user_id = u.id 
            WHERE 1=1";

        $params = [];

        if (!empty($author)) {
            $sql .= " AND u.name LIKE :author";
            $params['author'] = '%' . $author . '%';
        }

        if (!empty($date)) {
            $sql .= " AND DATE(r.created_at) = :date";
            $params['date'] = $date;
        }

        $sql .= " ORDER BY r.created_at DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    public static function deleteById(int $id): bool
    {
        $stmt = Connection::connect()->prepare("DELETE FROM reviews WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
