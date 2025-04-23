<?php

function getClientIp() {
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return trim(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0]);
    } elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

function redirect($shortUrl) {
    try {
        $db = getDatabaseConnection();
        $ipLimit = getClientIp();
        $stmt = $db->prepare("
            SELECT id, url, expiration, ip_limit 
            FROM urls 
            WHERE short_url = :short_url 
              AND (expiration IS NULL OR expiration > DATETIME('now')) 
              AND (ip_limit IS NULL OR ip_limit = :ip_limit)
        ");
        $stmt->bindParam(':short_url', $shortUrl, PDO::PARAM_STR);
        $stmt->bindValue(':ip_limit', $ipLimit, $ipLimit === null ? PDO::PARAM_NULL : PDO::PARAM_STR);

        $stmt->execute();

        $urlData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$urlData) {
            header("HTTP/1.0 404 Not Found");
            echo "URL not found.";
            exit;
        }

        $clickStmt = $db->prepare("INSERT INTO clicks (url_id) VALUES (:url_id)");
        $clickStmt->bindParam(':url_id', $urlData['id'], PDO::PARAM_INT);
        $clickStmt->execute();

        header("Location: " . $urlData['url']);
        exit;

    } catch (PDOException $e) {
        header("HTTP/1.0 500 Internal Server Error");
        echo "Erro ao processar a solicitação: " . $e->getMessage();
        exit;
    } catch (Exception $e) {
        header("HTTP/1.0 500 Internal Server Error");
        echo "Erro inesperado: " . $e->getMessage();
        exit;
    }
}