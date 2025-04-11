<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

function getDatabaseConnection() {
    try {
        return new SQLite3('frases_estoicas.db', SQLITE3_OPEN_READONLY);
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

$db = getDatabaseConnection();
$type = isset($_GET['type']) ? htmlspecialchars($_GET['type'], ENT_QUOTES, 'UTF-8') : null;
$result = getRandomQuote($db, $type);

if (!$result) {
    jsonResponse(['error' => 'Nenhuma citação encontrada.'], 404);
}

jsonResponse(['frase' => $result['frase'], 'autor' => $result['autor']]);
