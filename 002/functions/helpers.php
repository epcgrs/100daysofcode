<?php


function redirectWithFeedback(string $path, array $feedback): void
{
    session_start();
    foreach ($feedback as $key => $value) {
        $_SESSION[$key] = $value;
    }
    header('Location: ' . $path);
    exit;
}

function generateShortUrl(): string
{
    $db = getDatabaseConnection();
    $shortUrl = generateRandomString(4);
    while (!isUniqueShortUrl($shortUrl, $db)) {
        $shortUrl = generateRandomString(4);
    }
    return $shortUrl;
}

function isUniqueShortUrl(string $shortUrl, $db): bool
{
    $stmt = $db->prepare("SELECT COUNT(*) FROM urls WHERE short_url = :short_url");
    $stmt->bindParam(':short_url', $shortUrl);
    $stmt->execute();
    return $stmt->fetchColumn() == 0;
}

function generateRandomString($length = 6): string
{
    return bin2hex(random_bytes($length));
}

function isValidUrl($url): bool
{
    return filter_var($url, FILTER_VALIDATE_URL) !== false;
}