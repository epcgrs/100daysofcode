<?php
function getAddressInfo($address) {
    $url = "https://blockstream.info/api/address/$address";
    return json_decode(file_get_contents($url), true);
}

function getAddressTxs($address) {
    $url = "https://blockstream.info/api/address/$address/txs";
    return json_decode(file_get_contents($url), true);
}

$addressData = null;
$txs = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['address'])) {
    $address = trim($_POST['address']);
    $addressData = getAddressInfo($address);
    $txs = getAddressTxs($address);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Explorador Bitcoin</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>Explorador de Endereço Bitcoin</h1>
<form method="POST">
    <input type="text" name="address" placeholder="Endereço BTC" required>
    <button type="submit">Buscar</button>
</form>

<?php if ($addressData): ?>
    <h2>Informações do endereço</h2>
    <p><strong>Saldo:</strong> <?= $addressData['chain_stats']['funded_txo_sum'] / 100000000 ?> BTC</p>
    <p><strong>Transações:</strong> <?= $addressData['chain_stats']['tx_count'] ?></p>

    <h3>Últimas Transações</h3>
    <?php foreach ($txs as $tx): ?>
        <div class="tx">
            <div><strong>Hash:</strong> <small><?= $tx['txid'] ?></small></div>
            <div><strong>Valor:</strong> <?= array_sum(array_column($tx['vout'], 'value')) / 100000000 ?> BTC</div>
            <div><strong>Data:</strong> <?= date('d/m/Y H:i:s', $tx['status']['block_time'] ?? time()) ?></div>
            <div><strong>Status:</strong> <?= $tx['status']['confirmed'] ? 'Confirmada' : 'Pendente' ?></div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

</body>
</html>
