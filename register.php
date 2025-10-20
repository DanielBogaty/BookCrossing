<?php
require_once 'includes/auth.php';

if ($_POST) {
    // Здесь в будущем будет сохранение в БД
    // Сейчас просто редирект (как будто всё прошло успешно)
    $_SESSION['user_id'] = 1;
    header('Location: dashboard.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>📝 Регистрация</h1>
        <form method="POST">
            <label>Имя: <input type="text" name="username" required></label><br><br>
            <label>Email: <input type="email" name="email" required></label><br><br>
            <label>Пароль: <input type="password" name="password" required></label><br><br>
            <label>Telegram (@никнейм): <input type="text" name="telegram_username" placeholder="@ваш_ник" required></label><br><br>
            <button type="submit">Зарегистрироваться</button>
        </form>
        <p><a href="login.php">Уже есть аккаунт? Войти</a></p>
    </div>
</body>
</html>