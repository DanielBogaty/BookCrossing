<?php
header('Content-Type: text/html; charset=UTF-8');

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../app/services/AuthService.php';
require_once __DIR__ . '/../app/models/UserModel.php';
require_once __DIR__ . '/../app/models/RatingModel.php';
require_once __DIR__ . '/../app/helpers/view_helper.php';

AuthService::requireLogin();

$user_id = $_GET['id'] ?? 0;
$rated_user = UserModel::getById($user_id);
$current_user = AuthService::getCurrentUser();

if (!$rated_user) {
    die('Пользователь не найден.');
}

if ($current_user['id'] == $user_id) {
    die('Вы не можете оценить самого себя.');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $rating = $_POST['rating'] ?? 0;
    $comment = trim($_POST['comment'] ?? '');
    
    if ($rating < 1 || $rating > 5) {
        $error = 'Выберите оценку от 1 до 5.';
    } else {
        try {
            RatingModel::add($current_user['id'], $user_id, $rating, $comment);
            header('Location: /profile.php?id=' . $user_id);
            exit();
        } catch (Exception $e) {
            $error = 'Ошибка при добавлении отзыва: ' . $e->getMessage();
        }
    }
}

// Навигация
$navbarContent = '<a href="/index.php">Главная</a>
                  <a href="/dashboard.php">Личный кабинет</a>
                  <a href="/login.php?action=logout" class="btn-logout">Выход</a>';

renderWithLayout('profile/rate_user', [
    'rated_user' => $rated_user,
    'user_id' => $user_id,
    'error' => $error,
    'navbarContent' => $navbarContent,
    'pageTitle' => 'Оценить пользователя'
]);
?>

