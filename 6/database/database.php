<?php
require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

foreach ($_ENV as $key => $value) {
    putenv("$key=$value");
}

function getConnection() {
    $host = $_ENV['DB_HOST'] ?: 'localhost';
    $dbname = $_ENV['DB_NAME'] ?: 'pay_for_joke';
    $username = $_ENV['DB_USER'] ?: 'root';
    $password = $_ENV['DB_PASSWORD'] ?: '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Erro na conexão com o banco de dados: " . $e->getMessage());
    }
}