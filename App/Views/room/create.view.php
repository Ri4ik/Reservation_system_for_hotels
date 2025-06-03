<main>
    <div class="reservation-form-wrapper">
        <h4>Pridať izbu</h4>

        <form method="post" enctype="multipart/form-data">
            <div class="back-div">
                <a href="?c=room" class="back--review">Späť</a>
            </div>

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