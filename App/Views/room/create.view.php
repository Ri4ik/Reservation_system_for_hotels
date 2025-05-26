<h1>Pridať izbu</h1>

<form method="post">
    <label>Typ izby:</label>
    <input type="text" name="type" required>

    <label>Kapacita:</label>
    <input type="number" name="capacity" min="1" required>

    <label>Popis:</label>
    <textarea name="description"></textarea>

    <label>Obrázok (URL):</label>
    <input type="text" name="image">

    <button type="submit">Vytvoriť izbu</button>
</form>

<a href="?c=room">⏪ Späť</a>
