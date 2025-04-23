<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encurtador de URLs - Linkito</title>
    <link rel="stylesheet" href="/style.css">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <meta name="description" content="Um encurtador de URLs fácil de usar.">
    <meta name="keywords" content="encurtador, URLs, Linkito, fácil de usar">
    <meta name="author" content="Emmanuelpcg">
    <meta name="theme-color" content="#4CAF50">
    <meta name="robots" content="index, follow">

</head>
<body>
    <header>
        <div class="container">
            <a href="/" style="text-decoration: none;">
                <h1>Linkito</h1>
            </a>
            <nav>
                <ul>
                    <?php if (isset($_SESSION['user'])): ?>
                        <li><a href="/dashboard">Dashboard</a></li>
                        <li><a href="/logout">Sair</a></li>
                    <?php else: ?>
                        <li><a href="/login">Entrar</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <main>