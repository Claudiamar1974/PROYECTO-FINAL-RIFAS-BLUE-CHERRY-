<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
    echo '<p>Acceso denegado. Solo administradores.</p>';
    exit;
}
require_once '../app/models/Rifa.php';
require_once '../config/database.php';
$id = $_GET['id'];
$rifa = Rifa::find($pdo, $id);

// Obtener info del ganador si existe
$ganador = null;
if ($rifa['id_ganador']) {
    $stmt = $pdo->prepare('SELECT n.numero, u.nombre, u.email FROM numeros_rifa n LEFT JOIN reservas r ON n.id_reserva = r.id LEFT JOIN usuarios u ON r.id_usuario = u.id WHERE n.id = ?');
    $stmt->execute([$rifa['id_ganador']]);
    $ganador = $stmt->fetch();
}
?>
<?php $title = 'Ganador de la Rifa'; include __DIR__ . '/../../views/partials/header.php'; ?>

        <h1>Ganador de "<?= htmlspecialchars($rifa['titulo']) ?>"</h1>
        <?php if ($ganador): ?>
            <ul class="list-group">
                <li class="list-group-item"><strong>Número ganador:</strong> <?= $ganador['numero'] ?></li>
                <li class="list-group-item"><strong>Nombre:</strong> <?= htmlspecialchars($ganador['nombre']) ?></li>
                <li class="list-group-item"><strong>Email:</strong> <?= htmlspecialchars($ganador['email']) ?></li>
            </ul>
        <?php else: ?>
            <div class="alert alert-warning">No se ha asignado ganador aún.</div>
        <?php endif; ?>
        <a class="btn btn-link" href="index.php?r=admin/panel">Volver</a>

<?php include __DIR__ . '/../../views/partials/footer.php'; ?>