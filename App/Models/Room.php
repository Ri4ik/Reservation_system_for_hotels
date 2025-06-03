<?php

namespace App\Models;

use App\Core\DB\Connection;
use PDO;

class Room
{
    public static function getAllRooms(): array
    {
        $stmt = Connection::connect()->prepare("SELECT * FROM rooms ORDER BY id");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getRoomByID(string $id): ?array
    {
        $stmt = Connection::connect()->prepare("SELECT * FROM rooms WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public static function createRoom(string $name, int $capacity, string $description, string $image1, string $image2, string $image3, float $price): bool
    {
        $stmt = Connection::connect()->prepare(
            "INSERT INTO rooms (name, capacity, description, image1, image2, image3, price) 
         VALUES (:name, :capacity, :description, :image1, :image2, :image3, :price)"
        );
        return $stmt->execute([
            'name' => $name,
            'capacity' => $capacity,
            'description' => $description,
            'image1' => $image1,
            'image2' => $image2,
            'image3' => $image3,
            'price' => $price
        ]);
    }

    public static function updateRoom(int $id, string $name, int $capacity, string $description, string $image1, string $image2, string $image3, float $price): bool
    {
        $stmt = Connection::connect()->prepare(
            "UPDATE rooms SET name = :name, capacity = :capacity, description = :description, 
             image1 = :image1, image2 = :image2, image3 = :image3, price = :price WHERE id = :id"
        );
        return $stmt->execute([
            'id' => $id,
            'name' => $name,
            'capacity' => $capacity,
            'description' => $description,
            'image1' => $image1,
            'image2' => $image2,
            'image3' => $image3,
            'price' => $price
        ]);
    }

    public static function deleteRoom(int $id): bool
    {
        // Сначала получаем саму запись, чтобы знать имена файлов
        $stmt = Connection::connect()->prepare("SELECT image1, image2, image3 FROM rooms WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $room = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($room) {
            // Удаляем файлы, если они существуют
            foreach (['image1', 'image2', 'image3'] as $field) {
                if (!empty($room[$field])) {
                    $path = 'public/images/' . $room[$field];
                    if (file_exists($path)) {
                        unlink($path);
                    }
                }
            }
        }

        // Теперь удаляем запись из базы
        $stmtDelete = Connection::connect()->prepare("DELETE FROM rooms WHERE id = :id");
        return $stmtDelete->execute(['id' => $id]);
    }

}
