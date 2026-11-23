<?php

require_once __DIR__ . "/includes/verificar_sesion.php";
require_once __DIR__ . "/includes/csrf.php";
require_once __DIR__ . "/config/conexion.php";

$error = "";

$nombre = "";
$telefono = "";
$correo = "";
$ciudad = "";

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

            $sql = $conexion->prepare(
                "INSERT INTO contactos
                (id_usuario, nombre, telefono, correo, ciudad)
                VALUES (?, ?, ?, ?, ?)"
            );

            $sql->execute([
                $_SESSION["id_usuario"],
                $nombre,
                $telefono,
                $correo !== "" ? $correo : null,
                $ciudad !== "" ? $ciudad : null
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
    <title>Agregar contacto</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>

<body class="pagina-formulario">

    <main class="formulario-contenedor">
        <section class="formulario-card">

            <h1>Agregar contacto</h1>
            <p>Completa la información de tu nuevo contacto.</p>

            <?php if ($error !== ""): ?>
                <div class="error">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="agregar_contacto.php">

                <input
                    type="hidden"
                    name="csrf_token"
                    value="<?= htmlspecialchars($_SESSION["csrf_token"]) ?>">

                <label for="nombre">Nombre completo *</label>
                <input
                    type="text"
                    id="nombre"
                    name="nombre"
                    placeholder="Nombre del contacto"
                    value="<?= htmlspecialchars($nombre) ?>"
                    maxlength="100"
                    required>

                <label for="telefono">Teléfono *</label>
                <input
                    type="text"
                    id="telefono"
                    name="telefono"
                    placeholder="Número de teléfono"
                    value="<?= htmlspecialchars($telefono) ?>"
                    maxlength="20"
                    required>

                <label for="correo">Correo electrónico</label>
                <input
                    type="email"
                    id="correo"
                    name="correo"
                    placeholder="Correo del contacto"
                    value="<?= htmlspecialchars($correo) ?>"
                    maxlength="150">

                <label for="ciudad">Ciudad</label>
                <input
                    type="text"
                    id="ciudad"
                    name="ciudad"
                    placeholder="Ciudad del contacto"
                    value="<?= htmlspecialchars($ciudad) ?>"
                    maxlength="100">

                <button type="submit">Guardar contacto</button>

            </form>

            <a href="dashboard.php" class="formulario-enlace">
                Volver a mi agenda
            </a>

        </section>
    </main>

</body>
</html>
