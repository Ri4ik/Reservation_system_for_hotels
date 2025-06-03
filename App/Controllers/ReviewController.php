<?php

namespace App\Controllers;

use App\Core\AControllerBase;
use App\Models\Review;

class ReviewController extends AControllerBase
{
    public function index(): \App\Core\Responses\Response
    {
        $all = Review::getAll();
        $avgRating = count($all) ? round(array_sum(array_column($all, 'rating')) / count($all), 2) : 0;
        $totalVotes = count(array_filter(array_column($all, 'rating')));
        return $this->html([
            'reviews' => $all,
            'avgRating' => $avgRating,
            'totalVotes' => $totalVotes
        ]);

    }

    public function create(): \App\Core\Responses\Response
    {
        // Якщо не авторизований — перекинути на логін
        if (!$this->app->getAuth()->isLogged()) {
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
            return $this->redirect(\App\Config\Configuration::LOGIN_URL);
        }

        if ($this->request()->isPost()) {
            $userId = $this->app->getAuth()->getLoggedUserId(); // точно буде int
            $content = $this->request()->getValue('comment');
            $rating = (int) $this->request()->getValue('rating');
            if (trim($content ?? '') === '') {
                $data['message'] = '❌ Obsah recenzie nemôže byť prázdny!';
                return $this->html($data);
            }
            if ($rating < 1 || $rating > 5) {
                $data['message'] = '❌ Hodnotenie musí byť od 1 do 5 hviezdičiek.';
                return $this->html($data);
            }
            Review::create($userId, $content, $rating);
            return $this->redirect("?c=review");
        }

        return $this->html();
    }


    public function edit(): \App\Core\Responses\Response
    {
        $id = $this->request()->getValue('id');

        if ($this->request()->isPost()) {
            $form = $this->request()->getPost();

            $content = trim($form['comment'] ?? '');

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
        $all = Review::getAll();
        $avgRating = count($all) ? round(array_sum(array_column($all, 'rating')) / count($all), 2) : 0;
        $totalVotes = count(array_filter(array_column($all, 'rating')));

        return $this->json([
            'reviews' => $results,
            'avg' => $avgRating,
            'total' => $totalVotes
        ]);
    }

}
