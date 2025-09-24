<?php
if (session_status() === PHP_SESSION_NONE) session_start();
// Solo administradores y operadores pueden ver el listado
if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['rol'], ['admin','operador'])) {
    $_SESSION['flash'] = 'Acceso denegado.';
    header('Location: /public/index.php');
    exit;
}
$title = 'Usuarios'; include __DIR__ . '/../partials/header.php'; ?>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Listado de Usuarios</h1>
        <?php if (isset($_SESSION['user']['rol']) && $_SESSION['user']['rol'] === 'admin'): ?>
            <a class="btn btn-success" href="index.php?r=usuario/create">Crear nuevo usuario</a>
        <?php endif; ?>
    </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover">
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
                                <a class="btn btn-sm btn-primary" href="index.php?r=usuario/show&id=<?= $usuario['id'] ?>">Ver</a>
                                <a class="btn btn-sm btn-warning" href="index.php?r=usuario/edit&id=<?= $usuario['id'] ?>">Editar</a>
                                <a class="btn btn-sm btn-danger" href="index.php?r=usuario/destroy&id=<?= $usuario['id'] ?>" onclick="return confirm('¿Eliminar?')">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

<?php include __DIR__ . '/../partials/footer.php'; ?>