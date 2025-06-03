<?php
//$layout = 'Auth';
/** @var \App\Core\LinkGenerator $link */
?>

<div class="form-background">
    <form action="" method="POST" id="registerForm">
        <h2>Registrácia</h2>

        <?php if (!empty($message)) : ?>
            <p class="validate-error"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>
        <div id="error-message" class="validate-error" style="display:none;"></div>

        <label for="name">Meno:</label>
        <input type="text" name="name" id="name" value="<?= htmlspecialchars($name ?? '') ?>" required>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" value="<?= htmlspecialchars($email ?? '') ?>" required>

        <label for="phone">Telefón:</label>
        <input type="text" name="phone" id="phone" value="<?= htmlspecialchars($phone ?? '') ?>" required>

        <label for="password">Heslo:</label>
        <input type="password" name="password" id="password" required>

        <button type="submit">Zaregistrovať sa</button>
        <p class="presmerovaci-link">Máte už účet? <a href="<?= $link->url("Auth.login") ?>">Prihlásiť sa</a></p>
    </form>
</div>

<script src="public/js/validation-register.js"></script>
