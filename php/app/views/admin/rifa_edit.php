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
?>
<?php $title = 'Editar Rifa'; include __DIR__ . '/../../views/partials/header.php'; ?>

        <h1>Editar Rifa</h1>
    <form method="post" action="index.php?r=admin/rifa-update&id=<?= $rifa['id'] ?>" class="row g-3" enctype="multipart/form-data">
            <div class="col-md-6">
                <label class="form-label">Título</label>
                <input class="form-control" type="text" name="titulo" value="<?= htmlspecialchars($rifa['titulo']) ?>" required>
            </div>
            <div class="col-12">
                <label class="form-label">Descripción</label>
                <textarea class="form-control" name="descripcion"><?= htmlspecialchars($rifa['descripcion']) ?></textarea>
            </div>
            <div class="col-md-4">
                <label class="form-label">Precio por número</label>
                <input class="form-control" type="number" step="0.01" name="precio_por_numero" value="<?= $rifa['precio_por_numero'] ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Total de números</label>
                <input class="form-control" type="number" name="total_numeros" value="<?= $rifa['total_numeros'] ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Estado</label>
                <select class="form-select" name="estado">
                    <option value="activa" <?= $rifa['estado']=='activa'?'selected':'' ?>>Activa</option>
                    <option value="finalizada" <?= $rifa['estado']=='finalizada'?'selected':'' ?>>Finalizada</option>
                    <option value="cancelada" <?= $rifa['estado']=='cancelada'?'selected':'' ?>>Cancelada</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Fecha inicio</label>
                <input class="form-control" type="datetime-local" name="fecha_inicio" value="<?= date('Y-m-d\\TH:i', strtotime($rifa['fecha_inicio'])) ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Fecha fin</label>
                <input class="form-control" type="datetime-local" name="fecha_fin" value="<?= date('Y-m-d\\TH:i', strtotime($rifa['fecha_fin'])) ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Imagen (jpg, png, max 2MB) — dejar vacío para no cambiar</label>
                <input class="form-control" type="file" name="imagen" accept="image/png, image/jpeg">
            </div>
            <div class="col-12">
                <button class="btn btn-primary" type="submit">Actualizar</button>
                <a class="btn btn-link" href="index.php?r=admin/panel">Volver</a>
            </div>
        </form>

        <?php if (!empty($rifa['imagen'])): ?>
            <div class="mt-3">
                <label class="form-label">Imagen actual</label>
                <div>
                    <img src="/public/img/rifas/<?= htmlspecialchars($rifa['imagen']) ?>" alt="Imagen rifa" style="max-width:240px;height:auto;" />
                </div>
            </div>
        <?php endif; ?>

<?php include __DIR__ . '/../../views/partials/footer.php'; ?>