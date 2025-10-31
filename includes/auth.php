<?php
session_start();

/**
 * Проверяет, авторизован ли пользователь
 */
function is_logged_in() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Получить текущего пользователя из сессии
 */
function get_current_user_data() {
    if (!is_logged_in()) {
        return null;
    }
    
    require_once __DIR__ . '/db.php';
    return get_user($_SESSION['user_id']);
}

/**
 * Проверить, является ли текущий пользователь администратором
 */
function is_admin() {
    if (!is_logged_in()) {
        return false;
    }
    
    $user = get_current_user_data();
    return $user && $user['is_admin'];
}

/**
 * Требовать авторизацию (редирект на страницу входа)
 */
function require_login() {
    if (!is_logged_in()) {
        header('Location: /login.php');
        exit();
    }
}

/**
 * Требовать права администратора
 */
function require_admin() {
    require_login();
    
    if (!is_admin()) {
        http_response_code(403);
        die('Доступ запрещён. Требуются права администратора.');
    }
}

/**
 * Авторизовать пользователя
 */
function login_user($email, $password) {
    require_once __DIR__ . '/db.php';
    
    $user = get_user_by_email($email);
    
    if (!$user) {
        return false;
    }
    
    if (!password_verify($password, $user['password_hash'])) {
        return false;
    }
    
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['is_admin'] = $user['is_admin'];
    
    return true;
}

/**
 * Выйти из системы
 */
function logout_user() {
    session_destroy();
    header('Location: /index.php');
    exit();
}

/**
 * Проверить, принадлежит ли ресурс текущему пользователю или это админ
 */
function can_edit_resource($resource_user_id) {
    if (!is_logged_in()) {
        return false;
    }
    
    return $_SESSION['user_id'] == $resource_user_id || is_admin();
}
?>
