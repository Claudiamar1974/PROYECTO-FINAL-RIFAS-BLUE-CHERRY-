<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
    echo '<p>Acceso denegado. Solo administradores.</p>';
    exit;
}
require_once '../app/models/Usuario.php';
require_once '../config/database.php';
$id = $_GET['id'];
$usuario = Usuario::find($pdo, $id);
?>
<?php $title = 'Editar Usuario'; include __DIR__ . '/../../views/partials/header.php'; ?>

        <h1>Editar Usuario</h1>
        <form method="post" action="index.php?r=admin/usuario-update&id=<?= $usuario['id'] ?>" class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Nombre</label>
                <input class="form-control" type="text" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input class="form-control" type="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Google ID</label>
                <input class="form-control" type="text" name="google_id" value="<?= htmlspecialchars($usuario['google_id']) ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Avatar</label>
                <input class="form-control" type="text" name="avatar" value="<?= htmlspecialchars($usuario['avatar']) ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Rol</label>
                <select class="form-select" name="rol">
                    <option value="usuario" <?= $usuario['rol']=='usuario'?'selected':'' ?>>Usuario</option>
                    <option value="operador" <?= $usuario['rol']=='operador'?'selected':'' ?>>Operador</option>
                    <option value="admin" <?= $usuario['rol']=='admin'?'selected':'' ?>>Admin</option>
                </select>
            </div>
            <div class="col-12">
                <button class="btn btn-primary" type="submit">Actualizar</button>
                <a class="btn btn-link" href="index.php?r=admin/usuarios">Volver</a>
            </div>
        </form>

<?php include __DIR__ . '/../../views/partials/footer.php'; ?>