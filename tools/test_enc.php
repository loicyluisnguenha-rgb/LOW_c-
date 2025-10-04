<?php
require __DIR__ . '/api/utils.php';

try {
    $plain = 'usuario@exemplo.com';
    $c = enc($plain);
    $d = dec($c);
    echo "Plain: $plain\n";
    echo "Cipher (base64): $c\n";
    echo "Dec: $d\n";
    echo ($d === $plain) ? "OK\n" : "FAILED\n";
} catch (Throwable $e) {
    echo "Erro: " . $e->getMessage() . "\n";
}
