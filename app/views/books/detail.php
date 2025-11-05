<div class="container">
    <div class="book-detail">
        <div class="book-detail-main">
            <div class="book-image">
                <?php if ($book['image']): ?>
                    <img src="<?= e(UPLOAD_URL . $book['image']) ?>" alt="<?= e($book['title']) ?>">
                <?php else: ?>
                    <div class="book-placeholder-large">📖</div>
                <?php endif; ?>
            </div>
            
            <div class="book-details">
                <h1><?= e($book['title']) ?></h1>
                <p class="book-author">✍️ <?= e($book['author']) ?></p>
                
                <?php if ($book['genres']): ?>
                    <div class="book-genres">
                        <strong>🏷️ Жанры:</strong>
                        <?php 
                        $genres = explode(', ', $book['genres']);
                        foreach ($genres as $genre): 
                        ?>
                            <span class="genre-tag"><?= e(trim($genre)) ?></span>
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
                            default: echo e($book['status']);
                        }
                        ?>
                    </span>
                </div>
                
                <?php if ($book['description']): ?>
                    <div class="book-description">
                        <h3>📝 Описание</h3>
                        <p><?= nl2br(e($book['description'])) ?></p>
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
                    <h3><?= e($book['username']) ?></h3>
                    
                    <?php if ($book['city']): ?>
                        <p>🏙️ <strong>Город:</strong> <?= e($book['city']) ?></p>
                    <?php endif; ?>
                    
                    <?php if ($book['rating']): ?>
                        <p>⭐ <strong>Рейтинг:</strong> <?= number_format($book['rating'], 1) ?>/5.0</p>
                    <?php endif; ?>
                    
                    <?php if ($book['telegram_url']): ?>
                        <div class="contact-info">
                            <a href="https://t.me/<?= ltrim(e($book['telegram_url']), '@') ?>" 
                               target="_blank" class="btn-telegram">
                                💬 Написать в Telegram
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
                
                <?php if (isset($current_user) && $current_user && $current_user['id'] != $book['user_id']): ?>
                    <div class="owner-actions">
                        <a href="/rate_user.php?id=<?= $book['user_id'] ?>" class="btn-rate">
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
                                    <strong><?= e($rating['from_username']) ?></strong>
                                    <span class="review-rating">⭐ <?= $rating['rating'] ?>/5</span>
                                    <small><?= date('d.m.Y', strtotime($rating['created_at'])) ?></small>
                                </div>
                                <?php if ($rating['comment']): ?>
                                    <p class="review-comment"><?= e($rating['comment']) ?></p>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

