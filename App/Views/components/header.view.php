<?php
/** @var \App\Core\IAuthenticator $auth */
/** @var \App\Core\LinkGenerator $link */
?>
<nav class="navbar navbar-expand-sm bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= $link->url("home.index") ?>">
            <img src="public/images/vaiicko_logo.png" alt="Logo" title="<?= \App\Config\Configuration::APP_NAME ?>">
        </a>
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

        <?php if ($auth->isLogged()) { ?>
            <span class="navbar-text me-2">Prihlásený: <b><?= $auth->getLoggedUserName() ?></b></span>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="<?= $link->url("auth.logout") ?>">Odhlásenie</a>
                </li>
            </ul>
        <?php } else { ?>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="<?= \App\Config\Configuration::LOGIN_URL ?>">Prihlásenie</a>
                </li>
            </ul>
        <?php } ?>
    </div>
</nav>
