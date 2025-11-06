<?php
session_start();

/**
 * Сервис для работы с аутентификацией пользователей
 */
class AuthService {
    
    /**
     * Проверяет, авторизован ли пользователь
     */
    public static function isLoggedIn() {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }
    
    /**
     * Получить текущего пользователя из сессии
     */
    public static function getCurrentUser() {
        if (!self::isLoggedIn()) {
            return null;
        }
        
        require_once __DIR__ . '/../models/UserModel.php';
        return UserModel::getById($_SESSION['user_id']);
    }
    
    /**
     * Проверить, является ли текущий пользователь администратором
     */
    public static function isAdmin() {
        if (!self::isLoggedIn()) {
            return false;
        }
        
        $user = self::getCurrentUser();
        return $user && $user['is_admin'];
    }
    
    /**
     * Требовать авторизацию (редирект на страницу входа)
     */
    public static function requireLogin() {
        if (!self::isLoggedIn()) {
            header('Location: /login.php');
            exit();
        }
    }
    
    /**
     * Требовать права администратора
     */
    public static function requireAdmin() {
        if (!self::isLoggedIn()) {
            // Редирект на страницу входа в админку
            header('Location: /admin/login.php');
            exit();
        }
        
        if (!self::isAdmin()) {
            // Если не админ, выходим и редиректим на админ-логин
            session_destroy();
            header('Location: /admin/login.php?error=access_denied');
            exit();
        }
    }
    
    /**
     * Авторизовать пользователя
     */
    public static function login($email, $password) {
        require_once __DIR__ . '/../models/UserModel.php';
        
        $user = UserModel::getByEmail($email);
        
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
    public static function logout() {
        session_destroy();
        header('Location: /index.php');
        exit();
    }
    
    /**
     * Старые функции для обратной совместимости
     */
    public static function login_user($email, $password) {
        return self::login($email, $password);
    }
    
    public static function logout_user() {
        self::logout();
    }
    
    /**
     * Проверить, принадлежит ли ресурс текущему пользователю или это админ
     */
    public static function canEditResource($resource_user_id) {
        if (!self::isLoggedIn()) {
            return false;
        }
        
        return $_SESSION['user_id'] == $resource_user_id || self::isAdmin();
    }
}
?>

