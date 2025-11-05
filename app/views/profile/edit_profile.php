<div class="container">
    <h1>✏️ Редактировать профиль</h1>
    
    <?php if (isset($error) && $error): ?>
        <div class="alert alert-error"><?= e($error) ?></div>
    <?php endif; ?>
    
    <form method="POST" enctype="multipart/form-data" class="form">
        <div class="form-group">
            <label>Имя пользователя *</label>
            <input type="text" name="username" required value="<?= e($user['username']) ?>">
        </div>
        
        <div class="form-group">
            <label>Email (не изменяется)</label>
            <input type="email" value="<?= e($user['email']) ?>" disabled>
        </div>
        
        <div class="form-group">
            <label>Город</label>
            <input type="text" name="city" placeholder="Например: Москва" value="<?= e($user['city'] ?? '') ?>">
        </div>
        
        <div class="form-group">
            <label>Telegram (@никнейм) *</label>
            <input type="text" name="telegram_url" placeholder="@ваш_ник" required value="<?= e($user['telegram_url']) ?>">
        </div>
        
        <div class="form-group">
            <label>Аватар</label>
            <?php if ($user['avatar']): ?>
                <div class="current-avatar">
                    <img src="<?= e(UPLOAD_URL . $user['avatar']) ?>" alt="Текущий аватар" style="max-width: 150px; border-radius: 50%;">
                </div>
            <?php endif; ?>
            <input type="file" name="avatar" accept="image/*">
            <small>Максимальный размер: <?= MAX_FILE_SIZE / 1024 / 1024 ?> MB</small>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">💾 Сохранить</button>
            <a href="/dashboard.php" class="btn">Отмена</a>
        </div>
    </form>
</div>

