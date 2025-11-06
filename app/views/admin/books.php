<div class="admin-page-header">
    <h1>📚 Управление книгами</h1>
</div>

<div class="admin-table-wrapper">
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Название</th>
                <th>Автор</th>
                <th>Жанры</th>
                <th>Владелец</th>
                <th>Статус</th>
                <th>Дата добавления</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($books as $book): ?>
                <tr>
                    <td><?= $book['id'] ?></td>
                    <td><?= e($book['title']) ?></td>
                    <td><?= e($book['author']) ?></td>
                    <td><?= e($book['genres'] ?? '-') ?></td>
                    <td><?= e($book['username']) ?></td>
                    <td>
                        <span class="admin-badge admin-badge-<?= $book['status'] ?>">
                            <?= e($book['status']) ?>
                        </span>
                    </td>
                    <td><?= date('d.m.Y', strtotime($book['created_at'])) ?></td>
                    <td>
                        <a href="../edit_book.php?id=<?= $book['id'] ?>" class="admin-btn admin-btn-edit">✏️ Редактировать</a>
                        <a href="../delete_book.php?id=<?= $book['id'] ?>" onclick="return confirm('Удалить книгу?')" class="admin-btn admin-btn-danger">🗑️ Удалить</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

