<?php
require_once 'includes/auth.php';

if ($_POST) {
    // Заглушка: при любых данных вход успешен
    $_SESSION['user_id'] = 1;
    header('Location: dashboard.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вход</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>🔐 Вход</h1>
        <form method="POST">
            <label>Email: <input type="email" name="email" required></label><br><br>
            <label>Пароль: <input type="password" name="password" required></label><br><br>
            <button type="submit">Войти</button>
        </form>
        <p><a href="register.php">Нет аккаунта? Зарегистрируйтесь</a></p>
    </div>
</body>
</html>