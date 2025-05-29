<main>
    <h4>Upraviť rezerváciu</h4>

    <form method="post" action="?c=reservation&a=update">
        <input type="hidden" name="id" value="<?= $data['reservation']->getId() ?>">

        <div>
            <label for="room_id">Izba:</label>
            <select name="room_id" required>
                <?php foreach ($data['rooms'] as $room): ?>
                    <option value="<?= $room['id'] ?>" <?= $room['id'] == $data['reservation']->getRoomId() ? 'selected' : '' ?>>
                        <?= htmlspecialchars($room['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label for="date_from">Dátum od:</label>
            <input type="date" name="date_from" value="<?= $data['reservation']->getCheckin() ?>" required>
        </div>

        <div>
            <label for="date_to">Dátum do:</label>
            <input type="date" name="date_to" value="<?= $data['reservation']->getCheckout() ?>" required>
        </div>

        <button type="submit">Uložiť zmeny</button>
    </form>


    <a href="?c=reservation">⏪ Späť na zoznam rezervácií</a>
</main>