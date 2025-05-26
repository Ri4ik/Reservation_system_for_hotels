<h2>Všetky recenzie</h2>
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
    </div>
<?php endforeach; ?>
