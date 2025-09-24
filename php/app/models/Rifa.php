<?php
// Modelo de rifa
class Rifa {
    public $id;
    public $titulo;
    public $descripcion;
    public $imagen;
    public $precio_por_numero;
    public $total_numeros;
    public $fecha_inicio;
    public $fecha_fin;
    public $estado;
    public $id_ganador;
    public $creado_en;
    public $actualizado_en;

    public static function all($pdo, $q = null) {
        // Si se proporciona q, filtrar por título o descripción (búsqueda simple con LIKE)
        if ($q !== null && trim($q) !== '') {
            $like = '%' . str_replace('%', '\\%', $q) . '%';
            $stmt = $pdo->prepare('SELECT * FROM rifas WHERE titulo LIKE ? OR descripcion LIKE ? ORDER BY creado_en DESC');
            $stmt->execute([$like, $like]);
            return $stmt->fetchAll();
        }

        $stmt = $pdo->query('SELECT * FROM rifas ORDER BY creado_en DESC');
        return $stmt->fetchAll();
    }

    public static function find($pdo, $id) {
        $stmt = $pdo->prepare('SELECT * FROM rifas WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function create($pdo, $data) {
        $stmt = $pdo->prepare('INSERT INTO rifas (titulo, descripcion, imagen, precio_por_numero, total_numeros, fecha_inicio, fecha_fin, estado, creado_en, actualizado_en) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())');
        return $stmt->execute([
            $data['titulo'], $data['descripcion'], $data['imagen'] ?? null, $data['precio_por_numero'], $data['total_numeros'],
            $data['fecha_inicio'], $data['fecha_fin'], $data['estado']
        ]);
    }

    public static function update($pdo, $id, $data) {
        // Si viene imagen en data, actualizarla; si no, no tocar la columna imagen
        if (!empty($data['imagen'])) {
            $stmt = $pdo->prepare('UPDATE rifas SET titulo=?, descripcion=?, imagen=?, precio_por_numero=?, total_numeros=?, fecha_inicio=?, fecha_fin=?, estado=?, actualizado_en=NOW() WHERE id=?');
            return $stmt->execute([
                $data['titulo'], $data['descripcion'], $data['imagen'], $data['precio_por_numero'], $data['total_numeros'],
                $data['fecha_inicio'], $data['fecha_fin'], $data['estado'], $id
            ]);
        } else {
            $stmt = $pdo->prepare('UPDATE rifas SET titulo=?, descripcion=?, precio_por_numero=?, total_numeros=?, fecha_inicio=?, fecha_fin=?, estado=?, actualizado_en=NOW() WHERE id=?');
            return $stmt->execute([
                $data['titulo'], $data['descripcion'], $data['precio_por_numero'], $data['total_numeros'],
                $data['fecha_inicio'], $data['fecha_fin'], $data['estado'], $id
            ]);
        }
    }

    public static function delete($pdo, $id) {
        $stmt = $pdo->prepare('DELETE FROM rifas WHERE id=?');
        return $stmt->execute([$id]);
    }
}
