<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';

require_login();

$book_id = $_GET['id'] ?? 0;
$book = get_book($book_id);

if (!$book || !can_edit_resource($book['user_id'])) {
    die('Книга не найдена или у вас нет прав на её редактирование.');
}

$genres = get_all_genres();
$user = get_current_user_data();
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
                update_book($book_id, $title, $author, $description, $status, $image_filename, $genre_ids);
                header('Location: dashboard.php?success=updated');
                exit();
            } catch (Exception $e) {
                $error = 'Ошибка обновления книги: ' . $e->getMessage();
            }
        }
    }
} else {
    // Получаем текущие жанры книги
    $_POST['genres'] = explode(',', $book['genre_ids'] ?? '');
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактировать книгу</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="nav-brand">
                <a href="index.php"><h2>📚 BookCrossing</h2></a>
            </div>
            <div class="nav-links">
                <a href="index.php">Главная</a>
                <a href="dashboard.php">Личный кабинет</a>
                <a href="login.php?action=logout" class="btn-logout">Выход</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <h1>✏️ Редактировать книгу</h1>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <form method="POST" enctype="multipart/form-data" class="form">
            <div class="form-group">
                <label>Название книги *</label>
                <input type="text" name="title" required value="<?= htmlspecialchars($book['title']) ?>">
            </div>
            
            <div class="form-group">
                <label>Автор *</label>
                <input type="text" name="author" required value="<?= htmlspecialchars($book['author']) ?>">
            </div>
            
            <div class="form-group">
                <label>Жанры</label>
                <div class="checkbox-group">
                    <?php foreach ($genres as $genre): ?>
                        <label class="checkbox-label">
                            <input type="checkbox" name="genres[]" value="<?= $genre['id'] ?>" 
                                <?= in_array($genre['id'], $_POST['genres'] ?? []) ? 'checked' : '' ?>>
                            <?= htmlspecialchars($genre['name']) ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="form-group">
                <label>Описание</label>
                <textarea name="description" rows="5"><?= htmlspecialchars($book['description']) ?></textarea>
            </div>
            
            <div class="form-group">
                <label>Статус</label>
                <select name="status">
                    <option value="available" <?= $book['status'] == 'available' ? 'selected' : '' ?>>Доступна</option>
                    <option value="taken" <?= $book['status'] == 'taken' ? 'selected' : '' ?>>Взята</option>
                    <option value="reserved" <?= $book['status'] == 'reserved' ? 'selected' : '' ?>>Зарезервирована</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Фотография обложки</label>
                <?php if ($book['image']): ?>
                    <div class="current-image">
                        <img src="<?= htmlspecialchars(UPLOAD_URL . $book['image']) ?>" alt="Текущая обложка" style="max-width: 200px;">
                    </div>
                <?php endif; ?>
                <input type="file" name="image" accept="image/*">
                <small>Оставьте пустым, чтобы не менять изображение</small>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">💾 Сохранить изменения</button>
                <a href="dashboard.php" class="btn">Отмена</a>
            </div>
        </form>
    </div>

    <footer>
        <div class="container">
            <p>&copy; 2025 BookCrossing. Делитесь книгами и знаниями.</p>
        </div>
    </footer>
</body>
</html>

