<?php
// Notificar al usuario sobre la compra mediante mail() simple
function notificarCompra($toEmail, $toName, $reservaId, $rifaTitle, $numeros, $monto) {
    $subject = "Confirmación de compra - Reserva #$reservaId";
    $body = "Hola " . $toName . ",\n\n";
    $body .= "Tu compra fue confirmada. Detalles:\n";
    $body .= "Rifa: " . $rifaTitle . "\n";
    $body .= "Reserva ID: " . $reservaId . "\n";
    $body .= "Números: " . implode(',', $numeros) . "\n";
    $body .= "Monto: $" . number_format($monto, 2) . "\n\n";
    $body .= "Gracias por tu compra.\n";
    $headers = "From: no-reply@tu-dominio.com\r\n" .
               "Reply-To: no-reply@tu-dominio.com\r\n" .
               "X-Mailer: PHP/" . phpversion();
    // Nota: mail() puede fallar si no hay MTA configurado en el servidor
    return mail($toEmail, $subject, $body, $headers);
}
