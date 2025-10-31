<?php
header('Content-Type: text/html; charset=UTF-8');
require_once 'includes/auth.php';
require_once 'includes/db.php';

// Получаем параметры поиска и фильтрации
$search = $_GET['search'] ?? '';
$genre_id = $_GET['genre'] ?? null;
$city = $_GET['city'] ?? '';
$status = 'available'; // Показываем только доступные книги

$books = get_all_books($status, $search, $genre_id, $city);
$genres = get_all_genres();
$current_user = get_current_user_data();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookCrossing — Делись книгами бесплатно</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="nav-brand">
                <h2>📚 BookCrossing</h2>
            </div>
            <div class="nav-links">
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
        <div class="hero">
            <h1>Делись книгами — находи новые истории</h1>
            <p>Присоединяйся к сообществу книголюбов. Освобождай прочитанные книги и находи интересное для себя.</p>
        </div>

        <div class="search-panel">
            <form method="GET" action="index.php">
                <div class="search-row">
                    <input type="text" name="search" placeholder="🔍 Поиск по названию, автору или описанию..." value="<?= htmlspecialchars($search) ?>">
                    
                    <select name="genre">
                        <option value="">Все жанры</option>
                        <?php foreach ($genres as $genre): ?>
                            <option value="<?= $genre['id'] ?>" <?= $genre_id == $genre['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($genre['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    
                    <input type="text" name="city" placeholder="Город..." value="<?= htmlspecialchars($city) ?>">
                    
                    <button type="submit">Найти</button>
                </div>
            </form>
        </div>

        <h2>Доступные книги (<?= count($books) ?>)</h2>
        
        <?php if (empty($books)): ?>
            <p class="no-results">Книги не найдены. Попробуйте изменить параметры поиска.</p>
        <?php else: ?>
            <div class="books-list">
                <?php foreach ($books as $book): ?>
                    <a href="book_detail.php?id=<?= $book['id'] ?>" class="book-item-link">
                        <div class="book-item">
                            <?php if ($book['image']): ?>
                                <img src="<?= htmlspecialchars(UPLOAD_URL . $book['image']) ?>" alt="<?= htmlspecialchars($book['title']) ?>">
                            <?php else: ?>
                                <div class="book-placeholder">📖</div>
                            <?php endif; ?>
                            
                            <div class="book-info">
                                <h3><?= htmlspecialchars($book['title']) ?></h3>
                                <p class="author"><?= htmlspecialchars($book['author']) ?></p>
                            
                            <?php if ($book['genres']): ?>
                                <p class="genres"><?= htmlspecialchars($book['genres']) ?></p>
                            <?php endif; ?>
                            
                            <p class="description"><?= htmlspecialchars(mb_substr($book['description'], 0, 200)) ?><?= mb_strlen($book['description']) > 200 ? '...' : '' ?></p>
                            
                            <div class="book-footer">
                                <div class="owner-info">
                                    <span>Владелец: <a href="profile.php?id=<?= $book['user_id'] ?>"><?= htmlspecialchars($book['username']) ?></a></span>
                                    <?php if ($book['city']): ?>
                                        <span> • <?= htmlspecialchars($book['city']) ?></span>
                                    <?php endif; ?>
                                    <?php if ($book['rating']): ?>
                                        <span> • Рейтинг: <?= number_format($book['rating'], 1) ?></span>
                                    <?php endif; ?>
                                </div>
                                
                                <?php if ($book['telegram_url']): ?>
                                    <a href="https://t.me/<?= ltrim(htmlspecialchars($book['telegram_url']), '@') ?>" target="_blank" class="btn-telegram">
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

    <footer>
        <div class="container">
            <p>&copy; 2025 BookCrossing. Делитесь книгами и знаниями.</p>
        </div>
    </footer>
</body>
</html>
