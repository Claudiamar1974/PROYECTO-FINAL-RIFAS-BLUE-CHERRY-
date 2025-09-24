<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
    echo '<p>Acceso denegado. Solo administradores.</p>';
    exit;
}
require_once '../app/models/Reserva.php';
require_once '../config/database.php';
$reservas = Reserva::all($pdo);
?>
<?php $title = 'Gestión de Reservas'; include __DIR__ . '/../../views/partials/header.php'; ?>

        <h1>Gestión de Reservas</h1>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th><th>ID Usuario</th><th>ID Rifa</th><th>Estado</th><th>Reservado en</th><th>Expira en</th><th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($reservas as $reserva): ?>
                    <tr>
                        <td><?= $reserva['id'] ?></td>
                        <td><?= $reserva['id_usuario'] ?></td>
                        <td><?= $reserva['id_rifa'] ?></td>
                        <td><?= htmlspecialchars($reserva['estado']) ?></td>
                        <td><?= $reserva['reservado_en'] ?></td>
                        <td><?= $reserva['expira_en'] ?></td>
                        <td>
                                <a class="btn btn-sm btn-danger" href="index.php?r=admin/reserva-destroy&id=<?= $reserva['id'] ?>" onclick="return confirm('¿Eliminar?')">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <a class="btn btn-link" href="index.php?r=admin/panel">Volver al panel</a>

<?php include __DIR__ . '/../../views/partials/footer.php'; ?>