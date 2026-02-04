# Momentum - Gestor de Trucos de Skate

Momentum es una aplicación web progresiva construida con **HTML, CSS (Diseño Responsive), JavaScript y PHP** que permite a los skaters registrar, gestionar y visualizar sus trucos.

El proyecto está diseñado con un enfoque **Mobile-First**, asegurando una experiencia perfecta tanto en dispositivos móviles como en escritorio.

## Autores

- [@edualcrd](https://github.com/edualcrd)
- [@guillecaceres24](https://github.com/guillecaceres24)
- [@pabloleonptricker](https://github.com/pabloleonptricker)
- [@gonzalogilabert](https://github.com/gonzalogilabert)

## Características Principales

1.  **Autenticación Completa:** Registro e Inicio de Sesión.
2.  **Perfil de Usuario:** Personalización de foto, biografía y grupo de skate.
3.  **Gestión de Trucos:** Subida de fotos o videos de trucos con fecha y nombre.
4.  **Diseño Responsive:** Adaptable a cualquier tamaño de pantalla (Desktop/Tablet/Móvil).
5.  **Navegación Fluida:** Flujo intuitivo entre Login -> Perfil -> Añadir Truco -> Perfil.

## Requisitos de Instalación

Para ejecutar este proyecto necesitas un servidor local tipo **XAMPP**, **WAMP** o **MAMP** que incluya:
- PHP 7.4 o superior.
- MySQL / MariaDB.
- Servidor Apache.

## Instrucciones de Despliegue

### Paso 1: Colocar los archivos
1. Ve a la carpeta `htdocs` de tu instalación de XAMPP (o `www` en WAMP).
2. Crea una carpeta llamada `Momentum`.
3. Pega todos los archivos del proyecto dentro, manteniendo la estructura de carpetas original.
4. **IMPORTANTE:** Asegúrate de crear una carpeta llamada `uploads` en la raíz (`Momentum/uploads`) y dale permisos de escritura.

### Paso 2: Base de Datos
1. Abre **phpMyAdmin** (normalmente `http://localhost/phpmyadmin`).
2. Crea una nueva base de datos llamada `momentum_db`.
3. Importa el script SQL proporcionado o ejecuta las consultas manualmente (ver archivo `database.sql`).
4. Revisa el archivo `php/conexion.php` y asegúrate de que las credenciales coincidan con las de tu servidor (por defecto usuario `root` y contraseña vacía).

### Paso 3: Ejecución
1. Abre tu navegador web.
2. Navega a: `http://localhost/Momentum/authentication/signIn/index.html` para registrarte o `http://localhost/Momentum/authentication/logIn/logIn.php` si ya tienes cuenta.

## Navegación y Usabilidad (RA6)

El proyecto asegura una navegación fluida:
- Si intentas acceder a `main/index.php` sin loguearte, serás redirigido al Login.
- Desde el perfil, puedes acceder fácilmente a "Añadir Truco" y volver mediante botones claramente ubicados.
- Las flechas del carrusel y los iconos de edición son accesibles y funcionales.

## Diseño Responsive

Se han utilizado *Media Queries* en CSS para ajustar el layout:
- **Escritorio:** Visualización amplia, carrusel horizontal extendido y perfil a la izquierda.
- **Móvil:** Elementos apilados verticalmente (Flexbox direction column), botones más grandes para facilitar el toque táctil y menús adaptados.