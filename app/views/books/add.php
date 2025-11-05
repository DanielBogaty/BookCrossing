<div class="container">
    <h1>➕ Добавить книгу</h1>
    
    <?php if (isset($error) && $error): ?>
        <div class="alert alert-error"><?= e($error) ?></div>
    <?php endif; ?>
    
    <form method="POST" enctype="multipart/form-data" class="form">
        <div class="form-group">
            <label>Название книги *</label>
            <input type="text" name="title" required value="<?= e($_POST['title'] ?? '') ?>" placeholder="Например: 1984">
        </div>
        
        <div class="form-group">
            <label>Автор *</label>
            <input type="text" name="author" required value="<?= e($_POST['author'] ?? '') ?>" placeholder="Например: Джордж Оруэлл">
        </div>
        
        <div class="form-group">
            <label>Жанры</label>
            <div class="checkbox-group">
                <?php foreach ($genres as $genre): ?>
                    <label class="checkbox-label">
                        <input type="checkbox" name="genres[]" value="<?= $genre['id'] ?>" 
                            <?= in_array($genre['id'], $_POST['genres'] ?? []) ? 'checked' : '' ?>>
                        <?= e($genre['name']) ?>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>
        
        <div class="form-group">
            <label>Описание</label>
            <textarea name="description" rows="5" placeholder="Кратко опишите книгу..."><?= e($_POST['description'] ?? '') ?></textarea>
        </div>
        
        <div class="form-group">
            <label>Фотография обложки</label>
            <input type="file" name="image" accept="image/*">
            <small>Максимальный размер: <?= MAX_FILE_SIZE / 1024 / 1024 ?> MB</small>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">💾 Сохранить книгу</button>
            <a href="/dashboard.php" class="btn">Отмена</a>
        </div>
    </form>
</div>

