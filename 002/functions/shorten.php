<?php
function shortenUrl($url, $userId, $ipLimit = null, $expiration = null) {
    $db = getDatabaseConnection();
    $shortUrl = generateShortUrl();
    
    $sql = "INSERT INTO urls (url, short_url, user_id" . ($ipLimit ? ", ip_limit" : "") . ($expiration ? ", expiration" : "") . ") VALUES (:url, :short_url, :user_id" . ($ipLimit ? ", :ip_limit" : "") . ($expiration ? ", :expiration" : "") . ")";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':url', $url);
    $stmt->bindParam(':short_url', $shortUrl);
    $stmt->bindParam(':user_id', $userId);
    if ($ipLimit) {
        $stmt->bindParam(':ip_limit', $ipLimit);
    }
    if ($expiration) {
        $stmt->bindParam(':expiration', $expiration);
    }

    
    if ($stmt->execute()) {
        return $shortUrl;
    } else {
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_SESSION['user'])) {
        header('Location: /login');
        exit;
    }
    
    $url = $_POST['url'] ?? '';
    $ipLimit = $_POST['ip_limit'] ?? null;
    $expiration = $_POST['expiration'] ?? null;
    
    if (empty($url) || !isValidUrl($url)) {
        var_dump($url); die;
        redirectWithFeedback('/dashboard', ['error' => 'URL inválida.']);
    }

    if ($ipLimit) {
        if (!filter_var($ipLimit, FILTER_VALIDATE_IP)) {
            redirectWithFeedback('/dashboard', ['error' => 'IP inválido.']);
        }
    }

    if ($expiration) {
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $expiration)) {
            $expiration .= ' 00:00:00';
        }

        $expirationDate = DateTime::createFromFormat('Y-m-d H:i:s', $expiration);
        if (!$expirationDate || $expirationDate < new DateTime()) {
            redirectWithFeedback('/dashboard', ['error' => 'Data de expiração inválida.']);
        }
    }
    
    $shortUrl = shortenUrl($url, $_SESSION['user']['id'], $ipLimit, $expiration);
    
    if ($shortUrl) {
        redirectWithFeedback('/dashboard', ['success' => 'URL encurtada com sucesso: ' . $shortUrl]);
    } else {
        redirectWithFeedback('/dashboard', ['error' => 'Erro ao encurtar a URL.']);
    }
}

redirectWithFeedback('/dashboard', ['error' => 'Método inválido.']);