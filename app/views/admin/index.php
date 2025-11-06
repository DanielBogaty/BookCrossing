<div class="admin-page-header">
    <h1>🛠️ Панель администратора</h1>
</div>

<div class="admin-stats">
    <div class="admin-stat-card">
        <h3>👥 Пользователей</h3>
        <p class="stat-number"><?= $stats['users'] ?></p>
    </div>
    <div class="admin-stat-card">
        <h3>📚 Всего книг</h3>
        <p class="stat-number"><?= $stats['books'] ?></p>
    </div>
    <div class="admin-stat-card">
        <h3>✅ Доступных</h3>
        <p class="stat-number"><?= $stats['books_available'] ?></p>
    </div>
    <div class="admin-stat-card">
        <h3>🏷️ Жанров</h3>
        <p class="stat-number"><?= $stats['genres'] ?></p>
    </div>
    <div class="admin-stat-card">
        <h3>⭐ Отзывов</h3>
        <p class="stat-number"><?= $stats['ratings'] ?></p>
    </div>
</div>

<div class="admin-menu">
    <a href="users.php" class="admin-menu-item">
        <h3>👥 Управление пользователями</h3>
        <p>Просмотр, редактирование и удаление пользователей</p>
    </a>
    
    <a href="books.php" class="admin-menu-item">
        <h3>📚 Управление книгами</h3>
        <p>Просмотр, редактирование и модерация книг</p>
    </a>
    
    <a href="genres.php" class="admin-menu-item">
        <h3>🏷️ Управление жанрами</h3>
        <p>Добавление, редактирование и удаление жанров</p>
    </a>
</div>

