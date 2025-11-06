<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/services/AuthService.php';
require_once __DIR__ . '/../app/helpers/view_helper.php';

AuthService::requireAdmin();

// Получаем статистику
$pdo = get_db_connection();

$stats = [];
$stats['users'] = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$stats['books'] = $pdo->query("SELECT COUNT(*) FROM books")->fetchColumn();
$stats['books_available'] = $pdo->query("SELECT COUNT(*) FROM books WHERE status = 'available'")->fetchColumn();
$stats['genres'] = $pdo->query("SELECT COUNT(*) FROM genres")->fetchColumn();
$stats['ratings'] = $pdo->query("SELECT COUNT(*) FROM ratings")->fetchColumn();

$pageContent = render('admin/index', ['stats' => $stats]);
$content = render('admin/layout', ['content' => $pageContent]);

$pageTitle = 'Админ-панель | BookCrossing';
include __DIR__ . '/../app/views/layouts/admin.php';
?>

