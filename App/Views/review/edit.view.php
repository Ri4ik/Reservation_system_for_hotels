<main>
    <h4>Upraviť recenziu</h4>
    <div style="display: flex; justify-content: flex-start; margin-bottom: 20px;">
        <a href="?c=review" class="back--review">Späť</a>
    </div>
    <form method="post">
        <?php if (!empty($message)): ?>
            <p class="validate-error"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>
        <textarea name="content" required><?= htmlspecialchars($data['review']['comment']) ?></textarea>
        <label></label>
        <label></label>
        <button type="submit">Uložiť</button>
    </form>
</main>
