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

        if ($this->request()->isPost()) {
            $userId = $this->app->getAuth()->getLoggedUserId(); // точно буде int
            $content = $this->request()->getValue('content');
            Review::create($userId, $content);
            return $this->redirect("?c=review");
        }

        return $this->html();
    }


    public function edit(): \App\Core\Responses\Response
    {
        $id = (int)$this->request()->getValue('id');
        $review = Review::getById($id);

        if ($this->request()->isPost()) {
            $content = $this->request()->getValue('content');
            Review::update($id, $content);
            return $this->redirect("?c=review");
        }

        return $this->html(['review' => $review]);
    }
}
