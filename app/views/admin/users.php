<div class="admin-page-header">
    <h1>👥 Управление пользователями</h1>
</div>

<div class="admin-table-wrapper">
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Имя</th>
                <th>Email</th>
                <th>Город</th>
                <th>Telegram</th>
                <th>Рейтинг</th>
                <th>Админ</th>
                <th>Дата регистрации</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= $user['id'] ?></td>
                    <td><?= e($user['username']) ?></td>
                    <td><?= e($user['email']) ?></td>
                    <td><?= e($user['city'] ?? '-') ?></td>
                    <td><?= e($user['telegram_url'] ?? '-') ?></td>
                    <td><?= $user['rating'] ? number_format($user['rating'], 1) : '-' ?></td>
                    <td><?= $user['is_admin'] ? '✅' : '' ?></td>
                    <td><?= date('d.m.Y', strtotime($user['created_at'])) ?></td>
                    <td>
                        <a href="../profile.php?id=<?= $user['id'] ?>" class="admin-btn admin-btn-view">👁️ Просмотр</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

