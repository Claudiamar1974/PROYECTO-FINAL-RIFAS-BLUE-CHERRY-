<?php $title = 'Reserva #' . $reserva['id']; include __DIR__ . '/../partials/header.php'; ?>

        <div class="card">
            <div class="card-body">
                <h2 class="card-title">Reserva #<?= $reserva['id'] ?></h2>
                <p class="card-text"><strong>ID Usuario:</strong> <?= $reserva['id_usuario'] ?></p>
                <p class="card-text"><strong>ID Rifa:</strong> <?= $reserva['id_rifa'] ?></p>
                <p class="card-text"><strong>Estado:</strong> <?= htmlspecialchars($reserva['estado']) ?></p>
                <p class="card-text"><strong>Reservado en:</strong> <?= $reserva['reservado_en'] ?></p>
                <p class="card-text"><strong>Expira en:</strong> <?= $reserva['expira_en'] ?></p>
                <a class="btn btn-secondary" href="index.php?r=reserva">Volver</a>
            </div>
        </div>

<?php include __DIR__ . '/../partials/footer.php'; ?>