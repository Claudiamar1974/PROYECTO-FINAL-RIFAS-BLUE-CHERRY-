<?php
require_once '../app/models/Reserva.php';
require_once '../config/database.php';
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../utils/tiempo.php';
require_once __DIR__ . '/../../config/app.php';

if (!isset($_SESSION['user'])) {
    header('Location: index.php?r=auth/google-login');
    exit;
}

$id_rifa = $_GET['id'];
$numeros = isset($_POST['numeros']) ? json_decode($_POST['numeros'], true) : [];
$id_usuario = $_SESSION['user']['id'];

if (empty($numeros)) {
    echo '<p>No seleccionaste ningún número.</p><a href="index.php?r=publicrifa/show&id=' . $id_rifa . '">Volver</a>';
    exit;
}

// Verificar que los números estén libres y crear reserva atómica
$expireMinutes = defined('RESERVA_MINUTOS') ? RESERVA_MINUTOS : 15; // tiempo de reserva en minutos
try {
    $pdo->beginTransaction();

    // Bloquear las filas seleccionadas
    $placeholders = implode(',', array_fill(0, count($numeros), '?'));
    $sql = "SELECT id, estado FROM numeros_rifa WHERE id IN ($placeholders) AND id_rifa = ? FOR UPDATE";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array_merge($numeros, [$id_rifa]));
    $nums = $stmt->fetchAll();

    if (count($nums) !== count($numeros)) {
        $pdo->rollBack();
        echo '<p>Uno o más números seleccionados no son válidos. Por favor, vuelve a seleccionar.</p><a href="index.php?r=publicrifa/show&id=' . $id_rifa . '">Volver</a>';
        exit;
    }

    foreach ($nums as $n) {
        if ($n['estado'] !== 'libre') {
            $pdo->rollBack();
            echo '<p>Uno o más números ya no están disponibles. Por favor, vuelve a seleccionar.</p><a href="index.php?r=publicrifa/show&id=' . $id_rifa . '">Volver</a>';
            exit;
        }
    }

    // Calcular monto total
    $stmt = $pdo->prepare('SELECT precio_por_numero FROM rifas WHERE id = ?');
    $stmt->execute([$id_rifa]);
    $precio = $stmt->fetchColumn();
    $monto = $precio * count($numeros);

    // Insertar reserva usando la hora de Google como referencia
    $dtReservado = obtenerHoraGoogle('America/Montevideo');
    $reservado_en = $dtReservado->format('Y-m-d H:i:s');
    $dtExpira = $dtReservado->modify("+{$expireMinutes} minutes");
    $expira_en = $dtExpira->format('Y-m-d H:i:s');

    $insertSql = 'INSERT INTO reservas (id_usuario, id_rifa, estado, reservado_en, expira_en, creado_en, actualizado_en) VALUES (?, ?, ?, ?, ?, ?, ?)';
    $stmt = $pdo->prepare($insertSql);
    $nowForCreated = $dtReservado->format('Y-m-d H:i:s');
    $stmt->execute([$id_usuario, $id_rifa, 'reservado', $reservado_en, $expira_en, $nowForCreated, $nowForCreated]);
    $id_reserva = $pdo->lastInsertId();

    // Marcar numeros como reservados
    $updateSql = "UPDATE numeros_rifa SET id_reserva = ?, estado = 'reservado', actualizado_en = NOW() WHERE id IN ($placeholders) AND id_rifa = ?";
    $updateStmt = $pdo->prepare($updateSql);
    $updateStmt->execute(array_merge([$id_reserva], $numeros, [$id_rifa]));

    if ($updateStmt->rowCount() !== count($numeros)) {
        $pdo->rollBack();
        echo '<p>Error reservando los números. Por favor, inténtalo de nuevo.</p><a href="index.php?r=publicrifa/show&id=' . $id_rifa . '">Volver</a>';
        exit;
    }

    $pdo->commit();

    // Guardar en sesión la selección y el id de reserva
    $_SESSION['reserva'] = [
        'id' => $id_reserva,
        'id_usuario' => $id_usuario,
        'id_rifa' => $id_rifa,
        'numeros' => $numeros,
        'monto' => $monto,
        'expira_en' => $expira_en
    ];

    // Redirigir a checkout de MercadoPago
    header('Location: index.php?r=mp/checkout&descripcion=Rifa%20' . $id_rifa . '&monto=' . $monto);
    exit;

} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    echo '<p>Error interno: ' . htmlspecialchars($e->getMessage()) . '</p><a href="index.php?r=publicrifa/show&id=' . $id_rifa . '">Volver</a>';
    exit;
}
