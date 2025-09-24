<?php
// Script para liberar reservas expiradas (ejecutar periódicamente con cron)
require_once '../config/database.php';
require_once __DIR__ . '/../app/utils/liberar_reservas.php';
require_once __DIR__ . '/../app/utils/tiempo.php';

try {
    $now = ahoraGoogleFormato('Y-m-d H:i:s', 'America/Montevideo');
    $count = liberarReservasExpiradas($pdo, $now);
    // Para cron podemos dejar un pequeño log si se desea (stdout)
    if (PHP_SAPI === 'cli') {
        echo "Reservas expiradas liberadas: $count\n";
    }
} catch (Exception $e) {
    if (PHP_SAPI === 'cli') {
        fwrite(STDERR, 'Error liberando reservas: ' . $e->getMessage() . "\n");
    }
    // No hacemos más aquí; el cron puede captar el error por salida/exit code
    exit(1);
}
