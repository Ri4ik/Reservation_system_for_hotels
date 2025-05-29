<main>
    <h4>Vytvoriť rezerváciu</h4>

    <form method="post" action="?c=reservation&a=store">
        <div>
            <label for="room_id">Izba:</label>
            <select name="room_id" required>
                <?php foreach ($data['rooms'] as $room): ?>
                    <option value="<?= $room['id'] ?>">
                        <?= htmlspecialchars($room['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label for="date_from">Dátum od:</label>
            <input type="date" name="date_from" required>
        </div>

        <div>
            <label for="date_to">Dátum do:</label>
            <input type="date" name="date_to" required>
        </div>

        <button type="submit">Rezervovať</button>
    </form>

    <a href="?c=reservation">⏪ Späť na zoznam rezervácií</a>
</main>