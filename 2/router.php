<?php

include __DIR__ . '/functions/dashboard.php';
include __DIR__ . '/functions/helpers.php';

$routes = [];

function addRoute($method, $path, $callback) {
    global $routes;
    $routes[] = ['method' => $method, 'path' => $path, 'callback' => $callback];
}

function dispatch($requestUri, $requestMethod) {
    global $routes;

    foreach ($routes as $route) {
        if ($route['method'] === $requestMethod && preg_match("#^{$route['path']}$#", $requestUri, $matches)) {
            array_shift($matches);
            return call_user_func_array($route['callback'], $matches);
        }
    }

    http_response_code(404);
    echo "404 - Página não encontrada";
}


addRoute('GET', '/', function() {
    include 'pages/home.php';
});

addRoute('GET', '/register', function() {
    session_start();
    if (isset($_SESSION['user'])) {
        header('Location: /dashboard');
        exit;
    }
    include 'pages/register.php';
});

addRoute('POST', '/register', function() {
    include 'functions/registerPost.php';
});

addRoute('GET', '/login', function() {
    session_start();
    if (isset($_SESSION['user'])) {
        header('Location: /dashboard');
        exit;
    }
    include 'pages/login.php';
});

addRoute('POST', '/login', function() {
    include 'functions/login.php';
});

addRoute('GET', '/logout', function() {
    session_start();
    session_destroy();
    header('Location: /');
    exit;
});


addRoute('GET', '/dashboard', function() {
    session_start();
    if (!isset($_SESSION['user'])) {
        header('Location: /login');
        exit;
    }
    $userLinks = getUserLinks($_SESSION['user']['id']);
    $userClicks = getUserLinksClicks($_SESSION['user']['id']);
    include 'pages/dashboard.php';
});

addRoute('POST', '/dashboard/shorten', function() {
    session_start();
    if (!isset($_SESSION['user'])) {
        header('Location: /login');
        exit;
    }
    include 'functions/shorten.php';
});

addRoute('POST', '/dashboard/delete/(\d+)', function($id) {
    session_start();
    if (!isset($_SESSION['user'])) {
        header('Location: /login');
        exit;
    }
    include 'functions/delete.php';
});

addRoute('GET', '/(\w+)', function($shortUrl) {
    include 'functions/redirect.php';
    redirect($shortUrl);
});

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];
dispatch($requestUri, $requestMethod);