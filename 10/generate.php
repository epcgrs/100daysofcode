<?php

require 'vendor/autoload.php';
require 'lib/bech32m.php';

use Elliptic\EC;


function generateTaprootAddress() 
{

    $ec = new EC('secp256k1');

    // 1. Gera chave privada aleatória
    $privateKey = bin2hex(random_bytes(32));
    $key = $ec->keyFromPrivate($privateKey);

    // 2. Chave pública (x-only)
    $pubPoint = $key->getPublic();
    $x = str_pad($pubPoint->getX()->toString(16), 64, '0', STR_PAD_LEFT);
    // 3. Taproot tweak: H_TapTweak(internalPubkey) = sha256(x-only pubkey)
    $tweak = hash('sha256', hex2bin($x), true);

    // Converte tweak pra escalar
    $tweakInt = gmp_init(bin2hex($tweak), 16);
    $privInt = gmp_init($privateKey, 16);
    $tweakedPriv = gmp_add($privInt, $tweakInt);
    $tweakedPriv = gmp_mod($tweakedPriv, gmp_init($ec->n->toString(), 10)); // Converte $ec->n para GMP
    $tweakedPriv = gmp_strval($tweakedPriv, 16);
    $tweakedPriv = str_pad($tweakedPriv, 64, '0', STR_PAD_LEFT); // preenche com zeros

    // 4. Gera chave pública tweaked
    $tweakedKey = $ec->keyFromPrivate($tweakedPriv);
    $tweakedPub = $tweakedKey->getPublic();
    $tweakedX = str_pad($tweakedPub->getX()->toString(16), 64, '0', STR_PAD_LEFT);

        // 5. Prefixo do script (segwit v1)
    $data = [0x01]; // versão
    $data[] = unpack("C*", hex2bin($tweakedX)); // x-only pubkey como push

    $flat = array_merge(...array_slice($data, 1));
    $address = bech32m_encode('bc', 0x01, $flat); // v1 é bech32m
    // 6. Retorna os dados

    return [
        'private_key' => $privateKey,
        'taproot_pubkey' => $tweakedX,
        'address' => $address,
    ];
}