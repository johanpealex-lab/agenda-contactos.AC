<?php

// Iniciar sesión si todavía no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Crear un token de seguridad
if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

// Validar el token enviado por el formulario
function validar_csrf_token(): bool
{
    return isset($_POST["csrf_token"], $_SESSION["csrf_token"])
        && is_string($_POST["csrf_token"])
        && hash_equals($_SESSION["csrf_token"], $_POST["csrf_token"]);
}

?>
