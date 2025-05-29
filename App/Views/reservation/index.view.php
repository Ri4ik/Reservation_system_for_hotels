<main>
    <h4>Moje rezervácie</h4>

    <a href="?c=reservation&a=create">➕ Nová rezervácia</a>

    <table>
        <thead>
        <tr>
            <th>Izba</th>
            <th>Od</th>
            <th>Do</th>
            <th>Stav</th>
            <th>Akcie</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($data['reservations'] as $res): ?>
            <tr>
                <td><?= htmlspecialchars($res['room_name'] ?? '') ?></td>
                <td><?= htmlspecialchars($res['check_in'] ?? '') ?></td>
                <td><?= htmlspecialchars($res['check_out'] ?? '') ?></td>
                <td><?= htmlspecialchars($res['status'] ?? '') ?></td>
                <td>
                    <?php if (($res['status'] ?? '') === 'pending'): ?>
                        <a href="?c=reservation&a=edit&id=<?= $res['id'] ?>">✏️</a>
                        <a href="?c=reservation&a=delete&id=<?= $res['id'] ?>" onclick="return confirm('Naozaj zrušiť rezervaciu?')">❌</a>
                    <?php endif; ?>
                    <?php if ($isAdmin && ($res['status'] ?? '') === 'pending'): ?>
                        <a href="?c=reservation&a=confirm&id=<?= $res['id'] ?>">✅</a>
                        <a href="?c=reservation&a=cancel&id=<?= $res['id'] ?>">❌</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</main>