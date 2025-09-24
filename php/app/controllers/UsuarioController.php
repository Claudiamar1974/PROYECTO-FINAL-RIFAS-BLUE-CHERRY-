<?php
require_once '../app/models/Usuario.php';
require_once '../config/database.php';

// Controlador de usuario
class UsuarioController {
    public function index() {
        global $pdo;
        $usuarios = Usuario::all($pdo);
        require '../app/views/usuario/index.php';
    }

    public function show($id) {
        global $pdo;
        $usuario = Usuario::find($pdo, $id);

        // Calcular total de reservas del usuario
        $stmt = $pdo->prepare('SELECT COUNT(*) as total FROM reservas WHERE id_usuario = ?');
        $stmt->execute([$id]);
        $totalReservas = (int) ($stmt->fetchColumn() ?? 0);

        // Calcular balance (suma de pagos asociados a las reservas del usuario)
        $stmt2 = $pdo->prepare('SELECT COALESCE(SUM(p.monto), 0) as balance FROM pagos p JOIN reservas r ON p.id_reserva = r.id WHERE r.id_usuario = ? AND p.estado = "aprobado"');
        $stmt2->execute([$id]);
        $balance = (float) ($stmt2->fetchColumn() ?? 0);

        require '../app/views/usuario/show.php';
    }

    public function create() {
        require '../app/views/usuario/create.php';
    }

    public function store($data) {
        global $pdo;
        Usuario::create($pdo, $data);
        header('Location: /public/index.php?r=usuario');
    }

    public function edit($id) {
        global $pdo;
        $usuario = Usuario::find($pdo, $id);
        require '../app/views/usuario/edit.php';
    }

    public function update($id, $data) {
        global $pdo;
        Usuario::update($pdo, $id, $data);
        header('Location: /public/index.php?r=usuario');
    }

    public function destroy($id) {
        global $pdo;
        Usuario::delete($pdo, $id);
        header('Location: /public/index.php?r=usuario');
    }

    public function perfil() {
        // Lógica para mostrar el perfil de usuario
        require '../app/views/usuario/perfil.php';
    }
}
