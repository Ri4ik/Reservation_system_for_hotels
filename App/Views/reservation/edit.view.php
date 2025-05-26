<h2>Upraviť rezerváciu</h2>

<form method="post" action="?c=reservation&a=update">

    <div>
        <label for="room_id">Izba:</label>
        <select name="room_id" required>
            <?php foreach ($data['rooms'] as $room): ?>
                <input type="hidden" name="id" value="<?= $data['reservation']->getId() ?>">
                <option value="<?= $room['id'] ?>" <?= $room['id'] == $data['reservation']->getRoomId() ? 'selected' : '' ?>>
                    <?= htmlspecialchars($room['type']) ?> (<?= $room['capacity'] ?> osôb)
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div>
        <label for="date_from">Dátum od:</label>
        <input type="date" name="date_from" value="<?= $data['reservation']->getDateFrom() ?>" required>
    </div>

    <div>
        <label for="date_to">Dátum do:</label>
        <input type="date" name="date_to" value="<?= $data['reservation']->getDateTo() ?>" required>
    </div>

    <button type="submit">Uložiť zmeny</button>
</form>

<a href="?c=reservation">⏪ Späť na zoznam rezervácií</a>
