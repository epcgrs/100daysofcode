<?php

require 'lib/bech32m.php';

$address = $_GET['address'] ?? '';

function verify_bech32m_address(string $address): array {
    $CHARSET = 'qpzry9x8gf2tvdw0s3jn54khce6mua7l';
    $pos = strrpos($address, '1');
    if ($pos === false) return ['valid' => false, 'error' => 'Separador "1" não encontrado'];

    $hrp = substr($address, 0, $pos);
    $data_part = substr($address, $pos + 1);
    $data = [];

    foreach (str_split($data_part) as $char) {
        $pos = strpos($CHARSET, $char);
        if ($pos === false) return ['valid' => false, 'error' => "Caractere inválido '$char'"];
        $data[] = $pos;
    }

    if (count($data) < 6) return ['valid' => false, 'error' => "Checksum ausente"];

    $values = array_merge(bech32_hrp_expand($hrp), $data);
    if (bech32m_polymod($values) !== 0x2bc830a3) {
        return ['valid' => false, 'error' => "Checksum inválido"];
    }

    $witver = $data[0];
    $program = convertbits(array_slice($data, 1, -6), 5, 8, false);

    if ($witver !== 1) return ['valid' => false, 'error' => "Versão de witness inválida"];
    if (count($program) !== 32) return ['valid' => false, 'error' => "Comprimento do programa inválido"];

    return [
        'valid' => true,
        'hrp' => $hrp,
        'version' => $witver,
        'program' => $program,
    ];
}


if (!$address) {
    echo "Forneça um endereço com ?address=bc1p...";
    exit;
}

if (strlen($address) < 42 || strlen($address) > 90) {
    echo "❌ Endereço inválido: deve ter entre 42 e 90 caracteres.";
    exit;
}


$result = verify_bech32m_address($address);

if ($result['valid']) {
    echo "✅ Endereço Taproot válido!<br>";
    echo "• HRP: {$result['hrp']}<br>";
    echo "• Versão: {$result['version']}<br>";
    echo "• Tamanho do witness: " . count($result['program']) . " bytes<br>";
} else {
    echo "❌ Endereço inválido: {$result['error']}";
}
