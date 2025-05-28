<h2>Upraviť recenziu</h2>

<form method="post">
    <?php if (!empty($message)): ?>
        <p class="validate-error"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>
    <textarea name="content" required><?= htmlspecialchars($data['review']['comment']) ?></textarea>
    <button type="submit">Uložiť</button>
</form>

<a href="?c=review">⏪ Späť</a>
