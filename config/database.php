<?php
require_once __DIR__ . '/app.php';

// Глобальная переменная для подключения
$pdo = null;

/**
 * Получить подключение к базе данных
 */
function get_db_connection() {
    global $pdo;
    
    if ($pdo === null) {
        try {
            // Проверяем доступность драйвера
            if (!in_array('pgsql', PDO::getAvailableDrivers())) {
                die("Ошибка: драйвер pdo_pgsql не доступен. Доступные драйверы: " . implode(', ', PDO::getAvailableDrivers()));
            }
            
            $dsn = "pgsql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";options='--client_encoding=UTF8'";
            $pdo = new PDO($dsn, DB_USER, DB_PASSWORD, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
            
            // Устанавливаем кодировку UTF-8
            $pdo->exec("SET NAMES 'UTF8'");
        } catch (PDOException $e) {
            die("Ошибка подключения к базе данных: " . $e->getMessage());
        }
    }
    
    return $pdo;
}

// Инициализируем подключение
$pdo = get_db_connection();
?>

