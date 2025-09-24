<?php
// Enviar email al ganador de la rifa
function notificarGanador($email, $nombre, $rifa, $numero) {
    $asunto = "¡Felicidades! Ganaste la rifa: $rifa";
    $mensaje = "Hola $nombre,\n\n¡Felicitaciones! Has sido el ganador de la rifa '$rifa' con el número $numero.\n\nGracias por participar.";
    $cabeceras = "From: no-reply@tusitio.com\r\n";
    // mail() solo funciona si el servidor tiene configurado un servidor SMTP
    mail($email, $asunto, $mensaje, $cabeceras);
}

// Uso: notificarGanador($email, $nombre, $rifa, $numero);
