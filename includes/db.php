<?php
require_once __DIR__ . '/../config.php';

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

/**
 * Получить все книги с информацией о пользователях
 */
function get_all_books($status = null, $search = null, $genre_id = null, $city = null) {
    global $pdo;
    
    $sql = "SELECT b.*, u.username, u.telegram_url, u.city, u.rating,
                   STRING_AGG(g.name, ', ') as genres
            FROM books b
            JOIN users u ON b.user_id = u.id
            LEFT JOIN book_genre bg ON b.id = bg.book_id
            LEFT JOIN genres g ON bg.genre_id = g.id
            WHERE 1=1";
    
    $params = [];
    
    if ($status) {
        $sql .= " AND b.status = :status";
        $params['status'] = $status;
    }
    
    if ($search) {
        $sql .= " AND (b.title ILIKE :search OR b.author ILIKE :search OR b.description ILIKE :search)";
        $params['search'] = '%' . $search . '%';
    }
    
    if ($genre_id) {
        $sql .= " AND bg.genre_id = :genre_id";
        $params['genre_id'] = $genre_id;
    }
    
    if ($city) {
        $sql .= " AND u.city ILIKE :city";
        $params['city'] = '%' . $city . '%';
    }
    
    $sql .= " GROUP BY b.id, u.id ORDER BY b.created_at DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

/**
 * Получить книги конкретного пользователя
 */
function get_user_books($user_id) {
    global $pdo;
    
    $stmt = $pdo->prepare("
        SELECT b.*, 
               STRING_AGG(g.name, ', ') as genres
        FROM books b
        LEFT JOIN book_genre bg ON b.id = bg.book_id
        LEFT JOIN genres g ON bg.genre_id = g.id
        WHERE b.user_id = :user_id
        GROUP BY b.id
        ORDER BY b.created_at DESC
    ");
    $stmt->execute(['user_id' => $user_id]);
    return $stmt->fetchAll();
}

/**
 * Получить информацию о книге
 */
function get_book($book_id) {
    global $pdo;
    
    $stmt = $pdo->prepare("
        SELECT b.*, u.username, u.telegram_url, u.city, u.rating,
               STRING_AGG(g.name, ', ') as genres,
               STRING_AGG(CAST(g.id AS TEXT), ',') as genre_ids
        FROM books b
        JOIN users u ON b.user_id = u.id
        LEFT JOIN book_genre bg ON b.id = bg.book_id
        LEFT JOIN genres g ON bg.genre_id = g.id
        WHERE b.id = :id
        GROUP BY b.id, u.id
    ");
    $stmt->execute(['id' => $book_id]);
    return $stmt->fetch();
}

/**
 * Получить информацию о пользователе
 */
function get_user($user_id) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->execute(['id' => $user_id]);
    return $stmt->fetch();
}

/**
 * Получить пользователя по email
 */
function get_user_by_email($email) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    return $stmt->fetch();
}

/**
 * Создать нового пользователя
 */
function create_user($email, $password, $username, $telegram_url, $city = null) {
    global $pdo;
    
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
function update_user_profile($user_id, $username, $city, $telegram_url, $avatar = null) {
    global $pdo;
    
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
 * Добавить книгу
 */
function create_book($user_id, $title, $author, $description, $image = null, $genre_ids = []) {
    global $pdo;
    
    $stmt = $pdo->prepare("
        INSERT INTO books (user_id, title, author, description, image)
        VALUES (:user_id, :title, :author, :description, :image)
        RETURNING id
    ");
    
    $stmt->execute([
        'user_id' => $user_id,
        'title' => $title,
        'author' => $author,
        'description' => $description,
        'image' => $image
    ]);
    
    $book_id = $stmt->fetch()['id'];
    
    // Добавляем жанры
    if (!empty($genre_ids)) {
        $stmt = $pdo->prepare("INSERT INTO book_genre (book_id, genre_id) VALUES (:book_id, :genre_id)");
        foreach ($genre_ids as $genre_id) {
            $stmt->execute(['book_id' => $book_id, 'genre_id' => $genre_id]);
        }
    }
    
    return $book_id;
}

/**
 * Обновить книгу
 */
function update_book($book_id, $title, $author, $description, $status, $image = null, $genre_ids = []) {
    global $pdo;
    
    if ($image) {
        $stmt = $pdo->prepare("
            UPDATE books 
            SET title = :title, author = :author, description = :description, status = :status, image = :image
            WHERE id = :id
        ");
        $stmt->execute([
            'title' => $title,
            'author' => $author,
            'description' => $description,
            'status' => $status,
            'image' => $image,
            'id' => $book_id
        ]);
    } else {
        $stmt = $pdo->prepare("
            UPDATE books 
            SET title = :title, author = :author, description = :description, status = :status
            WHERE id = :id
        ");
        $stmt->execute([
            'title' => $title,
            'author' => $author,
            'description' => $description,
            'status' => $status,
            'id' => $book_id
        ]);
    }
    
    // Обновляем жанры
    $pdo->prepare("DELETE FROM book_genre WHERE book_id = :book_id")->execute(['book_id' => $book_id]);
    
    if (!empty($genre_ids)) {
        $stmt = $pdo->prepare("INSERT INTO book_genre (book_id, genre_id) VALUES (:book_id, :genre_id)");
        foreach ($genre_ids as $genre_id) {
            $stmt->execute(['book_id' => $book_id, 'genre_id' => $genre_id]);
        }
    }
}

/**
 * Удалить книгу
 */
function delete_book($book_id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM books WHERE id = :id");
    $stmt->execute(['id' => $book_id]);
}

// Функции для работы с местоположением удалены
// (карты больше не используются)

/**
 * Получить все жанры
 */
function get_all_genres() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM genres ORDER BY name");
    return $stmt->fetchAll();
}

/**
 * Добавить рейтинг пользователю
 */
function add_rating($from_user_id, $to_user_id, $rating, $comment = null) {
    global $pdo;
    
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
    update_user_rating($to_user_id);
    
    return true;
}

/**
 * Пересчитать средний рейтинг пользователя
 */
function update_user_rating($user_id) {
    global $pdo;
    
    $stmt = $pdo->prepare("
        UPDATE users 
        SET rating = (SELECT AVG(rating) FROM ratings WHERE to_user_id = :user_id)
        WHERE id = :user_id
    ");
    $stmt->execute(['user_id' => $user_id]);
}

/**
 * Получить отзывы о пользователе
 */
function get_user_ratings($user_id) {
    global $pdo;
    
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
?>
