<?php
header('Content-Type: text/html; charset=UTF-8');

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../app/services/AuthService.php';
require_once __DIR__ . '/../app/models/BookModel.php';
require_once __DIR__ . '/../app/models/GenreModel.php';
require_once __DIR__ . '/../app/helpers/view_helper.php';

AuthService::requireLogin();

$book_id = $_GET['id'] ?? 0;
$book = BookModel::getById($book_id);

if (!$book || !AuthService::canEditResource($book['user_id'])) {
    die('Книга не найдена или у вас нет прав на её редактирование.');
}

$genres = GenreModel::getAll();
$user = AuthService::getCurrentUser();
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title'] ?? '');
    $author = trim($_POST['author'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $status = $_POST['status'] ?? 'available';
    $genre_ids = $_POST['genres'] ?? [];
    
    // Валидация
    if (empty($title) || empty($author)) {
        $error = 'Пожалуйста, заполните название и автора книги.';
    } else {
        // Обработка загрузки изображения
        $image_filename = $book['image'];
        
        if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
            $file = $_FILES['image'];
            
            if (!in_array($file['type'], ALLOWED_IMAGE_TYPES)) {
                $error = 'Недопустимый формат изображения.';
            } elseif ($file['size'] > MAX_FILE_SIZE) {
                $error = 'Размер файла слишком большой.';
            } else {
                $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $image_filename = 'books/' . uniqid() . '.' . $extension;
                
                if (!move_uploaded_file($file['tmp_name'], UPLOAD_DIR . $image_filename)) {
                    $error = 'Ошибка при загрузке файла.';
                    $image_filename = $book['image'];
                }
            }
        }
        
        if (!$error) {
            try {
                BookModel::update($book_id, $title, $author, $description, $status, $image_filename, $genre_ids);
                header('Location: /dashboard.php?success=updated');
                exit();
            } catch (Exception $e) {
                $error = 'Ошибка обновления книги: ' . $e->getMessage();
            }
        }
    }
} else {
    // Получаем текущие жанры книги
    $selected_genres = explode(',', $book['genre_ids'] ?? '');
}

// Навигация
$navbarContent = '<a href="/index.php">Главная</a>
                  <a href="/dashboard.php">Личный кабинет</a>
                  <a href="/login.php?action=logout" class="btn-logout">Выход</a>';

renderWithLayout('books/edit', [
    'book' => $book,
    'genres' => $genres,
    'selected_genres' => $selected_genres,
    'error' => $error,
    'navbarContent' => $navbarContent,
    'pageTitle' => 'Редактировать книгу'
]);
?>

