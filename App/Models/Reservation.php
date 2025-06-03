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
        SELECT r.*, u.name AS user_name, u.email AS user_email, rm.name AS room_name
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
    public static function searchReservations(
        bool $isAdmin,
        ?int $currentUserId,
        ?string $userName = '',
        ?string $roomName = '',
        ?string $status = '',
        ?string $dateFrom = '',
        ?string $dateTo = ''
    ): array
    {
        $db = Connection::connect();

        // Базовый SELECT
        $sql = "
        SELECT r.*, u.name AS user_name, u.email AS user_email, rm.name AS room_name
        FROM reservations r
        JOIN users u ON r.user_id = u.id
        JOIN rooms rm ON r.room_id = rm.id
        WHERE 1=1
    ";

        $params = [];

        // Если не админ — всегда фильтруем по своему userId
        if (!$isAdmin) {
            $sql .= " AND r.user_id = :uid";
            $params[':uid'] = $currentUserId;
        } else {
            // Для админа — если передан фильтр по имени пользователя
            if (!empty($userName)) {
                $sql .= " AND u.name LIKE :user";
                $params[':user'] = "%$userName%";
            }
        }

        // Общие фильтры
        if (!empty($roomName)) {
            $sql .= " AND rm.name LIKE :room";
            $params[':room'] = "%$roomName%";
        }

        if (!empty($status)) {
            $sql .= " AND r.status = :status";
            $params[':status'] = $status;
        }

        if (!empty($dateFrom)) {
            $sql .= " AND r.check_in >= :dateFrom";
            $params[':dateFrom'] = $dateFrom;
        }

        if (!empty($dateTo)) {
            $sql .= " AND r.check_out <= :dateTo";
            $params[':dateTo'] = $dateTo;
        }

        $sql .= " ORDER BY r.check_in DESC";

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function hasConflict(int $roomId, string $dateFrom, string $dateTo, ?int $excludeReservationId = null): bool
    {
        $db = Connection::connect();

        $sql = "
        SELECT COUNT(*) as cnt FROM reservations
        WHERE room_id = :roomId
          AND status = 'potvrdená'
          AND NOT (check_out <= :dateFrom OR check_in >= :dateTo)
    ";

        $params = [
            ':roomId' => $roomId,
            ':dateFrom' => $dateFrom,
            ':dateTo' => $dateTo
        ];

        // Если редактируем — исключаем текущую резервацию из проверки
        if ($excludeReservationId !== null) {
            $sql .= " AND id != :excludeId";
            $params[':excludeId'] = $excludeReservationId;
        }

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['cnt'] > 0;
    }

    public static function getUnavailableDatesForRoom(int $roomId): array
    {
        $db = Connection::connect();
        $stmt = $db->prepare("
        SELECT check_in, check_out 
        FROM reservations 
        WHERE room_id = :room_id AND status = 'potvrdená'
    ");
        $stmt->execute(['room_id' => $roomId]);

        $unavailableDates = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $start = new \DateTime($row['check_in']);
            $end = new \DateTime($row['check_out']);

            while ($start <= $end) {
                $unavailableDates[] = $start->format('Y-m-d');
                $start->modify('+1 day');
            }
        }

        return $unavailableDates;
    }

}
