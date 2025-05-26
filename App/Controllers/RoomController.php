<?php

namespace App\Controllers;

use App\Models\Room;
use App\Core\AControllerBase;
use App\Core\Responses\Response;

class RoomController extends AControllerBase
{
    public function index(): Response
    {
        $rooms = Room::getAllRooms();
        return $this->html(['rooms' => $rooms]);
    }

    public function create(): Response
    {
        if ($this->request()->getMethod() === 'POST') {
            $data = $this->request()->getPost();
            Room::createRoom(
                $data['type'] ?? '',
                (int)($data['capacity'] ?? 0),
                $data['description'] ?? '',
                $data['image'] ?? ''
            );
            return $this->redirect("?c=room");
        }

        return $this->html();
    }

    public function edit(): Response
    {
        $id = (int)($this->request()->getValue('id'));
        $room = Room::getRoomById($id);

        if (!$room) {
            return $this->redirect("?c=room");
        }

        if ($this->request()->getMethod() === 'POST') {
            $data = $this->request()->getPost();
            Room::updateRoom(
                $id,
                $data['type'] ?? '',
                (int)($data['capacity'] ?? 0),
                $data['description'] ?? '',
                $data['image'] ?? ''
            );
            return $this->redirect("?c=room");
        }

        return $this->html(['room' => $room]);
    }

    public function delete(): Response
    {
        $id = (int)($this->request()->getValue('id'));
        Room::deleteRoom($id);
        return $this->redirect("?c=room");
    }
}
