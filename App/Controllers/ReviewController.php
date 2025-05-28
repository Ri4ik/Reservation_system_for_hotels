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
    public function ajaxDelete(): \App\Core\Responses\JsonResponse
    {
        if (!$this->app->getAuth()->isAdmin()) {
            return $this->json(['success' => false, 'message' => 'Unauthorized']);
        }

        $id = $this->request()->getValue('review_id');

        if ($id && is_numeric($id)) {
            $success = \App\Models\Review::deleteById((int)$id);
            return $this->json(['success' => $success]);
        }

        return $this->json(['success' => false, 'message' => 'Invalid ID']);
    }
//    public function delete(): \App\Core\Responses\Response
//    {
//        // Перевірка ролі
//        if (!$this->app->getAuth()->isAdmin()) {
//            return $this->redirect('?c=review');
//        }
//
//        $id = $this->request()->getValue('id');
//        if ($id && is_numeric($id)) {
//            \App\Models\Review::deleteById((int)$id);
//        }
//
//        return $this->redirect('?c=review');
//    }
    public function search(): \App\Core\Responses\JsonResponse
    {
        $isAdmin = $this->app->getAuth()->isAdmin();
        $isLogged = $this->app->getAuth()->isLogged();
        $author = $this->request()->getValue('author');
        $date = $this->request()->getValue('date');

        $results = [];

        $pdo = \App\Core\DB\Connection::connect();

        $sql = "SELECT r.*, u.name AS user_name 
            FROM reviews r 
            JOIN users u ON r.user_id = u.id 
            WHERE 1=1";

        $params = [];

        if (!empty($author)) {
            $sql .= " AND u.name LIKE :author";
            $params['author'] = '%' . $author . '%';
        }

        if (!empty($date)) {
            $sql .= " AND DATE(r.created_at) = :date";
            $params['date'] = $date;
        }

        $sql .= " ORDER BY r.created_at DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($results as &$row) {
            $row['is_admin'] = $isAdmin;
            $row['is_logged'] = $isLogged;
        }
        return $this->json(['reviews' => $results]);
    }

}
