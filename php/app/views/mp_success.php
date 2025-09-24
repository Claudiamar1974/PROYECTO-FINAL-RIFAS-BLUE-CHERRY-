<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../lib/mercadopago_helper.php';
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['reserva'])) {
    echo '<p>No hay reserva activa.</p><a href="index.php">Volver</a>';
    exit;
}

$reserva = $_SESSION['reserva'];
// Esperamos que $_SESSION['reserva'] contenga 'id' (id de la reserva creada al reservar)
if (empty($reserva['id'])) {
    echo '<p>Reserva inválida o incompleta.</p><a href="index.php">Volver</a>';
    exit;
}

$id_reserva = $reserva['id'];
$id_usuario = $reserva['id_usuario'];
$id_rifa = $reserva['id_rifa'];
$numeros = $reserva['numeros'];
$monto = $reserva['monto'];

try {
    $pdo->beginTransaction();

    // Verificar que la reserva exista y aún esté en estado 'reservado'
    $stmt = $pdo->prepare('SELECT estado FROM reservas WHERE id = ? FOR UPDATE');
    $stmt->execute([$id_reserva]);
    $row = $stmt->fetch();
    if (!$row) {
        $pdo->rollBack();
        echo '<p>Reserva no encontrada.</p><a href="index.php">Volver</a>';
        exit;
    }
    if ($row['estado'] !== 'reservado') {
        $pdo->rollBack();
        echo '<p>La reserva ya no está en estado reservado (estado: ' . htmlspecialchars($row['estado']) . ').</p><a href="index.php">Volver</a>';
        exit;
    }

    // Si se proporcionó payment_id, verificar el estado contra la API de MercadoPago
    $payment_id = $_GET['payment_id'] ?? $_POST['payment_id'] ?? null;
    if ($payment_id) {
        $check = mp_get_payment($payment_id);
        if (!empty($check['error'])) {
            $pdo->rollBack();
            echo '<p>Error verificando el pago: ' . htmlspecialchars($check['error']) . '</p><a href="index.php">Volver</a>';
            exit;
        }
        $body = $check['body'] ?? [];
        // Estado esperado 'approved' o 'approved' en pago
        if (empty($body['status']) || strtolower($body['status']) !== 'approved') {
            $pdo->rollBack();
            echo '<p>El pago no fue aprobado (estado: ' . htmlspecialchars($body['status'] ?? 'desconocido') . ').</p><a href="index.php">Volver</a>';
            exit;
        }
        // Registrar datos de pago
        $id_pago_mp = $payment_id;
        $estado_pago = $body['status'];
        $pagado_en = $body['date_approved'] ?? date('Y-m-d H:i:s');
        $metodo = $body['payment_type_id'] ?? 'MercadoPago';
    }

    // Marcar reserva como pagada
    $stmt = $pdo->prepare('UPDATE reservas SET estado = ?, actualizado_en = NOW() WHERE id = ?');
    $stmt->execute(['pagado', $id_reserva]);

    // Marcar numeros como vendidos (solo los que pertenecen a esta reserva)
    if (!empty($numeros)) {
        $placeholders = implode(',', array_fill(0, count($numeros), '?'));
        $sql = "UPDATE numeros_rifa SET estado = 'vendido', actualizado_en = NOW() WHERE id_reserva = ? AND id IN ($placeholders) AND id_rifa = ?";
        $params = array_merge([$id_reserva], $numeros, [$id_rifa]);
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
    }

    // Registrar pago: intentar tomar id de pago de MP desde GET/POST si está disponible
    $id_pago_mp = $_GET['payment_id'] ?? $_POST['payment_id'] ?? 'mercadopago';
    $estado_pago = 'aprobado';
    $metodo = 'MercadoPago';
    $pagado_en = date('Y-m-d H:i:s');

    $stmt = $pdo->prepare('INSERT INTO pagos (id_reserva, id_pago_mp, estado, monto, metodo_pago, pagado_en, creado_en, actualizado_en) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())');
    $stmt->execute([$id_reserva, $id_pago_mp, $estado_pago, $monto, $metodo, $pagado_en]);

    $pdo->commit();

    // Limpiar sesión
    unset($_SESSION['reserva']);

    // Intentar notificar por correo si el usuario tiene email
    require_once __DIR__ . '/../utils/notificar_compra.php';
    // Obtener datos de usuario y rifa para el mail
    $stmt = $pdo->prepare('SELECT u.email, u.nombre, r.titulo FROM reservas res LEFT JOIN usuarios u ON res.id_usuario = u.id LEFT JOIN rifas r ON res.id_rifa = r.id WHERE res.id = ?');
    $stmt->execute([$id_reserva]);
    $info = $stmt->fetch();
    if ($info && !empty($info['email'])) {
        @notificarCompra($info['email'], $info['nombre'] ?? 'Usuario', $id_reserva, $info['titulo'] ?? '', $numeros, $monto);
    }

    ?>
    <h2>Pago exitoso</h2>
    <p>¡El pago fue aprobado correctamente!</p>
    <p>Reserva ID: <?= htmlspecialchars($id_reserva) ?></p>
    <p>Monto: $<?= number_format($monto, 2) ?></p>
    <a href="index.php">Volver al inicio</a>
    <?php

} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    echo '<p>Error procesando el pago: ' . htmlspecialchars($e->getMessage()) . '</p><a href="index.php">Volver</a>';
    exit;
}