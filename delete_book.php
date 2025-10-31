<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';

require_login();

$book_id = $_GET['id'] ?? 0;
$book = get_book($book_id);

if (!$book || !can_edit_resource($book['user_id'])) {
    die('Книга не найдена или у вас нет прав на её удаление.');
}

// Удаляем книгу
delete_book($book_id);

// Удаляем изображение, если оно есть
if ($book['image'] && file_exists(UPLOAD_DIR . $book['image'])) {
    unlink(UPLOAD_DIR . $book['image']);
}

header('Location: dashboard.php?success=deleted');
exit();
?>

