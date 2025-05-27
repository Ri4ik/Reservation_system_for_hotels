<?php

namespace App\Controllers;

use App\Config\Configuration;
use App\Core\AControllerBase;
use App\Core\Responses\Response;
use App\Core\Responses\ViewResponse;

/**
 * Class AuthController
 * Controller for authentication actions
 * @package App\Controllers
 */
class AuthController extends AControllerBase
{
    /**
     *
     * @return Response
     */
    public function index(): Response
    {
        return $this->redirect(Configuration::LOGIN_URL);
    }

    /**
     * Login a user
     * @return Response
     */
    public function login(): Response
    {
        $formData = $this->app->getRequest()->getPost();
        $logged = null;
        if (isset($formData['submit'])) {
            $logged = $this->app->getAuth()->login($formData['login'], $formData['password']);
            if ($logged) {
                return $this->redirect($this->url("admin.index"));
            }
        }

        $data = ($logged === false ? ['message' => 'Zlý login alebo heslo!'] : []);
        return $this->html($data);
    }

    /**
     * Logout a user
     * @return ViewResponse
     */
    public function logout(): Response
    {
        $this->app->getAuth()->logout();
        return $this->html();
    }
    public function register(): Response
    {
        $data = [];

        if ($this->request()->isPost()) {
            $form = $this->request()->getPost();

            $name = trim($form['name'] ?? '');
            $email = trim($form['email'] ?? '');
            $phone = trim($form['phone'] ?? '');
            $password = trim($form['password'] ?? '');
            $data = [
                'name' => $form['name'] ?? '',
                'email' => $form['email'] ?? '',
                'phone' => $form['phone'] ?? '',
            ];

            if (empty($name) || empty($email) || empty($phone) || empty($password)) {
                $data['message'] = "❌ Všetky polia musia byť vyplnené!";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $data['message'] = "❌ Nesprávny formát emailu!";
            } elseif (!preg_match('/^\+?[0-9]{10,15}$/', $phone)) {
                $data['message'] = "❌ Nesprávny formát telefónneho čísla!";
            } elseif (strlen($password) < 6) {
                $data['message'] = "❌ Heslo musí mať aspoň 6 znakov!";
            } elseif (\App\Models\User::getOneByEmail($email)) {
                $data['message'] = "❌ Tento email je už zaregistrovaný!";
            } else {

                $user = new \App\Models\User();
                $user->setName($name);
                $user->setEmail($email);
                $user->setPhone($phone);
                $user->setRole('client');
                $user->setPasswordHash(password_hash($password, PASSWORD_BCRYPT));
                $user->save();

                return $this->redirect("?c=auth&a=login");
            }
        }

        return $this->html($data);
    }
}
