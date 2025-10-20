<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';

require_login();

if ($_POST) {
    // Здесь в будущем: INSERT INTO books ...
    // Сейчас просто имитируем успех
    header('Location: dashboard.php?added=1');
    exit();
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Добавить книгу</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>➕ Добавить книгу</h1>
        <form method="POST">
            <label>Название: <input type="text" name="title" required style="width: 100%; padding: 8px; margin: 6px 0"></label><br>
            <label>Автор: <input type="text" name="author" required style="width: 100%; padding: 8px; margin: 6px 0"></label><br>
            <label>Жанр: <input type="text" name="genre" placeholder="Например: фантастика, детектив" style="width: 100%; padding: 8px; margin: 6px 0"></label><br>
            <label>Описание: 
                <textarea name="description" rows="4" style="width: 100%; padding: 8px; margin: 6px 0"></textarea>
            </label><br>
            <button type="submit">Сохранить книгу</button>
        </form>
        <p><a href="dashboard.php">← Назад в кабинет</a></p>
    </div>
</body>
</html>