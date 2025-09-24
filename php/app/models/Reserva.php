<?php
// Modelo de reserva
class Reserva {
    public $id;
    public $id_usuario;
    public $id_rifa;
    public $estado;
    public $reservado_en;
    public $expira_en;
    public $creado_en;
    public $actualizado_en;

    public static function all($pdo) {
        $stmt = $pdo->query('SELECT * FROM reservas');
        return $stmt->fetchAll();
    }

    public static function find($pdo, $id) {
        $stmt = $pdo->prepare('SELECT * FROM reservas WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function create($pdo, $data) {
        $stmt = $pdo->prepare('INSERT INTO reservas (id_usuario, id_rifa, estado, reservado_en, expira_en, creado_en, actualizado_en) VALUES (?, ?, ?, ?, ?, NOW(), NOW())');
        return $stmt->execute([
            $data['id_usuario'], $data['id_rifa'], $data['estado'], $data['reservado_en'], $data['expira_en']
        ]);
    }

    public static function update($pdo, $id, $data) {
        $stmt = $pdo->prepare('UPDATE reservas SET id_usuario=?, id_rifa=?, estado=?, reservado_en=?, expira_en=?, actualizado_en=NOW() WHERE id=?');
        return $stmt->execute([
            $data['id_usuario'], $data['id_rifa'], $data['estado'], $data['reservado_en'], $data['expira_en'], $id
        ]);
    }

    public static function delete($pdo, $id) {
        $stmt = $pdo->prepare('DELETE FROM reservas WHERE id=?');
        return $stmt->execute([$id]);
    }
}
