<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h1>🔐 Вход</h1>
            <p>Добро пожаловать обратно в BookCrossing</p>
        </div>
        
        <?php if (isset($error) && $error): ?>
            <div class="alert alert-error"><?= e($error) ?></div>
        <?php endif; ?>
        
        <form method="POST" class="auth-form">
            <div class="auth-form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required 
                       value="<?= e($_POST['email'] ?? '') ?>"
                       placeholder="Введите ваш email">
            </div>
            
            <div class="auth-form-group">
                <label for="password">Пароль</label>
                <input type="password" id="password" name="password" required 
                       placeholder="Введите ваш пароль">
            </div>
            
            <button type="submit" class="auth-submit">Войти</button>
        </form>
        
        <div class="auth-links">
            <p><a href="/register.php">Нет аккаунта? Зарегистрируйтесь</a></p>
        </div>
        
        <div class="auth-test-info">
            <small>Для теста: <code>admin@bookcrossing.ru</code> / <code>admin123</code></small>
        </div>
    </div>
</div>

