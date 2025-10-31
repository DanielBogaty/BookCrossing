<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

require_admin();

global $pdo;

$error = '';
$success = '';

// Добавление нового жанра
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_genre'])) {
    $name = trim($_POST['name'] ?? '');
    
    if (empty($name)) {
        $error = 'Введите название жанра.';
    } else {
        try {
            $pdo->prepare("INSERT INTO genres (name) VALUES (:name)")->execute(['name' => $name]);
            $success = 'Жанр успешно добавлен.';
        } catch (Exception $e) {
            $error = 'Ошибка: ' . $e->getMessage();
        }
    }
}

// Удаление жанра
if (isset($_GET['delete'])) {
    try {
        $pdo->prepare("DELETE FROM genres WHERE id = :id")->execute(['id' => $_GET['delete']]);
        $success = 'Жанр удалён.';
    } catch (Exception $e) {
        $error = 'Ошибка при удалении: ' . $e->getMessage();
    }
}

$genres = get_all_genres();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление жанрами</title>
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
        <h1>🏷️ Управление жанрами</h1>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        
        <div class="form-card">
            <h3>➕ Добавить новый жанр</h3>
            <form method="POST" class="form">
                <div class="form-group">
                    <input type="text" name="name" placeholder="Название жанра" required>
                    <button type="submit" name="add_genre" class="btn btn-primary">Добавить</button>
                </div>
            </form>
        </div>
        
        <h3>Список жанров (<?= count($genres) ?>)</h3>
        
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
                        <td><?= htmlspecialchars($genre['name']) ?></td>
                        <td>
                            <a href="?delete=<?= $genre['id'] ?>" onclick="return confirm('Удалить жанр?')" class="btn-danger">🗑️ Удалить</a>
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

