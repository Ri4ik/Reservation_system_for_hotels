<h2>Moje rezervácie</h2>

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
            <td><?= htmlspecialchars($res->getRoomType()) ?></td>
            <td><?= $res->getDateFrom() ?></td>
            <td><?= $res->getDateTo() ?></td>
            <td><?= $res->getStatus() ?></td>
            <td>
                <?php if ($res->getStatus() === 'pending'): ?>
                    <a href="?c=reservation&a=edit&id=<?= $res->getId() ?>">✏️</a>
                    <a href="?c=reservation&a=delete&id=<?= $res->getId() ?>" onclick="return confirm('Naozaj zrušiť?')">❌</a>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
