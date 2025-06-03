<main>
    <div class="reservation-form-wrapper">
        <h4>Vytvoriť rezerváciu</h4>

        <div class="back-div">
            <a href="?c=reservation" class="back--review">Späť na zoznam rezervácií</a>
        </div>

        <form method="post" action="?c=reservation&a=store">
            <?php if (!empty($message)) : ?>
                <p class="validate-error"><?= htmlspecialchars($message) ?></p>
            <?php endif; ?>

            <div>
                <label for="room_id">Izba:</label>
                <select name="room_id" required>
                    <?php foreach ($data['rooms'] as $room): ?>
                        <option value="<?= $room['id'] ?>" <?= ($room['id'] == ($data['selectedRoomId'] ?? '')) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($room['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label for="date_from">Dátum od:</label>
                <input type="text" name="date_from" required>
            </div>

            <div>
                <label for="date_to">Dátum do:</label>
                <input type="text" name="date_to" required>
            </div>

            <button type="submit">Rezervovať</button>
        </form>
    </div>
</main>
<script src="public/js/validation-reservation.js"></script>
