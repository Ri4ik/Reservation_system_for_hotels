<?php

namespace App\Controllers;

use App\Core\DB\Connection;
use App\Core\AControllerBase;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\User;
use App\Core\Responses\Response;

class ReservationController extends AControllerBase
{
    public function index(): Response
    {
        $currentUser = $_SESSION['user'] ?? null;

        if (!$currentUser) {
            return $this->redirect("?c=Auth&a=login");
        }

        if ($currentUser['role'] === 'admin') {
            $reservations = Reservation::getAllWithUsersAndRooms();
        } else {
            $reservations = Reservation::getByUserIdWithRoom($currentUser['id']);
        }

        return $this->html([
            'reservations' => $reservations,
            'isAdmin' => $currentUser['role'] === 'admin'
        ]);
    }

    public function create(): Response
    {
        $rooms = Room::getAllRooms();
        return $this->html(['rooms' => $rooms]);
    }

    public function store(): Response
    {
        $data = $this->request()->getPost();
        $userId = $_SESSION['user']['id'];

        $reservation = new Reservation();
        $reservation->setUserId($userId);
        $reservation->setRoomId($data['room_id']);
        $reservation->setCheckin($data['date_from']);
        $reservation->setCheckout($data['date_to']);
        $reservation->setStatus('pending');
        $reservation->save();

        return $this->redirect("?c=reservation");
    }

    public function edit(): Response
    {
        $id = $this->request()->getValue('id');
        $reservation = Reservation::getOne($id);
        $rooms = Room::getAllRooms();

        if (!$reservation || $_SESSION['user']['id'] !== $reservation->getUserId()) {
            return $this->redirect("?c=reservation");
        }

        return $this->html([
            'reservation' => $reservation,
            'rooms' => $rooms
        ]);
    }

    public function update(): Response
    {
        $data = $this->request()->getPost();
        $reservation = Reservation::getOne($data['id']);

        if (!$reservation || $_SESSION['user']['id'] !== $reservation->getUserId()) {
            return $this->redirect("?c=reservation");
        }

        $reservation->setRoomId($data['room_id']);
        $reservation->setCheckin($data['date_from']);
        $reservation->setCheckout($data['date_to']);
        $reservation->save();

        return $this->redirect("?c=reservation");
    }

    public function confirm(): Response
    {
        $id = $this->request()->getValue('id');
        $reservation = Reservation::getOne($id);
        if ($reservation && $_SESSION['user']['role'] === 'admin') {
            $reservation->setStatus('confirmed');
            $reservation->save();
        }

        return $this->redirect("?c=reservation");
    }

    public function cancel(): Response
    {
        $id = $this->request()->getValue('id');
        $reservation = Reservation::getOne($id);
        $currentUser = $_SESSION['user'];

        if ($reservation &&
            ($currentUser['role'] === 'admin' || $currentUser['id'] === $reservation->getUserId())) {
            $reservation->setStatus('canceled');
            $reservation->save();
        }

        return $this->redirect("?c=reservation");
    }
    public function delete(): Response
    {
        $id = $this->request()->getValue('id');
        $reservation = Reservation::getOne($id);

        $currentUser = $_SESSION['user'] ?? null;

        if ($reservation && (
                $currentUser['role'] === 'admin' ||
                $currentUser['id'] === $reservation->getUserId()
            )) {
            $reservation->delete();
        }

        return $this->redirect("?c=reservation");
    }
}
