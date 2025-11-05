<?php
header('Content-Type: text/html; charset=UTF-8');

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../app/services/AuthService.php';
require_once __DIR__ . '/../app/models/UserModel.php';
require_once __DIR__ . '/../app/helpers/view_helper.php';

// Проверяем действие выхода
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    AuthService::logout();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error = 'Пожалуйста, заполните все поля.';
    } elseif (!AuthService::login($email, $password)) {
        $error = 'Неверный email или пароль.';
    } else {
        header('Location: /dashboard.php');
        exit();
    }
}

$content = render('auth/login', ['error' => $error]);
$pageTitle = 'Вход — BookCrossing';
include __DIR__ . '/../app/views/layouts/auth.php';
?>

