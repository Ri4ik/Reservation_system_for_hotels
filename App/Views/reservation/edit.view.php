<main>
    <div class="reservation-form-wrapper">
        <h4>Upraviť rezerváciu</h4>

        <div style="margin-bottom: 20px;">
            <a href="?c=reservation" class="back--review">Späť na zoznam rezervácií</a>
        </div>

        <form method="post" action="?c=reservation&a=update">
            <?php if (!empty($message)) : ?>
                <p class="validate-error"><?= htmlspecialchars($message) ?></p>
            <?php endif; ?>

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
                <input type="text" name="date_from" value="<?= $data['reservation']->getCheckin() ?>" required>
            </div>

            <div>
                <label for="date_to">Dátum do:</label>
                <input type="text" name="date_to" value="<?= $data['reservation']->getCheckout() ?>" required>
            </div>

            <button type="submit">Uložiť zmeny</button>
        </form>
    </div>
</main>
<script src="public/js/validation-reservation.js"></script>
