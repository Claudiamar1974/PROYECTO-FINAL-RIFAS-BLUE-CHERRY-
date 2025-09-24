<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
    echo '<p>Acceso denegado. Solo administradores.</p>';
    exit;
}
?>
<?php $title = 'Crear Rifa'; include __DIR__ . '/../../views/partials/header.php'; ?>

        <h1>Crear Nueva Rifa</h1>
    <form method="post" action="index.php?r=admin/rifa-store" class="row g-3" enctype="multipart/form-data">
            <div class="col-md-6">
                <label class="form-label">Título</label>
                <input class="form-control" type="text" name="titulo" required>
            </div>
            <div class="col-12">
                <label class="form-label">Descripción</label>
                <textarea class="form-control" name="descripcion"></textarea>
            </div>
            <div class="col-md-4">
                <label class="form-label">Precio por número</label>
                <input class="form-control" type="number" step="0.01" name="precio_por_numero" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Total de números</label>
                <input class="form-control" type="number" name="total_numeros" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Estado</label>
                <select class="form-select" name="estado">
                    <option value="activa">Activa</option>
                    <option value="finalizada">Finalizada</option>
                    <option value="cancelada">Cancelada</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Fecha inicio</label>
                <input class="form-control" type="datetime-local" name="fecha_inicio">
            </div>
            <div class="col-md-6">
                <label class="form-label">Fecha fin</label>
                <input class="form-control" type="datetime-local" name="fecha_fin">
            </div>
            <div class="col-md-6">
                <label class="form-label">Imagen (jpg, png, max 2MB)</label>
                <input class="form-control" type="file" name="imagen" accept="image/png, image/jpeg">
            </div>
            <div class="col-12">
                <button class="btn btn-primary" type="submit">Crear</button>
                <a class="btn btn-link" href="index.php?r=admin/panel">Volver</a>
            </div>
        </form>

<?php include __DIR__ . '/../../views/partials/footer.php'; ?>