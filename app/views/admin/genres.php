<div class="admin-page-header">
    <h1>🏷️ Управление жанрами</h1>
</div>

<div class="admin-form-card">
    <h3>➕ Добавить новый жанр</h3>
    <form method="POST" class="admin-form">
        <div class="admin-form-group">
            <input type="text" name="name" placeholder="Название жанра" required>
            <button type="submit" name="add_genre" class="btn btn-primary">Добавить</button>
        </div>
    </form>
</div>

<h3 style="margin-bottom: 1rem; color: #2c3e50;">Список жанров (<?= count($genres) ?>)</h3>

<div class="admin-table-wrapper">
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Название</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($genres as $genre): ?>
                <tr>
                    <td><?= $genre['id'] ?></td>
                    <td><?= e($genre['name']) ?></td>
                    <td>
                        <a href="?delete=<?= $genre['id'] ?>" onclick="return confirm('Удалить жанр?')" class="admin-btn admin-btn-danger">🗑️ Удалить</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

