<main>
    <h4>Naše izby</h4>
    <?php if ($this->app->getAuth()->isAdmin()): ?>
    <div class="create-div">
        <a class="create-review" href="?c=room&a=create">➕ Pridať novú izbu</a>
    </div>
    <?php endif; ?>
    <div class="rooms-container">
        <?php foreach ($rooms as $room): ?>
            <div class="room-card">
                <div class="swiper-container mySwiper<?= $room['id'] ?>">
                    <div class="swiper-wrapper">
                        <?php if (!empty($room['image1'])): ?>
                            <div class="swiper-slide">
                                <img src="public/images/<?= htmlspecialchars($room['image1']) ?>" alt="">
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($room['image2'])): ?>
                            <div class="swiper-slide">
                                <img src="public/images/<?= htmlspecialchars($room['image2']) ?>" alt="">
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($room['image3'])): ?>
                            <div class="swiper-slide">
                                <img src="public/images/<?= htmlspecialchars($room['image3']) ?>" alt="">
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="room-info">
                    <h2><?= htmlspecialchars($room['name']) ?></h2>
                    <p><strong>Kapacita:</strong> <?= htmlspecialchars($room['capacity']) ?> osôb</p>
                    <p><?= htmlspecialchars($room['description']) ?></p>
                    <p><strong>Cena:</strong> <?= htmlspecialchars($room['price']) ?> € / noc</p>
                    <?php if ($this->app->getAuth()->isAdmin()): ?>
                        <a href="?c=room&a=edit&id=<?= $room['id'] ?>" class="edit-review">✏️Upraviť</a>
                        <a href="?c=room&a=delete&id=<?= $room['id'] ?>" class="delete-review" onclick="return confirm(
                            'Naozaj chcete odstrániť túto izbu? Všetky rezervácie, ktoré sú s ňou spojené, budú tiež odstránené.'
                            )">❌Zmazať</a>

                    <?php endif; ?>
                    <?php if (!empty($_SESSION['user'])): ?>
                        <a href="?c=reservation&a=create&room_id=<?= $room['id'] ?>" class="create-review">Rezervovať</a>
                    <?php else: ?>
                        <?php $_SESSION['redirect_after_login'] = "?c=reservation&a=create&room_id=" . $room['id']; ?>
                        <a href="?c=auth&a=login" class="create-review">Rezervovať</a>
                    <?php endif; ?>
                </div>
            </div>

            <script>
                const swiper<?= $room['id'] ?> = new Swiper(".mySwiper<?= $room['id'] ?>", {
                    loop: true,
                    effect: 'coverflow',
                    grabCursor: true,
                    slidesPerView: 1,
                    spaceBetween: 10,
                    coverflowEffect: { rotate: 0, stretch: 0, depth: 100, modifier: 1.5, slideShadows: false }
                });
            </script>
        <?php endforeach; ?>
    </div>
</main>
