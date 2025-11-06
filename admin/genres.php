<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/services/AuthService.php';
require_once __DIR__ . '/../app/models/GenreModel.php';
require_once __DIR__ . '/../app/helpers/view_helper.php';

AuthService::requireAdmin();

$pdo = get_db_connection();

$error = '';
$success = '';

// Добавление нового жанра
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_genre'])) {
    $name = trim($_POST['name'] ?? '');
    
    if (empty($name)) {
        $error = 'Введите название жанра.';
    } else {
        try {
            $pdo->prepare("INSERT INTO genres (name) VALUES (:name)")->execute(['name' => $name]);
            header('Location: genres.php?success=added');
            exit();
        } catch (Exception $e) {
            $error = 'Ошибка: ' . $e->getMessage();
        }
    }
}

// Удаление жанра
if (isset($_GET['delete'])) {
    try {
        $pdo->prepare("DELETE FROM genres WHERE id = :id")->execute(['id' => $_GET['delete']]);
        header('Location: genres.php?success=deleted');
        exit();
    } catch (Exception $e) {
        $error = 'Ошибка при удалении: ' . $e->getMessage();
    }
}

// Обработка сообщений об успехе
if (isset($_GET['success'])) {
    if ($_GET['success'] == 'added') {
        $success = 'Жанр успешно добавлен.';
    } elseif ($_GET['success'] == 'deleted') {
        $success = 'Жанр успешно удалён.';
    }
}

$genres = GenreModel::getAll();

$pageContent = render('admin/genres', [
    'genres' => $genres
]);

$content = render('admin/layout', [
    'content' => $pageContent,
    'error' => $error,
    'success' => $success
]);

$pageTitle = 'Управление жанрами | BookCrossing';
include __DIR__ . '/../app/views/layouts/admin.php';
?>

