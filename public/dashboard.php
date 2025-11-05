<?php
header('Content-Type: text/html; charset=UTF-8');

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../app/services/AuthService.php';
require_once __DIR__ . '/../app/models/BookModel.php';
require_once __DIR__ . '/../app/helpers/view_helper.php';

AuthService::requireLogin();

$user = AuthService::getCurrentUser();
$my_books = BookModel::getByUserId($user['id']);
$success = $_GET['success'] ?? '';

// Навигация
$navbarContent = '<a href="/index.php">Главная</a>
                  <a href="/add_book.php" class="btn-primary">Добавить книгу</a>
                  <a href="/login.php?action=logout" class="btn-logout">Выход</a>';

renderWithLayout('profile/dashboard', [
    'user' => $user,
    'my_books' => $my_books,
    'success' => $success,
    'navbarContent' => $navbarContent,
    'pageTitle' => 'Личный кабинет'
]);
?>

