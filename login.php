<?php
header('Content-Type: text/html; charset=UTF-8');
require_once 'includes/auth.php';

$error = '';

// Проверяем действие выхода
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    logout_user();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error = 'Пожалуйста, заполните все поля.';
    } elseif (!login_user($email, $password)) {
        $error = 'Неверный email или пароль.';
    } else {
        header('Location: dashboard.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вход — BookCrossing</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h1>🔐 Вход</h1>
                <p>Добро пожаловать обратно в BookCrossing</p>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <form method="POST" class="auth-form">
                <div class="auth-form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required 
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                           placeholder="Введите ваш email">
                </div>
                
                <div class="auth-form-group">
                    <label for="password">Пароль</label>
                    <input type="password" id="password" name="password" required 
                           placeholder="Введите ваш пароль">
                </div>
                
                <button type="submit" class="auth-submit">Войти</button>
            </form>
            
            <div class="auth-links">
                <p><a href="register.php">Нет аккаунта? Зарегистрируйтесь</a></p>
            </div>
            
            <div class="auth-test-info">
                <small>Для теста: <code>admin@bookcrossing.ru</code> / <code>admin123</code></small>
            </div>
        </div>
    </div>
</body>
</html>