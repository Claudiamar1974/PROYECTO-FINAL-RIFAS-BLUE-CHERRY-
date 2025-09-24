<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['rol'], ['admin','operador'])) {
    echo '<p>Acceso denegado. Solo administradores u operadores.</p>';
    exit;
}

// Rutas corregidas usando __DIR__ para evitar problemas de include relativos
require_once __DIR__ . '/../../models/Rifa.php';
require_once __DIR__ . '/../../../config/database.php';
$rifas = Rifa::all($pdo);
?>
<?php $title = 'Panel de administración'; include __DIR__ . '/../partials/header.php'; ?>

    <h1>Panel de administración</h1>

    <div class="row">
        <aside class="col-md-3 mb-4">
            <div class="list-group">
                <?php if ($_SESSION['user']['rol'] === 'admin'): ?>
                    <a class="list-group-item list-group-item-action" href="/public/index.php?r=admin/usuarios">Usuarios</a>
                <?php endif; ?>
                <a class="list-group-item list-group-item-action" href="/public/index.php?r=admin/reservas">Reservas</a>
                <?php if ($_SESSION['user']['rol'] === 'admin'): ?>
                    <a class="list-group-item list-group-item-action" href="/public/index.php?r=admin/rifa-create">Crear rifa</a>
                <?php endif; ?>
            </div>
        </aside>

        <section class="col-md-9">
            <div id="rifas">
                <h2>Gestionar Rifas</h2>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr><th>ID</th><th>Imagen</th><th>Título</th><th>Estado</th><th>Creado</th><th>Acciones</th></tr>
                        </thead>
                        <tbody>
                        <?php foreach ($rifas as $r): ?>
                            <tr>
                                <td><?= $r['id'] ?></td>
                                <td>
                                    <?php if (!empty($r['imagen'])): ?>
                                        <img src="/public/img/rifas/<?= htmlspecialchars($r['imagen']) ?>" alt="img" style="width:64px;height:auto;">
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($r['titulo']) ?></td>
                                <td><?= htmlspecialchars($r['estado']) ?></td>
                                <td><?= htmlspecialchars($r['creado_en']) ?></td>
                                <td>
                                    <a class="btn btn-sm btn-primary" href="/public/index.php?r=rifa/show&id=<?= $r['id'] ?>">Ver</a>
                                    <a class="btn btn-sm btn-warning" href="/public/index.php?r=rifa/edit&id=<?= $r['id'] ?>">Editar</a>
                                    <a class="btn btn-sm btn-danger" href="/public/index.php?r=rifa/destroy&id=<?= $r['id'] ?>" onclick="return confirm('¿Eliminar rifa?')">Eliminar</a>
                                    <a class="btn btn-sm btn-info" href="/public/index.php?r=admin/rifa-ganador&id=<?= $r['id'] ?>">Ganador</a>
                                    <a class="btn btn-sm btn-success" href="/public/index.php?r=admin/rifa-generar-ganador&id=<?= $r['id'] ?>" onclick="return confirm('¿Generar ganador aleatorio para esta rifa?')">Generar ganador</a>
                                    <a class="btn btn-sm btn-secondary" href="/public/index.php?r=admin/rifa-generar-ganador&id=<?= $r['id'] ?>&mode=bycount" onclick="return confirm('¿Generar ganador aleatorio entre todos los números (1..total)? Esto puede elegir un número no vendido.')">Generar (1..total)</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>

<?php include __DIR__ . '/../partials/footer.php'; ?>