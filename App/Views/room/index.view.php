<h1>Zoznam izieb</h1>

<a href="?c=room&a=create">➕ Pridať novú izbu</a>

<table>
    <tr>
        <th>ID</th>
        <th>Typ</th>
        <th>Kapacita</th>
        <th>Popis</th>
        <th>Obrázok</th>
        <th>Akcie</th>
    </tr>

    <?php foreach ($rooms as $room): ?>
        <tr>
            <td><?= $room->getId() ?></td>
            <td><?= htmlspecialchars($room->getType()) ?></td>
            <td><?= htmlspecialchars($room->getCapacity()) ?></td>
            <td><?= htmlspecialchars($room->getDescription()) ?></td>
            <td>
                <?php if ($room->getImage()): ?>
                    <img src="<?= htmlspecialchars($room->getImage()) ?>" alt="Obrázok izby" width="100">
                <?php else: ?>
                    Žiadny obrázok
                <?php endif; ?>
            </td>
            <td>
                <a href="?c=room&a=edit&id=<?= $room->getId() ?>">✏️ Upraviť</a>
                <a href="?c=room&a=delete&id=<?= $room->getId() ?>" onclick="return confirm('Naozaj chcete odstrániť túto izbu?')">❌ Odstrániť</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
