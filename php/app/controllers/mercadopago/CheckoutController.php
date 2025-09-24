<?php
// Controlador para pagos con MercadoPago (checkout básico) SIN usar Composer/SDK
// Implementa la creación de una preferencia mediante REST (cURL)
// Cargar configuración de base de datos (ubicada en /config)
require_once __DIR__ . '/../../../config/database.php';
// Cargar helper de MercadoPago (ubicado en /app/lib)
require_once __DIR__ . '/../../lib/mercadopago_helper.php';

if (session_status() === PHP_SESSION_NONE) session_start();

// Verificar existencia de reserva en sesión
if (!isset($_SESSION['reserva'])) {
    echo '<p>No hay reserva activa.</p><a href="index.php">Volver</a>';
    exit;
}

$reserva = $_SESSION['reserva'];
$monto = (float)$reserva['monto'];
$descripcion = 'Rifa #' . $reserva['id_rifa'] . ' - Números: ' . implode(',', $reserva['numeros']);

$
// Obtener ACCESS_TOKEN: preferible definir en config/mercadopago.php o en variable de entorno MP_ACCESS_TOKEN
$mpToken = null;
// Intentar cargar config local si existe
if (file_exists(__DIR__ . '/../../../config/mercadopago.php')) {
    $cfg = include __DIR__ . '/../../../config/mercadopago.php';
    if (!empty($cfg['access_token'])) $mpToken = $cfg['access_token'];
}
// Fallback a variable de entorno
if (!$mpToken) $mpToken = getenv('MP_ACCESS_TOKEN') ?: null;

if (empty($mpToken) || $mpToken === 'TU_ACCESS_TOKEN') {
    echo '<h3>Configuración requerida</h3>';
    echo '<p>No se encontró un <strong>ACCESS_TOKEN</strong> de MercadoPago. Define <code>config/mercadopago.php</code> con <code>["access_token"]</code> o exporta la variable de entorno <code>MP_ACCESS_TOKEN</code>.</p>';
    echo '<p>Ejemplo de <code>config/mercadopago.php</code>:</p>';
    echo '<pre>&lt;?php return ["access_token" => "TU_ACCESS_TOKEN"];</pre>';
    exit;
}

// Construir la preferencia (REST)
// Añadir external_reference para relacionar la preferencia con la reserva
$payload = [
    'items' => [[
        'title' => $descripcion,
        'quantity' => 1,
        'unit_price' => $monto
    ]],
    'back_urls' => [
        'success' => 'http://localhost/public/index.php?r=mp/success',
        'failure' => 'http://localhost/public/index.php?r=mp/failure',
        'pending' => 'http://localhost/public/index.php?r=mp/pending'
    ],
    'auto_return' => 'approved'
];
// Agregar referencia externa con id de reserva si está disponible
if (!empty($reserva['id'])) $payload['external_reference'] = (string)$reserva['id'];

$result = mp_create_preference($payload);
if (!empty($result['error'])) {
    echo '<h3>Error al crear la preferencia</h3>' . htmlspecialchars((string)$result['error']);
    exit;
}
if (($result['http'] ?? 0) >= 200 && ($result['http'] ?? 0) < 300 && !empty($result['body']['init_point'])) {
    header('Location: ' . $result['body']['init_point']);
    exit;
} else {
    echo '<h3>Error al crear la preferencia</h3>';
    echo '<pre>' . htmlspecialchars($result['raw'] ?? json_encode($result)) . '</pre>';
    exit;
}
