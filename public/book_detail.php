<?php
header('Content-Type: text/html; charset=UTF-8');

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../app/services/AuthService.php';
require_once __DIR__ . '/../app/models/BookModel.php';
require_once __DIR__ . '/../app/models/UserModel.php';
require_once __DIR__ . '/../app/models/RatingModel.php';
require_once __DIR__ . '/../app/helpers/view_helper.php';

$book_id = $_GET['id'] ?? 0;
$book = BookModel::getById($book_id);

if (!$book) {
    die('Книга не найдена.');
}

$current_user = AuthService::getCurrentUser();
$owner_ratings = RatingModel::getByUserId($book['user_id']);

// Навигация
$navbarContent = '<a href="/index.php">← Назад к каталогу</a>';
if (AuthService::isLoggedIn()) {
    $navbarContent .= '<a href="/dashboard.php">Личный кабинет</a>
                       <a href="/add_book.php" class="btn-primary">➕ Добавить книгу</a>
                       <a href="/login.php?action=logout" class="btn-logout">Выход</a>';
} else {
    $navbarContent .= '<a href="/login.php">Войти</a>
                       <a href="/register.php" class="btn-primary">Регистрация</a>';
}

renderWithLayout('books/detail', [
    'book' => $book,
    'current_user' => $current_user,
    'owner_ratings' => $owner_ratings,
    'navbarContent' => $navbarContent,
    'pageTitle' => e($book['title']) . ' — BookCrossing'
]);
?>

