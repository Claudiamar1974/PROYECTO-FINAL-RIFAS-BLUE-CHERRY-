<?php
// Header parcial con Bootstrap 5 CDN
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= isset($title) ? htmlspecialchars($title) : 'Mi aplicación' ?></title>
  <!-- Favicon -->
  <link rel="icon" type="image/png" href="https://tienda.blisoft.com.uy/wp-content/uploads/2025/09/log-plano-17.png">
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Hoja de estilos local -->
    <link rel="stylesheet" href="/public/css/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="/public/index.php">
      <img src="https://tienda.blisoft.com.uy/wp-content/uploads/2025/09/log-plano-17.png" alt="Logo" style="height:36px;margin-right:8px;"> 
      <span class="fw-bold">Rifas</span>
    </a>

    <div class="d-none d-lg-flex flex-grow-1 mx-4">
      <form class="w-100" action="/public/index.php" method="get">
        <input type="hidden" name="r" value="publicrifa">
        <div class="input-group">
          <input class="form-control" type="search" placeholder="Buscar rifas, títulos, códigos..." aria-label="Buscar" name="q">
          <button class="btn btn-dark" type="submit">Buscar</button>
        </div>
      </form>
    </div>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarMain">
      <ul class="navbar-nav ms-auto align-items-lg-center">
        <li class="nav-item me-2 d-lg-none">
          <form action="/public/index.php" method="get" class="d-flex">
            <input type="hidden" name="r" value="publicrifa">
            <input class="form-control" type="search" placeholder="Buscar" name="q">
            <button class="btn btn-dark ms-2" type="submit">Ok</button>
          </form>
        </li>
        <li class="nav-item me-2"><a class="nav-link text-white" href="/public/index.php">Inicio</a></li>
    <li class="nav-item me-2"><a class="nav-link text-white" href="/public/index.php?r=publicrifa">Rifas</a></li>
    <?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
    <!-- El enlace 'Mi cuenta' se muestra sólo dentro del dropdown del usuario -->
    <?php if (!empty($_SESSION['user']) && isset($_SESSION['user']['rol']) && in_array($_SESSION['user']['rol'], ['admin','operador'])): ?>
      <li class="nav-item me-2 d-none d-lg-block"><a class="nav-link text-white" href="/public/index.php?r=admin/panel">Administrar</a></li>
    <?php endif; ?>

        <?php if (!empty($_SESSION['user'])): ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle text-white d-flex align-items-center" href="#" id="userMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <img src="<?= htmlspecialchars($_SESSION['user']['avatar'] ?? '/public/img/avatar-default.png') ?>" alt="avatar" class="rounded-circle" style="width:30px;height:30px;object-fit:cover;margin-right:8px;">
              <span><?= htmlspecialchars($_SESSION['user']['nombre'] ?? 'Usuario') ?></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
              <li><a class="dropdown-item" href="/public/index.php?r=usuario">Mi cuenta</a></li>
              <!-- Panel admin removido del dropdown; queda solo Mi cuenta y Cerrar sesión -->
              <li><a class="dropdown-item" href="/public/index.php?r=auth/google-logout">Cerrar sesión</a></li>
            </ul>
          </li>
        <?php else: ?>
          <li class="nav-item">
            <a class="btn btn-light ms-2" href="/public/index.php?r=auth/google-login">Iniciar sesión</a>
          </li>
        <?php endif; ?>

      </ul>
    </div>
  </div>
</nav>
<main class="container mt-4">
<!-- Toast container (usado para mensajes flash) -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1080">
  <div id="flashToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-header">
      <strong class="me-auto">Mensaje</strong>
      <small>Ahora</small>
      <button type="button" class="btn-close ms-2 mb-1" data-bs-dismiss="toast" aria-label="Cerrar"></button>
    </div>
    <div class="toast-body">
      <!-- mensaje se inyecta desde footer JS -->
    </div>
  </div>
</div>
<?php if (!empty($_SESSION['flash'])) { /* dejar que footer lo muestre y se auto-destruya */ } ?>
