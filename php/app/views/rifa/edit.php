<?php $title = 'Editar Rifa'; include __DIR__ . '/../partials/header.php'; ?>

        <h1>Editar Rifa</h1>
        <form method="post" action="index.php?r=rifa/update&id=<?= $rifa['id'] ?>" class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Título</label>
                <input class="form-control" type="text" name="titulo" value="<?= htmlspecialchars($rifa['titulo']) ?>" required>
            </div>
            <div class="col-12">
                <label class="form-label">Descripción</label>
                <textarea class="form-control" name="descripcion"><?= htmlspecialchars($rifa['descripcion']) ?></textarea>
            </div>
            <div class="col-md-4">
                <label class="form-label">Precio por número</label>
                <input class="form-control" type="number" step="0.01" name="precio_por_numero" value="<?= $rifa['precio_por_numero'] ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Total de números</label>
                <input class="form-control" type="number" name="total_numeros" value="<?= $rifa['total_numeros'] ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Estado</label>
                <select class="form-select" name="estado">
                    <option value="activa" <?= $rifa['estado']=='activa'?'selected':'' ?>>Activa</option>
                    <option value="finalizada" <?= $rifa['estado']=='finalizada'?'selected':'' ?>>Finalizada</option>
                    <option value="cancelada" <?= $rifa['estado']=='cancelada'?'selected':'' ?>>Cancelada</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Fecha inicio</label>
                <input class="form-control" type="datetime-local" name="fecha_inicio" value="<?= date('Y-m-d\TH:i', strtotime($rifa['fecha_inicio'])) ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Fecha fin</label>
                <input class="form-control" type="datetime-local" name="fecha_fin" value="<?= date('Y-m-d\TH:i', strtotime($rifa['fecha_fin'])) ?>">
            </div>
            <div class="col-12">
                <button class="btn btn-primary" type="submit">Actualizar</button>
                <a class="btn btn-link" href="index.php?r=rifa">Volver</a>
            </div>
        </form>

<?php include __DIR__ . '/../partials/footer.php'; ?>