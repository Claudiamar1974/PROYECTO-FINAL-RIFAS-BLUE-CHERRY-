<?php
// Helper simple para llamadas a la API de MercadoPago sin usar el SDK
function mp_get_token() {
    // Intentar config local
    if (file_exists(__DIR__ . '/../../config/mercadopago.php')) {
        $cfg = include __DIR__ . '/../../config/mercadopago.php';
        if (!empty($cfg['access_token'])) return $cfg['access_token'];
    }
    // fallback a variable de entorno
    return getenv('MP_ACCESS_TOKEN') ?: null;
}

function mp_create_preference($data) {
    $token = mp_get_token();
    if (!$token) return ['error' => 'missing_token'];

    $ch = curl_init('https://api.mercadopago.com/checkout/preferences');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $token,
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    $resp = curl_exec($ch);
    $http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if (curl_errno($ch)) { $err = curl_error($ch); curl_close($ch); return ['error' => $err]; }
    curl_close($ch);
    return ['http' => $http, 'body' => json_decode($resp, true), 'raw' => $resp];
}

function mp_get_payment($payment_id) {
    $token = mp_get_token();
    if (!$token) return ['error' => 'missing_token'];
    $url = 'https://api.mercadopago.com/v1/payments/' . urlencode($payment_id);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $token]);
    $resp = curl_exec($ch);
    $http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if (curl_errno($ch)) { $err = curl_error($ch); curl_close($ch); return ['error' => $err]; }
    curl_close($ch);
    return ['http' => $http, 'body' => json_decode($resp, true), 'raw' => $resp];
}
