<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
    $_SESSION['flash'] = 'Acceso denegado.';
    header('Location: /public/index.php');
    exit;
}
$title = 'Crear Usuario'; include __DIR__ . '/../partials/header.php'; ?>

    <h1>Crear Nuevo Usuario</h1>
        <form method="post" action="index.php?r=usuario/store" class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Nombre</label>
                <input class="form-control" type="text" name="nombre" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input class="form-control" type="email" name="email" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Google ID</label>
                <input class="form-control" type="text" name="google_id">
            </div>
            <div class="col-md-6">
                <label class="form-label">Avatar</label>
                <input class="form-control" type="text" name="avatar">
            </div>
            <div class="col-md-6">
                <label class="form-label">Rol</label>
                <select class="form-select" name="rol">
                    <option value="usuario">Usuario</option>
                    <option value="operador">Operador</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div class="col-12">
                <button class="btn btn-primary" type="submit">Crear</button>
                <a class="btn btn-link" href="index.php?r=usuario">Volver</a>
            </div>
        </form>

<?php include __DIR__ . '/../partials/footer.php'; ?>