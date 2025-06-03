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
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
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

    public function store(): \App\Core\Responses\Response
    {
        $data = $this->request()->getPost();
        $userId = $_SESSION['user']['id'];

        $roomId = isset($data['room_id']) ? intval($data['room_id']) : 0;
        $dateFrom = $data['date_from'];
        $dateTo = $data['date_to'];

        $today = date('Y-m-d');
        $room = Room::getRoomByID($roomId);
        // Валидация данных
        if (!$room) {
            $message = '❌Zvolená izba neexistuje.';
        } elseif (empty($roomId) || empty($dateFrom) || empty($dateTo)) {
            $message = '❌Všetky polia musia byť vyplnené.';
        } elseif ($dateFrom < $today) {
            $message = '❌Dátum od nemôže byť v minulosti.';
        } elseif ($dateTo <= $dateFrom) {
            $message = '❌Dátum do musí byť neskorší ako dátum od.';
        } elseif (Reservation::hasConflict($roomId, $dateFrom, $dateTo)) {
            $message = '❌Na zvolené dátumy už existuje potvrdená rezervácia.';
        } else {
            $reservation = new Reservation();
            $reservation->setUserId($userId);
            $reservation->setRoomId($roomId);
            $reservation->setCheckin($dateFrom);
            $reservation->setCheckout($dateTo);
            $reservation->setStatus('čaká na schválenie');
            $reservation->save();

            return $this->redirect("?c=reservation");
        }
        $rooms = Room::getAllRooms();
        return $this->html(['rooms' => $rooms, 'message' => $message], 'create');
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

        $roomId = isset($data['room_id']) ? intval($data['room_id']) : 0;
        $dateFrom = $data['date_from'];
        $dateTo = $data['date_to'];

        $today = date('Y-m-d');
        $room = Room::getRoomByID($roomId);
        if (!$room) {
            $message = '❌Zvolená izba neexistuje.';
        } elseif (empty($roomId) || empty($dateFrom) || empty($dateTo)) {
            $message = '❌Všetky polia musia byť vyplnené.';
        } elseif ($dateFrom < $today) {
            $message = '❌Dátum od nemôže byť v minulosti.';
        } elseif ($dateTo <= $dateFrom) {
            $message = '❌Dátum do musí byť neskorší ako dátum od.';
        } elseif (Reservation::hasConflict($roomId, $dateFrom, $dateTo, $reservation->getId())) {
            $message = '❌Na zvolené dátumy už existuje potvrdená rezervácia.';
        } else {
            $reservation->setRoomId($roomId);
            $reservation->setCheckin($dateFrom);
            $reservation->setCheckout($dateTo);
            $reservation->save();

            return $this->redirect("?c=reservation");
        }

//        return $this->html($message);
        $rooms = Room::getAllRooms();
        return $this->html([
            'reservation' => $reservation,
            'rooms' => $rooms,
            'message' => $message
        ], 'edit');
    }

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

    public function checkAvailability(): \App\Core\Responses\JsonResponse
    {
        $roomId = intval($this->request()->getValue('room_id'));
        $dateFrom = $this->request()->getValue('date_from');
        $dateTo = $this->request()->getValue('date_to');

        if (!$roomId || !$dateFrom || !$dateTo) {
            return $this->json(['available' => false, 'message' => 'Neplatné vstupy']);
        }

        $conflict = Reservation::hasConflict($roomId, $dateFrom, $dateTo);
        return $this->json(['available' => !$conflict]);
    }

    public function getUnavailableDates(): \App\Core\Responses\JsonResponse
    {
        $roomId = $this->request()->getValue('room_id');

        if (!$roomId || !is_numeric($roomId)) {
            return $this->json(['unavailable' => []]);
        }

        $unavailableDates = Reservation::getUnavailableDatesForRoom((int)$roomId);

        return $this->json(['unavailable' => $unavailableDates]);
    }

    public function exportReservations() {
        $reservations = Reservation::getAllWithUsersAndRooms();
        $filename = "rezervacie_" . date("Y_m_d_H_i_s") . ".csv";

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        $output = fopen('php://output', 'w');

        // <<< ДОБАВЛЯЕМ BOM >>>
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

        // Заголовки CSV
        $headers = ['Meno zákazníka', 'Email', 'Izba', 'Dátum od', 'Dátum do', 'Stav'];
        fputcsv($output, $headers, ';');

        // Данные
        foreach ($reservations as $reservation) {
            fputcsv($output, [
                $reservation['user_name'] ?? '---',
                $reservation['user_email'] ?? '---',
                $reservation['room_name'] ?? '---',
                $reservation['check_in'],
                $reservation['check_out'],
                $reservation['status']
            ], ';');
        }

        fclose($output);
        exit;
    }


}
