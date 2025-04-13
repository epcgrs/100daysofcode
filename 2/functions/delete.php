<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $urlId = $_POST['id'];
    $userId = $_SESSION['user']['id'];

    $db = getDatabaseConnection();
    $stmt = $db->prepare("DELETE FROM urls WHERE id = :url_id AND user_id = :user_id");
    $stmt->bindParam(':url_id', $urlId);
    $stmt->bindParam(':user_id', $userId);

    if ($stmt->execute()) {
        redirectWithFeedback('/dashboard', ['success' => 'URL deleted successfully.']);
    } else {
        redirectWithFeedback('/dashboard', ['error' => 'Failed to delete URL.']);
    }
}

