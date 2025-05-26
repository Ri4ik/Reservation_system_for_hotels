<?php

namespace App\Models;

use App\Core\Model;
use App\Core\DB\Connection;
use PDO;

class Reservation extends Model
{
    protected int $id;
    protected int $user_id;
    protected int $room_id;
    protected string $date_from;
    protected string $date_to;
    protected string $status;

    // Стандартні гетери і сеттери
    public function getId(): int { return $this->id; }
    public function getUserId(): int { return $this->user_id; }
    public function setUserId(int $user_id): void { $this->user_id = $user_id; }

    public function getRoomId(): int { return $this->room_id; }
    public function setRoomId(int $room_id): void { $this->room_id = $room_id; }

    public function getDateFrom(): string { return $this->date_from; }
    public function setDateFrom(string $date_from): void { $this->date_from = $date_from; }

    public function getDateTo(): string { return $this->date_to; }
    public function setDateTo(string $date_to): void { $this->date_to = $date_to; }

    public function getStatus(): string { return $this->status; }
    public function setStatus(string $status): void { $this->status = $status; }

    // Таблиця у БД
    public static function getTableName(): string
    {
        return 'reservations';
    }

    // Завантажити всі резервації з даними користувача і кімнати (адміну)
    public static function getAllWithUsersAndRooms(): array
    {
        $db = Connection::connect();
        $stmt = $db->prepare("
            SELECT r.*, u.name AS user_name, rm.type AS room_type
            FROM reservations r
            JOIN users u ON r.user_id = u.id
            JOIN rooms rm ON r.room_id = rm.id
            ORDER BY r.date_from DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Завантажити резервації певного користувача з назвами кімнат
    public static function getByUserIdWithRoom(int $userId): array
    {
        $db = Connection::connect();
        $stmt = $db->prepare("
            SELECT r.*, rm.type AS room_type
            FROM reservations r
            JOIN rooms rm ON r.room_id = rm.id
            WHERE r.user_id = :uid
            ORDER BY r.date_from DESC
        ");
        $stmt->execute([':uid' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Отримати тип кімнати (тільки якщо є в масиві)
    public function getRoomType(): ?string {
        return $this->room_type ?? null;
    }

    public function getUserName(): ?string {
        return $this->user_name ?? null;
    }
}
