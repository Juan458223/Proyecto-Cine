# Proyecto Cine - Sistema de Autenticación Avanzada

Este proyecto es una aplicación web para la gestión de un cine, modernizada con una arquitectura profesional y un sistema de seguridad de dos factores (2FA).

## Requisitos Previos
Para ejecutar esta aplicación, asegúrate de tener instalados los siguientes componentes:

*   **PHP 8.0 o superior**
*   **MySQL 5.7 o superior**
*   **Composer** (Gestor de dependencias de PHP)
*   **Node.js y npm** (Para la gestión de estilos con Tailwind CSS y Vite)
*   **Servidor Web** (XAMPP, Laragon, Apache, etc.)

## Instalación y Configuración

### 1. Clonar y Preparar el Proyecto
Navega a la carpeta raíz del proyecto y ejecuta los siguientes comandos para instalar todas las librerías necesarias:

```bash
# Instalar dependencias de PHP (PHPMailer, etc.)
composer install

# Instalar dependencias de Frontend (Tailwind CSS, Vite)
npm install
```

### 2. Configuración de la Base de Datos
1.  Importa el archivo `Database/cine.sql` en tu servidor MySQL (puedes usar PHPMyAdmin o MySQL Workbench).
2.  Verifica que las credenciales de conexión en `src/Core/DatabaseConnection.php` coincidan con tu servidor local.

### 3. Configuración de Correo (SMTP)
El sistema utiliza Gmail para enviar los códigos de verificación. Las credenciales están configuradas en `src/Core/SmtpConfig.php`.
> **Nota:** Si cambias la cuenta de correo, asegúrate de usar una "Contraseña de Aplicación" de Google, no tu contraseña normal.

### 4. Ejecutar el Proyecto
Para ver los cambios de estilos en tiempo real y habilitar Tailwind CSS v4, inicia el servidor de desarrollo de Vite:

```bash
npm run dev
```

Luego, abre tu navegador y accede a la carpeta `public/` a través de tu servidor local (ej: `http://localhost/Proyecto-Cine/public/`).

## ¿Por qué demora la validación?
Al iniciar sesión, notarás una pequeña pausa antes de que aparezca el modal de verificación. Esto **no es un error**, sino una consecuencia del proceso de seguridad:

*   **Conexión SMTP:** El servidor debe negociar una conexión cifrada con los servidores de Google para enviar el correo.
*   **Latencia de Red:** El envío de un paquete de datos a través de internet para la entrega del mensaje toma entre 1 y 3 segundos dependiendo de la conexión.
*   **Seguridad:** Este tiempo extra garantiza que el código sea enviado antes de permitir que el usuario intente validarlo.

## Arquitectura
La aplicación sigue el patrón **Service-DAO-DTO**, lo que permite una separación clara entre la interfaz de usuario, la lógica de negocio y el acceso a los datos.
