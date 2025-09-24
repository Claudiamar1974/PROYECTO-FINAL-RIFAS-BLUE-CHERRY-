<!-- Formulario crear rifa -->
<?php $title = 'Crear Rifa'; include __DIR__ . '/../partials/header.php'; ?>

        <h1>Crear Rifa</h1>
        <form method="post" action="index.php?r=rifa/store" class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Título</label>
                <input class="form-control" type="text" name="titulo" required>
            </div>
            <div class="col-12">
                <label class="form-label">Descripción</label>
                <textarea class="form-control" name="descripcion"></textarea>
            </div>
            <div class="col-md-4">
                <label class="form-label">Precio por número</label>
                <input class="form-control" type="number" step="0.01" name="precio_por_numero">
            </div>
            <div class="col-md-4">
                <label class="form-label">Total de números</label>
                <input class="form-control" type="number" name="total_numeros">
            </div>
            <div class="col-12">
                <button class="btn btn-primary" type="submit">Crear</button>
                <a class="btn btn-link" href="index.php?r=rifa">Volver</a>
            </div>
        </form>

<?php include __DIR__ . '/../partials/footer.php'; ?>
            <option value="cancelada">Cancelada</option>
        </select></label><br>
        <button type="submit">Crear</button>
    </form>
    <a href="index.php?r=rifa">Volver</a>
</body>
</html>