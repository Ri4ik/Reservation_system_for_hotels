<main>
    <div class="review-form-wrapper">
        <h4>Napíšte recenziu</h4>

        <div style="display: flex; justify-content: flex-start; margin-bottom: 20px;">
            <a href="?c=review" class="back--review">Späť</a>
        </div>

        <form method="post">
            <?php if (!empty($message)) : ?>
                <p class="validate-error"><?= htmlspecialchars($message) ?></p>
            <?php endif; ?>

            <div>
                <label for="comment">Obsah recenzie:</label>
                <textarea name="comment" required></textarea>
            </div>

            <div>
                <label for="rating">Hodnotenie:</label>
                <select name="rating" required>
                    <option value="">Vyberte počet hviezdičiek</option>
                    <?php for ($i = 5; $i >= 1; $i--): ?>
                        <option value="<?= $i ?>"><?= $i ?> ★</option>
                    <?php endfor; ?>
                </select>
            </div>

            <button type="submit">Odoslať</button>
        </form>
    </div>
</main>
<script src="public/js/validation-review.js"></script>
