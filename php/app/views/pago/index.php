<?php $title = 'Pagos'; include __DIR__ . '/../partials/header.php'; ?>

        <div class="d-flex justify-content-between align-items-center mb-3">
                <h1>Listado de Pagos</h1>
                <a class="btn btn-success" href="index.php?r=pago/create">Registrar nuevo pago</a>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th><th>ID Reserva</th><th>ID Pago MP</th><th>Estado</th><th>Monto</th><th>Método</th><th>Pagado en</th><th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($pagos as $pago): ?>
                    <tr>
                        <td><?= $pago['id'] ?></td>
                        <td><?= $pago['id_reserva'] ?></td>
                        <td><?= htmlspecialchars($pago['id_pago_mp']) ?></td>
                        <td><?= htmlspecialchars($pago['estado']) ?></td>
                        <td><?= $pago['monto'] ?></td>
                        <td><?= htmlspecialchars($pago['metodo_pago']) ?></td>
                        <td><?= $pago['pagado_en'] ?></td>
                        <td>
                                <a class="btn btn-sm btn-primary" href="index.php?r=pago/show&id=<?= $pago['id'] ?>">Ver</a>
                                <a class="btn btn-sm btn-warning" href="index.php?r=pago/edit&id=<?= $pago['id'] ?>">Editar</a>
                                <a class="btn btn-sm btn-danger" href="index.php?r=pago/destroy&id=<?= $pago['id'] ?>" onclick="return confirm('¿Eliminar?')">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

<?php include __DIR__ . '/../partials/footer.php'; ?>