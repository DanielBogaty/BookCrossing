<?php
header('Content-Type: text/html; charset=UTF-8');

// Загружаем зависимости
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../app/services/AuthService.php';
require_once __DIR__ . '/../app/models/BookModel.php';
require_once __DIR__ . '/../app/models/GenreModel.php';
require_once __DIR__ . '/../app/helpers/view_helper.php';

// Старые функции для обратной совместимости (можно удалить после миграции)
if (!function_exists('is_logged_in')) {
    function is_logged_in() {
        return AuthService::isLoggedIn();
    }
}

if (!function_exists('get_current_user_data')) {
    function get_current_user_data() {
        return AuthService::getCurrentUser();
    }
}

// Получаем параметры поиска и фильтрации
$search = $_GET['search'] ?? '';
$genre_id = $_GET['genre'] ?? null;
$city = $_GET['city'] ?? '';
$status = 'available'; // Показываем только доступные книги

$books = BookModel::getAll($status, $search, $genre_id, $city);
$genres = GenreModel::getAll();
$current_user = AuthService::getCurrentUser();

// Определяем контент навбара
$navbarContent = '';
if (is_logged_in()) {
    $navbarContent = '<a href="/dashboard.php">Личный кабинет</a>
                      <a href="/add_book.php" class="btn-primary">➕ Добавить книгу</a>
                      <a href="/login.php?action=logout" class="btn-logout">Выход</a>';
} else {
    $navbarContent = '<a href="/login.php">Войти</a>
                      <a href="/register.php" class="btn-primary">Регистрация</a>';
}

// Рендерим представление
renderWithLayout('index', [
    'books' => $books,
    'genres' => $genres,
    'search' => $search,
    'genre_id' => $genre_id,
    'city' => $city,
    'current_user' => $current_user,
    'navbarContent' => $navbarContent,
    'pageTitle' => 'BookCrossing — Делись книгами бесплатно'
]);
?>

