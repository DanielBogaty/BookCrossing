<?php
header('Content-Type: text/html; charset=UTF-8');

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../app/services/AuthService.php';
require_once __DIR__ . '/../app/models/BookModel.php';
require_once __DIR__ . '/../app/models/GenreModel.php';
require_once __DIR__ . '/../app/helpers/view_helper.php';

AuthService::requireLogin();

$user = AuthService::getCurrentUser();
$genres = GenreModel::getAll();
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title'] ?? '');
    $author = trim($_POST['author'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $genre_ids = $_POST['genres'] ?? [];
    
    // Валидация
    if (empty($title) || empty($author)) {
        $error = 'Пожалуйста, заполните название и автора книги.';
    } else {
        // Обработка загрузки изображения
        $image_filename = null;
        
        if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
            $file = $_FILES['image'];
            
            // Проверяем тип файла
            if (!in_array($file['type'], ALLOWED_IMAGE_TYPES)) {
                $error = 'Недопустимый формат изображения. Используйте JPEG, PNG или GIF.';
            } elseif ($file['size'] > MAX_FILE_SIZE) {
                $error = 'Размер файла слишком большой. Максимум ' . (MAX_FILE_SIZE / 1024 / 1024) . ' MB.';
            } else {
                // Генерируем уникальное имя файла
                $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $image_filename = 'books/' . uniqid() . '.' . $extension;
                
                // Перемещаем файл
                if (!move_uploaded_file($file['tmp_name'], UPLOAD_DIR . $image_filename)) {
                    $error = 'Ошибка при загрузке файла.';
                    $image_filename = null;
                }
            }
        }
        
        if (!$error) {
            try {
                BookModel::create($user['id'], $title, $author, $description, $image_filename, $genre_ids);
                header('Location: /dashboard.php?success=added');
                exit();
            } catch (Exception $e) {
                $error = 'Ошибка добавления книги: ' . $e->getMessage();
            }
        }
    }
}

// Навигация
$navbarContent = '<a href="/index.php">Главная</a>
                  <a href="/dashboard.php">Личный кабинет</a>
                  <a href="/login.php?action=logout" class="btn-logout">Выход</a>';

renderWithLayout('books/add', [
    'error' => $error,
    'genres' => $genres,
    'navbarContent' => $navbarContent,
    'pageTitle' => 'Добавить книгу'
]);
?>

