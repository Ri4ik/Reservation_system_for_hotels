<h2>Napíšte recenziu</h2>

<form method="post">
    <?php if (!empty($message)) : ?>
        <p class="validate-error"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>
    <label>Obsah recenzie:</label>
    <textarea name="comment" required></textarea>
    <button type="submit">Odoslať</button>
</form>

<a href="?c=review">⏪ Späť</a>
