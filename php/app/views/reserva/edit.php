<?php $title = 'Editar Reserva'; include __DIR__ . '/../partials/header.php'; ?>

        <h1>Editar Reserva</h1>
        <form method="post" action="index.php?r=reserva/update&id=<?= $reserva['id'] ?>" class="row g-3">
            <div class="col-md-6">
                <label class="form-label">ID Usuario</label>
                <input class="form-control" type="number" name="id_usuario" value="<?= $reserva['id_usuario'] ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">ID Rifa</label>
                <input class="form-control" type="number" name="id_rifa" value="<?= $reserva['id_rifa'] ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Estado</label>
                <select class="form-select" name="estado">
                        <option value="reservado" <?= $reserva['estado']=='reservado'?'selected':'' ?>>Reservado</option>
                        <option value="pagado" <?= $reserva['estado']=='pagado'?'selected':'' ?>>Pagado</option>
                        <option value="cancelado" <?= $reserva['estado']=='cancelado'?'selected':'' ?>>Cancelado</option>
                        <option value="expirado" <?= $reserva['estado']=='expirado'?'selected':'' ?>>Expirado</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Reservado en</label>
                <input class="form-control" type="datetime-local" name="reservado_en" value="<?= date('Y-m-d\\TH:i', strtotime($reserva['reservado_en'])) ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Expira en</label>
                <input class="form-control" type="datetime-local" name="expira_en" value="<?= date('Y-m-d\\TH:i', strtotime($reserva['expira_en'])) ?>">
            </div>
            <div class="col-12">
                <button class="btn btn-primary" type="submit">Actualizar</button>
                <a class="btn btn-link" href="index.php?r=reserva">Volver</a>
            </div>
        </form>

<?php include __DIR__ . '/../partials/footer.php'; ?>