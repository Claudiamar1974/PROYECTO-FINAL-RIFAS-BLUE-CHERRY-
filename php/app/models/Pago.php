<?php
// Modelo de pago
class Pago {
    public $id;
    public $id_reserva;
    public $id_pago_mp;
    public $estado;
    public $monto;
    public $metodo_pago;
    public $pagado_en;
    public $creado_en;
    public $actualizado_en;

    public static function all($pdo) {
        $stmt = $pdo->query('SELECT * FROM pagos');
        return $stmt->fetchAll();
    }

    public static function find($pdo, $id) {
        $stmt = $pdo->prepare('SELECT * FROM pagos WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function create($pdo, $data) {
        $stmt = $pdo->prepare('INSERT INTO pagos (id_reserva, id_pago_mp, estado, monto, metodo_pago, pagado_en, creado_en, actualizado_en) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())');
        return $stmt->execute([
            $data['id_reserva'], $data['id_pago_mp'], $data['estado'], $data['monto'], $data['metodo_pago'], $data['pagado_en']
        ]);
    }

    public static function update($pdo, $id, $data) {
        $stmt = $pdo->prepare('UPDATE pagos SET id_reserva=?, id_pago_mp=?, estado=?, monto=?, metodo_pago=?, pagado_en=?, actualizado_en=NOW() WHERE id=?');
        return $stmt->execute([
            $data['id_reserva'], $data['id_pago_mp'], $data['estado'], $data['monto'], $data['metodo_pago'], $data['pagado_en'], $id
        ]);
    }

    public static function delete($pdo, $id) {
        $stmt = $pdo->prepare('DELETE FROM pagos WHERE id=?');
        return $stmt->execute([$id]);
    }
}
