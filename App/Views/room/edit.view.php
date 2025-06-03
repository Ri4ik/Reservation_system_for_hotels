<main>
    <div class="reservation-form-wrapper">
    <h4>Upraviť izbu</h4>
    <form method="post" enctype="multipart/form-data">
        <div class="back-div">
            <a href="?c=room" class="back--review">Späť</a>
        </div>
        <label>Názov izby:</label>
        <input type="text" name="name" value="<?= htmlspecialchars($room['name']) ?>" required>

        <label>Kapacita:</label>
        <input type="number" name="capacity" min="1" value="<?= htmlspecialchars($room['capacity']) ?>" required>

        <label>Popis:</label>
        <textarea name="description"><?= htmlspecialchars($room['description']) ?></textarea>

        <label>Cena:</label>
        <input type="number" name="price" value="<?= htmlspecialchars($room['price']) ?>" required>

        <label>Obrázok 1:</label>
        <input type="file" name="image1">
        <input type="hidden" name="existing_image1" value="<?= htmlspecialchars($room['image1']) ?>">

        <label>Obrázok 2:</label>
        <input type="file" name="image2">
        <input type="hidden" name="existing_image2" value="<?= htmlspecialchars($room['image2']) ?>">

        <label>Obrázok 3:</label>
        <input type="file" name="image3">
        <input type="hidden" name="existing_image3" value="<?= htmlspecialchars($room['image3']) ?>">

        <button type="submit">Uložiť</button>
    </form>

    </div>
</main>
