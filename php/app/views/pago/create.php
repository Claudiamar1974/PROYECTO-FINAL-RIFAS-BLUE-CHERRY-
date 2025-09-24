<?php $title = 'Registrar Pago'; include __DIR__ . '/../partials/header.php'; ?>

        <h1>Registrar Nuevo Pago</h1>
        <form method="post" action="index.php?r=pago/store" class="row g-3">
            <div class="col-md-6">
                <label class="form-label">ID Reserva</label>
                <input class="form-control" type="number" name="id_reserva" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">ID Pago MP</label>
                <input class="form-control" type="text" name="id_pago_mp">
            </div>
            <div class="col-md-6">
                <label class="form-label">Estado</label>
                <select class="form-select" name="estado">
                    <option value="pendiente">Pendiente</option>
                    <option value="aprobado">Aprobado</option>
                    <option value="rechazado">Rechazado</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Monto</label>
                <input class="form-control" type="number" step="0.01" name="monto" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Método de pago</label>
                <input class="form-control" type="text" name="metodo_pago">
            </div>
            <div class="col-md-6">
                <label class="form-label">Pagado en</label>
                <input class="form-control" type="datetime-local" name="pagado_en">
            </div>
            <div class="col-12">
                <button class="btn btn-primary" type="submit">Registrar</button>
                <a class="btn btn-link" href="index.php?r=pago">Volver</a>
            </div>
        </form>

<?php include __DIR__ . '/../partials/footer.php'; ?>