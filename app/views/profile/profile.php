<div class="profile-container">
    <div class="profile-card">
        <div class="profile-header">
            <div class="profile-avatar">
                <?php if ($profile_user['avatar']): ?>
                    <img src="<?= e(UPLOAD_URL . $profile_user['avatar']) ?>" alt="Аватар" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                <?php else: ?>
                    <div style="font-size: 3rem;">🍂</div>
                <?php endif; ?>
            </div>
            <div class="profile-info">
                <h1><?= e($profile_user['username']) ?></h1>
                <div class="profile-details">
                    <?php if ($profile_user['email']): ?>
                        <div class="profile-detail-item">
                            <span class="profile-detail-icon">📧</span>
                            <span class="profile-detail-text"><?= e($profile_user['email']) ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if ($profile_user['city']): ?>
                        <div class="profile-detail-item">
                            <span class="profile-detail-icon">🏙️</span>
                            <span class="profile-detail-text"><?= e($profile_user['city']) ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if ($profile_user['telegram_url']): ?>
                        <div class="profile-detail-item">
                            <span class="profile-detail-icon">💬</span>
                            <span class="profile-detail-text">
                                <a href="https://t.me/<?= ltrim(e($profile_user['telegram_url']), '@') ?>" target="_blank" style="color: var(--primary-color); text-decoration: none;">
                                    <?= e($profile_user['telegram_url']) ?>
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
                
                <?php if (isset($current_user) && $current_user && $current_user['id'] != $user_id): ?>
                    <div class="profile-actions">
                        <a href="/rate_user.php?id=<?= $user_id ?>" class="profile-edit-btn">
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
                <?php if ($book['status'] == 'available'): ?>
                    <div class="book-card">
                        <?php if ($book['image']): ?>
                            <img src="<?= e(UPLOAD_URL . $book['image']) ?>" alt="<?= e($book['title']) ?>">
                        <?php else: ?>
                            <div class="book-placeholder">📖</div>
                        <?php endif; ?>
                        
                        <div class="book-info">
                            <h3><?= e($book['title']) ?></h3>
                            <p class="author">✍️ <?= e($book['author']) ?></p>
                            
                            <?php if ($book['genres']): ?>
                                <p class="genres">🏷️ <?= e($book['genres']) ?></p>
                            <?php endif; ?>
                            
                            <p class="description"><?= e($book['description']) ?></p>
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
                        <strong><?= e($rating['from_username']) ?></strong>
                        <span class="rating-stars">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <?= $i <= $rating['rating'] ? '⭐' : '☆' ?>
                            <?php endfor; ?>
                        </span>
                    </div>
                    <?php if ($rating['comment']): ?>
                        <p><?= e($rating['comment']) ?></p>
                    <?php endif; ?>
                    <small><?= date('d.m.Y', strtotime($rating['created_at'])) ?></small>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

