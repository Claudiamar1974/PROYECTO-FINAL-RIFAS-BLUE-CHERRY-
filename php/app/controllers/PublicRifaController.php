<?php
require_once '../app/models/Rifa.php';
require_once '../config/database.php';
require_once __DIR__ . '/../utils/liberar_reservas.php';
require_once __DIR__ . '/../utils/tiempo.php';

class PublicRifaController {
    public function index() {
        global $pdo;
        // Tomar el parámetro de búsqueda q desde GET si existe
        $q = isset($_GET['q']) ? trim($_GET['q']) : null;
        $rifas = Rifa::all($pdo, $q);
        require '../app/views/public_rifa/index.php';
    }

    public function show($id) {
        global $pdo;
        $rifa = Rifa::find($pdo, $id);
        // Intentar liberar reservas expiradas antes de listar números (lazy cleanup)
        try {
            $now = ahoraGoogleFormato('Y-m-d H:i:s', 'America/Montevideo');
            liberarReservasExpiradas($pdo, $now);
        } catch (Exception $e) {
            // no bloquear la vista si falla la limpieza
        }
        // Obtener números disponibles
        $stmt = $pdo->prepare('SELECT * FROM numeros_rifa WHERE id_rifa = ?');
        $stmt->execute([$id]);
        $numeros = $stmt->fetchAll();
        require '../app/views/public_rifa/show.php';
    }
}
