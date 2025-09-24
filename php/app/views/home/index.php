<?php $title = 'Inicio'; include __DIR__ . '/../partials/header.php'; ?>

<div class="site-hero">
    <div class="container d-flex flex-column flex-md-row align-items-center justify-content-between">
        <div>
            <h1 class="display-6">Encuentra tus rifas favoritas</h1>
            <p class="lead">Participa en sorteos y apoya a iniciativas locales. Compra números y espera ser el ganador.</p>
            <a class="btn btn-dark" href="/public/index.php?r=publicrifa">Ver rifas</a>
        </div>
        <div class="d-none d-md-block text-end">
            <img src="/public/img/hero-rifa.png" alt="rifa" style="max-height:140px;">
        </div>
    </div>
</div>

<!-- Aquí se mostrará contenido dinámico con rifas si hay datos en la base. -->
<div class="row g-4" id="rifas-container">
    <!-- Contenido cargado dinámicamente desde la BD -->
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>