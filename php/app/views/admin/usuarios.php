<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
    echo '<p>Acceso denegado. Solo administradores.</p>';
    exit;
}
require_once '../app/models/Usuario.php';
require_once '../config/database.php';
$usuarios = Usuario::all($pdo);
?>
<?php $title = 'Gestión de usuarios'; include __DIR__ . '/../partials/header.php'; ?>

        <div class="d-flex justify-content-between align-items-center mb-3">
                <h1>Usuarios</h1>
                <a class="btn btn-success" href="index.php?r=usuario/create">Crear usuario</a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>ID</th><th>Nombre</th><th>Email</th><th>Rol</th><th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($usuarios as $usuario): ?>
                    <tr>
                        <td><?= $usuario['id'] ?></td>
                        <td><?= htmlspecialchars($usuario['nombre']) ?></td>
                        <td><?= htmlspecialchars($usuario['email']) ?></td>
                        <td><?= htmlspecialchars($usuario['rol']) ?></td>
                        <td>
                                <a class="btn btn-sm btn-warning" href="index.php?r=admin/usuario-edit&id=<?= $usuario['id'] ?>">Editar</a>
                                <a class="btn btn-sm btn-danger" href="index.php?r=admin/usuario-destroy&id=<?= $usuario['id'] ?>" onclick="return confirm('¿Eliminar?')">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <a class="btn btn-link" href="index.php?r=admin/panel">Volver al panel</a>

<?php include __DIR__ . '/../partials/footer.php'; ?>