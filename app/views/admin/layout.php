<nav class="admin-navbar">
    <div class="container">
        <div class="admin-nav-brand">
            <a href="index.php"><h2>📚 BookCrossing - Админ</h2></a>
        </div>
        <div class="admin-nav-links">
            <a href="index.php">Админ-панель</a>
            <a href="../index.php">На сайт</a>
            <a href="logout.php" class="btn-logout">Выход</a>
        </div>
    </div>
</nav>

<div class="admin-container">
    <?php if (isset($error) && $error): ?>
        <div class="admin-alert admin-alert-error"><?= e($error) ?></div>
    <?php endif; ?>
    
    <?php if (isset($success) && $success): ?>
        <div class="admin-alert admin-alert-success"><?= e($success) ?></div>
    <?php endif; ?>
    
    <?= $content ?? '' ?>
</div>

<footer>
    <div class="container">
        <p>&copy; 2025 BookCrossing. Делитесь книгами и знаниями.</p>
    </div>
</footer>

