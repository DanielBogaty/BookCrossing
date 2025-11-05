<?php
require_once __DIR__ . '/../../config/database.php';

/**
 * Модель для работы с пользователями
 */
class UserModel {
    
    /**
     * Получить пользователя по ID
     */
    public static function getById($user_id) {
        $pdo = get_db_connection();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $user_id]);
        return $stmt->fetch();
    }
    
    /**
     * Получить пользователя по email
     */
    public static function getByEmail($email) {
        $pdo = get_db_connection();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }
    
    /**
     * Создать нового пользователя
     */
    public static function create($email, $password, $username, $telegram_url, $city = null) {
        $pdo = get_db_connection();
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $pdo->prepare("
            INSERT INTO users (email, password_hash, username, telegram_url, city)
            VALUES (:email, :password_hash, :username, :telegram_url, :city)
            RETURNING id
        ");
        
        $stmt->execute([
            'email' => $email,
            'password_hash' => $password_hash,
            'username' => $username,
            'telegram_url' => $telegram_url,
            'city' => $city
        ]);
        
        return $stmt->fetch()['id'];
    }
    
    /**
     * Обновить профиль пользователя
     */
    public static function updateProfile($user_id, $username, $city, $telegram_url, $avatar = null) {
        $pdo = get_db_connection();
        
        if ($avatar) {
            $stmt = $pdo->prepare("
                UPDATE users 
                SET username = :username, city = :city, telegram_url = :telegram_url, avatar = :avatar
                WHERE id = :id
            ");
            $stmt->execute([
                'username' => $username,
                'city' => $city,
                'telegram_url' => $telegram_url,
                'avatar' => $avatar,
                'id' => $user_id
            ]);
        } else {
            $stmt = $pdo->prepare("
                UPDATE users 
                SET username = :username, city = :city, telegram_url = :telegram_url
                WHERE id = :id
            ");
            $stmt->execute([
                'username' => $username,
                'city' => $city,
                'telegram_url' => $telegram_url,
                'id' => $user_id
            ]);
        }
    }
    
    /**
     * Получить отзывы о пользователе
     */
    public static function getRatings($user_id) {
        $pdo = get_db_connection();
        
        $stmt = $pdo->prepare("
            SELECT r.*, u.username as from_username
            FROM ratings r
            JOIN users u ON r.from_user_id = u.id
            WHERE r.to_user_id = :user_id
            ORDER BY r.created_at DESC
        ");
        $stmt->execute(['user_id' => $user_id]);
        return $stmt->fetchAll();
    }
    
    /**
     * Пересчитать средний рейтинг пользователя
     */
    public static function updateRating($user_id) {
        $pdo = get_db_connection();
        
        $stmt = $pdo->prepare("
            UPDATE users 
            SET rating = (SELECT AVG(rating) FROM ratings WHERE to_user_id = :user_id)
            WHERE id = :user_id
        ");
        $stmt->execute(['user_id' => $user_id]);
    }
}
?>

