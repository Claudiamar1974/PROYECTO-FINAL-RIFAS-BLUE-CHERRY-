<?php $title = htmlspecialchars($rifa['titulo']) . ' - Rifa'; include __DIR__ . '/../partials/header.php'; ?>

        <div class="card">
            <div class="card-body">
                <h2 class="card-title"><?= htmlspecialchars($rifa['titulo']) ?></h2>
                <p class="card-text"><strong>Descripción:</strong> <?= htmlspecialchars($rifa['descripcion']) ?></p>
                <ul class="list-group list-group-flush mb-3">
                    <li class="list-group-item"><strong>Precio por número:</strong> $<?= number_format($rifa['precio_por_numero'], 2) ?></li>
                    <li class="list-group-item"><strong>Total de números:</strong> <?= $rifa['total_numeros'] ?></li>
                    <li class="list-group-item"><strong>Estado:</strong> <?= htmlspecialchars($rifa['estado']) ?></li>
                </ul>
                <a class="btn btn-secondary" href="index.php?r=rifa">Volver</a>
            </div>
        </div>

<?php include __DIR__ . '/../partials/footer.php'; ?>