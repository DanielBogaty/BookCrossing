<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'BookCrossing' ?></title>
    <link rel="stylesheet" href="/css/style.css">
    <?php if (isset($additionalStyles)): ?>
        <?php foreach ($additionalStyles as $style): ?>
            <link rel="stylesheet" href="<?= $style ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="nav-brand">
                <a href="/index.php"><h2>📚 BookCrossing</h2></a>
            </div>
            <div class="nav-links">
                <?= $navbarContent ?? '' ?>
            </div>
        </div>
    </nav>

    <?php if (isset($successMessage)): ?>
        <div class="container">
            <div class="alert alert-success"><?= htmlspecialchars($successMessage) ?></div>
        </div>
    <?php endif; ?>
    
    <?php if (isset($errorMessage)): ?>
        <div class="container">
            <div class="alert alert-error"><?= htmlspecialchars($errorMessage) ?></div>
        </div>
    <?php endif; ?>
    
    <?= $content ?? '' ?>

    <footer>
        <div class="container">
            <p>&copy; 2025 BookCrossing. Делитесь книгами и знаниями.</p>
        </div>
    </footer>
</body>
</html>

