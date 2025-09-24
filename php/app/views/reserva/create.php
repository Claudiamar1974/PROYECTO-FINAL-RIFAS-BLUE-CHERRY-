<?php $title = 'Crear Reserva'; include __DIR__ . '/../partials/header.php'; ?>

        <h1>Crear Reserva</h1>
        <form method="post" action="index.php?r=reserva/store" class="row g-3">
            <div class="col-md-6">
                <label class="form-label">ID Usuario</label>
                <input class="form-control" type="number" name="id_usuario" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">ID Rifa</label>
                <input class="form-control" type="number" name="id_rifa" required>
            </div>
            <div class="col-12">
                <label class="form-label">Números (JSON)</label>
                <input class="form-control" type="text" name="numeros">
            </div>
            <div class="col-12">
                <button class="btn btn-primary" type="submit">Crear</button>
                <a class="btn btn-link" href="index.php?r=reserva">Volver</a>
            </div>
        </form>

<?php include __DIR__ . '/../partials/footer.php'; ?>
        <label>Reservado en: <input type="datetime-local" name="reservado_en"></label><br>
        <label>Expira en: <input type="datetime-local" name="expira_en"></label><br>
        <button type="submit">Crear</button>
    </form>
    <a href="index.php?r=reserva">Volver</a>
</body>
</html>