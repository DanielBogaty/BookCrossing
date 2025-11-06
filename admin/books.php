<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/services/AuthService.php';
require_once __DIR__ . '/../app/models/BookModel.php';
require_once __DIR__ . '/../app/helpers/view_helper.php';

AuthService::requireAdmin();

$books = BookModel::getAll();

$pageContent = render('admin/books', ['books' => $books]);
$content = render('admin/layout', ['content' => $pageContent]);

$pageTitle = 'Управление книгами | BookCrossing';
include __DIR__ . '/../app/views/layouts/admin.php';
?>

