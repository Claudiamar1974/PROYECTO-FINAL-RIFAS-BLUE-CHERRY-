<?php
// util: liberar_reservas.php
// Provee una función reutilizable para liberar reservas expiradas.
function liberarReservasExpiradas($pdo, $now = null) {
    // $now: optional string 'Y-m-d H:i:s' representing current time in same tz as expira_en
    require_once __DIR__ . '/tiempo.php';
    try {
        // si no se pasa $now, tomar la hora de Google en America/Montevideo
        if ($now === null) {
            $now = obtenerHoraGoogle('America/Montevideo')->format('Y-m-d H:i:s');
        }

        $pdo->beginTransaction();

        // Seleccionamos ids de reservas expiradas para actuar sobre ellas (comparando con $now)
        $stmt = $pdo->prepare("SELECT id FROM reservas WHERE estado = 'reservado' AND expira_en < ? FOR UPDATE");
        $stmt->execute([$now]);
        $reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($reservas as $res) {
            $id_reserva = $res['id'];
            // Liberar números asociados
            $stmt2 = $pdo->prepare("UPDATE numeros_rifa SET id_reserva = NULL, estado = 'libre', actualizado_en = ? WHERE id_reserva = ?");
            $stmt2->execute([$now, $id_reserva]);
            // Marcar reserva como expirada
            $stmt3 = $pdo->prepare("UPDATE reservas SET estado = 'expirado', actualizado_en = ? WHERE id = ?");
            $stmt3->execute([$now, $id_reserva]);
        }

        $pdo->commit();
        return count($reservas);
    } catch (Exception $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        throw $e;
    }
}

?>