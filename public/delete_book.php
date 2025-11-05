<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../app/services/AuthService.php';
require_once __DIR__ . '/../app/models/BookModel.php';
require_once __DIR__ . '/../app/helpers/view_helper.php';

AuthService::requireLogin();

$book_id = $_GET['id'] ?? 0;
$book = BookModel::getById($book_id);

if (!$book || !AuthService::canEditResource($book['user_id'])) {
    die('Книга не найдена или у вас нет прав на её удаление.');
}

// Удаляем книгу
BookModel::delete($book_id);

// Удаляем изображение, если оно есть
if ($book['image'] && file_exists(UPLOAD_DIR . $book['image'])) {
    unlink(UPLOAD_DIR . $book['image']);
}

header('Location: /dashboard.php?success=deleted');
exit();
?>

