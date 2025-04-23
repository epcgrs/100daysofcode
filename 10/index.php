<?php
require 'generate.php';

$address = generateTaprootAddress();
$data = $address;
$address = $data['address'] ?? '';
$address = $_GET['address'] ?? '';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Gerador de Endereço Taproot</title>
    <style>
        body { font-family: sans-serif; padding: 2em; background: #f4f4f4; color: #333; }
        input { width: 100%; padding: 0.5em; font-size: 1em; }
        .box { background: white; padding: 1em; margin-top: 1em; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        h1 { color: #1a1a1a; }
    </style>
</head>
<body>
    <h1>🔐 Gerador de Endereço Taproot (P2TR)</h1>
    <div class="box">
        <strong>🔑 Chave Privada:</strong><br>
        <input readonly value="<?= $data['private_key'] ?>">
    </div>

    <div class="box">
        <strong>🧠 X-only PubKey (tweaked):</strong><br>
        <input readonly value="<?= $data['taproot_pubkey'] ?>">
    </div>

    <div class="box">
        <strong>🏷️ Endereço Taproot:</strong><br>
        <input readonly value="<?= $data['address'] ?>">
    </div>

    <div class="box">
        <form action="verify.php" method="get">
            <strong>🔍 Verificar Endereço:</strong><br>
            <input name="address" placeholder="bc1p..." required>
            <button type="submit">Verificar</button>
        </form>
    </div>
</body>
</html>
