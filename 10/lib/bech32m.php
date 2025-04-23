<?php

function bech32m_polymod(array $values): int {
    $GEN = [0x3b6a57b2, 0x26508e6d, 0x1ea119fa, 0x3d4233dd, 0x2a1462b3];
    $chk = 1;
    foreach ($values as $v) {
        $b = $chk >> 25;
        $chk = (($chk & 0x1ffffff) << 5) ^ $v;
        for ($i = 0; $i < 5; $i++) {
            if ((($b >> $i) & 1) === 1) {
                $chk ^= $GEN[$i];
            }
        }
    }
    return $chk;
}

function bech32_hrp_expand(string $hrp): array {
    $ret = [];
    $len = strlen($hrp);
    for ($i = 0; $i < $len; $i++) {
        $ret[] = ord($hrp[$i]) >> 5;
    }
    $ret[] = 0;
    for ($i = 0; $i < $len; $i++) {
        $ret[] = ord($hrp[$i]) & 31;
    }
    return $ret;
}

function bech32m_create_checksum(string $hrp, array $data): array {
    $values = array_merge(bech32_hrp_expand($hrp), $data);
    $values = array_merge($values, array_fill(0, 6, 0));
    $polymod = bech32m_polymod($values) ^ 0x2bc830a3; // Checksum Bech32m
    $ret = [];
    for ($i = 0; $i < 6; $i++) {
        $ret[] = ($polymod >> 5 * (5 - $i)) & 31;
    }
    return $ret;
}

function bech32m_encode(string $hrp, int $witver, array $witprog): string {
    $CHARSET = 'qpzry9x8gf2tvdw0s3jn54khce6mua7l';

    $data = [$witver];
    $data = array_merge($data, convertbits($witprog, 8, 5, true));
    $combined = array_merge($data, bech32m_create_checksum($hrp, $data));
    $encoded = $hrp . '1';
    foreach ($combined as $c) {
        $encoded .= $CHARSET[$c];
    }
    return $encoded;
}

function convertbits(array $data, int $frombits, int $tobits, bool $pad = true): array {
    $acc = 0;
    $bits = 0;
    $ret = [];
    $maxv = (1 << $tobits) - 1;
    foreach ($data as $value) {
        if ($value < 0 || $value >> $frombits) {
            return [];
        }
        $acc = ($acc << $frombits) | $value;
        $bits += $frombits;
        while ($bits >= $tobits) {
            $bits -= $tobits;
            $ret[] = ($acc >> $bits) & $maxv;
        }
    }
    if ($pad) {
        if ($bits) {
            $ret[] = ($acc << ($tobits - $bits)) & $maxv;
        }
    } elseif ($bits >= $frombits || (($acc << ($tobits - $bits)) & $maxv)) {
        return [];
    }
    return $ret;
}
