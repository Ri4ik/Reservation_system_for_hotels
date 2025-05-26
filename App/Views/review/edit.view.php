<h2>Upraviť recenziu</h2>

<form method="post">
    <textarea name="content" required><?= htmlspecialchars($data['review']['content']) ?></textarea>
    <button type="submit">Uložiť</button>
</form>

<a href="?c=review">⏪ Späť</a>
