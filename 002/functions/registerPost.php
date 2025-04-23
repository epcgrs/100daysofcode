<?php

function registerUser($username, $password) {
    $db = getDatabaseConnection();

    try {
        $stmt = $db->prepare('SELECT * FROM users WHERE username = :username');
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->fetch()) {
            redirectWithFeedback('/register', ['error' => 'Usuário já existe.']);
        }
    } catch (PDOException $e) {
        redirectWithFeedback('/register', ['error' => 'Erro ao verificar usuário: ' . $e->getMessage()]);
    }

    try {
        // Insere o novo usuário
        $stmt = $db->prepare('INSERT INTO users (username, password) VALUES (:username, :password)');
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt->bindValue(':password', password_hash($password, PASSWORD_BCRYPT), PDO::PARAM_STR);

        if ($stmt->execute()) {
            redirectWithFeedback('/login', ['success' => 'Usuário registrado com sucesso.']);
        } else {
            redirectWithFeedback('/register', ['error' => 'Erro ao registrar usuário.']);
        }
    } catch (PDOException $e) {
        redirectWithFeedback('/register', ['error' => 'Erro ao registrar usuário: ' . $e->getMessage()]);
    } finally {
        closeDatabaseConnection();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        redirectWithFeedback('/register', ['error' => 'Preencha todos os campos.']);
    }

    if (strlen($password) < 6) {
        redirectWithFeedback('/register', ['error' => 'A senha deve ter pelo menos 6 caracteres.']);
    }

    registerUser($username, $password);
}

redirectWithFeedback('/register', ['error' => 'Método inválido.']);