<?php
// util para obtener la hora desde la cabecera Date de Google como fuente de tiempo confiable
function obtenerHoraGoogle($tz = 'America/Montevideo') {
    $dateHeader = null;

    // Intentar con cURL
    if (function_exists('curl_version')) {
        $ch = curl_init('https://www.google.com');
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($ch, CURLOPT_TIMEOUT, 2);
        $hdr = curl_exec($ch);
        if ($hdr !== false) {
            if (preg_match('/^Date:\s*(.+?)\r?$/mi', $hdr, $m)) {
                $dateHeader = trim($m[1]);
            }
        }
        curl_close($ch);
    }

    // Fallback: usar get_headers si cURL no estuvo disponible o no devolvió Date
    if (!$dateHeader) {
        try {
            $headers = @get_headers('https://www.google.com', 1);
            if ($headers && isset($headers['Date'])) {
                // get_headers puede devolver Date como array si hay varios
                $dateHeader = is_array($headers['Date']) ? end($headers['Date']) : $headers['Date'];
            }
        } catch (Exception $e) {
            // ignore
        }
    }

    // Si tenemos cabecera Date, crear DateTime en GMT y convertir a la TZ solicitada
    if ($dateHeader) {
        try {
            $dt = new DateTimeImmutable($dateHeader, new DateTimeZone('GMT'));
            $dtLocal = $dt->setTimezone(new DateTimeZone($tz));
            return $dtLocal;
        } catch (Exception $e) {
            // continuar al fallback
        }
    }

    // Último recurso: usar hora local del servidor, ajustada a la TZ solicitada
    if (!ini_get('date.timezone')) {
        date_default_timezone_set($tz);
    }
    return new DateTimeImmutable('now', new DateTimeZone($tz));
}

function ahoraGoogleFormato($format = 'Y-m-d H:i:s', $tz = 'America/Montevideo') {
    $dt = obtenerHoraGoogle($tz);
    return $dt->format($format);
}

?>