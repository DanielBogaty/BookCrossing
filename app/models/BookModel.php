<?php
require_once __DIR__ . '/../../config/database.php';

/**
 * Модель для работы с книгами
 */
class BookModel {
    
    /**
     * Получить все книги с информацией о пользователях
     */
    public static function getAll($status = null, $search = null, $genre_id = null, $city = null) {
        $pdo = get_db_connection();
        
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
    public static function getByUserId($user_id) {
        $pdo = get_db_connection();
        
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
    public static function getById($book_id) {
        $pdo = get_db_connection();
        
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
     * Создать книгу
     */
    public static function create($user_id, $title, $author, $description, $image = null, $genre_ids = []) {
        $pdo = get_db_connection();
        
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
    public static function update($book_id, $title, $author, $description, $status, $image = null, $genre_ids = []) {
        $pdo = get_db_connection();
        
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
    public static function delete($book_id) {
        $pdo = get_db_connection();
        $stmt = $pdo->prepare("DELETE FROM books WHERE id = :id");
        $stmt->execute(['id' => $book_id]);
    }
}
?>

