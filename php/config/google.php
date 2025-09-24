<?php
// Configuración de Google OAuth
return [
    // Reemplaza estos valores por los de tu proyecto en Google Cloud Console
    'client_id' => getenv('GOOGLE_CLIENT_ID') ?: '967620035300-0fm2s0q5hlh7scoec5ne77huvg8vu1ir.apps.googleusercontent.com',
    'client_secret' => getenv('GOOGLE_CLIENT_SECRET') ?: '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',
    // Asegúrate de usar la URL de callback correcta (ver instrucciones README abajo)
    'redirect_uri' => getenv('GOOGLE_REDIRECT_URI') ?: 'https://proyecto.blisoft.com.uy/public/index.php?r=auth/google-callback',
    'scopes' => [ 'email', 'profile' ],
];
