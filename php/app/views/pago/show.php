<?php $title = 'Pago #' . $pago['id']; include __DIR__ . '/../partials/header.php'; ?>

        <div class="card">
            <div class="card-body">
                <h2 class="card-title">Pago #<?= $pago['id'] ?></h2>
                <p class="card-text"><strong>ID Reserva:</strong> <?= $pago['id_reserva'] ?></p>
                <p class="card-text"><strong>ID Pago MP:</strong> <?= htmlspecialchars($pago['id_pago_mp']) ?></p>
                <p class="card-text"><strong>Estado:</strong> <?= htmlspecialchars($pago['estado']) ?></p>
                <p class="card-text"><strong>Monto:</strong> <?= $pago['monto'] ?></p>
                <p class="card-text"><strong>Método de pago:</strong> <?= htmlspecialchars($pago['metodo_pago']) ?></p>
                <p class="card-text"><strong>Pagado en:</strong> <?= $pago['pagado_en'] ?></p>
                <a class="btn btn-secondary" href="index.php?r=pago">Volver</a>
            </div>
        </div>

<?php include __DIR__ . '/../partials/footer.php'; ?>