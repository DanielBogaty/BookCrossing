<?php
// Заглушка вместо реального подключения к БД
// Здесь в будущем будет: $pdo = new PDO(...)

function get_mock_books() {
    return [
        [
            'id' => 1,
            'title' => '1984',
            'author' => 'Джордж Оруэлл',
            'genre' => 'Антиутопия',
            'description' => 'Книга о тоталитарном обществе и слежке.',
            'telegram_username' => '@anna_readsfaster',
            'status' => 'available'
        ],
        [
            'id' => 2,
            'title' => 'Мастер и Маргарита',
            'author' => 'Михаил Булгаков',
            'genre' => 'Роман, фэнтези',
            'description' => 'Воланд навещает Москву. Магия, любовь, философия.',
            'telegram_username' => '@bulgakov_fan',
            'status' => 'available'
        ]
    ];
}

function get_mock_user() {
    return [
        'id' => 1,
        'username' => 'testuser',
        'email' => 'user@example.com',
        'telegram_username' => '@testuser'
    ];
}
?>