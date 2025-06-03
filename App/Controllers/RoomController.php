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
//        print_r(Room::getAllRooms());
        return $this->html(['rooms' => $rooms]);
    }
    public function checkRoom(): \App\Core\Responses\JsonResponse
    {
        $roomId = $this->request()->getValue('id');
        if (!$roomId) {
            return $this->json(['exists' => false]);
        }

        $room = \App\Models\Room::getRoomByID($roomId);
        return $this->json(['exists' => $room !== null]);
    }

    // Простая функция для сохранения загруженного файла
    function saveUploadedFile($file)
    {
        if (isset($file) && $file['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $allowed = ['jpg', 'jpeg', 'png', 'webp'];

            if (!in_array(strtolower($ext), $allowed)) {
                return ''; // Файл не допустимого типа
            }

            $filename = uniqid() . '.' . $ext;
            $target = 'public/images/' . $filename;
            if (move_uploaded_file($file['tmp_name'], $target)) {
                return $filename;
            }
        }
        return '';
    }
    public function create(): Response
    {
        if ($this->request()->getMethod() === 'POST') {
            $data = $this->request()->getPost();
            $files = $this->request()->getFiles();



            $image1 = $this->saveUploadedFile($files['image1']);
            $image2 = $this->saveUploadedFile($files['image2']);
            $image3 = $this->saveUploadedFile($files['image3']);

            Room::createRoom(
                $data['name'] ?? '',
                (int)($data['capacity'] ?? 0),
                $data['description'] ?? '',
                $image1,
                $image2,
                $image3,
                (float)($data['price'] ?? 0)
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
            $files = $this->request()->getFiles();

            // Сохраняем новые картинки если выбраны
            $image1 = $this->saveUploadedFile($files['image1']);
            $image2 = $this->saveUploadedFile($files['image2']);
            $image3 = $this->saveUploadedFile($files['image3']);

            // Если файл не загружен — берём старую картинку из hidden поля
            if (!$image1) $image1 = $data['existing_image1'] ?? '';
            if (!$image2) $image2 = $data['existing_image2'] ?? '';
            if (!$image3) $image3 = $data['existing_image3'] ?? '';

            Room::updateRoom(
                $id,
                $data['name'] ?? '',
                (int)($data['capacity'] ?? 0),
                $data['description'] ?? '',
                $image1,
                $image2,
                $image3,
                (float)($data['price'] ?? 0)
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
