<?php

require __DIR__ . '/vendor/autoload.php';

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\LabelAlignment;
use Endroid\QrCode\Label\Font\OpenSans;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['text'])) {
    $text = $_POST['text'];

    try {
        $builder = new Builder(
            writer: new PngWriter(),
            writerOptions: [],
            validateResult: false,
            data: $text,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 300,
            margin: 10,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
            logoPath: __DIR__.'/img/lightning.png',
            logoResizeToWidth: 50,
            logoPunchoutBackground: true,
            labelText: '',
            labelFont: new OpenSans(20),
            labelAlignment: LabelAlignment::Center
        );
        
        $result = $builder->build();

        // Define o cabeçalho para exibir a imagem
        header('Content-Type: ' . $result->getMimeType());
        echo $result->getString();
        exit;
    } catch (Exception $e) {
        http_response_code(500);
        echo "Erro ao gerar QR Code: " . $e->getMessage();
        exit;
    }
}

http_response_code(400);
echo "Texto não fornecido.";