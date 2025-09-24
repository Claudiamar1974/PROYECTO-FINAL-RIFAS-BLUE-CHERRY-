<?php
// Controlador para login con Google (sin usar composer/google-client)
// Implementa el flujo OAuth2 manualmente (intercambio de code por token + request a userinfo)
// Ajuste de ruta: subir tres niveles para alcanzar /config/database.php en la raíz del proyecto
require_once __DIR__ . '/../../../config/database.php';

if (session_status() === PHP_SESSION_NONE) session_start();

$googleConfig = require __DIR__ . '/../../../config/google.php';

$client_id = $googleConfig['client_id'];
$client_secret = $googleConfig['client_secret'];
$redirect_uri = $googleConfig['redirect_uri'];
$scopes = implode(' ', $googleConfig['scopes']);

// Simple helper: POST request (cURL si está, sino stream_context)
function http_post($url, $data, $headers = []) {
    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        $res = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);
        if ($res === false) return ['error' => $err];
        return json_decode($res, true);
    } else {
        $opts = [
            'http' => [
                'method' => 'POST',
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'content' => http_build_query($data),
                'timeout' => 15,
            ]
        ];
        $context = stream_context_create($opts);
        $result = @file_get_contents($url, false, $context);
        if ($result === false) return ['error' => 'request_failed'];
        return json_decode($result, true);
    }
}

function http_get($url, $headers = []) {
    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        $res = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);
        if ($res === false) return ['error' => $err];
        return json_decode($res, true);
    } else {
        $opts = [
            'http' => [
                'method' => 'GET',
                'header' => implode("\r\n", $headers),
                'timeout' => 15,
            ]
        ];
        $context = stream_context_create($opts);
        $result = @file_get_contents($url, false, $context);
        if ($result === false) return ['error' => 'request_failed'];
        return json_decode($result, true);
    }
}

if (isset($_GET['logout'])) {
    unset($_SESSION['user']);
    header('Location: /public/index.php');
    exit;
}

// Paso 1: si no hay code, redirigir al endpoint de autorización
if (!isset($_GET['code'])) {
    $authUrl = 'https://accounts.google.com/o/oauth2/v2/auth' .
        '?response_type=code' .
        '&client_id=' . urlencode($client_id) .
        '&redirect_uri=' . urlencode($redirect_uri) .
        '&scope=' . urlencode($scopes) .
        '&access_type=offline' .
        '&prompt=select_account';

    header('Location: ' . $authUrl);
    exit;
}

// Paso 2: intercambiar el code por tokens
$code = $_GET['code'];
$tokenEndpoint = 'https://oauth2.googleapis.com/token';
$post = [
    'code' => $code,
    'client_id' => $client_id,
    'client_secret' => $client_secret,
    'redirect_uri' => $redirect_uri,
    'grant_type' => 'authorization_code'
];

$tokenResp = http_post($tokenEndpoint, $post);
if (isset($tokenResp['error'])) {
    // Mostrar un mensaje simple y salir
    $_SESSION['flash'] = 'Error al obtener token de Google: ' . (is_array($tokenResp['error']) ? json_encode($tokenResp['error']) : $tokenResp['error']);
    header('Location: /public/index.php');
    exit;
}

$access_token = $tokenResp['access_token'] ?? null;
if (!$access_token) {
    $_SESSION['flash'] = 'No se recibió access_token de Google.';
    header('Location: /public/index.php');
    exit;
}

// Paso 3: obtener información del usuario
$userInfoEndpoint = 'https://openidconnect.googleapis.com/v1/userinfo';
$userResp = http_get($userInfoEndpoint, [ 'Authorization: Bearer ' . $access_token ]);
if (isset($userResp['error'])) {
    $_SESSION['flash'] = 'Error al obtener userinfo: ' . (is_array($userResp['error']) ? json_encode($userResp['error']) : $userResp['error']);
    header('Location: /public/index.php');
    exit;
}

$google_user = $userResp; // contiene sub, name, email, picture, etc.

// Buscar o crear usuario en la base de datos (usa $pdo desde config/database.php)
$stmt = $pdo->prepare('SELECT * FROM usuarios WHERE google_id = ?');
$stmt->execute([$google_user['sub'] ?? $google_user['id'] ?? null]);
$user = $stmt->fetch();
if (!$user) {
    // Detectar columnas reales en la tabla usuarios para evitar errores (p.ej. falta 'rol')
    $cols = [];
    try {
        $colStmt = $pdo->query("DESCRIBE usuarios");
        $colInfo = $colStmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($colInfo as $c) { $cols[] = $c['Field']; }
    } catch (Exception $e) {
        // Si falla DESCRIBE, asumir columnas comunes
        $cols = ['nombre','email','google_id','avatar','rol'];
    }

    $insertCols = [];
    $placeholders = [];
    $values = [];

    $mapping = [
        'nombre' => $google_user['name'] ?? null,
        'email' => $google_user['email'] ?? null,
        'google_id' => $google_user['sub'] ?? $google_user['id'] ?? null,
        'avatar' => $google_user['picture'] ?? null,
        'rol' => 'usuario'
    ];

    foreach ($mapping as $col => $val) {
        if (in_array($col, $cols)) {
            $insertCols[] = $col;
            $placeholders[] = '?';
            $values[] = $val;
        }
    }

    if (!empty($insertCols)) {
        $sql = 'INSERT INTO usuarios (' . implode(',', $insertCols) . ', creado_en, actualizado_en) VALUES (' . implode(',', $placeholders) . ', NOW(), NOW())';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($values);
    }

    // Construir objeto user para la sesión aunque la BD no tenga 'rol'
    $user = [
        'id' => $pdo->lastInsertId(),
        'nombre' => $google_user['name'] ?? null,
        'email' => $google_user['email'] ?? null,
        'google_id' => $google_user['sub'] ?? $google_user['id'] ?? null,
        'avatar' => $google_user['picture'] ?? null,
        'rol' => 'usuario'
    ];
}

$_SESSION['user'] = $user;
header('Location: /public/index.php');
exit;
