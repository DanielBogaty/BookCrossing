<?php
header('Content-Type: text/html; charset=UTF-8');

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../app/services/AuthService.php';
require_once __DIR__ . '/../app/models/UserModel.php';
require_once __DIR__ . '/../app/models/BookModel.php';
require_once __DIR__ . '/../app/models/RatingModel.php';
require_once __DIR__ . '/../app/helpers/view_helper.php';

$user_id = $_GET['id'] ?? 0;
$profile_user = UserModel::getById($user_id);

if (!$profile_user) {
    die('Пользователь не найден.');
}

$user_books = BookModel::getByUserId($user_id);
$ratings = RatingModel::getByUserId($user_id);
$current_user = AuthService::getCurrentUser();

// Навигация
$navbarContent = '<a href="/index.php">Главная</a>';
if (AuthService::isLoggedIn()) {
    $navbarContent .= '<a href="/dashboard.php">Личный кабинет</a>
                       <a href="/login.php?action=logout" class="btn-logout">Выход</a>';
} else {
    $navbarContent .= '<a href="/login.php">Войти</a>';
}

renderWithLayout('profile/profile', [
    'profile_user' => $profile_user,
    'user_id' => $user_id,
    'user_books' => $user_books,
    'ratings' => $ratings,
    'current_user' => $current_user,
    'navbarContent' => $navbarContent,
    'pageTitle' => 'Профиль: ' . e($profile_user['username'])
]);
?>

