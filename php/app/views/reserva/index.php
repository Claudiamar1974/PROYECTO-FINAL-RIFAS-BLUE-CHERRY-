<?php $title = 'Reservas'; include __DIR__ . '/../partials/header.php'; ?>

        <div class="d-flex justify-content-between align-items-center mb-3">
                <h1>Listado de Reservas</h1>
                <a class="btn btn-success" href="index.php?r=reserva/create">Crear nueva reserva</a>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th><th>ID Usuario</th><th>ID Rifa</th><th>Estado</th><th>Reservado en</th><th>Expira en</th><th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($reservas as $reserva): ?>
                    <tr>
                        <td><?= $reserva['id'] ?></td>
                        <td><?= $reserva['id_usuario'] ?></td>
                        <td><?= $reserva['id_rifa'] ?></td>
                        <td><?= htmlspecialchars($reserva['estado']) ?></td>
                        <td><?= $reserva['reservado_en'] ?></td>
                        <td><?= $reserva['expira_en'] ?></td>
                        <td>
                                <a class="btn btn-sm btn-primary" href="index.php?r=reserva/show&id=<?= $reserva['id'] ?>">Ver</a>
                                <a class="btn btn-sm btn-warning" href="index.php?r=reserva/edit&id=<?= $reserva['id'] ?>">Editar</a>
                                <a class="btn btn-sm btn-danger" href="index.php?r=reserva/destroy&id=<?= $reserva['id'] ?>" onclick="return confirm('¿Eliminar?')">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

<?php include __DIR__ . '/../partials/footer.php'; ?>