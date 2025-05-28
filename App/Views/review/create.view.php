<main>
    <h4>Napíšte recenziu</h4>

    <div style="display: flex; justify-content: flex-start; margin-bottom: 20px;">
        <a href="?c=review" class="back--review">Späť</a>
    </div>

    <form method="post">
        <?php if (!empty($message)) : ?>
            <p class="validate-error"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <label>Obsah recenzie:</label>
        <textarea name="comment" required></textarea>

        <label>Hodnotenie:</label>
        <select name="rating" required>
            <option value="">Vyberte počet hviezdičiek</option>
            <?php for ($i = 5; $i >= 1; $i--): ?>
                <option value="<?= $i ?>"><?= $i ?> ★</option>
            <?php endfor; ?>
        </select>

        <button type="submit">Odoslať</button>
    </form>
</main>
