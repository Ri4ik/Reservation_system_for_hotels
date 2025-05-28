
<main>
    <h2>Všetky recenzie</h2>
    <div class="reviews" id="reviews-list">
        <a href="?c=review&a=create">➕ Pridať recenziu</a>

        <?php foreach ($data['reviews'] as $r): ?>
        <div class="review-item">
            <p><?= htmlspecialchars($r['comment']) ?></p>
        <div class="review-author">
            — <?= htmlspecialchars($r['user_name']) ?>
        </div>
        <div class="review-time">
            <?= $r['created_at'] ?>
        </div>
            <a href="?c=review&a=edit&id=<?= $r['id'] ?>" class="edit-review">Upraviť</a>
            <?php if ($this->app->getAuth()->isAdmin()): ?>
                <a href="?c=review&a=delete&id=<?= $r['id'] ?>" class="delete-review" onclick="return confirm('Naozaj chcete vymazať túto recenziu?')">🗑 Zmazať</a>
            <?php endif; ?>
        </div>

        <?php endforeach; ?>
    </div>
</main>