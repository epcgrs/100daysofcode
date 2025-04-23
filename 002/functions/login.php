<?php

include_once __DIR__ . '/../database/databaseConnection.php';
include_once __DIR__ . '/helpers.php';

function login($username, $password)
{
    $db = getDatabaseConnection();

    try {
        $stmt = $db->prepare('SELECT * FROM users WHERE username = :username');
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user'] = [
                'id' => $user['id'],
                'username' => $user['username'],
            ];
            redirectWithFeedback('/dashboard', ['success' => 'Login bem-sucedido.']);
        } else {
            redirectWithFeedback('/login', ['error' => 'Nome de usuário ou senha incorretos.']);
        }
    } catch (PDOException $e) {
        redirectWithFeedback('/login', ['error' => 'Erro ao fazer login: ' . $e->getMessage()]);
    } finally {
        closeDatabaseConnection();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        redirectWithFeedback('/login', ['error' => 'Preencha todos os campos.']);
    }

    login($username, $password);
}
redirectWithFeedback('/login', ['error' => 'Método inválido.']);