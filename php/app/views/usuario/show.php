<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$title = htmlspecialchars($usuario['nombre']) . ' - Usuario';
include __DIR__ . '/../partials/header.php';

$currentUser = $_SESSION['user'] ?? null;
$isOwner = $currentUser && isset($currentUser['id']) && $currentUser['id'] == $usuario['id'];
$isAdmin = $currentUser && isset($currentUser['rol']) && $currentUser['rol'] === 'admin';
$isOperator = $currentUser && isset($currentUser['rol']) && $currentUser['rol'] === 'operador';
?>
        <!-- Resumen removido: la vista de usuario es sólo de consulta -->

        <div class="card">
            <div class="card-body">
                <h2 class="card-title"><?= htmlspecialchars($usuario['nombre']) ?></h2>
                <p class="card-text"><strong>Email:</strong> <?= htmlspecialchars($usuario['email']) ?></p>
                <p class="card-text"><strong>Rol:</strong> <?= htmlspecialchars($usuario['rol']) ?></p>
                <p class="card-text"><strong>Google ID:</strong> <?= htmlspecialchars($usuario['google_id']) ?></p>
                <p class="card-text"><strong>Avatar:</strong>
                    <?php if (!empty($usuario['avatar'])): ?>
                        <img src="<?= htmlspecialchars($usuario['avatar']) ?>" alt="avatar" class="rounded-circle" style="width:48px;height:48px;object-fit:cover;">
                    <?php else: ?>
                        <img src="/public/img/avatar-default.svg" alt="avatar" class="rounded-circle" style="width:48px;height:48px;object-fit:cover;">
                    <?php endif; ?>
                </p>

                <div class="mt-3">
                    <!-- Edit deshabilitado para vista de solo consulta -->

                    <a class="btn btn-secondary" href="/public/index.php">Volver</a>
                </div>
            </div>
        </div>

<?php include __DIR__ . '/../partials/footer.php'; ?>