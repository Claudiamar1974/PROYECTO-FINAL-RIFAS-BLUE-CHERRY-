<?php $title = 'Listado de rifas'; include __DIR__ . '/../partials/header.php'; ?>

        <div class="d-flex justify-content-between align-items-center mb-3">
                <h1>Listado de Rifas</h1>
                <a class="btn btn-success" href="index.php?r=rifa/create">Crear nueva rifa</a>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Precio</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($rifas as $rifa): ?>
                    <tr>
                        <td><?= $rifa['id'] ?></td>
                        <td><?= htmlspecialchars($rifa['titulo']) ?></td>
                        <td>$<?= number_format($rifa['precio_por_numero'], 2) ?></td>
                        <td><?= htmlspecialchars($rifa['estado']) ?></td>
                        <td>
                                <a class="btn btn-sm btn-primary" href="index.php?r=rifa/show&id=<?= $rifa['id'] ?>">Ver</a>
                                <a class="btn btn-sm btn-warning" href="index.php?r=rifa/edit&id=<?= $rifa['id'] ?>">Editar</a>
                                <a class="btn btn-sm btn-danger" href="index.php?r=rifa/destroy&id=<?= $rifa['id'] ?>" onclick="return confirm('¿Eliminar?')">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

<?php include __DIR__ . '/../partials/footer.php'; ?>