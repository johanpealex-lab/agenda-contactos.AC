<?php

require_once __DIR__ . "/includes/verificar_sesion.php";
require_once __DIR__ . "/includes/csrf.php";
require_once __DIR__ . "/config/conexion.php";

// Obtener y validar el ID del contacto
$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id || $id <= 0) {
    header("Location: dashboard.php");
    exit();
}

// Buscar el contacto perteneciente al usuario
$sql = $conexion->prepare(
    "SELECT * FROM contactos
     WHERE id = ? AND id_usuario = ?"
);

$sql->execute([
    $id,
    $_SESSION["id_usuario"]
]);

$contacto = $sql->fetch();

if (!$contacto) {
    header("Location: dashboard.php");
    exit();
}

$error = "";

$nombre = $contacto["nombre"];
$telefono = $contacto["telefono"];
$correo = $contacto["correo"] ?? "";
$ciudad = $contacto["ciudad"] ?? "";

// Procesar actualización
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Comprobar el token CSRF
    if (!validar_csrf_token()) {

        $error = "La solicitud no es válida. Recarga la página e inténtalo nuevamente.";

    } else {

        $nombre = trim($_POST["nombre"] ?? "");
        $telefono = trim($_POST["telefono"] ?? "");
        $correo = trim($_POST["correo"] ?? "");
        $ciudad = trim($_POST["ciudad"] ?? "");

        if ($nombre === "" || $telefono === "") {
            $error = "El nombre y el teléfono son obligatorios.";

        } elseif (
            strlen($nombre) > 100 ||
            strlen($telefono) > 20 ||
            strlen($correo) > 150 ||
            strlen($ciudad) > 100
        ) {
            $error = "Uno de los campos supera la longitud permitida.";

        } elseif (
            $correo !== "" &&
            !filter_var($correo, FILTER_VALIDATE_EMAIL)
        ) {
            $error = "Ingresa un correo electrónico válido.";

        } else {

            $actualizar = $conexion->prepare(
                "UPDATE contactos
                 SET nombre = ?, telefono = ?, correo = ?, ciudad = ?
                 WHERE id = ? AND id_usuario = ?"
            );

            $actualizar->execute([
                $nombre,
                $telefono,
                $correo !== "" ? $correo : null,
                $ciudad !== "" ? $ciudad : null,
                $id,
                $_SESSION["id_usuario"]
            ]);

            header("Location: dashboard.php");
            exit();
        }
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar contacto</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>

<body class="pagina-formulario">

    <main class="formulario-contenedor">
        <section class="formulario-card">

            <h1>Editar contacto</h1>
            <p>Modifica la información de tu contacto.</p>

            <?php if ($error !== ""): ?>
                <div class="error">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="editar_contacto.php?id=<?= (int)$id ?>">

                <input
                    type="hidden"
                    name="csrf_token"
                    value="<?= htmlspecialchars($_SESSION["csrf_token"]) ?>">

                <label for="nombre">Nombre completo *</label>
                <input
                    type="text"
                    id="nombre"
                    name="nombre"
                    value="<?= htmlspecialchars($nombre) ?>"
                    maxlength="100"
                    required>

                <label for="telefono">Teléfono *</label>
                <input
                    type="text"
                    id="telefono"
                    name="telefono"
                    value="<?= htmlspecialchars($telefono) ?>"
                    maxlength="20"
                    required>

                <label for="correo">Correo electrónico</label>
                <input
                    type="email"
                    id="correo"
                    name="correo"
                    value="<?= htmlspecialchars($correo) ?>"
                    maxlength="150">

                <label for="ciudad">Ciudad</label>
                <input
                    type="text"
                    id="ciudad"
                    name="ciudad"
                    value="<?= htmlspecialchars($ciudad) ?>"
                    maxlength="100">

                <button type="submit">Guardar cambios</button>

            </form>

            <a href="dashboard.php" class="formulario-enlace">
                Volver a mi agenda
            </a>

        </section>
    </main>

</body>
</html>

