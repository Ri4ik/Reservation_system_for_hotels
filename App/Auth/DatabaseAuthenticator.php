<?php

namespace App\Auth;

use App\Core\IAuthenticator;
use App\Models\User;

class DatabaseAuthenticator implements IAuthenticator
{
    public function __construct()
    {
        session_start();
    }

    public function login($login, $password): bool
    {
        $user = User::getOneByEmail($login);
        if ($user && password_verify($password, $user->getPasswordHash())) {
            $_SESSION['user'] = [
                'id' => $user->getId(),
                'name' => $user->getName(),
                'role' => $user->getRole(),
                'email' => $user->getEmail()
            ];
            return true;
        }
        return false;
    }

    public function logout(): void
    {
        session_destroy();
    }

    public function getLoggedUserName(): string
    {
        return $_SESSION['user']['name'] ?? throw new \Exception("User not logged in");
    }

    public function getLoggedUserId(): mixed
    {
        return $_SESSION['user']['id'] ?? null;
    }

    public function getLoggedUserContext(): mixed
    {
        return $_SESSION['user'] ?? null;
    }

    public function isLogged(): bool
    {
        return isset($_SESSION['user']);
    }

    public function isAdmin(): bool
    {
        $user = $this->getLoggedUserContext();
        return isset($user['role']) && $user['role'] === 'admin';
    }
}
