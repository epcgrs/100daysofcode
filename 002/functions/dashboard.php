<?php 
include __DIR__ .'/../database/databaseConnection.php';

function getUserLinks($userId) {
    $db = getDatabaseConnection();
    $stmt = $db->prepare("SELECT * FROM urls WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $userId);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getUserLinksClicks($userId) {
    $db = getDatabaseConnection();
    $stmt = $db->prepare("SELECT short_url, COUNT(*) as clicks FROM clicks INNER JOIN urls ON clicks.url_id = urls.id WHERE user_id = :user_id GROUP BY short_url");
    $stmt->bindParam(':user_id', $userId);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
