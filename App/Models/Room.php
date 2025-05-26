<?php

namespace App\Models;

use App\Core\DB\Connection;
use PDO;

class Room
{
    // Отримати всі номери
    public static function getAllRooms(): array
    {
        $stmt = Connection::connect()->prepare("SELECT * FROM rooms ORDER BY id");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Отримати номер за ID
    public static function getRoomById(int $id): ?array
    {
        $stmt = Connection::connect()->prepare("SELECT * FROM rooms WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    // Додати новий номер
    public static function createRoom(string $type, int $capacity, string $description, string $image): bool
    {
        $stmt = Connection::connect()->prepare(
            "INSERT INTO rooms (type, capacity, description, image) 
             VALUES (:type, :capacity, :description, :image)"
        );
        return $stmt->execute([
            'type' => $type,
            'capacity' => $capacity,
            'description' => $description,
            'image' => $image
        ]);
    }

    // Оновити існуючий номер
    public static function updateRoom(int $id, string $type, int $capacity, string $description, string $image): bool
    {
        $stmt = Connection::connect()->prepare(
            "UPDATE rooms 
             SET type = :type, capacity = :capacity, description = :description, image = :image 
             WHERE id = :id"
        );
        return $stmt->execute([
            'id' => $id,
            'type' => $type,
            'capacity' => $capacity,
            'description' => $description,
            'image' => $image
        ]);
    }

    // Видалити номер
    public static function deleteRoom(int $id): bool
    {
        $stmt = Connection::connect()->prepare("DELETE FROM rooms WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
