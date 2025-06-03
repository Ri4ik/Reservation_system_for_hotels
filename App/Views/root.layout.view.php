<?php

/** @var string $contentHTML */
/** @var \App\Core\IAuthenticator $auth */
/** @var \App\Core\LinkGenerator $link */
?>
<!DOCTYPE html>
<html lang="sk">

<head>
    <title><?= \App\Config\Configuration::APP_NAME ?></title>
    <link rel="stylesheet" href="public/css/styl.css">
<!--    <script src="public/js/script.js"></script>-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<!--    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/sk.js"></script>-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
</head>
<body>
    <div class="wrapper">
        <?php require "App/Views/components/header.view.php"; ?>
            <div class="main-content">
                <?= $contentHTML ?>
            </div>
        <?php require "App/Views/components/footer.view.php"; ?>
    </div>
</body>
</html>
