<?php
header('Content-Type: text/html; charset=UTF-8');
require_once 'includes/auth.php';
require_once 'includes/db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';
    $username = trim($_POST['username'] ?? '');
    $telegram_url = trim($_POST['telegram_url'] ?? '');
    $city = trim($_POST['city'] ?? '');
    
    // Валидация
    if (empty($email) || empty($password) || empty($username) || empty($telegram_url)) {
        $error = 'Пожалуйста, заполните все обязательные поля.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Некорректный email адрес.';
    } elseif (strlen($password) < 6) {
        $error = 'Пароль должен быть не менее 6 символов.';
    } elseif ($password !== $password_confirm) {
        $error = 'Пароли не совпадают.';
    } elseif (get_user_by_email($email)) {
        $error = 'Пользователь с таким email уже существует.';
    } else {
        // Создаём пользователя
        try {
            $user_id = create_user($email, $password, $username, $telegram_url, $city);
            
            // Автоматически авторизуем
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;
            
            header('Location: dashboard.php');
            exit();
        } catch (Exception $e) {
            $error = 'Ошибка регистрации: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация — BookCrossing</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h1>📝 Регистрация</h1>
                <p>Присоединяйтесь к сообществу BookCrossing</p>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
        
            <form method="POST" class="auth-form">
                <div class="auth-form-group">
                    <label for="username">Имя пользователя</label>
                    <input type="text" id="username" name="username" required 
                           value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                           placeholder="Введите ваше имя">
                </div>
                
                <div class="auth-form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required 
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                           placeholder="Введите ваш email">
                </div>
                
                <div class="auth-form-group">
                    <label for="password">Пароль</label>
                    <input type="password" id="password" name="password" required minlength="6"
                           placeholder="Минимум 6 символов">
                </div>
                
                <div class="auth-form-group">
                    <label for="password_confirm">Подтверждение пароля</label>
                    <input type="password" id="password_confirm" name="password_confirm" required minlength="6"
                           placeholder="Повторите пароль">
                </div>
                
                <div class="auth-form-group">
                    <label for="telegram_url">Telegram</label>
                    <input type="text" id="telegram_url" name="telegram_url" required 
                           value="<?= htmlspecialchars($_POST['telegram_url'] ?? '') ?>"
                           placeholder="@ваш_никнейм">
                </div>
                
                <div class="auth-form-group">
                    <label for="city">Город</label>
                    <input type="text" id="city" name="city" 
                           value="<?= htmlspecialchars($_POST['city'] ?? '') ?>"
                           placeholder="Например: Москва">
                </div>
                
                <button type="submit" class="auth-submit">Зарегистрироваться</button>
            </form>
            
            <div class="auth-links">
                <p><a href="login.php">Уже есть аккаунт? Войдите</a></p>
            </div>
        </div>
    </div>
</body>
</html>