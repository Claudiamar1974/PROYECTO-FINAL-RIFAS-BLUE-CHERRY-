<?php
// Configuración de la base de datos

// Asegurar que PHP use la hora de Montevideo
if (!ini_get('date.timezone')) {
     date_default_timezone_set('America/Montevideo');
}

$host = 'localhost';
$db   = 'proy_rifas_db';
$user = 'proy_root';
$pass = '123456789';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
     PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
     PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
     PDO::ATTR_EMULATE_PREPARES   => false,
];
try {
      $pdo = new PDO($dsn, $user, $pass, $options);
      // Alinear la zona horaria de MySQL con PHP (si el servidor lo permite)
      try {
           $tz = date('P'); // offset like +03:00
           $pdo->exec("SET time_zone = '$tz'");
      } catch (Exception $e) {
           // No bloquear si no es posible cambiar la zona en MySQL (permisos)
      }
} catch (PDOException $e) {
      throw new PDOException($e->getMessage(), (int)$e->getCode());
}
