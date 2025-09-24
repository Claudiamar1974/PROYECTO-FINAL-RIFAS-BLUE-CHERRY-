<?php
// Modelo de usuario
class Usuario {
    public $id;
    public $nombre;
    public $email;
    public $google_id;
    public $avatar;
    public $rol;
    public $creado_en;
    public $actualizado_en;

    public static function all($pdo) {
        $stmt = $pdo->query('SELECT * FROM usuarios');
        return $stmt->fetchAll();
    }

    public static function find($pdo, $id) {
        $stmt = $pdo->prepare('SELECT * FROM usuarios WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function create($pdo, $data) {
        $stmt = $pdo->prepare('INSERT INTO usuarios (nombre, email, google_id, avatar, rol, creado_en, actualizado_en) VALUES (?, ?, ?, ?, ?, NOW(), NOW())');
        return $stmt->execute([
            $data['nombre'], $data['email'], $data['google_id'], $data['avatar'], $data['rol']
        ]);
    }

    public static function update($pdo, $id, $data) {
        $stmt = $pdo->prepare('UPDATE usuarios SET nombre=?, email=?, google_id=?, avatar=?, rol=?, actualizado_en=NOW() WHERE id=?');
        return $stmt->execute([
            $data['nombre'], $data['email'], $data['google_id'], $data['avatar'], $data['rol'], $id
        ]);
    }

    public static function delete($pdo, $id) {
        $stmt = $pdo->prepare('DELETE FROM usuarios WHERE id=?');
        return $stmt->execute([$id]);
    }
}
