<?php
// Роутер для встроенного PHP сервера при запуске из public/
// Использование: cd public && php -S localhost:8000 router.php

$requestUri = $_SERVER['REQUEST_URI'];
$requestPath = parse_url($requestUri, PHP_URL_PATH);

// Убираем ведущий слеш
$requestPath = ltrim($requestPath, '/');

// Если запрос к корню, используем index.php
if (empty($requestPath) || $requestPath === '/') {
    $requestPath = 'index.php';
}

// Полный путь к файлу в текущей директории (public/)
$localFilePath = __DIR__ . DIRECTORY_SEPARATOR . $requestPath;

// Если файл существует в public/, обрабатываем его
if (file_exists($localFilePath) && is_file($localFilePath)) {
    $ext = strtolower(pathinfo($localFilePath, PATHINFO_EXTENSION));
    
    // Если это PHP файл, выполняем его
    if ($ext === 'php') {
        $_SERVER['SCRIPT_NAME'] = '/' . $requestPath;
        $_SERVER['PHP_SELF'] = '/' . $requestPath;
        require $localFilePath;
        return true;
    }
    
    // Для статических файлов (CSS, JS, изображения) отдаём напрямую
    if (in_array($ext, ['css', 'js', 'jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'ico'])) {
        if ($ext === 'css') {
            header('Content-Type: text/css; charset=utf-8');
        } elseif ($ext === 'js') {
            header('Content-Type: application/javascript; charset=utf-8');
        } elseif (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
            header('Content-Type: image/' . $ext);
        }
        readfile($localFilePath);
        return true;
    }
    
    // Для других статических файлов возвращаем false
    return false;
}

// Проверяем статические файлы в родительской директории (css/, background/, uploads/)
$parentPath = dirname(__DIR__) . DIRECTORY_SEPARATOR . $requestPath;
if (file_exists($parentPath) && is_file($parentPath)) {
    $ext = strtolower(pathinfo($parentPath, PATHINFO_EXTENSION));
    // Если это статический файл (CSS, JS, изображения), отдаём его
    if (in_array($ext, ['css', 'js', 'jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'ico'])) {
        if ($ext === 'css') {
            header('Content-Type: text/css; charset=utf-8');
        } elseif ($ext === 'js') {
            header('Content-Type: application/javascript; charset=utf-8');
        }
        readfile($parentPath);
        return true;
    }
}

// Если ничего не найдено, пробуем index.php
$indexFile = __DIR__ . DIRECTORY_SEPARATOR . 'index.php';
if (file_exists($indexFile)) {
    $_SERVER['SCRIPT_NAME'] = '/index.php';
    $_SERVER['PHP_SELF'] = '/index.php';
    require $indexFile;
    return true;
}

// 404
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
    <p><a href="/">Go to homepage</a></p>
</body>
</html>
<?php
return true;
?>

