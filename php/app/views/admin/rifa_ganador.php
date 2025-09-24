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
// Buscar números vendidos
$stmt = $pdo->prepare('SELECT * FROM numeros_rifa WHERE id_rifa=? AND estado="vendido"');
$stmt->execute([$id]);
$vendidos = $stmt->fetchAll();
?>
<?php $title = 'Asignar Ganador'; include __DIR__ . '/../../views/partials/header.php'; ?>

    <h1>Asignar Ganador a "<?= htmlspecialchars($rifa['titulo']) ?>"</h1>
    <form method="post" action="index.php?r=admin/rifa-ganador&id=<?= $rifa['id'] ?>" class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Número ganador</label>
        <select class="form-select" name="id_ganador" required>
            <option value="">Selecciona...</option>
            <?php foreach($vendidos as $num): ?>
            <option value="<?= $num['id'] ?>">N° <?= $num['numero'] ?></option>
            <?php endforeach; ?>
        </select>
      </div>
      <div class="col-12">
        <button class="btn btn-primary" type="submit">Asignar</button>
        <a class="btn btn-link" href="index.php?r=admin/panel">Volver</a>
      </div>
    </form>

<?php include __DIR__ . '/../../views/partials/footer.php'; ?>