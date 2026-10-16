<?php

// Pastikan direktori kompilasi view dan cache tersedia di /tmp (karena filesystem Vercel read-only)
if (!is_dir('/tmp/views')) {
    mkdir('/tmp/views', 0755, true);
}
if (!is_dir('/tmp/cache')) {
    mkdir('/tmp/cache', 0755, true);
}

// Jika berjalan di Vercel dan belum ada koneksi MySQL remote yang diatur, fallback otomatis ke SQLite di /tmp
if (getenv('VERCEL') && (!getenv('DB_HOST') || getenv('DB_HOST') === '127.0.0.1')) {
    putenv('DB_CONNECTION=sqlite');
    putenv('DB_DATABASE=/tmp/database.sqlite');
    $_ENV['DB_CONNECTION'] = 'sqlite';
    $_ENV['DB_DATABASE'] = '/tmp/database.sqlite';
}

// Teruskan seluruh request HTTP ke public/index.php milik Laravel
require __DIR__ . '/../public/index.php';
