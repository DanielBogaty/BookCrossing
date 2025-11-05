<?php
// Конфигурация базы данных
// Для продакшена рекомендуется использовать переменные окружения

define('DB_HOST', 'localhost');
define('DB_PORT', '5432');
define('DB_NAME', 'bookcrossing');
define('DB_USER', 'postgres');
define('DB_PASSWORD', 'VaniaVera175'); // Измените на ваш пароль

// Настройки приложения
define('SITE_URL', 'http://localhost');
define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('UPLOAD_URL', '/uploads/');

// Максимальный размер загружаемых файлов (5 MB)
define('MAX_FILE_SIZE', 5 * 1024 * 1024);

// Разрешённые типы изображений
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/jpg', 'image/gif']);

// Создаём директорию для загрузок если её нет
if (!file_exists(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0755, true);
}
if (!file_exists(UPLOAD_DIR . 'books/')) {
    mkdir(UPLOAD_DIR . 'books/', 0755, true);
}
if (!file_exists(UPLOAD_DIR . 'avatars/')) {
    mkdir(UPLOAD_DIR . 'avatars/', 0755, true);
}
?>

