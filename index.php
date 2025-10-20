<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';

$books = get_mock_books();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Буккроссинг</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>📚 Буккроссинг — делись книгами бесплатно</h1>

        <?php if (is_logged_in()): ?>
            <p><strong>Вы вошли как: @<?= htmlspecialchars(get_mock_user()['telegram_username']) ?></strong></p>
            <a href="dashboard.php">Личный кабинет</a> | <a href="add_book.php">➕ Добавить книгу</a>
        <?php else: ?>
            <a href="login.php">Войти</a> | <a href="register.php">Регистрация</a>
        <?php endif; ?>

        <hr>

        <h2>Доступные книги</h2>
        <?php foreach ($books as $book): ?>
            <div class="book-card">
                <h3><?= htmlspecialchars($book['title']) ?> — <?= htmlspecialchars($book['author']) ?></h3>
                <p><strong>Жанр:</strong> <?= htmlspecialchars($book['genre']) ?></p>
                <p><?= htmlspecialchars($book['description']) ?></p>
                <p>
                    <a href="https://t.me/<?= urlencode($book['telegram_username']) ?>" target="_blank">
                        💬 Написать владельцу: <?= htmlspecialchars($book['telegram_username']) ?>
                    </a>
                </p>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>