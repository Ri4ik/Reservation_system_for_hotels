<main>
<!--    <h2>Všetky recenzie</h2>-->
    <h4>Hľadať recenzie</h4>
    <form id="search-form" action="javascript:void(0);">

        <div class="search-fields">
            <input type="text" id="search-author" name="search-author" placeholder="Hľadať podľa autora" />
            <input type="date" id="search-date" name="search-date" />
            <button type="button" id="clear-filters">✖</button>
        </div>
    </form>
    <div id="avg-rating">
        <h3>Priemerné hodnotenie: <?= $avgRating ?> ⭐ (<?= $totalVotes ?> hlasov)</h3>
    </div>
    <div class="reviews" id="reviews-list">
            <a href="?c=review&a=create" class="create-review">➕ Pridať recenziu</a>
        <?php foreach ($data['reviews'] as $r): ?>
            <div class="review-item" id="review-<?= $r['id'] ?>">
            <p><?= htmlspecialchars($r['comment']) ?></p>
            <div class="review-rating">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <span style="color: gold;"><?= $i <= $r['rating'] ? '★' : '☆' ?></span>
                <?php endfor; ?>
            </div>
            <div class="review-author">
                — <?= htmlspecialchars($r['user_name']) ?>
            </div>
            <div class="review-time">
                <?= $r['created_at'] ?>
            </div class="review-actions">
                <?php if ($this->app->getAuth()->isAdmin()): ?>
                    <a href="?c=review&a=edit&id=<?= $r['id'] ?>" class="edit-review">✏️Upraviť</a>
                    <a href="#" class="delete-review" data-id="<?= $r['id'] ?>">❌Zmazať</a>
                <?php endif; ?>
            </div>

        <?php endforeach; ?>
    </div>
</main>
<script src="/Rezervacny_System_VAII/public/js/review-actions.js"></script>