<?php
header('Content-Type: text/html; charset=UTF-8');

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../app/services/AuthService.php';
require_once __DIR__ . '/../app/models/UserModel.php';
require_once __DIR__ . '/../app/helpers/view_helper.php';

AuthService::requireLogin();

$user = AuthService::getCurrentUser();
$error = '';

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
                UserModel::updateProfile($user['id'], $username, $city, $telegram_url, $avatar_filename);
                header('Location: /dashboard.php?success=profile_updated');
                exit();
            } catch (Exception $e) {
                $error = 'Ошибка обновления профиля: ' . $e->getMessage();
            }
        }
    }
}

// Навигация
$navbarContent = '<a href="/index.php">Главная</a>
                  <a href="/dashboard.php">Личный кабинет</a>
                  <a href="/login.php?action=logout" class="btn-logout">Выход</a>';

renderWithLayout('profile/edit_profile', [
    'user' => $user,
    'error' => $error,
    'navbarContent' => $navbarContent,
    'pageTitle' => 'Редактировать профиль'
]);
?>

