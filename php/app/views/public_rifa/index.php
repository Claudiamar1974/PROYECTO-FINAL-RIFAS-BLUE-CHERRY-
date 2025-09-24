<?php $title = 'Rifas disponibles'; include __DIR__ . '/../partials/header.php'; ?>

        <h1>Rifas disponibles</h1>
        <div class="row">
            <?php foreach($rifas as $rifa): ?>
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <?php if (!empty($rifa['imagen'])): ?>
                            <img src="/public/img/rifas/<?= htmlspecialchars($rifa['imagen']) ?>" class="card-img-top" alt="<?= htmlspecialchars($rifa['titulo']) ?>">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($rifa['titulo']) ?></h5>
                            <p class="card-text">$<?= number_format($rifa['precio_por_numero'], 2) ?></p>
                            <a href="index.php?r=publicrifa/show&id=<?= $rifa['id'] ?>" class="btn btn-primary">Ver</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

<?php include __DIR__ . '/../partials/footer.php'; ?>