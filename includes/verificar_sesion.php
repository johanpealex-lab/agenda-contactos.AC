
<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Comprobar si existe un usuario autenticado
if (!isset($_SESSION["id_usuario"])) {

    header("Location: login.php");
    exit();

}

?>
