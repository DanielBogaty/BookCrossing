<?php
session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/services/AuthService.php';
require_once __DIR__ . '/../app/models/UserModel.php';
require_once __DIR__ . '/../app/helpers/view_helper.php';

// Если уже авторизован как админ, редирект на админ-панель
if (AuthService::isAdmin()) {
    header('Location: index.php');
    exit();
}

$error = '';
$email = '';

// Обработка ошибки из URL параметра
if (isset($_GET['error'])) {
    if ($_GET['error'] == 'access_denied') {
        $error = 'У вас нет прав доступа к админ-панели.';
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error = 'Пожалуйста, заполните все поля.';
    } else {
        // Проверяем авторизацию
        if (AuthService::login($email, $password)) {
            $user = AuthService::getCurrentUser();
            
            // Проверяем, является ли пользователь администратором
            if ($user && $user['is_admin']) {
                header('Location: index.php');
                exit();
            } else {
                // Если не админ, выходим и показываем ошибку
                AuthService::logout();
                $error = 'У вас нет прав доступа к админ-панели.';
            }
        } else {
            $error = 'Неверный email или пароль.';
        }
    }
}

$content = render('admin/login', [
    'error' => $error,
    'email' => $email
]);

$pageTitle = 'Вход в админ-панель | BookCrossing';
include __DIR__ . '/../app/views/layouts/admin.php';
?>

