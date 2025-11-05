<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/UserModel.php';

/**
 * Модель для работы с рейтингами
 */
class RatingModel {
    
    /**
     * Добавить рейтинг пользователю
     */
    public static function add($from_user_id, $to_user_id, $rating, $comment = null) {
        $pdo = get_db_connection();
        
        // Нельзя оценивать самого себя
        if ($from_user_id == $to_user_id) {
            return false;
        }
        
        $stmt = $pdo->prepare("
            INSERT INTO ratings (from_user_id, to_user_id, rating, comment)
            VALUES (:from_user_id, :to_user_id, :rating, :comment)
            ON CONFLICT (from_user_id, to_user_id) 
            DO UPDATE SET rating = :rating, comment = :comment, created_at = CURRENT_TIMESTAMP
        ");
        
        $stmt->execute([
            'from_user_id' => $from_user_id,
            'to_user_id' => $to_user_id,
            'rating' => $rating,
            'comment' => $comment
        ]);
        
        // Обновляем средний рейтинг пользователя
        UserModel::updateRating($to_user_id);
        
        return true;
    }
    
    /**
     * Получить отзывы о пользователе
     */
    public static function getByUserId($user_id) {
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
}
?>

