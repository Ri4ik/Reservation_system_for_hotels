<main>
    <div class="review-form-wrapper">
        <h4>Upraviť recenziu</h4>

        <div style="display: flex; justify-content: flex-start; margin-bottom: 20px;">
            <a href="?c=review" class="back--review">Späť</a>
        </div>

        <form method="post">
            <?php if (!empty($message)): ?>
                <p class="validate-error"><?= htmlspecialchars($message) ?></p>
            <?php endif; ?>

            <div>
                <label for="comment">Obsah recenzie:</label>
                <textarea name="comment" required><?= htmlspecialchars($data['review']['comment']) ?></textarea>
            </div>

            <button type="submit">Uložiť zmeny</button>
        </form>
    </div>
</main>
<script src="public/js/validation-review.js"></script>
