<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../services/jokes.php';
require __DIR__ . '/../services/payments.php';
require __DIR__ . '/../database/database.php';


use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app = AppFactory::create();

// env

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

foreach ($_ENV as $key => $value) {
    putenv("$key=$value");
}

// Página inicial
$app->get('/', function (Request $request, Response $response) {
    ob_start();
    include __DIR__ . '/view/home.php';
    $html = ob_get_clean();
    $response->getBody()->write($html);
    return $response;
});

// Criar invoice
$app->post('/api/criar-invoice', function (Request $request, Response $response) {
    header('Content-Type: application/json');

    try {
        $apiKey = $_ENV['COINOS_TOKEN'];
        $amount = $request->getParsedBody()['amount'] ?? 10;

        $ch = curl_init($_ENV['COINOS_API_URL'] . "invoice");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['invoice' => [
            "amount" => $amount,
            "type" => "lightning",
        ]]));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $apiKey",
            "Content-Type: application/json"
        ]);
        $res = curl_exec($ch);
        
        if (curl_errno($ch)) {
            $response->getBody()->write("Erro ao criar invoice: " . curl_error($ch));
            return $response;
        }
        curl_close($ch);


        $invoice = json_decode($res, true);
    } catch (Exception $e) {
        $response->getBody()->write("Erro ao criar invoice: " . $e->getMessage());
        return $response;
    }

    if (isset($invoice['error']) || $invoice === null) {
        $response->getBody()->write("Erro ao criar invoice: " . ($invoice['error'] ?? 'Resposta inválida'));
        return $response;
    }

    createPayment(
        $invoice['text'],
        $invoice['amount'],
        $invoice['received'] ?? 0
    );
    $response->getBody()->write(json_encode([
        'invoice' => $invoice,
    ]));
    return $response;
});

// Verificar pagamento
$app->get('/api/checar/{id}', function (Request $request, Response $response, $args) {
    header('Content-Type: application/json');
    $invoiceId = $args['id'];
    $apiKey = $_ENV['COINOS_TOKEN'];
    
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    $ch = curl_init($_ENV['COINOS_API_URL'] . "invoice/{$invoiceId}");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $apiKey"
    ]);
    $res = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($res, true);
    $piada = null;

    if ($data['received'] > 0) {
        $piada = getRandomJoke();

        updatePaymentStatus($invoiceId, $data['received'], date('Y-m-d H:i:s'));
    } else {
        $response->getBody()->write(json_encode([
            'message' => 'Pagamento não recebido',
            'piada' => null,
            'received' => 0,
        ]));
        return $response;
    }



    $response->getBody()->write(json_encode([
        'piada' => $piada,
        'received' => $data['received'],
    ]));
    return $response;
});

$app->run();
