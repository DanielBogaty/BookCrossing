<div class="admin-login-container">
    <div class="admin-login-card">
        <div class="admin-login-header">
            <span class="admin-icon">🔐</span>
            <h1>Админ-панель</h1>
            <p>Вход в панель управления</p>
        </div>
        
        <?php if (isset($error) && $error): ?>
            <div class="admin-error">
                <?= e($error) ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" class="admin-login-form">
            <div class="admin-form-group">
                <label for="email">Email</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    placeholder="Введите email администратора"
                    value="<?= e($email ?? '') ?>"
                    required
                    autofocus
                >
            </div>
            
            <div class="admin-form-group">
                <label for="password">Пароль</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    placeholder="Введите пароль"
                    required
                >
            </div>
            
            <button type="submit" class="admin-submit-btn">
                Войти в админ-панель
            </button>
        </form>
        
        <div class="admin-security-notice">
            <strong>🔒 Безопасность:</strong> Доступ только для администраторов системы.
        </div>
        
        <div class="admin-back-link">
            <a href="../index.php">← Вернуться на сайт</a>
        </div>
    </div>
</div>

