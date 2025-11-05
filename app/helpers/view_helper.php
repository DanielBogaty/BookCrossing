<?php
/**
 * Вспомогательные функции для работы с представлениями
 */

/**
 * Рендерит представление
 */
function render($viewPath, $data = []) {
    extract($data);
    ob_start();
    include __DIR__ . '/../views/' . $viewPath . '.php';
    return ob_get_clean();
}

/**
 * Рендерит представление с layout
 */
function renderWithLayout($viewPath, $data = [], $layout = 'layouts/base') {
    $content = render($viewPath, $data);
    $data['content'] = $content;
    extract($data);
    include __DIR__ . '/../views/' . $layout . '.php';
}

/**
 * Экранирует HTML (аналог htmlspecialchars)
 */
function e($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Безопасная функция для работы с многобайтовыми строками
 * Использует mb_substr если доступно, иначе substr
 */
function safe_substr($string, $start, $length = null) {
    if (function_exists('mb_substr')) {
        return mb_substr($string ?? '', $start, $length);
    }
    return substr($string ?? '', $start, $length);
}

/**
 * Безопасная функция для получения длины многобайтовой строки
 * Использует mb_strlen если доступно, иначе strlen
 */
function safe_strlen($string) {
    if (function_exists('mb_strlen')) {
        return mb_strlen($string ?? '');
    }
    return strlen($string ?? '');
}

/**
 * Старая функция для обратной совместимости
 */
if (!function_exists('is_logged_in')) {
    function is_logged_in() {
        require_once __DIR__ . '/../services/AuthService.php';
        return AuthService::isLoggedIn();
    }
}
?>

