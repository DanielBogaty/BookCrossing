<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/services/AuthService.php';
require_once __DIR__ . '/../app/helpers/view_helper.php';

AuthService::requireAdmin();

$pdo = get_db_connection();

// Получаем всех пользователей
$users = $pdo->query("SELECT * FROM users ORDER BY created_at DESC")->fetchAll();

$pageContent = render('admin/users', ['users' => $users]);
$content = render('admin/layout', ['content' => $pageContent]);

$pageTitle = 'Управление пользователями | BookCrossing';
include __DIR__ . '/../app/views/layouts/admin.php';
?>

