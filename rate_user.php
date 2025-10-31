<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';

require_login();

$user_id = $_GET['id'] ?? 0;
$rated_user = get_user($user_id);
$current_user = get_current_user_data();

if (!$rated_user) {
    die('Пользователь не найден.');
}

if ($current_user['id'] == $user_id) {
    die('Вы не можете оценить самого себя.');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $rating = $_POST['rating'] ?? 0;
    $comment = trim($_POST['comment'] ?? '');
    
    if ($rating < 1 || $rating > 5) {
        $error = 'Выберите оценку от 1 до 5.';
    } else {
        try {
            add_rating($current_user['id'], $user_id, $rating, $comment);
            header('Location: profile.php?id=' . $user_id);
            exit();
        } catch (Exception $e) {
            $error = 'Ошибка при добавлении отзыва: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Оценить пользователя</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .star-rating {
            font-size: 2em;
            margin: 20px 0;
        }
        .star-rating input[type="radio"] {
            display: none;
        }
        .star-rating label {
            cursor: pointer;
            color: #ddd;
        }
        .star-rating label:hover,
        .star-rating label:hover ~ label,
        .star-rating input[type="radio"]:checked ~ label {
            color: #FFD700;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="nav-brand">
                <a href="index.php"><h2>📚 BookCrossing</h2></a>
            </div>
            <div class="nav-links">
                <a href="index.php">Главная</a>
                <a href="dashboard.php">Личный кабинет</a>
                <a href="login.php?action=logout" class="btn-logout">Выход</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <h1>⭐ Оценить пользователя: <?= htmlspecialchars($rated_user['username']) ?></h1>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <form method="POST" class="form">
            <div class="form-group">
                <label>Ваша оценка *</label>
                <div class="star-rating">
                    <input type="radio" name="rating" value="5" id="star5" required>
                    <label for="star5">⭐</label>
                    <input type="radio" name="rating" value="4" id="star4">
                    <label for="star4">⭐</label>
                    <input type="radio" name="rating" value="3" id="star3">
                    <label for="star3">⭐</label>
                    <input type="radio" name="rating" value="2" id="star2">
                    <label for="star2">⭐</label>
                    <input type="radio" name="rating" value="1" id="star1">
                    <label for="star1">⭐</label>
                </div>
            </div>
            
            <div class="form-group">
                <label>Комментарий (необязательно)</label>
                <textarea name="comment" rows="5" placeholder="Расскажите о вашем опыте обмена книгами с этим пользователем..."><?= htmlspecialchars($_POST['comment'] ?? '') ?></textarea>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">💾 Отправить отзыв</button>
                <a href="profile.php?id=<?= $user_id ?>" class="btn">Отмена</a>
            </div>
        </form>
    </div>

    <footer>
        <div class="container">
            <p>&copy; 2025 BookCrossing. Делитесь книгами и знаниями.</p>
        </div>
    </footer>
</body>
</html>

