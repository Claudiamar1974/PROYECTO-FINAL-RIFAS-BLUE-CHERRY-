<?php
// Punto de entrada de la app
require '../app/controllers/HomeController.php';
require '../app/controllers/RifaController.php';
require '../app/controllers/UsuarioController.php';
require '../app/controllers/ReservaController.php';
require '../app/controllers/PagoController.php';
require '../app/controllers/PublicRifaController.php';

// Google Auth
if (session_status() === PHP_SESSION_NONE) session_start();
include '../app/views/auth_google.php';

// Intentar liberar reservas expiradas en cada request (no reemplaza al cron)
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/utils/liberar_reservas.php';
try {
    // Llamada silenciosa; errores no fatales aquí. Usar hora de Google como referencia.
    require_once __DIR__ . '/../app/utils/tiempo.php';
    $now = ahoraGoogleFormato('Y-m-d H:i:s', 'America/Montevideo');
    liberarReservasExpiradas($pdo, $now);
} catch (Exception $e) {
    // Ignorar para no interrumpir la experiencia de usuario; el cron/errores pueden ser revisados en logs
}

// Enrutador simple por parámetro r
$r = $_GET['r'] ?? '';

switch ($r) {
    case 'rifa':
        $controller = new RifaController();
        $controller->index();
        break;
    case 'rifa/show':
        $controller = new RifaController();
        $controller->show($_GET['id']);
        break;
    case 'rifa/create':
        $controller = new RifaController();
        $controller->create();
        break;
    case 'rifa/store':
        $controller = new RifaController();
        $controller->store($_POST);
        break;
    case 'rifa/edit':
        $controller = new RifaController();
        $controller->edit($_GET['id']);
        break;
    case 'rifa/update':
        $controller = new RifaController();
        $controller->update($_GET['id'], $_POST);
        break;
    case 'rifa/destroy':
        $controller = new RifaController();
        $controller->destroy($_GET['id']);
        break;
    case 'usuario':
        // Mostrar siempre el perfil del usuario autenticado (solo lectura)
        if (!empty($_SESSION['user'])) {
            $user = $_SESSION['user'];
            $controller = new UsuarioController();
            $controller->show($user['id']);
        } else {
            $_SESSION['flash'] = 'Debes iniciar sesión para ver tu cuenta.';
            header('Location: /public/index.php');
        }
        break;
    case 'usuario/show':
        // Permitir ver un usuario si eres admin o si es tu propio perfil
        if (empty($_SESSION['user'])) { $_SESSION['flash'] = 'Debes iniciar sesión.'; header('Location: /public/index.php'); exit; }
        $current = $_SESSION['user'];
        $targetId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($current['rol'] === 'admin' || $current['rol'] === 'operador' || $current['id'] === $targetId) {
            $controller = new UsuarioController();
            $controller->show($targetId);
        } else {
            $_SESSION['flash'] = 'Acceso denegado.';
            header('Location: /public/index.php');
        }
        break;
    case 'usuario/create':
        if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') { $_SESSION['flash'] = 'Acceso denegado.'; header('Location: /public/index.php'); exit; }
        $controller = new UsuarioController();
        $controller->create();
        break;
    case 'usuario/store':
        if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') { $_SESSION['flash'] = 'Acceso denegado.'; header('Location: /public/index.php'); exit; }
        $controller = new UsuarioController();
        $controller->store($_POST);
        break;
    case 'usuario/edit':
        if (empty($_SESSION['user'])) { $_SESSION['flash'] = 'Debes iniciar sesión.'; header('Location: /public/index.php'); exit; }
        $current = $_SESSION['user'];
        $targetId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($current['rol'] === 'admin' || $current['id'] === $targetId) {
            $controller = new UsuarioController();
            $controller->edit($targetId);
        } else {
            $_SESSION['flash'] = 'Acceso denegado.';
            header('Location: /public/index.php');
        }
        break;
    case 'usuario/update':
        if (empty($_SESSION['user'])) { $_SESSION['flash'] = 'Debes iniciar sesión.'; header('Location: /public/index.php'); exit; }
        $current = $_SESSION['user'];
        $targetId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($current['rol'] === 'admin' || $current['id'] === $targetId) {
            $controller = new UsuarioController();
            $controller->update($targetId, $_POST);
        } else {
            $_SESSION['flash'] = 'Acceso denegado.';
            header('Location: /public/index.php');
        }
        break;
    case 'usuario/destroy':
        // Solo admin puede eliminar usuarios
        if (empty($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') { $_SESSION['flash'] = 'Acceso denegado.'; header('Location: /public/index.php'); exit; }
        $controller = new UsuarioController();
        $controller->destroy($_GET['id']);
        break;
    case 'reserva':
        $controller = new ReservaController();
        $controller->index();
        break;
    case 'reserva/show':
        $controller = new ReservaController();
        $controller->show($_GET['id']);
        break;
    case 'reserva/create':
        $controller = new ReservaController();
        $controller->create();
        break;
    case 'reserva/store':
        $controller = new ReservaController();
        $controller->store($_POST);
        break;
    case 'reserva/edit':
        $controller = new ReservaController();
        $controller->edit($_GET['id']);
        break;
    case 'reserva/update':
        $controller = new ReservaController();
        $controller->update($_GET['id'], $_POST);
        break;
    case 'reserva/destroy':
        $controller = new ReservaController();
        $controller->destroy($_GET['id']);
        break;
    case 'pago':
        $controller = new PagoController();
        $controller->index();
        break;
    case 'pago/show':
        $controller = new PagoController();
        $controller->show($_GET['id']);
        break;
    case 'pago/create':
        $controller = new PagoController();
        $controller->create();
        break;
    case 'pago/store':
        $controller = new PagoController();
        $controller->store($_POST);
        break;
    case 'pago/edit':
        $controller = new PagoController();
        $controller->edit($_GET['id']);
        break;
    case 'pago/update':
        $controller = new PagoController();
        $controller->update($_GET['id'], $_POST);
        break;
    case 'pago/destroy':
        $controller = new PagoController();
        $controller->destroy($_GET['id']);
        break;
    case 'auth/google-login':
        require '../app/controllers/auth/GoogleAuthController.php';
        break;
    case 'auth/google-callback':
        require '../app/controllers/auth/GoogleAuthController.php';
        break;
    case 'auth/google-logout':
        unset($_SESSION['user']);
        header('Location: /public/index.php');
        exit;
    case 'mp/checkout':
        require '../app/controllers/mercadopago/CheckoutController.php';
        break;
    case 'mp/webhook':
        require '../app/controllers/mercadopago/webhook.php';
        break;
    case 'mp/success':
        include '../app/views/mp_success.php';
        break;
    case 'mp/pending':
        include '../app/views/mp_pending.php';
        break;
    case 'mp/failure':
        include '../app/views/mp_failure.php';
        break;
    case 'publicrifa':
        $controller = new PublicRifaController();
        $controller->index();
        break;
    case 'publicrifa/show':
        $controller = new PublicRifaController();
        $controller->show($_GET['id']);
        break;
    case 'publicrifa/reservar':
        require '../app/controllers/public_rifa_reservar.php';
        break;
    case 'admin/panel':
        include '../app/views/admin/panel.php';
        break;
    case 'admin/rifa-create':
        include '../app/views/admin/rifa_create.php';
        break;
    case 'admin/rifa-store':
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') { echo 'Acceso denegado.'; exit; }
        require_once '../app/models/Rifa.php';
        require_once '../config/database.php';

        // Manejo de imagen
        $imagenNombre = null;
        if (!empty($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/jpg' => 'jpg'];
            $type = mime_content_type($_FILES['imagen']['tmp_name']);
            if (isset($allowed[$type]) && $_FILES['imagen']['size'] <= 2 * 1024 * 1024) {
                $ext = $allowed[$type];
                $imagenNombre = 'rifa_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
                $dest = __DIR__ . '/../public/img/rifas/' . $imagenNombre;
                move_uploaded_file($_FILES['imagen']['tmp_name'], $dest);
            }
        }

        $data = $_POST;
        if ($imagenNombre) $data['imagen'] = $imagenNombre;
        Rifa::create($pdo, $data);
        // Crear los números de la rifa
        $id_rifa = $pdo->lastInsertId();
        for ($i=1; $i<=(int)$_POST['total_numeros']; $i++) {
            $stmt = $pdo->prepare('INSERT INTO numeros_rifa (id_rifa, numero, estado, creado_en, actualizado_en) VALUES (?, ?, "libre", NOW(), NOW())');
            $stmt->execute([$id_rifa, $i]);
        }
        header('Location: index.php?r=admin/panel');
        break;
    case 'admin/rifa-edit':
        include '../app/views/admin/rifa_edit.php';
        break;
    case 'admin/rifa-update':
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') { echo 'Acceso denegado.'; exit; }
        require_once '../app/models/Rifa.php';
        require_once '../config/database.php';

        // Manejo de imagen en update
        $imagenNombre = null;
        if (!empty($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/jpg' => 'jpg'];
            $type = mime_content_type($_FILES['imagen']['tmp_name']);
            if (isset($allowed[$type]) && $_FILES['imagen']['size'] <= 2 * 1024 * 1024) {
                $ext = $allowed[$type];
                $imagenNombre = 'rifa_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
                $dest = __DIR__ . '/../public/img/rifas/' . $imagenNombre;
                move_uploaded_file($_FILES['imagen']['tmp_name'], $dest);
            }
        }

        $data = $_POST;
        if ($imagenNombre) $data['imagen'] = $imagenNombre;
        Rifa::update($pdo, $_GET['id'], $data);
        header('Location: index.php?r=admin/panel');
        break;
    case 'admin/rifa-destroy':
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') { echo 'Acceso denegado.'; exit; }
        require_once '../app/models/Rifa.php';
        require_once '../config/database.php';
        Rifa::delete($pdo, $_GET['id']);
        header('Location: index.php?r=admin/panel');
        break;
    case 'admin/rifa-ganador':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (session_status() === PHP_SESSION_NONE) session_start();
            if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') { echo 'Acceso denegado.'; exit; }
            require_once '../config/database.php';
            $stmt = $pdo->prepare('UPDATE rifas SET id_ganador=?, estado="finalizada", actualizado_en=NOW() WHERE id=?');
            $stmt->execute([$_POST['id_ganador'], $_GET['id']]);
            // Notificar ganador
            $stmt = $pdo->prepare('SELECT n.numero, u.nombre, u.email, r.titulo FROM numeros_rifa n LEFT JOIN reservas res ON n.id_reserva = res.id LEFT JOIN usuarios u ON res.id_usuario = u.id LEFT JOIN rifas r ON n.id_rifa = r.id WHERE n.id = ?');
            $stmt->execute([$_POST['id_ganador']]);
            $ganador = $stmt->fetch();
            if ($ganador && $ganador['email']) {
                require_once '../app/utils/notificar_ganador.php';
                notificarGanador($ganador['email'], $ganador['nombre'], $ganador['titulo'], $ganador['numero']);
            }
            header('Location: index.php?r=admin/panel');
        } else {
            include '../app/views/admin/rifa_ganador.php';
        }
        break;
    case 'admin/rifa-generar-ganador':
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['user']) || !in_array($_SESSION['user']['rol'], ['admin','operador'])) { echo 'Acceso denegado.'; exit; }
        require_once '../config/database.php';
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if (!$id) { header('Location: index.php?r=admin/panel'); exit; }
        try {
            $chosen = null;
            // Si se solicita modo bycount, elegir un número aleatorio entre 1 y total_numeros
            if (isset($_GET['mode']) && $_GET['mode'] === 'bycount') {
                // Obtener total_numeros de la rifa
                $stmt = $pdo->prepare('SELECT total_numeros FROM rifas WHERE id = ?');
                $stmt->execute([$id]);
                $total = (int)$stmt->fetchColumn();
                if ($total > 0) {
                    $chosenNumber = rand(1, $total);
                    // Buscar el id del registro en numeros_rifa con ese numero (si existe)
                    $stmt = $pdo->prepare('SELECT id FROM numeros_rifa WHERE id_rifa = ? AND numero = ? LIMIT 1');
                    $stmt->execute([$id, $chosenNumber]);
                    $row = $stmt->fetch();
                    if ($row) {
                        $chosen = $row['id'];
                    }
                }
            } else {
                // Intentar seleccionar aleatoriamente un número vendido
                $stmt = $pdo->prepare("SELECT id FROM numeros_rifa WHERE id_rifa = ? AND estado = 'vendido'");
                $stmt->execute([$id]);
                $vendidos = $stmt->fetchAll();
                if (!empty($vendidos)) {
                    $chosen = $vendidos[array_rand($vendidos)]['id'];
                } else {
                    // Si no hay vendidos, intentar entre reservas pagadas
                    $stmt = $pdo->prepare("SELECT n.id FROM numeros_rifa n JOIN reservas r ON n.id_reserva = r.id WHERE n.id_rifa = ? AND r.estado = 'pagado'");
                    $stmt->execute([$id]);
                    $pagados = $stmt->fetchAll();
                    if (!empty($pagados)) {
                        $chosen = $pagados[array_rand($pagados)]['id'];
                    } else {
                        // Si no hay pagados, intentar entre reservados no expirados
                        $stmt = $pdo->prepare("SELECT n.id FROM numeros_rifa n JOIN reservas r ON n.id_reserva = r.id WHERE n.id_rifa = ? AND r.estado = 'reservado' AND r.expira_en > NOW()");
                        $stmt->execute([$id]);
                        $reservados = $stmt->fetchAll();
                        if (!empty($reservados)) {
                            $chosen = $reservados[array_rand($reservados)]['id'];
                        }
                    }
                }
            }

            if ($chosen) {
                // Actualizar rifa y notificar
                $pdo->beginTransaction();
                $stmt = $pdo->prepare('UPDATE rifas SET id_ganador = ?, estado = "finalizada", actualizado_en = NOW() WHERE id = ?');
                $stmt->execute([$chosen, $id]);
                // Obtener info del ganador
                $stmt = $pdo->prepare('SELECT n.numero, u.nombre, u.email, r.titulo FROM numeros_rifa n LEFT JOIN reservas res ON n.id_reserva = res.id LEFT JOIN usuarios u ON res.id_usuario = u.id LEFT JOIN rifas r ON n.id_rifa = r.id WHERE n.id = ?');
                $stmt->execute([$chosen]);
                $gan = $stmt->fetch();
                // Notificar si tiene email
                if ($gan && !empty($gan['email'])) {
                    require_once '../app/utils/notificar_ganador.php';
                    notificarGanador($gan['email'], $gan['nombre'], $gan['titulo'], $gan['numero']);
                }
                $pdo->commit();
            }
        } catch (Exception $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
        }
        header('Location: index.php?r=admin/panel');
        break;
    case 'admin/rifa-ganador-info':
        include '../app/views/admin/rifa_ganador_info.php';
        break;
    case 'admin/usuarios':
        include '../app/views/admin/usuarios.php';
        break;
    case 'admin/usuario-edit':
        include '../app/views/admin/usuario_edit.php';
        break;
    case 'admin/usuario-update':
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') { echo 'Acceso denegado.'; exit; }
        require_once '../app/models/Usuario.php';
        require_once '../config/database.php';
        Usuario::update($pdo, $_GET['id'], $_POST);
        header('Location: index.php?r=admin/usuarios');
        break;
    case 'admin/usuario-destroy':
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') { echo 'Acceso denegado.'; exit; }
        require_once '../app/models/Usuario.php';
        require_once '../config/database.php';
        Usuario::delete($pdo, $_GET['id']);
        header('Location: index.php?r=admin/usuarios');
        break;
    case 'admin/reservas':
        include '../app/views/admin/reservas.php';
        break;
    case 'admin/reserva-destroy':
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') { echo 'Acceso denegado.'; exit; }
        require_once '../app/models/Reserva.php';
        require_once '../config/database.php';
        Reserva::delete($pdo, $_GET['id']);
        header('Location: index.php?r=admin/reservas');
        break;
    default:
        $controller = new HomeController();
        $controller->index();
        break;
}
