<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

function getDatabaseConnection() {
    try {
        return new SQLite3('frases_estoicas.db');
    } catch (Exception $e) {
        jsonResponse(['error' => 'Erro ao conectar ao banco de dados.'], 500);
        exit;
    }
}
function getRandomQuote($db, $type = null) {

    try{
        $query = 'SELECT frase, autor FROM frases_estoicas';
        if ($type && $type !== 'null') {
            $query .= ' WHERE type = :type ';
        }
        $query .= ' ORDER BY RANDOM() LIMIT 1';

        $stmt = $db->prepare($query);

        if ($type && $type !== 'null') {
            $stmt->bindValue(':type', $type, SQLITE3_TEXT);
        }

        $result = $stmt->execute();

        return $result->fetchArray(SQLITE3_ASSOC);
    } catch (SQLite3Exception $e) {
        jsonResponse(['error' => 'Erro ao executar a consulta. ' . $e->getMessage()], 500);
    } catch (Exception $e) {
        jsonResponse(['error' => 'Erro ao executar a consulta. ' . $e->getMessage()], 500);
    } 
}

function jsonResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    echo json_encode($data);
    exit;
}


// ?action=getAll

if (isset($_GET['action']) && $_GET['action'] === 'getAll') {
    $db = getDatabaseConnection();
    $result = $db->query('SELECT * FROM frases_estoicas');
    $quotes = [];

    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $quotes[] = $row;
    }

    jsonResponse($quotes);
}

// ?action=create
if (isset($_GET['action']) && $_GET['action'] === 'create') {
    $db = getDatabaseConnection();
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['frase']) || !isset($data['autor']) || !isset($data['type'])) {
        jsonResponse(['error' => 'Dados inválidos.'], 400);
    }

    $stmt = $db->prepare('INSERT INTO frases_estoicas (frase, autor, type) VALUES (:frase, :autor, :type)');
    $stmt->bindValue(':frase', $data['frase'], SQLITE3_TEXT);
    $stmt->bindValue(':autor', $data['autor'], SQLITE3_TEXT);
    $stmt->bindValue(':type', $data['type'], SQLITE3_TEXT);

    if ($stmt->execute()) {
        jsonResponse(['message' => 'Frase criada com sucesso.']);
    } else {
        jsonResponse(['error' => 'Erro ao criar a frase.'], 500);
    }
}
// ?action=update
if(isset($_GET['action']) && $_GET['action'] === 'update') {
    $db = getDatabaseConnection();
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id']) || !isset($data['frase']) || !isset($data['autor']) || !isset($data['type'])) {
        jsonResponse(['error' => 'Dados inválidos.'], 400);
    }

    $stmt = $db->prepare('UPDATE frases_estoicas SET frase = :frase, autor = :autor, type = :type WHERE id = :id');
    $stmt->bindValue(':id', $data['id'], SQLITE3_INTEGER);
    $stmt->bindValue(':frase', $data['frase'], SQLITE3_TEXT);
    $stmt->bindValue(':autor', $data['autor'], SQLITE3_TEXT);
    $stmt->bindValue(':type', $data['type'], SQLITE3_TEXT);

    if ($stmt->execute()) {
        jsonResponse(['message' => 'Frase atualizada com sucesso.']);
    } else {
        jsonResponse(['error' => 'Erro ao atualizar a frase.'], 500);
    }
}
// ?action=delete DELETE verb HTTP

if (isset($_GET['action']) && $_GET['action'] === 'delete') {
    $db = getDatabaseConnection();
    $id = isset($_GET['id']) ? (int)$_GET['id'] : null;

    if (!$id) {
        jsonResponse(['error' => 'ID inválido.'], 400);
    }

    $stmt = $db->prepare('DELETE FROM frases_estoicas WHERE id = :id');
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

    if ($stmt->execute()) {
        jsonResponse(['message' => 'Frase deletada com sucesso.']);
    } else {
        jsonResponse(['error' => 'Erro ao deletar a frase.'], 500);
    }
}
//action=get&id=$id
if (isset($_GET['action']) && $_GET['action'] === 'get') {
    $db = getDatabaseConnection();
    $id = isset($_GET['id']) ? (int)$_GET['id'] : null;

    if (!$id) {
        jsonResponse(['error' => 'ID inválido.'], 400);
    }

    $stmt = $db->prepare('SELECT * FROM frases_estoicas WHERE id = :id');
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    $result = $stmt->execute()->fetchArray(SQLITE3_ASSOC);

    if (!$result) {
        jsonResponse(['error' => 'Frase não encontrada.'], 404);
    }

    jsonResponse($result);
}

$db = getDatabaseConnection();
$type = isset($_GET['type']) ? htmlspecialchars($_GET['type'], ENT_QUOTES, 'UTF-8') : null;
$result = getRandomQuote($db, $type);

if (!$result) {
    jsonResponse(['error' => 'Nenhuma citação encontrada.'], 404);
}

jsonResponse(['frase' => $result['frase'], 'autor' => $result['autor']]);
