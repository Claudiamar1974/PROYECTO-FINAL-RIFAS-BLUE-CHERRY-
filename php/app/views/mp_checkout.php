<?php
// Vista de pago con MercadoPago
$monto = $_GET['monto'] ?? 0;
$descripcion = $_GET['descripcion'] ?? '';
?>
<h2>Pagar con MercadoPago</h2>
<form method="get" action="index.php">
    <input type="hidden" name="r" value="mp/checkout">
    <label>Monto: <input type="number" name="monto" value="<?= htmlspecialchars($monto) ?>" required></label><br>
    <label>Descripción: <input type="text" name="descripcion" value="<?= htmlspecialchars($descripcion) ?>" required></label><br>
    <button type="submit">Pagar con MercadoPago</button>
</form>
