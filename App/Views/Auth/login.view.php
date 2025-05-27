
<?php
//$layout = 'auth';
/** @var Array $data */
/** @var \App\Core\LinkGenerator $link */
?>

<div class="form-background">
    <form class="form-signin" method="post" action="<?= $link->url("login") ?>" id="registerForm">
        <h2>Prihlásenie</h2>

        <?php if (!empty($data['message'])) : ?>
            <div class="validate-error">
                <?= htmlspecialchars($data['message']) ?>
            </div>
        <?php endif; ?>

        <label for="login">Email:</label>
        <input type="text" name="login" id="login" value="<?= htmlspecialchars($data['login'] ?? '') ?>" required>

        <label for="password">Heslo:</label>
        <input type="password" name="password" id="password" required>

        <button type="submit" name="submit">Prihlásiť sa</button>

        <p class="presmerovaci-link">Nemáte ešte účet? <a href="<?= $link->url("auth.register") ?>">Zaregistrovať sa</a></p>
    </form>
</div>

