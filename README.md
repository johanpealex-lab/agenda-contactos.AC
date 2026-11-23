# agenda-contactos.AC

URL pública

La aplicación web se encuentra alojada en InfinityFree y permite gestionar una agenda personal de contactos desde un navegador web.

Enlace: http://agenda-contactos.42web.io

2. Capturas de pantalla
3. Formulario de registro
4. <img width="886" height="498" alt="image" src="https://github.com/user-attachments/assets/00107b12-f1db-449a-8039-faf07bf3097e" />
Inicio de sesión
<img width="886" height="498" alt="image" src="https://github.com/user-attachments/assets/51aeb11a-cbac-411d-975b-ed490069cdeb" />
Panel principal
<img width="886" height="498" alt="image" src="https://github.com/user-attachments/assets/64208b82-1aa4-4ce0-b1f7-95850359ddca" />
Búsqueda de contactos
<img width="886" height="498" alt="image" src="https://github.com/user-attachments/assets/5d8dad09-237e-4d7e-bcc5-4b4775f49f0e" />
Ordenamiento
<img width="886" height="498" alt="image" src="https://github.com/user-attachments/assets/81f750be-ec31-439b-81d5-b3015b339d2f" />
Agregar contacto
<img width="886" height="498" alt="image" src="https://github.com/user-attachments/assets/9a0b02f3-b511-443e-9462-dd12cb04e825" />
Base de datos
<img width="886" height="498" alt="image" src="https://github.com/user-attachments/assets/db0d2db7-75d7-4cc5-9a36-298c9acc551a" />

Tecnologías utilizadas

Para el desarrollo de la aplicación se utilizaron las siguientes tecnologías:

HTML5: estructura de las páginas web.

CSS: diseño y presentación visual de la aplicación.

PHP: programación de la lógica del sistema y procesamiento de formularios.

MySQL: almacenamiento y administración de usuarios y contactos.

PDO: conexión y ejecución de consultas a la base de datos.

XAMPP: entorno de desarrollo local para ejecutar Apache y MySQL.

Visual Studio Code: editor utilizado para desarrollar el proyecto.

InfinityFree: servicio de alojamiento web gratuito.

GitHub: almacenamiento y publicación del código fuente


Instrucciones para instalar el proyecto localmente

Para ejecutar el proyecto en un entorno local se deben seguir los siguientes pasos:

Requisitos previos:

Tener instalado XAMPP.

Tener instalado Visual Studio Code.

Contar con los archivos del proyecto y el script database.sql.

Procedimiento de instalación:

Descargar o clonar el repositorio de GitHub.

Copiar la carpeta agenda_contactos dentro del directorio htdocs de XAMPP.

Abrir el panel de control de XAMPP e iniciar los servicios Apache y MySQL.

Acceder a phpMyAdmin desde http://localhost/phpmyadmin.

Crear una base de datos llamada agenda_contactos.

Seleccionar la base de datos creada y entrar en la opción Importar.

Seleccionar el archivo database.sql e importar las tablas y sus registros.

Abrir el archivo config/conexion.php y configurar la conexión local:

<?php
$host = "localhost";
$usuario = "root";
$password = "";
$base_datos = "agenda_contactos";

try {
    $conexion = new PDO(
        "mysql:host=$host;dbname=$base_datos;charset=utf8mb4",
        $usuario,
        $password
    );

    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conexion->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Error al conectar con la base de datos.");
}
?>

Guardar los cambios y abrir el navegador.

Ingresar a la dirección http://localhost/agenda_contactos/ para utilizar la aplicación.


Detalle de las mejoras respecto a la versión anterior

En esta versión se incorporaron dos mejoras para facilitar la búsqueda y organización de los contactos registrados.

A. Búsqueda de contactos

Se implementó un buscador que permite localizar contactos mediante los siguientes campos:


Nombre.

Correo electrónico.

Ciudad.

Esta mejora facilita encontrar información específica sin tener que revisar todos los contactos manualmente.

B. Ordenamiento de contactos

Se incorporó una opción para ordenar los contactos según diferentes criterios:

Por nombre: organiza los contactos alfabéticamente.

Por ciudad: permite agruparlos según su ubicación.

Más recientes: muestra primero los contactos agregados recientemente.

Estas mejoras permiten administrar la información de manera más organizada y sencilla, mejorando la experiencia de uso de la aplicación.
