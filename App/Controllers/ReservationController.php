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
        $reservation->setStatus('čaká na schválenie');
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

//    public function confirm(): Response
//    {
//        $id = $this->request()->getValue('id');
//        $reservation = Reservation::getOne($id);
//        if ($reservation && $_SESSION['user']['role'] === 'admin') {
//            $reservation->setStatus('potvrdená');
//            $reservation->save();
//        }
//
//        return $this->redirect("?c=reservation");
//    }
    public function confirm(): \App\Core\Responses\JsonResponse
    {
        // Проверка прав через централизованный Auth
        if (!$this->app->getAuth()->isAdmin()) {
            return $this->json(['success' => false, 'message' => 'Unauthorized']);
        }

        $id = $this->request()->getValue('id');

        if ($id && is_numeric($id)) {
            $reservation = Reservation::getOne((int)$id);

            if ($reservation) {
                $reservation->setStatus('potvrdená');
                $reservation->save();
                return $this->json(['success' => true]);
            } else {
                return $this->json(['success' => false, 'message' => 'Reservation not found']);
            }
        }

        return $this->json(['success' => false, 'message' => 'Invalid ID']);
    }

    public function cancel(): \App\Core\Responses\JsonResponse
    {
        if (!$this->app->getAuth()->isAdmin()) {
            return $this->json(['success' => false, 'message' => 'Unauthorized']);
        }

        $id = $this->request()->getValue('id');

        if ($id && is_numeric($id)) {
            $reservation = Reservation::getOne((int)$id);

            if ($reservation) {
                $reservation->setStatus('zrušená');
                $reservation->save();
                return $this->json(['success' => true]);
            } else {
                return $this->json(['success' => false, 'message' => 'Reservation not found']);
            }
        }

        return $this->json(['success' => false, 'message' => 'Invalid ID']);
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

    public function search(): Response
    {
        $currentUser = $_SESSION['user'];
        $post = $this->request()->getPost();

        $roomName = trim($post['room'] ?? '');
        $dateFrom = trim($post['date_from'] ?? '');
        $dateTo = trim($post['date_to'] ?? '');
        $status = trim($post['status'] ?? '');
        if ($currentUser['role'] === 'admin') {
            $userName = trim($post['user'] ?? '');
            $reservations = Reservation::searchReservations(
                true,
                null,
                $userName, $roomName, $status, $dateFrom, $dateTo
            );
        } else {
            $reservations = Reservation::searchReservations(
                false,
                $currentUser['id'],
                '', $roomName, $status, $dateFrom, $dateTo
            );
        }

        return $this->json([
            'reservations' => $reservations,
            'isAdmin' => $currentUser['role'] === 'admin'
        ]);
    }
}
