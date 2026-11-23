
<?php
require_once __DIR__ . "/config/conexion.php";

$error = "";
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nombre = trim($_POST["nombre"] ?? "");
    $correo = trim($_POST["correo"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($nombre === "" || $correo === "" || $password === "") {
        $error = "Completa todos los campos.";
    } elseif (strlen($nombre) > 100) {
        $error = "El nombre es demasiado largo.";
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL) || strlen($correo) > 150) {
        $error = "Ingresa un correo electrónico válido.";
    } elseif (strlen($password) < 8) {
        $error = "La contraseña debe tener al menos 8 caracteres.";
    } else {

        $consulta = $conexion->prepare(
            "SELECT id FROM usuarios WHERE correo = ?"
        );

        $consulta->execute([$correo]);

        if ($consulta->fetch()) {
            $error = "Este correo electrónico ya está registrado.";
        } else {

            $password_seguro = password_hash($password, PASSWORD_DEFAULT);

            $sql = $conexion->prepare(
                "INSERT INTO usuarios (nombre, correo, password)
                 VALUES (?, ?, ?)"
            );

            $sql->execute([
                $nombre,
                $correo,
                $password_seguro
            ]);

            $mensaje = "Tu cuenta fue creada correctamente. Ya puedes iniciar sesión.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear cuenta</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>

<body class="pagina-formulario">

    <main class="formulario-contenedor">
        <section class="formulario-card">

            <h1>Crear una cuenta</h1>
            <p>Regístrate para comenzar a organizar tus contactos.</p>

            <?php if ($error !== ""): ?>
                <div class="error">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <?php if ($mensaje !== ""): ?>
                <div class="mensaje">
                    <?= htmlspecialchars($mensaje) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="registro.php">

                <label for="nombre">Nombre completo</label>
                <input
                    type="text"
                    id="nombre"
                    name="nombre"
                    placeholder="Ingresa tu nombre"
                    value="<?= htmlspecialchars($_POST["nombre"] ?? "") ?>"
                    maxlength="100"
                    required>

                <label for="correo">Correo electrónico</label>
                <input
                    type="email"
                    id="correo"
                    name="correo"
                    placeholder="ejemplo@correo.com"
                    value="<?= htmlspecialchars($_POST["correo"] ?? "") ?>"
                    maxlength="150"
                    required>

                <label for="password">Contraseña</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Mínimo 8 caracteres"
                    minlength="8"
                    required>

                <button type="submit">Registrarme</button>

            </form>

            <a href="login.php" class="formulario-enlace">
                ¿Ya tienes una cuenta? Inicia sesión
            </a>

        </section>
    </main>

</body>
</html>
