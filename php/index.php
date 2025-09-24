<?php
// Redirige a la carpeta public/ para evitar 404 cuando la raíz no contiene el index del front
header('Location: ./public/');
exit;
