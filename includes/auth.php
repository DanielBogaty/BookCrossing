<?php
session_start();

// Простая заглушка: если пользователь "залогинен", то показываем личный кабинет
function is_logged_in() {
    return isset($_SESSION['user_id']) && $_SESSION['user_id'] == 1;
}

function require_login() {
    if (!is_logged_in()) {
        header('Location: login.php');
        exit();
    }
}
?>