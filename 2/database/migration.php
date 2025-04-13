<?php

try {
    $conn = new PDO('sqlite:' . __DIR__ . '/linkito.db');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $conn->exec("CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT NOT NULL UNIQUE,
        password TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    $conn->exec("CREATE TABLE IF NOT EXISTS urls (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        url TEXT NOT NULL,
        short_url TEXT NOT NULL UNIQUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id)
    )");

    $conn->exec("CREATE TABLE IF NOT EXISTS clicks (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        url_id INTEGER NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (url_id) REFERENCES urls(id) ON DELETE CASCADE
    )");

    $columns = $conn->query("PRAGMA table_info(urls)")->fetchAll(PDO::FETCH_COLUMN);
    if (!in_array('ip_limit', $columns)) {
        $conn->exec("ALTER TABLE urls ADD COLUMN ip_limit TEXT");
    }
    if (!in_array('expiration', $columns)) {
        $conn->exec("ALTER TABLE urls ADD COLUMN expiration TIMESTAMP");
    }

    $conn->exec("UPDATE urls SET ip_limit = NULL, expiration = NULL");

    echo "Migração concluída com sucesso!";
} catch (PDOException $e) {
    echo "Erro ao criar tabelas: " . $e->getMessage();
} finally {
    $conn = null;
    echo "Conexão fechada com sucesso.";
}