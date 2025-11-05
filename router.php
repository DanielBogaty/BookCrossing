<?php
// Роутер для встроенного PHP сервера
// Использование: php -S localhost:8000 router.php
// Важно: запускать из корня проекта (D:\BookCrossing), не из public/

$requestUri = $_SERVER['REQUEST_URI'];
$requestPath = parse_url($requestUri, PHP_URL_PATH);

// Убираем ведущий слеш
$requestPath = ltrim($requestPath, '/');

// СНАЧАЛА проверяем статические файлы в корне проекта (CSS, JS, изображения)
// Это должно быть ДО проверки public/, чтобы статические файлы работали
$rootFilePath = __DIR__ . DIRECTORY_SEPARATOR . $requestPath;
if (file_exists($rootFilePath) && is_file($rootFilePath)) {
    $ext = strtolower(pathinfo($rootFilePath, PATHINFO_EXTENSION));
    // Если это не PHP файл, отдаём его напрямую
    if ($ext !== 'php' && $ext !== 'phtml') {
        // Устанавливаем правильный Content-Type для статических файлов
        if ($ext === 'css') {
            header('Content-Type: text/css; charset=utf-8');
        } elseif ($ext === 'js') {
            header('Content-Type: application/javascript; charset=utf-8');
        } elseif (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
            header('Content-Type: image/' . $ext);
        }
        readfile($rootFilePath);
        return true;
    }
}

// Если запрос к корню, используем index.php
if (empty($requestPath) || $requestPath === '/') {
    $requestPath = 'index.php';
}

// Полный путь к файлу в public/
$publicFilePath = __DIR__ . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $requestPath;

// Если файл существует в public/, обрабатываем его
if (file_exists($publicFilePath) && is_file($publicFilePath)) {
    $ext = strtolower(pathinfo($publicFilePath, PATHINFO_EXTENSION));
    
    // Если это PHP файл, выполняем его
    if ($ext === 'php') {
        // Устанавливаем переменные окружения для правильной работы
        $_SERVER['SCRIPT_NAME'] = '/' . $requestPath;
        $_SERVER['PHP_SELF'] = '/' . $requestPath;
        
        // Меняем рабочую директорию на public для правильной работы путей
        chdir(__DIR__ . DIRECTORY_SEPARATOR . 'public');
        
        // Выполняем PHP файл
        require $publicFilePath;
        return true;
    }
    
    // Для статических файлов в public/ (если они есть)
    $ext = strtolower(pathinfo($publicFilePath, PATHINFO_EXTENSION));
    if (in_array($ext, ['css', 'js', 'jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'ico'])) {
        if ($ext === 'css') {
            header('Content-Type: text/css; charset=utf-8');
        } elseif ($ext === 'js') {
            header('Content-Type: application/javascript; charset=utf-8');
        }
        readfile($publicFilePath);
        return true;
    }
    
    // Для других статических файлов возвращаем false
    return false;
}

// Если ничего не найдено, пробуем index.php в public/
$indexFilePath = __DIR__ . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'index.php';
if (file_exists($indexFilePath)) {
    $_SERVER['SCRIPT_NAME'] = '/index.php';
    $_SERVER['PHP_SELF'] = '/index.php';
    chdir(__DIR__ . DIRECTORY_SEPARATOR . 'public');
    require $indexFilePath;
    return true;
}

// 404 - Файл не найден
http_response_code(404);
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>404 - Not Found</title>
    <meta charset="UTF-8">
</head>
<body>
    <h1>404 - File not found</h1>
    <p>Requested path: <?= htmlspecialchars($requestPath) ?></p>
    <p>Root path checked: <?= htmlspecialchars($rootFilePath) ?></p>
    <p>Public path checked: <?= htmlspecialchars($publicFilePath) ?></p>
    <p><a href="/">Go to homepage</a></p>
</body>
</html>
<?php
return true;
?>
