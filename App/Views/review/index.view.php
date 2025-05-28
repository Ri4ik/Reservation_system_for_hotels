
<main>
    <h2>Všetky recenzie</h2>
    <form id="search-form" action="javascript:void(0);">
        <h2>Hľadať recenzie</h2>
        <input type="text" id="search-author" name="search-author" placeholder="Hľadať podľa autora" />
        <input type="date" id="search-date" name="search-date" />
    </form>
    <div class="reviews" id="reviews-list">
        <a href="?c=review&a=create">➕ Pridať recenziu</a>

        <?php foreach ($data['reviews'] as $r): ?>
            <div class="review-item" id="review-<?= $r['id'] ?>">
            <p><?= htmlspecialchars($r['comment']) ?></p>
        <div class="review-author">
            — <?= htmlspecialchars($r['user_name']) ?>
        </div>
        <div class="review-time">
            <?= $r['created_at'] ?>
        </div>
            <a href="?c=review&a=edit&id=<?= $r['id'] ?>" class="edit-review">Upraviť</a>
            <?php if ($this->app->getAuth()->isAdmin()): ?>
                <a href="#" class="delete-review" data-id="<?= $r['id'] ?>">🗑 Zmazať</a>
            <?php endif; ?>
        </div>

        <?php endforeach; ?>
    </div>
</main>
<script src="/Rezervacny_System_VAII/public/js/ajax_search.js"></script>
<script src="/Rezervacny_System_VAII/public/js/ajax_delete.js"></script>