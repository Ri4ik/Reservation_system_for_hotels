<?php
/** @var \App\Core\IAuthenticator $auth */
/** @var \App\Core\LinkGenerator $link */
?>
<header>
    <div class="container-fluid">
        <h1>Hotel Forest Paradise</h1>
    <nav>
        <ul class="navbar-nav me-auto">
            <li class="nav-item">
                <a class="nav-link" href="<?= $link->url("home.index") ?>">Domov</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= $link->url("home.contact") ?>">Kde nás nájdete</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= $link->url("room.index") ?>">Naše izby</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= $link->url("reservation.index") ?>">Rezervácie</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= $link->url("review.index") ?>">Recenzie</a>
            </li>
        </ul>
    </nav>
    </div>
    <div class="auth-icons">
        <?php if (isset($_SESSION['user'])): ?>
            <!-- Ak je používateľ prihlásený, zobraziť tlačidlo na odhlásenie -->
            <a href="?c=auth&a=logout" class="auth-icon">
                <img src="public/images/logout_70dp_E6D2DC.svg" alt="Logout" />
            </a>
        <?php else: ?>
            <!-- Ak používateľ nie je prihlásený, zobraziť tlačidlo prihlásenia -->
            <a href="?c=auth&a=login" class="auth-icon">
                <img src="public/images/login_70dp_E6D2DC.svg" alt="Login" />
            </a>
        <?php endif; ?>
    </div>
</header>
