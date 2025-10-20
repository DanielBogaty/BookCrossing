<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';

require_login(); // проверяем, залогинен ли пользователь

$my_books = get_mock_books(); // в будущем: SELECT * FROM books WHERE owner_id = ?
$user = get_mock_user();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Личный кабинет</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>👤 Личный кабинет</h1>
        <p><strong>Профиль:</strong> <?= htmlspecialchars($user['username']) ?> (<?= htmlspecialchars($user['email']) ?>)</p>
        <p><strong>Telegram:</strong> <?= htmlspecialchars($user['telegram_username']) ?></p>
        <a href="add_book.php">➕ Добавить книгу</a> | <a href="index.php">На главную</a>
        <hr>

        <h2>Ваши книги</h2>
        <?php if (count($my_books)): ?>
            <?php foreach ($my_books as $book): ?>
                <div class="book-card">
                    <h3><?= htmlspecialchars($book['title']) ?> — <?= htmlspecialchars($book['author']) ?></h3>
                    <p><?= htmlspecialchars($book['description']) ?></p>
                    <p><strong>Статус:</strong> <?= $book['status'] === 'available' ? 'Доступна' : 'Не доступна' ?></p>
                    <a href="#">Редактировать</a> | <a href="#">Удалить</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>У вас пока нет добавленных книг.</p>
        <?php endif; ?>
    </div>
</body>
</html>