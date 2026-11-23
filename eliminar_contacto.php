<?php

require_once __DIR__ . "/includes/verificar_sesion.php";
require_once __DIR__ . "/includes/csrf.php";
require_once __DIR__ . "/config/conexion.php";

// Aceptar únicamente solicitudes POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: dashboard.php");
    exit();
}

// Validar el token CSRF
if (!validar_csrf_token()) {
    http_response_code(403);
    exit("La solicitud no es válida. Regresa a tu agenda e inténtalo nuevamente.");
}

// Validar el ID recibido
$id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

if (!$id || $id <= 0) {
    header("Location: dashboard.php");
    exit();
}

// Eliminar únicamente un contacto del usuario autenticado
$sql = $conexion->prepare(
    "DELETE FROM contactos
     WHERE id = ? AND id_usuario = ?"
);

$sql->execute([
    $id,
    $_SESSION["id_usuario"]
]);

header("Location: dashboard.php");
exit();

?>

