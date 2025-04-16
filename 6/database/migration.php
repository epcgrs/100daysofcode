<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/database.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$pdo = getConnection();


$pdo->exec("CREATE TABLE IF NOT EXISTS jokes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Criação da tabela de pagamentos
$pdo->exec("CREATE TABLE IF NOT EXISTS payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    payment_hash LONGTEXT NOT NULL,
    amount INT NOT NULL,
    received INT DEFAULT 0,
    joke_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    payment_date TIMESTAMP NULL,
    FOREIGN KEY (joke_id) REFERENCES jokes(id)
)");

// Importar piadas do JSON para o banco de dados (se estiverem vazias)
$stmt = $pdo->query("SELECT COUNT(*) FROM jokes");
if ($stmt->fetchColumn() == 0) {
    $piadas = json_decode(file_get_contents(__DIR__ . '/../piadas.json'), true);
    $stmt = $pdo->prepare("INSERT INTO jokes (content) VALUES (?)");
    
    foreach ($piadas as $piada) {
        $stmt->execute([$piada]);
    }
    
    echo "Piadas importadas com sucesso!\n";
}

echo "Banco de dados inicializado com sucesso!\n";