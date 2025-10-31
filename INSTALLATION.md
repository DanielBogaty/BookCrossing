# 📚 BookCrossing - Инструкция по установке

## Требования

- **PHP** 7.4 или выше
- **PostgreSQL** 12 или выше
- **Apache/Nginx** веб-сервер
- **Расширения PHP:**
  - pdo_pgsql
  - mbstring
  - gd (для работы с изображениями)
  - fileinfo

## Шаг 1: Клонирование проекта

```bash
git clone <repository-url>
cd BookCrossing
```

## Шаг 2: Настройка базы данных

### 2.1 Создание базы данных PostgreSQL

```bash
# Войдите в PostgreSQL
psql -U postgres

# Создайте базу данных
CREATE DATABASE bookcrossing;

# Создайте пользователя (опционально)
CREATE USER bookcrossing_user WITH PASSWORD 'your_password';
GRANT ALL PRIVILEGES ON DATABASE bookcrossing TO bookcrossing_user;

# Выйдите
\q
```

### 2.2 Импорт схемы базы данных

```bash
psql -U postgres -d bookcrossing -f database/init.sql
```

Или через pgAdmin:
1. Откройте pgAdmin
2. Подключитесь к серверу
3. Выберите базу данных `bookcrossing`
4. Query Tool → Откройте файл `database/init.sql`
5. Выполните запрос (F5)

## Шаг 3: Настройка конфигурации

### 3.1 Отредактируйте файл `config.php`

```php
define('DB_HOST', 'localhost');
define('DB_PORT', '5432');
define('DB_NAME', 'bookcrossing');
define('DB_USER', 'postgres');
define('DB_PASSWORD', 'ваш_пароль');
```

## Шаг 4: Настройка прав доступа

```bash
# Создайте директории для загрузок
mkdir -p uploads/books uploads/avatars

# Установите права доступа
chmod -R 755 uploads/
chown -R www-data:www-data uploads/  # Для Apache/Nginx
```

## Шаг 5: Настройка веб-сервера

### Apache

Убедитесь, что `.htaccess` включен:

```apache
<Directory /path/to/BookCrossing>
    AllowOverride All
    Require all granted
</Directory>
```

### Nginx

Создайте конфигурацию:

```nginx
server {
    listen 80;
    server_name bookcrossing.local;
    root /path/to/BookCrossing;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(ht|git) {
        deny all;
    }
}
```

## Шаг 6: Тестирование

1. Откройте браузер и перейдите на `http://localhost/BookCrossing` (или ваш домен)
2. Вы должны увидеть главную страницу с книгами

### Тестовые учётные записи

После импорта `init.sql` доступны следующие тестовые аккаунты:

**Администратор:**
- Email: `admin@bookcrossing.ru`
- Пароль: `admin123`

**Обычные пользователи:**
- Email: `anna@example.com` | Пароль: `password123`
- Email: `ivan@example.com` | Пароль: `password123`
- Email: `maria@example.com` | Пароль: `password123`

## Шаг 7: Безопасность (для продакшена)

### 7.1 Измените пароли всех тестовых аккаунтов

### 7.2 Настройте HTTPS

```bash
# Для Let's Encrypt
sudo certbot --apache -d yourdomain.com
```

### 7.3 Отключите отображение ошибок PHP

В `php.ini`:
```ini
display_errors = Off
log_errors = On
error_log = /path/to/logs/php-error.log
```

### 7.4 Используйте переменные окружения для чувствительных данных

Создайте `.env` файл:
```
DB_PASSWORD=your_secure_password
YANDEX_API_KEY=your_api_key
```

И загружайте их в `config.php`.

## Возможные проблемы

### Ошибка подключения к PostgreSQL

```
Ошибка подключения к базе данных: could not connect to server
```

**Решение:**
- Проверьте, запущен ли PostgreSQL: `sudo systemctl status postgresql`
- Проверьте настройки в `pg_hba.conf`
- Убедитесь, что порт 5432 открыт

### Ошибка загрузки изображений

```
Ошибка при загрузке файла
```

**Решение:**
- Проверьте права доступа к папке `uploads/`
- Увеличьте `upload_max_filesize` в `php.ini`
- Проверьте, что расширение `gd` установлено: `php -m | grep gd`


## Структура проекта

```
BookCrossing/
├── admin/              # Админ-панель
├── css/                # Стили
├── database/           # SQL скрипты
├── includes/           # PHP модули
│   ├── auth.php       # Авторизация
│   └── db.php         # Работа с БД
├── uploads/           # Загруженные файлы
├── config.php         # Конфигурация
├── index.php          # Главная страница
└── ...                # Остальные страницы
```

## Дополнительная информация

- **Документация PostgreSQL:** https://www.postgresql.org/docs/
- **PHP PDO:** https://www.php.net/manual/ru/book.pdo.php

## Поддержка

При возникновении проблем проверьте:
1. Логи PHP: `/var/log/php-error.log`
2. Логи PostgreSQL: `/var/log/postgresql/`
3. Логи веб-сервера: `/var/log/apache2/` или `/var/log/nginx/`

---

**Удачной работы с BookCrossing! 📚**

