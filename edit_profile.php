<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';

require_login();

$user = get_current_user_data();
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $telegram_url = trim($_POST['telegram_url'] ?? '');
    
    // Валидация
    if (empty($username) || empty($telegram_url)) {
        $error = 'Пожалуйста, заполните обязательные поля.';
    } else {
        // Обработка загрузки аватара
        $avatar_filename = $user['avatar'];
        
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == UPLOAD_ERR_OK) {
            $file = $_FILES['avatar'];
            
            // Проверяем тип файла
            if (!in_array($file['type'], ALLOWED_IMAGE_TYPES)) {
                $error = 'Недопустимый формат изображения. Используйте JPEG, PNG или GIF.';
            } elseif ($file['size'] > MAX_FILE_SIZE) {
                $error = 'Размер файла слишком большой. Максимум ' . (MAX_FILE_SIZE / 1024 / 1024) . ' MB.';
            } else {
                // Генерируем уникальное имя файла
                $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $avatar_filename = 'avatars/' . uniqid() . '.' . $extension;
                
                // Перемещаем файл
                if (!move_uploaded_file($file['tmp_name'], UPLOAD_DIR . $avatar_filename)) {
                    $error = 'Ошибка при загрузке файла.';
                    $avatar_filename = $user['avatar'];
                }
            }
        }
        
        if (!$error) {
            try {
                update_user_profile($user['id'], $username, $city, $telegram_url, $avatar_filename);
                header('Location: dashboard.php?success=profile_updated');
                exit();
            } catch (Exception $e) {
                $error = 'Ошибка обновления профиля: ' . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактировать профиль</title>
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
        <h1>✏️ Редактировать профиль</h1>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <form method="POST" enctype="multipart/form-data" class="form">
            <div class="form-group">
                <label>Имя пользователя *</label>
                <input type="text" name="username" required value="<?= htmlspecialchars($user['username']) ?>">
            </div>
            
            <div class="form-group">
                <label>Email (не изменяется)</label>
                <input type="email" value="<?= htmlspecialchars($user['email']) ?>" disabled>
            </div>
            
            <div class="form-group">
                <label>Город</label>
                <input type="text" name="city" placeholder="Например: Москва" value="<?= htmlspecialchars($user['city'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label>Telegram (@никнейм) *</label>
                <input type="text" name="telegram_url" placeholder="@ваш_ник" required value="<?= htmlspecialchars($user['telegram_url']) ?>">
            </div>
            
            <div class="form-group">
                <label>Аватар</label>
                <?php if ($user['avatar']): ?>
                    <div class="current-avatar">
                        <img src="<?= htmlspecialchars(UPLOAD_URL . $user['avatar']) ?>" alt="Текущий аватар" style="max-width: 150px; border-radius: 50%;">
                    </div>
                <?php endif; ?>
                <input type="file" name="avatar" accept="image/*">
                <small>Максимальный размер: <?= MAX_FILE_SIZE / 1024 / 1024 ?> MB</small>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">💾 Сохранить</button>
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

