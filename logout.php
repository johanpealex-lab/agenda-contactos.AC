<?php

require_once __DIR__ . "/includes/verificar_sesion.php";
require_once __DIR__ . "/includes/csrf.php";

// Aceptar únicamente solicitudes POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: dashboard.php");
    exit();
}

// Validar el token CSRF
if (!validar_csrf_token()) {
    http_response_code(403);
    exit("La solicitud no es válida.");
}

// Vaciar los datos de la sesión
$_SESSION = [];

// Eliminar la sesión
session_destroy();

// Regresar al inicio de sesión
header("Location: login.php");
exit();

?>
