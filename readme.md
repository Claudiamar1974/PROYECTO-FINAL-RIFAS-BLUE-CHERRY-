Rifas Solidarias.

Descripción: Este proyecto permite a los clientes que compren números de rifa. Se requiere de un organizador de rifas.
Se tiene en cuenta además, consideraciones legales para el desarrollo y aplicación del proyecto,
además de integrar estandares de calidad de implementación directamente en el sistema.

📋 Guía de Instalación Completa - Sistema de Rifas
Esta guía te llevará paso a paso desde la creación de contenedores Docker hasta tener tu sistema de rifas completamente funcional con PHP puro.

📑 Índice
    1. Requisitos Previos 
    2. Instalación de Docker 
    3. Creación de Contenedores Docker 
    4. Configuración de Apache y MySQL 
    5. Despliegue del Sitio Web 
    6. Configuración de la Base de Datos 
    7. Configuración de Google OAuth 
    8. Configuración de MercadoPago 
    9. Verificación Final 
    10. Troubleshooting 

1. Requisitos Previos
    • Sistema operativo: Linux (Ubuntu/Debian recomendado) o Windows con WSL2 
    • Acceso root o sudo 
    • Conexión a Internet 
    • Editor de texto (nano, vim o VSCode) 

2. Instalación de Docker
En Ubuntu/Debian:
# Actualizar el sistema
sudo apt update && sudo apt upgrade -y

# Instalar dependencias
sudo apt install -y apt-transport-https ca-certificates curl software-properties-common

# Agregar la clave GPG oficial de Docker
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /usr/share/keyrings/docker-archive-keyring.gpg

# Agregar el repositorio de Docker
echo "deb [arch=$(dpkg --print-architecture) signed-by=/usr/share/keyrings/docker-archive-keyring.gpg] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null

# Instalar Docker
sudo apt update
sudo apt install -y docker-ce docker-ce-cli containerd.io

# Verificar instalación
sudo docker --version

# Agregar tu usuario al grupo docker (para no usar sudo)
sudo usermod -aG docker $USER
newgrp docker

3. Creación de Contenedores Docker
Crear estructura de directorios:
mkdir -p ~/proyecto-rifas
cd ~/proyecto-rifas
mkdir -p web mysql-data
Crear red de Docker:
docker network create rifas_network
Crear contenedor MySQL:
docker run -d \
  --name rifas_mysql \
  --network rifas_network \
  -e MYSQL_ROOT_PASSWORD=rootpassword123 \
  -e MYSQL_DATABASE=proy_rifas_db \
  -e MYSQL_USER=proy_root \
  -e MYSQL_PASSWORD=123456789 \
  -e TZ=America/Montevideo \
  -p 3306:3306 \
  -v ~/proyecto-rifas/mysql-data:/var/lib/mysql \
  --restart always \
  mysql:8.0 \
  --default-authentication-plugin=mysql_native_password \
  --character-set-server=utf8mb4 \
  --collation-server=utf8mb4_unicode_ci
Crear contenedor Apache + PHP:
docker run -d \
  --name rifas_web \
  --network rifas_network \
  -e TZ=America/Montevideo \
  -p 80:80 \
  -p 443:443 \
  -v ~/proyecto-rifas/web:/var/www/html \
  --restart always \
  php:8.3-apache
Crear contenedor phpMyAdmin (opcional):
docker run -d \
  --name rifas_phpmyadmin \
  --network rifas_network \
  -e PMA_HOST=rifas_mysql \
  -e PMA_USER=proy_root \
  -e PMA_PASSWORD=123456789 \
  -p 8080:80 \
  --restart always \
  phpmyadmin:latest
Verificar que los contenedores estén corriendo:
docker ps
Deberías ver 3 contenedores: rifas_mysql, rifas_web, y rifas_phpmyadmin.

4. Configuración de Apache y MySQL
Instalar extensiones PHP necesarias:
# Entrar al contenedor web
docker exec -it rifas_web bash

# Actualizar paquetes e instalar dependencias
apt-get update
apt-get install -y libzip-dev zip unzip git libpng-dev libjpeg-dev libfreetype6-dev

# Instalar extensiones PHP
docker-php-ext-install pdo pdo_mysql mysqli zip

# Configurar GD para imágenes
docker-php-ext-configure gd --with-freetype --with-jpeg
docker-php-ext-install gd

# Habilitar mod_rewrite de Apache
a2enmod rewrite

# Configurar PHP para zona horaria
echo "date.timezone = America/Montevideo" >> /usr/local/etc/php/php.ini

# Reiniciar Apache
service apache2 restart

# Salir del contenedor
exit
Configurar Apache para el sitio:
docker exec -it rifas_web bash

# Crear configuración del sitio
cat > /etc/apache2/sites-available/rifas.conf << 'EOF'
<VirtualHost *:80>
    ServerName localhost
    DocumentRoot /var/www/html

    <Directory /var/www/html>
        Options Indexes FollowSymLinks MultiViews
        AllowOverride All
        Require all granted
    </Directory>

    # Configuración para archivos PHP
    <FilesMatch \.php$>
        SetHandler application/x-httpd-php
    </FilesMatch>

    # Seguridad básica
    <Directory /var/www/html/config>
        Require all denied
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
EOF

# Habilitar el sitio
a2dissite 000-default.conf
a2ensite rifas.conf

# Reiniciar Apache
service apache2 restart

exit

5. Despliegue del Sitio Web
Copiar archivos del sitio web:
# Clonar el repositorio
cd ~
git clone git sparse-checkout https://github.com/Claudiamar1974/PROYECTO-FINAL-RIFAS-BLUE-CHERRY-/tree/main/php/hito3

# Copiar todo el contenido a /var/www/
sudo cp -r ~/sitio-rifas/* /var/www/

# Opcional: Eliminar el directorio clonado
rm -rf ~/sitio-rifas

# Verificar que los archivos se copiaron correctamente
ls -la /var/www/
Ajustar permisos:
# Ajustar permisos para que Apache pueda escribir en uploads
sudo chmod -R 775 /var/www/uploads
sudo chown -R www-data:www-data /var/www/uploads

# Permisos generales del sitio
sudo chmod -R 755 /var/www
sudo chown -R www-data:www-data /var/www

# Proteger archivos de configuración
sudo chmod 640 /var/www/config/*.php
Copiar archivos de configuración:
Opción 1: Usar WinSCP (Windows) o cualquier cliente SFTP:
WinSCP es una herramienta gráfica que facilita la transferencia de archivos por SSH/SFTP.
    1. Descargar e instalar WinSCP:
        ◦ Descarga desde: https://winscp.net/ 
        ◦ Instala en tu Windows 
    2. Conectar al servidor:
        ◦ Abre WinSCP 
        ◦ Protocolo: SFTP 
        ◦ Servidor: tu-servidor.com (o IP) 
        ◦ Puerto: 22 
        ◦ Usuario: tu_usuario 
        ◦ Contraseña: tu_contraseña 
        ◦ Click en "Login" 
    3. Editar archivos de configuración:
        ◦ En el panel derecho (servidor), navega a: /var/www/config/ 
        ◦ Haz doble click en los archivos para editarlos: 
            ▪ database.php 
            ▪ google.php 
            ▪ mercadopago.php 
        ◦ WinSCP abrirá los archivos en tu editor (Notepad++ por defecto) 
        ◦ Edita y guarda, WinSCP subirá automáticamente los cambios 
    4. O copiar archivos locales:
        ◦ Panel izquierdo: tus archivos locales editados 
        ◦ Panel derecho: servidor en /var/www/config/ 
        ◦ Arrastra los archivos del panel izquierdo al derecho 
Opción 2: Usar SCP desde línea de comandos (Linux/Mac/Windows con PowerShell):
# Copiar archivos de configuración desde tu máquina local al servidor
scp database.php usuario@tu-servidor.com:/var/www/config/
scp google.php usuario@tu-servidor.com:/var/www/config/
scp mercadopago.php usuario@tu-servidor.com:/var/www/config/

# O copiar todos a la vez
scp database.php google.php mercadopago.php usuario@tu-servidor.com:/var/www/config/
Opción 3: Editar directamente en el servidor con nano:
# Conectarse por SSH
ssh usuario@tu-servidor.com

# Editar archivos
nano /var/www/config/database.php
nano /var/www/config/google.php
nano /var/www/config/mercadopago.php
Nota: El sitio web ya incluye su propio archivo .htaccess configurado, no es necesario crearlo.

6. Configuración de la Base de Datos
Crear archivo SQL para inicialización:
cd ~
nano init-schema.sql
Pegar el siguiente contenido:
-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS proy_rifas_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE proy_rifas_db;

-- Tabla: usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255),
    email VARCHAR(255) UNIQUE,
    google_id VARCHAR(255) UNIQUE,
    avatar VARCHAR(255),
    rol ENUM('admin','operador','usuario') DEFAULT 'usuario',
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_usuarios_rol (rol)
) ENGINE=InnoDB;

-- Tabla: rifas
CREATE TABLE IF NOT EXISTS rifas (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255),
    descripcion TEXT,
    imagen VARCHAR(255),
    precio_por_numero DECIMAL(10,2),
    total_numeros INT,
    fecha_inicio DATETIME,
    fecha_fin DATETIME,
    estado ENUM('activa','finalizada','cancelada') DEFAULT 'activa',
    id_ganador BIGINT UNSIGNED,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_rifas_activas (estado, fecha_inicio, fecha_fin),
    INDEX idx_rifas_finalizadas (fecha_fin, estado)
) ENGINE=InnoDB;

-- Tabla: numeros_rifa
CREATE TABLE IF NOT EXISTS numeros_rifa (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_rifa BIGINT UNSIGNED NOT NULL,
    id_reserva BIGINT UNSIGNED DEFAULT NULL,
    numero INT NOT NULL,
    estado ENUM('libre','reservado','vendido') DEFAULT 'libre',
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_numeros_rifa_estado (id_rifa, estado),
    INDEX idx_numeros_disponibles (id_rifa, estado, numero),
    FOREIGN KEY (id_rifa) REFERENCES rifas(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Tabla: reservas
CREATE TABLE IF NOT EXISTS reservas (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_usuario BIGINT UNSIGNED NOT NULL,
    id_rifa BIGINT UNSIGNED NOT NULL,
    estado ENUM('reservado','pagado','cancelado','expirado') DEFAULT 'reservado',
    reservado_en DATETIME DEFAULT NULL,
    expira_en DATETIME DEFAULT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_reservas_usuario_estado (id_usuario, estado, reservado_en),
    INDEX idx_reservas_expiracion (expira_en, estado),
    INDEX idx_reservas_rifa_estado (id_rifa, estado),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (id_rifa) REFERENCES rifas(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Tabla: pagos
CREATE TABLE IF NOT EXISTS pagos (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_reserva BIGINT UNSIGNED NOT NULL,
    id_pago_mp VARCHAR(255),
    estado ENUM('aprobado','rechazado','pendiente') DEFAULT 'pendiente',
    monto DECIMAL(10,2),
    metodo_pago VARCHAR(50),
    preference_id VARCHAR(100),
    init_point VARCHAR(255),
    payment_id VARCHAR(100),
    payment_status VARCHAR(50),
    payment_detail TEXT,
    merchant_order_id VARCHAR(100),
    pagado_en DATETIME DEFAULT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_pagos_reserva_estado (id_reserva, estado),
    INDEX idx_pagos_fecha (pagado_en, estado),
    INDEX idx_pagos_payment_id (payment_id),
    FOREIGN KEY (id_reserva) REFERENCES reservas(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Agregar foreign key de id_ganador después de crear numeros_rifa
ALTER TABLE rifas ADD CONSTRAINT fk_rifas_ganador 
    FOREIGN KEY (id_ganador) REFERENCES numeros_rifa(id) ON DELETE SET NULL;
Importar el schema a MySQL:
Opción 1: Usar WinSCP para copiar el archivo SQL:
    1. Conectar con WinSCP al servidor (como se explicó arriba)
    2. Copiar el archivo SQL:
        ◦ En tu PC, crea el archivo init-schema.sql con todo el contenido SQL 
        ◦ En WinSCP, arrastra init-schema.sql desde tu PC al directorio /root/ o /home/tu_usuario/ del servidor 
    3. Ejecutar el script por SSH:
       # Conectarse al servidor
       ssh usuario@tu-servidor.com
       
       # Copiar el SQL al contenedor MySQL
       docker cp ~/init-schema.sql rifas_mysql:/tmp/
       
       # Ejecutar el script
       docker exec -it rifas_mysql mysql -uproy_root -p123456789 < /tmp/init-schema.sql
Opción 2: Usar SCP desde línea de comandos:
# Desde tu máquina local, copiar el archivo SQL al servidor
scp init-schema.sql usuario@tu-servidor.com:~/

# Conectarte por SSH al servidor
ssh usuario@tu-servidor.com

# Copiar al contenedor MySQL
docker cp ~/init-schema.sql rifas_mysql:/tmp/

# Ejecutar el script
docker exec -it rifas_mysql mysql -uproy_root -p123456789 < /tmp/init-schema.sql
Opción 3: Copiar directamente si estás en el servidor:
# Si ya estás conectado por SSH al servidor y creaste el archivo ahí
docker cp ~/init-schema.sql rifas_mysql:/tmp/

# Ejecutar el script SQL
docker exec -it rifas_mysql mysql -uproy_root -p123456789 < /tmp/init-schema.sql

# O ejecutarlo de forma interactiva
docker exec -it rifas_mysql mysql -uproy_root -p123456789 -e "source /tmp/init-schema.sql"
Verificar creación de tablas:
docker exec -it rifas_mysql mysql -uproy_root -p123456789 proy_rifas_db -e "SHOW TABLES;"

7. Configuración de Google OAuth
Paso 1: Crear proyecto en Google Cloud Console
    1. Ve a Google Cloud Console 
    2. Crea un nuevo proyecto: 
        ◦ Click en el selector de proyectos (arriba) 
        ◦ Click en "Nuevo proyecto" 
        ◦ Nombre: "Sistema de Rifas" 
        ◦ Click en "Crear" 
Paso 2: Habilitar Google+ API
    1. En el menú lateral, ve a "APIs y servicios" → "Biblioteca" 
    2. Busca "Google+ API" 
    3. Click en "Habilitar" 
Paso 3: Configurar pantalla de consentimiento OAuth
    1. Ve a "APIs y servicios" → "Pantalla de consentimiento de OAuth"
    2. Selecciona "Externo" y click en "Crear"
    3. Completa la información:
        ◦ Nombre de la aplicación: Sistema de Rifas 
        ◦ Correo de asistencia: tu-email@gmail.com 
        ◦ Logo de la aplicación: (opcional) 
        ◦ Dominios autorizados: 
            ▪ proyecto.blisoft.com.uy (o tu dominio) 
            ▪ localhost (para desarrollo) 
        ◦ Correo del desarrollador: tu-email@gmail.com 
    4. Click en "Guardar y continuar"
    5. En "Permisos", click en "Agregar o quitar permisos":
        ◦ Busca y selecciona: 
            ▪ userinfo.email 
            ▪ userinfo.profile 
            ▪ openid 
    6. Click en "Actualizar" y luego "Guardar y continuar"
    7. En "Usuarios de prueba" (si es necesario):
        ◦ Agrega tu email y otros emails que usarás para probar 
        ◦ Click en "Guardar y continuar" 
Paso 4: Crear credenciales OAuth 2.0
    1. Ve a "Credenciales" → "Crear credenciales" → "ID de cliente de OAuth 2.0"
    2. Si es la primera vez, deberás configurar la pantalla de consentimiento (ya lo hiciste)
    3. Configuración:
        ◦ Tipo de aplicación: "Aplicación web" 
        ◦ Nombre: "Sistema Rifas Web" 
    4. Orígenes de JavaScript autorizados:
        ◦ Click en "Agregar URI" 
        ◦ Agrega: 
          http://localhosthttp://localhost:80https://proyecto.blisoft.com.uy
    5. URIs de redireccionamiento autorizadas:
        ◦ Click en "Agregar URI" 
        ◦ Agrega (según tu estructura de URLs): 
          http://localhost/auth/google-callbackhttp://localhost/index.php?r=auth/google-callbackhttps://proyecto.blisoft.com.uy/auth/google-callbackhttps://proyecto.blisoft.com.uy/index.php?r=auth/google-callback
        ◦ IMPORTANTE: Asegúrate de usar la URL exacta que tu aplicación usa 
    6. Click en "Crear"
Paso 5: Obtener credenciales
Aparecerá un modal con:
    • ID de cliente: 967620035300-xxxxxxxxxxxxxxxxxxxxx.apps.googleusercontent.com 
    • Secreto de cliente: GOCSPX-xxxxxxxxxxxxxxxxxxxx 
¡Copia estos valores!
Paso 6: Configurar en tu aplicación
Edita el archivo config/google.php:
nano ~/proyecto-rifas/web/config/google.php
Reemplaza con tus credenciales:
<?php
// Configuración de Google OAuth
return [
    // Reemplaza con tu Client ID
    'client_id' => '967620035300-xxxxxxxxxxxxxxxxxxxxx.apps.googleusercontent.com',
    
    // Reemplaza con tu Client Secret
    'client_secret' => 'GOCSPX-xxxxxxxxxxxxxxxxxxxx',
    
    // URL de callback - debe coincidir EXACTAMENTE con la configurada en Google
    'redirect_uri' => 'https://proyecto.blisoft.com.uy/index.php?r=auth/google-callback',
    
    // Permisos solicitados
    'scopes' => ['email', 'profile'],
];
Paso 7: Obtener tu Google ID para ser admin
IMPORTANTE: Primero debes iniciar sesión con Google para que tu usuario se cree en la base de datos.
    1. Inicia sesión en tu aplicación con Google por primera vez:
        ◦ Ve a tu sitio: http://localhost 
        ◦ Click en "Iniciar sesión con Google" 
        ◦ Autoriza la aplicación 
        ◦ Tu usuario se creará automáticamente con rol "usuario" 
    2. Consulta tu email en la base de datos:
docker exec -it rifas_mysql mysql -uproy_root -p123456789 proy_rifas_db -e "SELECT id, nombre, email, rol FROM usuarios;"
Verás algo como:
+----+---------------+---------------------+----------+
| id | nombre        | email               | rol      |
+----+---------------+---------------------+----------+
|  1 | Tu Nombre     | tu@gmail.com        | usuario  |
+----+---------------+---------------------+----------+
    3. Actualiza tu usuario a admin usando tu EMAIL: 
# Reemplaza 'tu@gmail.com' con tu email real de Google
docker exec -it rifas_mysql mysql -uproy_root -p123456789 proy_rifas_db -e "UPDATE usuarios SET rol='admin' WHERE email='tu@gmail.com';"
    4. Verifica que el cambio se aplicó: 
docker exec -it rifas_mysql mysql -uproy_root -p123456789 proy_rifas_db -e "SELECT id, nombre, email, rol FROM usuarios WHERE rol='admin';"
Deberías ver:
+----+---------------+---------------------+----------+
| id | nombre        | email               | rol      |
+----+---------------+---------------------+----------+
|  1 | Tu Nombre     | tu@gmail.com        | admin    |
+----+---------------+---------------------+----------+
    5. Refresca la página o vuelve a iniciar sesión para que tu aplicación reconozca tu nuevo rol de admin. 

8. Configuración de MercadoPago
Paso 1: Crear cuenta en MercadoPago (si no tienes)
    1. Ve a MercadoPago Uruguay 
    2. Regístrate con tu email 
    3. Completa la verificación de cuenta 
Paso 2: Acceder al panel de desarrolladores
    1. Ve a MercadoPago Developers 
    2. Inicia sesión con tu cuenta 
    3. Verás el panel de desarrolladores 
Paso 3: Crear aplicación
    1. En el menú lateral, click en "Tus integraciones" 
    2. Click en "Crear aplicación" 
    3. Completa: 
        ◦ Nombre de la aplicación: Sistema de Rifas 
        ◦ ¿Qué producto integrarás?: Pagos online 
        ◦ Modelo de integración: Checkout Pro (el formulario de pago) 
    4. Click en "Crear aplicación" 
Paso 4: Crear cuentas de prueba
Para probar sin dinero real, necesitas crear usuarios de prueba:
    1. En el menú lateral, ve a "Cuentas de prueba" 
    2. Click en "Crear cuenta de prueba" 
    3. Crea DOS cuentas: 
Cuenta 1 - Vendedor:
País: Uruguay
Descripción: Vendedor de rifas
Dinero disponible: Dejar por defecto
Guarda los datos que te da:
    • User ID: 2946101514 
    • Email: TESTUSER8172191615368042194 
    • Contraseña: 7Kp1sZgt1P 
Cuenta 2 - Comprador:
País: Uruguay
Descripción: Comprador de números
Dinero disponible: Dejar por defecto
Guarda los datos:
    • Email: TESTBUYERXXXXXX 
    • Contraseña: qatest123 
Paso 5: Obtener credenciales de PRODUCCIÓN de la cuenta de prueba
IMPORTANTE: Aunque sean cuentas de "prueba", debes usar las credenciales de "producción" de esas cuentas de prueba.
    1. Cierra sesión de tu cuenta principal de MercadoPago
    2. Inicia sesión con el usuario VENDEDOR de prueba:
        ◦ Email: TESTUSER8172191615368042194 
        ◦ Contraseña: 7Kp1sZgt1P 
    3. Ve a MercadoPago Developers
    4. Ve a "Tus integraciones"
    5. Selecciona tu aplicación
    6. Ve a la pestaña "Credenciales de producción"
    7. Verás:
        ◦ Public Key: APP_USR-xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx 
        ◦ Access Token: APP_USR-xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx 
¡Copia estos valores!
Paso 6: Configurar en tu aplicación
Edita el archivo config/mercadopago.php:
nano ~/proyecto-rifas/web/config/mercadopago.php
Reemplaza con tus credenciales:
<?php
/**
 * Configuración de MercadoPago
 * 
 * IMPORTANTE: Estas son credenciales de PRODUCCIÓN de una cuenta de PRUEBA
 * País: Uruguay
 * User ID: 2946101514
 * Usuario: TESTUSER8172191615368042194
 * Contraseña: 7Kp1sZgt1P
 */

return [
    'access_token' => 'APP_USR-3999801925870316-102515-00e93a2d2c40477afacebd9aac9d7a4a-2902306082',
    'public_key' => 'APP_USR-bd05a90d-7070-4733-b58a-2dd28b1caa5a'
];
Paso 7: Configurar URLs de notificación (Webhooks)
Los webhooks permiten que MercadoPago notifique a tu sitio cuando un pago se completa.
    1. En el panel de tu aplicación, ve a "Webhooks" o "Notificaciones"
    2. Click en "Configurar notificaciones"
    3. Configuración:
        ◦ URL de producción: 
          https://proyecto.blisoft.com.uy/webhook.php
          O según tu estructura: 
          https://proyecto.blisoft.com.uy/index.php?r=pagos/webhook
    4. Eventos a recibir:
        ◦ ✅ payment (pagos) 
        ◦ ✅ merchant_order (órdenes) 
    5. Click en "Guardar"
Paso 8: Tarjetas de prueba para pagos
Cuando hagas pruebas, usa estas tarjetas de MercadoPago:
Para pagos APROBADOS:
Número: 4509 9535 6623 3704 (Visa)
O
Número: 5031 7557 3453 0604 (Mastercard)

CVV: 123
Fecha de expiración: Cualquier fecha futura (ej: 12/25)
Nombre del titular: APRO
DNI: 12345678
Para pagos RECHAZADOS:
Número: 4509 9535 6623 3704
CVV: 123
Fecha: 12/25
Nombre: OTRE (otros errores)
Para pagos PENDIENTES:
Nombre: CONT (en proceso)
Paso 9: Probar el flujo completo
    1. Inicia sesión en tu sitio 
    2. Selecciona números de una rifa 
    3. Click en "Pagar con MercadoPago" 
    4. Serás redirigido al checkout de MercadoPago 
    5. Usa las tarjetas de prueba 
    6. Completa el pago 
    7. Deberías volver a tu sitio con el pago confirmado 

9. Verificación Final
Verificar servicios en Docker:
# Ver contenedores corriendo
docker ps

# Ver logs en tiempo real
docker logs -f rifas_web
docker logs -f rifas_mysql

# Verificar Apache
docker exec -it rifas_web apache2ctl -t

# Verificar MySQL
docker exec -it rifas_mysql mysqladmin -uproy_root -p123456789 ping
Probar el sitio:
    1. Abrir en navegador:
        ◦ http://localhost (sitio principal) 
        ◦ http://localhost:8080 (phpMyAdmin) 
    2. Probar login con Google:
        ◦ Click en "Iniciar sesión con Google" 
        ◦ Autoriza la aplicación 
        ◦ Deberías ver tu nombre y avatar 
    3. Probar creación de rifa (como admin):
        ◦ Ve al panel de administración 
        ◦ Crea una rifa de prueba 
        ◦ Sube una imagen 
        ◦ Define números y precio 
    4. Probar compra de números:
        ◦ Como usuario normal (otra sesión/navegador) 
        ◦ Selecciona números 
        ◦ Procede al pago con MercadoPago 
        ◦ Usa tarjeta de prueba 
Verificar base de datos:
# Conectarse a MySQL
docker exec -it rifas_mysql mysql -uproy_root -p123456789 proy_rifas_db

# Verificar tablas
SHOW TABLES;

# Verificar estructura
DESCRIBE usuarios;
DESCRIBE rifas;
DESCRIBE numeros_rifa;
DESCRIBE reservas;
DESCRIBE pagos;

# Ver datos
SELECT * FROM usuarios;
SELECT * FROM rifas;

# Salir
exit;
Verificar permisos de archivos:
# Verificar permisos del directorio web
docker exec -it rifas_web ls -la /var/www/html/

# Verificar que uploads sea escribible
docker exec -it rifas_web ls -la /var/www/html/uploads/

# Si hay problemas de permisos
docker exec -it rifas_web chown -R www-data:www-data /var/www/html/uploads
docker exec -it rifas_web chmod -R 775 /var/www/html/uploads

10. Troubleshooting
Problema: Los contenedores no inician
# Ver logs detallados
docker logs rifas_web
docker logs rifas_mysql

# Reiniciar contenedores
docker restart rifas_web rifas_mysql

# Si persiste, eliminar y recrear
docker stop rifas_web rifas_mysql
docker rm rifas_web rifas_mysql

# Luego volver a ejecutar los comandos docker run del paso 3
Problema: Error de conexión a MySQL
# Verificar que MySQL esté corriendo
docker ps | grep mysql

# Probar conexión desde el contenedor web
docker exec -it rifas_web ping rifas_mysql

# Verificar credenciales
docker exec -it rifas_mysql mysql -uproy_root -p123456789 -e "SELECT 1"

# Revisar archivo config/database.php
nano ~/proyecto-rifas/web/config/database.php

# El host debe ser: 'rifas_mysql' (nombre del contenedor)
# No usar 'localhost'
Problema: PHP no se ejecuta (descarga archivos .php)
# Verificar que PHP esté instalado
docker exec -it rifas_web php -v

# Verificar configuración de Apache
docker exec -it rifas_web cat /etc/apache2/sites-enabled/
