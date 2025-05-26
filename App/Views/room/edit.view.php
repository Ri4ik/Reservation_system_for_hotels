<h1>Upraviť izbu</h1>

<form method="post">
    <label>Typ izby:</label>
    <input type="text" name="type" value="<?= htmlspecialchars($room->getType()) ?>" required>

    <label>Kapacita:</label>
    <input type="number" name="capacity" value="<?= htmlspecialchars($room->getCapacity()) ?>" min="1" required>

    <label>Popis:</label>
    <textarea name="description"><?= htmlspecialchars($room->getDescription()) ?></textarea>

    <label>Obrázok (URL):</label>
    <input type="text" name="image" value="<?= htmlspecialchars($room->getImage()) ?>">

    <button type="submit">Uložiť</button>
</form>

<a href="?c=room">⏪ Späť</a>
