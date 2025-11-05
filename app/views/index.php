<div class="container">
<div class="hero">
    <h1>Делись книгами — находи новые истории</h1>
    <p>Присоединяйся к сообществу книголюбов. Освобождай прочитанные книги и находи интересное для себя.</p>
</div>

<div class="search-panel">
    <form method="GET" action="/index.php">
        <div class="search-row">
            <input type="text" name="search" placeholder="🔍 Поиск по названию, автору или описанию..." value="<?= e($search ?? '') ?>">
            
            <select name="genre">
                <option value="">Все жанры</option>
                <?php foreach ($genres as $genre): ?>
                    <option value="<?= $genre['id'] ?>" <?= ($genre_id ?? null) == $genre['id'] ? 'selected' : '' ?>>
                        <?= e($genre['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            
            <input type="text" name="city" placeholder="Город..." value="<?= e($city ?? '') ?>">
            
            <button type="submit">Найти</button>
        </div>
    </form>
</div>

<h2>Доступные книги (<?= count($books ?? []) ?>)</h2>

<?php if (empty($books)): ?>
    <p class="no-results">Книги не найдены. Попробуйте изменить параметры поиска.</p>
<?php else: ?>
    <div class="books-list">
        <?php foreach ($books as $book): ?>
            <a href="/book_detail.php?id=<?= $book['id'] ?>" class="book-item-link">
                <div class="book-item">
                    <?php if ($book['image']): ?>
                        <img src="<?= e(UPLOAD_URL . $book['image']) ?>" alt="<?= e($book['title']) ?>">
                    <?php else: ?>
                        <div class="book-placeholder">📖</div>
                    <?php endif; ?>
                    
                    <div class="book-info">
                        <h3><?= e($book['title']) ?></h3>
                        <p class="author"><?= e($book['author']) ?></p>
                    
                    <?php if ($book['genres']): ?>
                        <p class="genres"><?= e($book['genres']) ?></p>
                    <?php endif; ?>
                    
                    <p class="description"><?= e(safe_substr($book['description'] ?? '', 0, 200)) ?><?= safe_strlen($book['description'] ?? '') > 200 ? '...' : '' ?></p>
                    
                    <div class="book-footer">
                        <div class="owner-info">
                            <span>Владелец: <a href="/profile.php?id=<?= $book['user_id'] ?>"><?= e($book['username']) ?></a></span>
                            <?php if ($book['city']): ?>
                                <span> • <?= e($book['city']) ?></span>
                            <?php endif; ?>
                            <?php if ($book['rating']): ?>
                                <span> • Рейтинг: <?= number_format($book['rating'], 1) ?></span>
                            <?php endif; ?>
                        </div>
                        
                        <?php if ($book['telegram_url']): ?>
                            <a href="https://t.me/<?= ltrim(e($book['telegram_url']), '@') ?>" target="_blank" class="btn-telegram">
                                Написать в Telegram
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
</div>

