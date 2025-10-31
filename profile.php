<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';

$user_id = $_GET['id'] ?? 0;
$profile_user = get_user($user_id);

if (!$profile_user) {
    die('Пользователь не найден.');
}

$user_books = get_user_books($user_id);
$ratings = get_user_ratings($user_id);
$current_user = get_current_user_data();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Профиль: <?= htmlspecialchars($profile_user['username']) ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="profile-page">
    <nav class="navbar">
        <div class="container">
            <div class="nav-brand">
                <a href="index.php"><h2>📚 BookCrossing</h2></a>
            </div>
            <div class="nav-links">
                <a href="index.php">Главная</a>
                <?php if (is_logged_in()): ?>
                    <a href="dashboard.php">Личный кабинет</a>
                    <a href="login.php?action=logout" class="btn-logout">Выход</a>
                <?php else: ?>
                    <a href="login.php">Войти</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="profile-container">
        <div class="profile-card">
            <div class="profile-header">
                <div class="profile-avatar">
                    <?php if ($profile_user['avatar']): ?>
                        <img src="<?= htmlspecialchars(UPLOAD_URL . $profile_user['avatar']) ?>" alt="Аватар" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                    <?php else: ?>
                        <div style="font-size: 3rem;">🍂</div>
                    <?php endif; ?>
                </div>
                <div class="profile-info">
                    <h1><?= htmlspecialchars($profile_user['username']) ?></h1>
                    <div class="profile-details">
                        <?php if ($profile_user['email']): ?>
                            <div class="profile-detail-item">
                                <span class="profile-detail-icon">📧</span>
                                <span class="profile-detail-text"><?= htmlspecialchars($profile_user['email']) ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if ($profile_user['city']): ?>
                            <div class="profile-detail-item">
                                <span class="profile-detail-icon">🏙️</span>
                                <span class="profile-detail-text"><?= htmlspecialchars($profile_user['city']) ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if ($profile_user['telegram_url']): ?>
                            <div class="profile-detail-item">
                                <span class="profile-detail-icon">💬</span>
                                <span class="profile-detail-text">
                                    <a href="https://t.me/<?= ltrim(htmlspecialchars($profile_user['telegram_url']), '@') ?>" target="_blank" style="color: var(--primary-color); text-decoration: none;">
                                        <?= htmlspecialchars($profile_user['telegram_url']) ?>
                                    </a>
                                </span>
                            </div>
                        <?php endif; ?>
                        <?php if ($profile_user['rating']): ?>
                            <div class="profile-detail-item">
                                <span class="profile-detail-icon">⭐</span>
                                <span class="profile-detail-text">Рейтинг: <?= number_format($profile_user['rating'], 1) ?> / 5.0 (<?= count($ratings) ?> отзывов)</span>
                            </div>
                        <?php endif; ?>
                        <div class="profile-detail-item">
                            <span class="profile-detail-icon">📅</span>
                            <span class="profile-detail-text">Участник с <?= date('d.m.Y', strtotime($profile_user['created_at'])) ?></span>
                        </div>
                    </div>
                    
                    <?php if ($current_user && $current_user['id'] != $user_id): ?>
                        <div class="profile-actions">
                            <a href="rate_user.php?id=<?= $user_id ?>" class="profile-edit-btn">
                                ⭐ Оценить пользователя
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="container">

        <hr>

        <h2>Книги пользователя (<?= count($user_books) ?>)</h2>
        
        <?php if (empty($user_books)): ?>
            <p class="no-results">У пользователя пока нет книг.</p>
        <?php else: ?>
            <div class="books-grid">
                <?php foreach ($user_books as $book): ?>
                    <?php if ($book['status'] == 'available'): // Показываем только доступные книги ?>
                        <div class="book-card">
                            <?php if ($book['image']): ?>
                                <img src="<?= htmlspecialchars(UPLOAD_URL . $book['image']) ?>" alt="<?= htmlspecialchars($book['title']) ?>">
                            <?php else: ?>
                                <div class="book-placeholder">📖</div>
                            <?php endif; ?>
                            
                            <div class="book-info">
                                <h3><?= htmlspecialchars($book['title']) ?></h3>
                                <p class="author">✍️ <?= htmlspecialchars($book['author']) ?></p>
                                
                                <?php if ($book['genres']): ?>
                                    <p class="genres">🏷️ <?= htmlspecialchars($book['genres']) ?></p>
                                <?php endif; ?>
                                
                                <p class="description"><?= htmlspecialchars($book['description']) ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <hr>

        <h2>Отзывы о пользователе (<?= count($ratings) ?>)</h2>
        
        <?php if (empty($ratings)): ?>
            <p class="no-results">Пока нет отзывов.</p>
        <?php else: ?>
            <div class="ratings-list">
                <?php foreach ($ratings as $rating): ?>
                    <div class="rating-card">
                        <div class="rating-header">
                            <strong><?= htmlspecialchars($rating['from_username']) ?></strong>
                            <span class="rating-stars">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <?= $i <= $rating['rating'] ? '⭐' : '☆' ?>
                                <?php endfor; ?>
                            </span>
                        </div>
                        <?php if ($rating['comment']): ?>
                            <p><?= htmlspecialchars($rating['comment']) ?></p>
                        <?php endif; ?>
                        <small><?= date('d.m.Y', strtotime($rating['created_at'])) ?></small>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <footer>
        <div class="container">
            <p>&copy; 2025 BookCrossing. Делитесь книгами и знаниями.</p>
        </div>
    </footer>
</body>
</html>

