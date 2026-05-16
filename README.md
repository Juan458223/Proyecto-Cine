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


Este es un proyecto de la universidad que consiste en crear una aplicacion para internet de lo siguiente: 

- Enunciado
    
    La asociación de cines de una ciudad quiere crear un servicio telefónico en el que se pueda hacer cualquier tipo de consulta sobre las películas que se están proyectando actualmente: 
    
    - En qué cines hacen una determinada película 
    - El horario de los pases
    - Qué películas de dibujos animados se están proyectando y dónde
    - Qué películas hay en un determinado cine
    
    Para ello debemos diseñar una base de datos relacional que contenga toda esta información.
    En concreto, para cada cine:
    
    - Se debe dar el título de la película y el horario de las funciones, cada pelicula debe contener:
        - El nombre del director de la misma, el nombre de hasta tres de sus protagonistas
        - El género (comedia, intriga, etc.) 
        - La clasificación (tolerada menores, mayores de 18 años, etc.). 
    - La calle y número donde está el cine
    - El teléfono 
    - Los distintos precios según el día (día del espectador, día del jubilado, festivos y vísperas, carnet de estudiante, etc.).

    Hay que tener en cuenta:
    - Algunos cines tienen varias salas en las que se pasan distintas películas 
    - En un mismo cine se pueden pasar películas distintas en diferentes pases. 
    
    A continuación se muestra un ejemplo de la información que los cines proporcionarán al nuevo servicio telefónico.

    ![ejemplos cine](public/img/ejemplos.png)
    

Adicionalmente de esto nosotros vamos a hacer que dentroo de la interfaz las personas puedan reservar sus entradas y añadir obviamente un limite de compra
por funcion, a continuacion agregamos el modelo de la base de datos.

![base de datos](public/img/database.png)

Por esto mismo nosotros nos dividiremos el trabajo

- Juan Rodriguez (JDRFdev): Se encargara de el frontend, usando bootstrap, sweetalert, tailwind entre otras, ademas de las validaciones.
- Santiago Tejero (TellMeT): Se encargara de el backend, es decir, bases de datos, crud y logica del negocio.


Asi mismo nosotros vimos que este proyecto nos puede servir para mostrar habilidades en campos especificos, por esto mismo el enfoque en mostrar
la division de trabajo.