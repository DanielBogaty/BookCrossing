<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

require_admin();

$books = get_all_books();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление книгами</title>
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
        <h1>📚 Управление книгами</h1>
        
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
                        <td><?= htmlspecialchars($book['title']) ?></td>
                        <td><?= htmlspecialchars($book['author']) ?></td>
                        <td><?= htmlspecialchars($book['genres'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($book['username']) ?></td>
                        <td>
                            <span class="badge badge-<?= $book['status'] ?>">
                                <?= $book['status'] ?>
                            </span>
                        </td>
                        <td><?= date('d.m.Y', strtotime($book['created_at'])) ?></td>
                        <td>
                            <a href="../edit_book.php?id=<?= $book['id'] ?>">✏️ Редактировать</a>
                            <a href="../delete_book.php?id=<?= $book['id'] ?>" onclick="return confirm('Удалить книгу?')">🗑️</a>
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

