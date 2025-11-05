<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h1>📝 Регистрация</h1>
            <p>Присоединяйтесь к сообществу BookCrossing</p>
        </div>
        
        <?php if (isset($error) && $error): ?>
            <div class="alert alert-error"><?= e($error) ?></div>
        <?php endif; ?>
        
        <?php if (isset($success) && $success): ?>
            <div class="alert alert-success"><?= e($success) ?></div>
        <?php endif; ?>
    
        <form method="POST" class="auth-form">
            <div class="auth-form-group">
                <label for="username">Имя пользователя</label>
                <input type="text" id="username" name="username" required 
                       value="<?= e($_POST['username'] ?? '') ?>"
                       placeholder="Введите ваше имя">
            </div>
            
            <div class="auth-form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required 
                       value="<?= e($_POST['email'] ?? '') ?>"
                       placeholder="Введите ваш email">
            </div>
            
            <div class="auth-form-group">
                <label for="password">Пароль</label>
                <input type="password" id="password" name="password" required minlength="6"
                       placeholder="Минимум 6 символов">
            </div>
            
            <div class="auth-form-group">
                <label for="password_confirm">Подтверждение пароля</label>
                <input type="password" id="password_confirm" name="password_confirm" required minlength="6"
                       placeholder="Повторите пароль">
            </div>
            
            <div class="auth-form-group">
                <label for="telegram_url">Telegram</label>
                <input type="text" id="telegram_url" name="telegram_url" required 
                       value="<?= e($_POST['telegram_url'] ?? '') ?>"
                       placeholder="@ваш_никнейм">
            </div>
            
            <div class="auth-form-group">
                <label for="city">Город</label>
                <input type="text" id="city" name="city" 
                       value="<?= e($_POST['city'] ?? '') ?>"
                       placeholder="Например: Москва">
            </div>
            
            <button type="submit" class="auth-submit">Зарегистрироваться</button>
        </form>
        
        <div class="auth-links">
            <p><a href="/login.php">Уже есть аккаунт? Войдите</a></p>
        </div>
    </div>
</div>

