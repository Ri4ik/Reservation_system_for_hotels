<?php

namespace App\Models;

use App\Core\Model;
use App\Core\DB\Connection;
use PDO;

class User extends Model
{
    protected int $id;
    protected string $name;
    protected string $email;
    protected string $phone;
    protected string $role;
    protected string $password;

    public function getId(): int { return $this->id; }
    public function getPhone(): string {
        return $this->phone;
    }

    public function setPhone(string $phone): void {
        $this->phone = $phone;
    }
    public function getName(): string { return $this->name; }
    public function setName(string $name): void { $this->name = $name; }

    public function getEmail(): string { return $this->email; }
    public function setEmail(string $email): void { $this->email = $email; }

    public function getRole(): string { return $this->role; }
    public function setRole(string $role): void { $this->role = $role; }

    public function getPasswordHash(): string { return $this->password; }
    public function setPasswordHash(string $hash): void { $this->password = $hash; }

    public static function getTableName(): string
    {
        return 'users';
    }

    public static function getAllUsers(): array
    {
        $stmt = Connection::connect()->prepare("SELECT * FROM users ORDER BY id");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, self::class);
    }

    public static function getOneByEmail(string $email): ?User
    {
        $stmt = Connection::connect()->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        //PDO::FETCH_CLASS — pri vykonávaní fetch() sa vytvorí objekt zadanej triedy (self::class - User).
        //Naplní jeho polia hodnotami z výsledku dotazu
        //PDO::FETCH_PROPS_LATE — ovplyvňuje poradie vyplnenia:
        //Najprv sa zavolá konštruktor objektu.
        //Potom PDO dosadí vlastnosti z výsledku dotazu.
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, self::class);
        return $stmt->fetch() ?: null;
    }
}
