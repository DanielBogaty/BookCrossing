<div class="container">
    <h1>⭐ Оценить пользователя: <?= e($rated_user['username']) ?></h1>
    
    <?php if (isset($error) && $error): ?>
        <div class="alert alert-error"><?= e($error) ?></div>
    <?php endif; ?>
    
    <form method="POST" class="form">
        <div class="form-group">
            <label>Ваша оценка *</label>
            <div class="star-rating">
                <input type="radio" name="rating" value="5" id="star5" required>
                <label for="star5">⭐</label>
                <input type="radio" name="rating" value="4" id="star4">
                <label for="star4">⭐</label>
                <input type="radio" name="rating" value="3" id="star3">
                <label for="star3">⭐</label>
                <input type="radio" name="rating" value="2" id="star2">
                <label for="star2">⭐</label>
                <input type="radio" name="rating" value="1" id="star1">
                <label for="star1">⭐</label>
            </div>
        </div>
        
        <div class="form-group">
            <label>Комментарий (необязательно)</label>
            <textarea name="comment" rows="5" placeholder="Расскажите о вашем опыте обмена книгами с этим пользователем..."><?= e($_POST['comment'] ?? '') ?></textarea>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">💾 Отправить отзыв</button>
            <a href="/profile.php?id=<?= $user_id ?>" class="btn">Отмена</a>
        </div>
    </form>
</div>

<style>
    .star-rating {
        font-size: 2em;
        margin: 20px 0;
    }
    .star-rating input[type="radio"] {
        display: none;
    }
    .star-rating label {
        cursor: pointer;
        color: #ddd;
    }
    .star-rating label:hover,
    .star-rating label:hover ~ label,
    .star-rating input[type="radio"]:checked ~ label {
        color: #FFD700;
    }
</style>

