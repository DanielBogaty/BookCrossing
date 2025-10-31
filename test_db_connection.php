<?php
echo "<h2>Database Connection Test</h2>";

// Проверяем конфигурацию
require_once 'config.php';
echo "<p><strong>DB_HOST:</strong> " . DB_HOST . "</p>";
echo "<p><strong>DB_PORT:</strong> " . DB_PORT . "</p>";
echo "<p><strong>DB_NAME:</strong> " . DB_NAME . "</p>";
echo "<p><strong>DB_USER:</strong> " . DB_USER . "</p>";

// Проверяем доступные драйверы
echo "<p><strong>Available PDO Drivers:</strong> " . implode(', ', PDO::getAvailableDrivers()) . "</p>";

if (!in_array('pgsql', PDO::getAvailableDrivers())) {
    echo "<p style='color: red;'>❌ pdo_pgsql driver is NOT available</p>";
    exit;
}

// Пробуем подключиться
try {
    $dsn = "pgsql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME;
    echo "<p><strong>DSN:</strong> " . $dsn . "</p>";
    
    $pdo = new PDO($dsn, DB_USER, DB_PASSWORD, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    
    echo "<p style='color: green;'>✅ Database connection successful!</p>";
    
    // Тестируем запрос
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
    $result = $stmt->fetch();
    echo "<p>Users in database: " . $result['count'] . "</p>";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Database connection failed: " . $e->getMessage() . "</p>";
}
?>
