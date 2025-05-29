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
    protected string $check_in;
    protected string $check_out;
    protected string $status;

    // Стандартні гетери і сеттери
    public function getId(): int { return $this->id; }
    public function getUserId(): int { return $this->user_id; }
    public function setUserId(int $user_id): void { $this->user_id = $user_id; }

    public function getRoomId(): int { return $this->room_id; }
    public function setRoomId(int $room_id): void { $this->room_id = $room_id; }

    public function getCheckin(): string { return $this->check_in; }
    public function setCheckin(string $check_in): void { $this->check_in = $check_in; }

    public function getCheckout(): string { return $this->check_out; }
    public function setCheckout(string $check_out): void { $this->check_out = $check_out; }

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
            SELECT r.*, u.name AS user_name, rm.name AS room_name
            FROM reservations r
            JOIN users u ON r.user_id = u.id
            JOIN rooms rm ON r.room_id = rm.id
            ORDER BY r.check_in DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Завантажити резервації певного користувача з назвами кімнат
    public static function getByUserIdWithRoom(int $userId): array
    {
        $db = Connection::connect();
        $stmt = $db->prepare("
            SELECT r.*, rm.name AS room_name
            FROM reservations r
            JOIN rooms rm ON r.room_id = rm.id
            WHERE r.user_id = :uid
            ORDER BY r.check_in DESC
        ");
        $stmt->execute([':uid' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Отримати тип кімнати (тільки якщо є в масиві)
    public function getRoomName(): ?string {
        return $this->room_name ?? null;
    }

    public function getUserName(): ?string {
        return $this->user_name ?? null;
    }
}
