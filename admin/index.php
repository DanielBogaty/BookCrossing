<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/services/AuthService.php';

AuthService::requireAdmin();

// Получаем статистику
$pdo = get_db_connection();

$stats = [];
$stats['users'] = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$stats['books'] = $pdo->query("SELECT COUNT(*) FROM books")->fetchColumn();
$stats['books_available'] = $pdo->query("SELECT COUNT(*) FROM books WHERE status = 'available'")->fetchColumn();
$stats['genres'] = $pdo->query("SELECT COUNT(*) FROM genres")->fetchColumn();
$stats['ratings'] = $pdo->query("SELECT COUNT(*) FROM ratings")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ-панель</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="nav-brand">
                <a href="../index.php"><h2>📚 BookCrossing - Админ</h2></a>
            </div>
            <div class="nav-links">
                <a href="../index.php">На сайт</a>
                <a href="../login.php?action=logout" class="btn-logout">Выход</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <h1>🛠️ Панель администратора</h1>
        
        <div class="admin-stats">
            <div class="stat-card">
                <h3>👥 Пользователей</h3>
                <p class="stat-number"><?= $stats['users'] ?></p>
            </div>
            <div class="stat-card">
                <h3>📚 Всего книг</h3>
                <p class="stat-number"><?= $stats['books'] ?></p>
            </div>
            <div class="stat-card">
                <h3>✅ Доступных</h3>
                <p class="stat-number"><?= $stats['books_available'] ?></p>
            </div>
            <div class="stat-card">
                <h3>🏷️ Жанров</h3>
                <p class="stat-number"><?= $stats['genres'] ?></p>
            </div>
            <div class="stat-card">
                <h3>⭐ Отзывов</h3>
                <p class="stat-number"><?= $stats['ratings'] ?></p>
            </div>
        </div>

        <div class="admin-menu">
            <a href="users.php" class="admin-menu-item">
                <h3>👥 Управление пользователями</h3>
                <p>Просмотр, редактирование и удаление пользователей</p>
            </a>
            
            <a href="books.php" class="admin-menu-item">
                <h3>📚 Управление книгами</h3>
                <p>Просмотр, редактирование и модерация книг</p>
            </a>
            
            <a href="genres.php" class="admin-menu-item">
                <h3>🏷️ Управление жанрами</h3>
                <p>Добавление, редактирование и удаление жанров</p>
            </a>
        </div>
    </div>

    <footer>
        <div class="container">
            <p>&copy; 2025 BookCrossing. Делитесь книгами и знаниями.</p>
        </div>
    </footer>
</body>
</html>

