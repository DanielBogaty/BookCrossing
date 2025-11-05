<?php
header('Content-Type: text/html; charset=UTF-8');

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../app/services/AuthService.php';
require_once __DIR__ . '/../app/models/UserModel.php';
require_once __DIR__ . '/../app/helpers/view_helper.php';

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
    } elseif (UserModel::getByEmail($email)) {
        $error = 'Пользователь с таким email уже существует.';
    } else {
        // Создаём пользователя
        try {
            $user_id = UserModel::create($email, $password, $username, $telegram_url, $city);
            
            // Автоматически авторизуем
            AuthService::login($email, $password);
            
            header('Location: /dashboard.php');
            exit();
        } catch (Exception $e) {
            $error = 'Ошибка регистрации: ' . $e->getMessage();
        }
    }
}

$content = render('auth/register', ['error' => $error, 'success' => $success]);
$pageTitle = 'Регистрация — BookCrossing';
include __DIR__ . '/../app/views/layouts/auth.php';
?>

