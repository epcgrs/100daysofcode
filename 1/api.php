<?php

header('Content-Type: application/json');

// Conexão com o banco de dados SQLite
$db = new SQLite3('frases_estoicas.db');

// Seleciona uma frase aleatória do banco de dados

$query = 'SELECT frase, autor FROM frases_estoicas';

if(isset($_GET['type']) && !empty($_GET['type'])) {
    $type = $_GET['type'];
    if ($type == 'cristã') {
        $query .= ' WHERE type = "cristã"';
    } elseif ($type == 'estoica') {
        $query .= ' WHERE type = "estoica"';
    }
}
$query .= '  ORDER BY RANDOM() LIMIT 1';

$result = $db->query($query);

if (!$result) {
    echo json_encode(['error' => 'Erro ao buscar a frase.']);
    exit;
}

$frase_aleatoria = $result->fetchArray(SQLITE3_ASSOC);

// Retorna a frase em formato JSON
echo json_encode(['frase' => $frase_aleatoria['frase'], 'autor' => $frase_aleatoria['autor']]);
exit;