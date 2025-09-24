<?php
// Script de migración: añade la columna `rol` a la tabla `usuarios` si no existe.
// Uso (CLI): php scripts/add_rol_column.php
// Uso (web): acceder a https://tu-dominio/.../scripts/add_rol_column.php (asegúrate de eliminarlo luego o protegerlo)

require_once __DIR__ . '/../config/database.php';

function out($msg) {
    if (php_sapi_name() === 'cli') {
        echo $msg . PHP_EOL;
    } else {
        echo '<pre>' . htmlspecialchars($msg) . '</pre>';
    }
}

try {
    // Verificar si la tabla y la columna existen
    $check = $pdo->prepare("SHOW COLUMNS FROM usuarios LIKE 'rol'");
    $check->execute();
    $exists = $check->fetch();

    if ($exists) {
        out("La columna 'rol' ya existe en la tabla usuarios. Nada que hacer.");
        exit(0);
    }

    // Añadir la columna rol como ENUM
    $sql = "ALTER TABLE usuarios ADD COLUMN rol ENUM('admin','operador','usuario') NOT NULL DEFAULT 'usuario'";
    $pdo->exec($sql);
    out("Columna 'rol' añadida correctamente.");
} catch (PDOException $e) {
    out("Error SQL: " . $e->getMessage());
    exit(1);
} catch (Exception $e) {
    out("Error: " . $e->getMessage());
    exit(1);
}
