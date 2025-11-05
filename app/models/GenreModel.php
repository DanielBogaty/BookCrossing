<?php
require_once __DIR__ . '/../../config/database.php';

/**
 * Модель для работы с жанрами
 */
class GenreModel {
    
    /**
     * Получить все жанры
     */
    public static function getAll() {
        $pdo = get_db_connection();
        $stmt = $pdo->query("SELECT * FROM genres ORDER BY name");
        return $stmt->fetchAll();
    }
}
?>

