<?php

namespace App\Controllers;

use App\Core\AControllerBase;
use App\Core\Responses\Response;
use App\Models\User;

class UserController extends AControllerBase
{
    public function authorize($action)
    {
        return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';
    }

    public function index(): Response
    {
        $users = User::getAllUsers();
        return $this->html(['users' => $users]);
    }

    public function edit(): Response
    {
        $id = (int)$this->request()->getValue('id');
        $user = User::getOne($id);

        if (!$user) {
            return $this->redirect("?c=user");
        }

        return $this->html(['user' => $user]);
    }

    public function update(): Response
    {
        $data = $this->request()->getPost();
        $user = User::getOne($data['id']);

        if ($user) {
            $user->setName($data['name']);
            $user->setEmail($data['email']);
            $user->setRole($data['role']);
            $user->save();
        }

        return $this->redirect("?c=user");
    }
}
