<div class="container">
    <?php if (isset($success) && $success): ?>
        <div class="alert alert-success">
            <?php if ($success == 'added'): ?>
                Книга успешно добавлена!
            <?php elseif ($success == 'updated'): ?>
                Книга успешно обновлена!
            <?php elseif ($success == 'deleted'): ?>
                Книга удалена!
            <?php elseif ($success == 'profile_updated'): ?>
                Профиль успешно обновлён!
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- Фоновое изображение для профиля -->
    <div style="position: relative; background: url('/background/OIP-865082702.jpg') center/cover no-repeat; border-radius: 12px; overflow: hidden; margin-bottom: 0;">
        <!-- Темный оверлей -->
        <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: linear-gradient(135deg, rgba(0, 0, 0, 0.6) 0%, rgba(0, 0, 0, 0.5) 50%, rgba(0, 0, 0, 0.6) 100%); z-index: 1;"></div>
        
        <div class="profile-header" style="position: relative; z-index: 2; padding: 2rem; color: white !important; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8); background: rgba(0, 0, 0, 0.2);">
        <div class="profile-avatar">
            <?php if ($user['avatar']): ?>
                <img src="<?= e(UPLOAD_URL . $user['avatar']) ?>" alt="Аватар">
            <?php else: ?>
                <div class="avatar-placeholder">🍂</div>
            <?php endif; ?>
        </div>
        <div class="profile-info" style="color: white !important;">
            <h1 style="color: white !important;"><?= e($user['username']) ?></h1>
            <p style="color: white !important;">📧 <?= e($user['email']) ?></p>
            <?php if ($user['city']): ?>
                <p style="color: white !important;">🏙️ <?= e($user['city']) ?></p>
            <?php endif; ?>
            <?php if ($user['telegram_url']): ?>
                <p style="color: white !important;">💬 <a href="https://t.me/<?= ltrim(e($user['telegram_url']), '@') ?>" target="_blank" style="color: white !important; text-decoration: underline;"><?= e($user['telegram_url']) ?></a></p>
            <?php endif; ?>
            <?php if ($user['rating']): ?>
                <p style="color: white !important;">⭐ Рейтинг: <?= number_format($user['rating'], 1) ?> / 5.0</p>
            <?php endif; ?>
            <a href="/edit_profile.php" style="color: white !important; background: rgba(255, 255, 255, 0.2) !important; border: 1px solid rgba(255, 255, 255, 0.3) !important; margin-top: 1rem; display: inline-block; padding: 0.8rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: bold; box-shadow: none !important;">✏️ Редактировать профиль</a>
        </div>
        </div>
    </div>

    <h2 style="margin-top: 2rem;">Ваши книги (<?= count($my_books) ?>)</h2>
    
    <?php if (empty($my_books)): ?>
        <div class="empty-state">
            <p>У вас пока нет добавленных книг.</p>
            <a href="/add_book.php" class="btn btn-primary">➕ Добавить первую книгу</a>
        </div>
    <?php else: ?>
        <div class="books-grid">
            <?php foreach ($my_books as $book): ?>
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
                        
                        <p class="status">
                            <strong>Статус:</strong>
                            <span class="badge badge-<?= $book['status'] ?>">
                                <?php
                                $statuses = [
                                    'available' => 'Доступна',
                                    'taken' => 'Взята',
                                    'reserved' => 'Зарезервирована'
                                ];
                                echo $statuses[$book['status']] ?? $book['status'];
                                ?>
                            </span>
                        </p>
                        
                        <div class="book-actions">
                            <a href="/edit_book.php?id=<?= $book['id'] ?>" class="btn">✏️ Редактировать</a>
                            <a href="/delete_book.php?id=<?= $book['id'] ?>" class="btn btn-danger" onclick="return confirm('Удалить эту книгу?')">🗑️ Удалить</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

