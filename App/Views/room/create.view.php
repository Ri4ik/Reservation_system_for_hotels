<main>
    <div class="reservation-form-wrapper">
        <h4>Pridať izbu</h4>
        <div class="back-div">
            <a href="?c=room" class="back--review">Späť</a>
        </div>
        <form method="post" enctype="multipart/form-data" data-mode="create">
            <?php if (!empty($message)) : ?>
                <p class="validate-error"><?= htmlspecialchars($message) ?></p>
            <?php endif; ?>

            <label>Názov izby:</label>
            <input type="text" name="name" required>

            <label>Kapacita:</label>
            <input type="number" name="capacity" min="1" required>

            <label>Popis:</label>
            <textarea name="description"></textarea>

            <label>Cena:</label>
            <input type="number" name="price" required>

            <label>Obrázok 1:</label>
            <input type="file" name="image1">

            <label>Obrázok 2:</label>
            <input type="file" name="image2">

            <label>Obrázok 3:</label>
            <input type="file" name="image3">

            <button type="submit">Vytvoriť izbu</button>
        </form>
    </div>
</main>
<script src="public/js/validation-room.js"></script>
