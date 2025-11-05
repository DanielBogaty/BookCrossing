-- BookCrossing Database Schema for PostgreSQL (без карт)

-- Удаляем существующие таблицы если они есть
DROP TABLE IF EXISTS book_genre CASCADE;
DROP TABLE IF EXISTS ratings CASCADE;
DROP TABLE IF EXISTS books CASCADE;
DROP TABLE IF EXISTS genres CASCADE;
DROP TABLE IF EXISTS users CASCADE;

-- Таблица пользователей
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    username VARCHAR(100) NOT NULL,
    avatar VARCHAR(255) DEFAULT NULL,
    city VARCHAR(100) DEFAULT NULL,
    telegram_url VARCHAR(100) DEFAULT NULL,
    rating DECIMAL(3,2) DEFAULT 0.00,
    is_admin BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Таблица жанров
CREATE TABLE genres (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) UNIQUE NOT NULL
);

-- Таблица книг
CREATE TABLE books (
    id SERIAL PRIMARY KEY,
    user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    description TEXT,
    image VARCHAR(255) DEFAULT NULL,
    status VARCHAR(50) DEFAULT 'available', -- available, taken, reserved
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Связь многие-ко-многим между книгами и жанрами
CREATE TABLE book_genre (
    book_id INTEGER NOT NULL REFERENCES books(id) ON DELETE CASCADE,
    genre_id INTEGER NOT NULL REFERENCES genres(id) ON DELETE CASCADE,
    PRIMARY KEY (book_id, genre_id)
);

-- Таблица рейтингов (для оценки пользователей друг другом)
CREATE TABLE ratings (
    id SERIAL PRIMARY KEY,
    from_user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    to_user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    rating INTEGER CHECK (rating >= 1 AND rating <= 5),
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(from_user_id, to_user_id)
);

-- Вставляем начальные данные

-- Жанры
INSERT INTO genres (name) VALUES 
    ('Фантастика'),
    ('Детектив'),
    ('Роман'),
    ('Фэнтези'),
    ('Научная литература'),
    ('Биография'),
    ('Приключения'),
    ('Антиутопия'),
    ('Классика'),
    ('Современная проза');

-- Тестовый администратор (пароль: admin123)
INSERT INTO users (email, password_hash, username, city, telegram_url, is_admin) VALUES
    ('admin@bookcrossing.ru', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Администратор', 'Москва', '@admin_books', TRUE);

-- Тестовые пользователи (пароль для всех: password123)
INSERT INTO users (email, password_hash, username, city, telegram_url) VALUES
    ('anna@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Анна Читалкина', 'Санкт-Петербург', '@anna_readsfaster'),
    ('ivan@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Иван Книголюб', 'Москва', '@ivan_books'),
    ('maria@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Мария Петрова', 'Казань', '@maria_reader');

-- Тестовые книги
INSERT INTO books (user_id, title, author, description, status) VALUES
    (2, '1984', 'Джордж Оруэлл', 'Антиутопический роман о тоталитарном обществе, слежке и контроле над мыслями.', 'available'),
    (2, 'Мастер и Маргарита', 'Михаил Булгаков', 'Легендарный роман о визите Воланда в Москву. Магия, любовь и философия.', 'available'),
    (3, 'Гарри Поттер и философский камень', 'Дж.К. Роулинг', 'История мальчика-волшебника, который узнаёт о своём предназначении.', 'available'),
    (4, 'Три товарища', 'Эрих Мария Ремарк', 'Роман о дружбе, любви и жизни в послевоенной Германии.', 'available');

-- Привязка жанров к книгам
INSERT INTO book_genre (book_id, genre_id) VALUES
    (1, 8), -- 1984 - Антиутопия
    (2, 3), -- Мастер и Маргарита - Роман
    (2, 4), -- Мастер и Маргарита - Фэнтези
    (3, 4), -- Гарри Поттер - Фэнтези
    (3, 7), -- Гарри Поттер - Приключения
    (4, 3), -- Три товарища - Роман
    (4, 9); -- Три товарища - Классика

-- Индексы для улучшения производительности
CREATE INDEX idx_books_user_id ON books(user_id);
CREATE INDEX idx_books_status ON books(status);
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_ratings_to_user ON ratings(to_user_id);
