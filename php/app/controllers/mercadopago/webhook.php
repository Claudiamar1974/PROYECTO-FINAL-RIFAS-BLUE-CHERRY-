<?php
// Webhook para notificaciones de MercadoPago
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../lib/mercadopago_helper.php';

// Leer payload JSON
$raw = file_get_contents('php://input');
if (empty($raw)) {
    http_response_code(400);
    echo 'Empty payload';
    exit;
}

$payload = json_decode($raw, true);
if (!$payload) {
    http_response_code(400);
    echo 'Invalid JSON';
    exit;
}

// Según la documentación, los webhooks pueden enviar different resource_type and action
// Intentaremos obtener payment id o external_reference
$resource = $payload['resource'] ?? null;
$topic = $payload['topic'] ?? $payload['type'] ?? null;

$payment_id = null;
$external_ref = null;

// Estructuras varían; intentar casos comunes
if (!empty($payload['data']['id'])) {
    $payment_id = $payload['data']['id'];
}
if (!empty($payload['action']) && !empty($payload['data']['id'])) {
    $payment_id = $payload['data']['id'];
}
if (!empty($payload['data']['external_reference'])) {
    $external_ref = $payload['data']['external_reference'];
}

// Si no viene payment id pero sí viene resource with id
if (!$payment_id && !empty($payload['resource']) && !empty($payload['resource']['id'])) {
    $payment_id = $payload['resource']['id'];
}

// Si tenemos payment_id, consultamos la API
if ($payment_id) {
    $check = mp_get_payment($payment_id);
    if (!empty($check['error'])) {
        http_response_code(500);
        echo 'Error fetching payment: ' . $check['error'];
        exit;
    }
    $body = $check['body'] ?? [];
    $status = $body['status'] ?? null;
    $external_ref = $body['external_reference'] ?? $external_ref;

    if ($status && strtolower($status) === 'approved' && $external_ref) {
        // Procesar confirmación: external_reference es el id de reserva
        $id_reserva = (int)$external_ref;
        try {
            $pdo->beginTransaction();
            // Verificar reserva
            $stmt = $pdo->prepare('SELECT estado FROM reservas WHERE id = ? FOR UPDATE');
            $stmt->execute([$id_reserva]);
            $r = $stmt->fetch();
            if ($r && $r['estado'] === 'reservado') {
                // Actualizar reserva
                $stmt = $pdo->prepare('UPDATE reservas SET estado = ?, actualizado_en = NOW() WHERE id = ?');
                $stmt->execute(['pagado', $id_reserva]);
                // Actualizar numeros
                $stmt = $pdo->prepare('UPDATE numeros_rifa SET estado = "vendido", actualizado_en = NOW() WHERE id_reserva = ?');
                $stmt->execute([$id_reserva]);
                // Registrar pago
                $stmt = $pdo->prepare('INSERT INTO pagos (id_reserva, id_pago_mp, estado, monto, metodo_pago, pagado_en, creado_en, actualizado_en) VALUES (?, ?, ?, ?, ?, NOW(), NOW(), NOW())');
                $stmt->execute([$id_reserva, $payment_id, 'aprobado', $body['transaction_amount'] ?? 0, $body['payment_type_id'] ?? 'MercadoPago']);
                    // Notificar por correo
                    if (!empty($body['payer']['email'])) {
                        require_once __DIR__ . '/../utils/notificar_compra.php';
                        // Obtener numeros para el correo
                        $stmt2 = $pdo->prepare('SELECT r.titulo, u.nombre FROM reservas res LEFT JOIN usuarios u ON res.id_usuario = u.id LEFT JOIN rifas r ON res.id_rifa = r.id WHERE res.id = ?');
                        $stmt2->execute([$id_reserva]);
                        $info = $stmt2->fetch();
                        // Obtener numeros
                        $stmt3 = $pdo->prepare('SELECT numero FROM numeros_rifa WHERE id_reserva = ?');
                        $stmt3->execute([$id_reserva]);
                        $rows = $stmt3->fetchAll();
                        $nums = array_column($rows, 'numero');
                        @notificarCompra($body['payer']['email'], $info['nombre'] ?? 'Usuario', $id_reserva, $info['titulo'] ?? '', $nums, $body['transaction_amount'] ?? 0);
                    }
            }
            $pdo->commit();
            http_response_code(200);
            echo 'OK';
            exit;
        } catch (Exception $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            http_response_code(500);
            echo 'Error processing webhook: ' . $e->getMessage();
            exit;
        }
    }
}

// Si llegamos aquí, no hicimos nada
http_response_code(200);
echo 'ignored';
