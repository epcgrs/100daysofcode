<?php

function getRandomJoke() {
    $pdo = getConnection();

    try {
        // Consulta para buscar uma piada aleatória
        $stmt = $pdo->query("SELECT content FROM jokes ORDER BY RAND() LIMIT 1");
        $joke = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($joke) {
            return $joke['content'];
        } else {
            return "Nenhuma piada encontrada no banco de dados.";
        }
    } catch (PDOException $e) {
        return "Erro ao buscar piada: " . $e->getMessage();
    }
}