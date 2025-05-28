<?php

namespace App\Controllers;

use App\Core\AControllerBase;
use App\Models\Review;

class ReviewController extends AControllerBase
{
    public function index(): \App\Core\Responses\Response
    {
        return $this->html([
            'reviews' => Review::getAll()
        ]);
    }

    public function create(): \App\Core\Responses\Response
    {
        // Якщо не авторизований — перекинути на логін
        if (!$this->app->getAuth()->isLogged()) {
            return $this->redirect(\App\Config\Configuration::LOGIN_URL);
        }

        if ($this->request()->isPost()) {
            $userId = $this->app->getAuth()->getLoggedUserId(); // точно буде int
            $content = $this->request()->getValue('comment');
            if (trim($content ?? '') === '') {
                $data['message'] = '❌ Obsah recenzie nemôže byť prázdny!';
                return $this->html($data);
            }
            Review::create($userId, $content);
            return $this->redirect("?c=review");
        }

        return $this->html();
    }


    public function edit(): \App\Core\Responses\Response
    {
        $id = $this->request()->getValue('id');

        if ($this->request()->isPost()) {
            $form = $this->request()->getPost();

            $content = trim($form['content'] ?? '');

            if ($content !== '') {
                \App\Models\Review::update($id, $content);
                return $this->redirect('?c=review');
            } else {
                $data['message'] = '❌ Obsah recenzie nemôže byť prázdny!';
            }
        }

        $review = \App\Models\Review::getById($id);

        if (!$review) {
            return $this->redirect('?c=review');
        }

        return $this->html(['review' => $review] + ($data ?? []));
    }

    public function delete(): \App\Core\Responses\Response
    {
        // Перевірка ролі
        if (!$this->app->getAuth()->isAdmin()) {
            return $this->redirect('?c=review');
        }

        $id = $this->request()->getValue('id');
        if ($id && is_numeric($id)) {
            \App\Models\Review::deleteById((int)$id);
        }

        return $this->redirect('?c=review');
    }

}
