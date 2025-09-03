#!/bin/bash

# ==========================================================
# Script de automatización: Creación de usuarios y llaves SSH
# Para Ubuntu Server - Práctico Obligatorio
# ==========================================================

# Verificación de root
if [ "$(id -u)" -ne 0 ]; then
    echo "  Este script debe ejecutarse como root."
    exit 1
fi

# Archivo de usuarios
USERS_FILE="usuarios.csv"

# ==========================================================
# 1. Instalar y habilitar SSH
# ==========================================================
echo "Instalando OpenSSH Server..."
apt update -y
apt install -y openssh-server

echo "Habilitando y arrancando el servicio SSH..."
systemctl enable ssh
systemctl start ssh

# ==========================================================
# 2. Configuración del firewall (UFW)
# ==========================================================
echo "Configurando firewall (UFW)..."
apt install -y ufw
ufw allow ssh
ufw --force enable

# ==========================================================
# 3. Configuración de seguridad SSH
# ==========================================================
echo "Asegurando configuración de SSH..."
SSHD_CONFIG="/etc/ssh/sshd_config"
sed -i 's/#\?PasswordAuthentication yes/PasswordAuthentication yes/' $SSHD_CONFIG
sed -i 's/#\?PubkeyAuthentication no/PubkeyAuthentication yes/' $SSHD_CONFIG
systemctl restart ssh

# ==========================================================
# 4. Creación de usuarios desde CSV
# ==========================================================
if [ ! -f "$USERS_FILE" ]; then
    echo "No se encontró el archivo $USERS_FILE"
    exit 1
fi

echo "Creando usuarios desde $USERS_FILE..."

while IFS=',' read -r usuario clave rol; do
    usuario=$(echo $usuario | tr -d '"')
    clave=$(echo $clave | tr -d '"')
    rol=$(echo $rol | tr -d '"')

    echo "Creando usuario: $usuario (rol: $rol)"

    # Crear usuario con su home y bash
    useradd -m -s /bin/bash "$usuario"

    # Asignar contraseña
    echo "$usuario:$clave" | chpasswd

    # Crear grupo de rol si no existe y asignar
    if ! getent group "$rol" >/dev/null; then
        groupadd "$rol"
    fi
    usermod -aG "$rol" "$usuario"

    # Generar par de claves SSH en el home del usuario
    sudo -u "$usuario" ssh-keygen -t rsa -b 2048 -f "/home/$usuario/.ssh/id_rsa" -q -N ""

    # Copiar clave pública a authorized_keys
    mkdir -p /home/$usuario/.ssh
    cat /home/$usuario/.ssh/id_rsa.pub >> /home/$usuario/.ssh/authorized_keys
    chown -R $usuario:$usuario /home/$usuario/.ssh
    chmod 700 /home/$usuario/.ssh
    chmod 600 /home/$usuario/.ssh/authorized_keys

done < <(tail -n +2 "$USERS_FILE")   # Saltar cabecera del CSV

echo "Proceso completado. Los usuarios ya pueden acceder vía SSH."
echo "Ejemplo de conexión: ssh usuario@IP_SERVIDOR"