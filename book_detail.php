<?php
header('Content-Type: text/html; charset=UTF-8');
require_once 'includes/auth.php';
require_once 'includes/db.php';

$book_id = $_GET['id'] ?? 0;
$book = get_book($book_id);

if (!$book) {
    die('Книга не найдена.');
}

$current_user = get_current_user_data();
$owner_ratings = get_user_ratings($book['user_id']);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($book['title']) ?> — BookCrossing</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="nav-brand">
                <h2>📚 BookCrossing</h2>
            </div>
            <div class="nav-links">
                <a href="index.php">← Назад к каталогу</a>
                <?php if (is_logged_in()): ?>
                    <a href="dashboard.php">Личный кабинет</a>
                    <a href="add_book.php" class="btn-primary">➕ Добавить книгу</a>
                    <a href="login.php?action=logout" class="btn-logout">Выход</a>
                <?php else: ?>
                    <a href="login.php">Войти</a>
                    <a href="register.php" class="btn-primary">Регистрация</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="book-detail">
            <div class="book-detail-main">
                <div class="book-image">
                    <?php if ($book['image']): ?>
                        <img src="<?= htmlspecialchars(UPLOAD_URL . $book['image']) ?>" alt="<?= htmlspecialchars($book['title']) ?>">
                    <?php else: ?>
                        <div class="book-placeholder-large">📖</div>
                    <?php endif; ?>
                </div>
                
                <div class="book-details">
                    <h1><?= htmlspecialchars($book['title']) ?></h1>
                    <p class="book-author">✍️ <?= htmlspecialchars($book['author']) ?></p>
                    
                    <?php if ($book['genres']): ?>
                        <div class="book-genres">
                            <strong>🏷️ Жанры:</strong>
                            <?php 
                            $genres = explode(', ', $book['genres']);
                            foreach ($genres as $genre): 
                            ?>
                                <span class="genre-tag"><?= htmlspecialchars(trim($genre)) ?></span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="book-status">
                        <strong>📊 Статус:</strong>
                        <span class="status-<?= $book['status'] ?>">
                            <?php
                            switch($book['status']) {
                                case 'available': echo 'Доступна'; break;
                                case 'taken': echo 'Взята'; break;
                                case 'reserved': echo 'Зарезервирована'; break;
                                default: echo $book['status'];
                            }
                            ?>
                        </span>
                    </div>
                    
                    <?php if ($book['description']): ?>
                        <div class="book-description">
                            <h3>📝 Описание</h3>
                            <p><?= nl2br(htmlspecialchars($book['description'])) ?></p>
                        </div>
                    <?php endif; ?>
                    
                    <div class="book-date">
                        <small>📅 Добавлена: <?= date('d.m.Y', strtotime($book['created_at'])) ?></small>
                    </div>
                </div>
            </div>
            
            <div class="owner-section">
                <h2>🍂 Владелец книги</h2>
                <div class="owner-card">
                    <div class="owner-info">
                        <h3><?= htmlspecialchars($book['username']) ?></h3>
                        
                        <?php if ($book['city']): ?>
                            <p>🏙️ <strong>Город:</strong> <?= htmlspecialchars($book['city']) ?></p>
                        <?php endif; ?>
                        
                        <?php if ($book['rating']): ?>
                            <p>⭐ <strong>Рейтинг:</strong> <?= number_format($book['rating'], 1) ?>/5.0</p>
                        <?php endif; ?>
                        
                        <?php if ($book['telegram_url']): ?>
                            <div class="contact-info">
                                <a href="https://t.me/<?= ltrim(htmlspecialchars($book['telegram_url']), '@') ?>" 
                                   target="_blank" class="btn-telegram">
                                    💬 Написать в Telegram
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <?php if (is_logged_in() && $current_user && $current_user['id'] != $book['user_id']): ?>
                        <div class="owner-actions">
                            <a href="rate_user.php?id=<?= $book['user_id'] ?>" class="btn-rate">
                                ⭐ Оценить владельца
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
                
                <?php if (!empty($owner_ratings)): ?>
                    <div class="owner-reviews">
                        <h3>📝 Отзывы о владельце</h3>
                        <div class="reviews-list">
                            <?php foreach ($owner_ratings as $rating): ?>
                                <div class="review-item">
                                    <div class="review-header">
                                        <strong><?= htmlspecialchars($rating['from_username']) ?></strong>
                                        <span class="review-rating">⭐ <?= $rating['rating'] ?>/5</span>
                                        <small><?= date('d.m.Y', strtotime($rating['created_at'])) ?></small>
                                    </div>
                                    <?php if ($rating['comment']): ?>
                                        <p class="review-comment"><?= htmlspecialchars($rating['comment']) ?></p>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <footer>
        <div class="container">
            <p>&copy; 2025 BookCrossing. Делитесь книгами и знаниями.</p>
        </div>
    </footer>
</body>
</html>
