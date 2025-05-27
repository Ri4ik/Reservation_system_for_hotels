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
    <script src="public/js/script.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
