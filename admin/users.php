<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/services/AuthService.php';

AuthService::requireAdmin();

$pdo = get_db_connection();

// Получаем всех пользователей
$users = $pdo->query("SELECT * FROM users ORDER BY created_at DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление пользователями</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="nav-brand">
                <a href="index.php"><h2>📚 BookCrossing - Админ</h2></a>
            </div>
            <div class="nav-links">
                <a href="index.php">Админ-панель</a>
                <a href="../index.php">На сайт</a>
                <a href="../login.php?action=logout" class="btn-logout">Выход</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <h1>👥 Управление пользователями</h1>
        
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
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['city'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($user['telegram_url'] ?? '-') ?></td>
                        <td><?= $user['rating'] ? number_format($user['rating'], 1) : '-' ?></td>
                        <td><?= $user['is_admin'] ? '✅' : '' ?></td>
                        <td><?= date('d.m.Y', strtotime($user['created_at'])) ?></td>
                        <td>
                            <a href="../profile.php?id=<?= $user['id'] ?>">👁️ Просмотр</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <footer>
        <div class="container">
            <p>&copy; 2025 BookCrossing. Делитесь книгами и знаниями.</p>
        </div>
    </footer>
</body>
</html>

