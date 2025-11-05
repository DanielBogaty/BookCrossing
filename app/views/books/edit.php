<div class="container">
    <h1>✏️ Редактировать книгу</h1>
    
    <?php if (isset($error) && $error): ?>
        <div class="alert alert-error"><?= e($error) ?></div>
    <?php endif; ?>
    
    <form method="POST" enctype="multipart/form-data" class="form">
        <div class="form-group">
            <label>Название книги *</label>
            <input type="text" name="title" required value="<?= e($book['title']) ?>">
        </div>
        
        <div class="form-group">
            <label>Автор *</label>
            <input type="text" name="author" required value="<?= e($book['author']) ?>">
        </div>
        
        <div class="form-group">
            <label>Жанры</label>
            <div class="checkbox-group">
                <?php foreach ($genres as $genre): ?>
                    <label class="checkbox-label">
                        <input type="checkbox" name="genres[]" value="<?= $genre['id'] ?>" 
                            <?= in_array($genre['id'], $selected_genres ?? []) ? 'checked' : '' ?>>
                        <?= e($genre['name']) ?>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>
        
        <div class="form-group">
            <label>Описание</label>
            <textarea name="description" rows="5"><?= e($book['description']) ?></textarea>
        </div>
        
        <div class="form-group">
            <label>Статус</label>
            <select name="status">
                <option value="available" <?= $book['status'] == 'available' ? 'selected' : '' ?>>Доступна</option>
                <option value="taken" <?= $book['status'] == 'taken' ? 'selected' : '' ?>>Взята</option>
                <option value="reserved" <?= $book['status'] == 'reserved' ? 'selected' : '' ?>>Зарезервирована</option>
            </select>
        </div>
        
        <div class="form-group">
            <label>Фотография обложки</label>
            <?php if ($book['image']): ?>
                <div class="current-image">
                    <img src="<?= e(UPLOAD_URL . $book['image']) ?>" alt="Текущая обложка" style="max-width: 200px;">
                </div>
            <?php endif; ?>
            <input type="file" name="image" accept="image/*">
            <small>Оставьте пустым, чтобы не менять изображение</small>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">💾 Сохранить изменения</button>
            <a href="/dashboard.php" class="btn">Отмена</a>
        </div>
    </form>
</div>

