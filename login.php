
<?php
session_start();

require_once __DIR__ . "/config/conexion.php";

// Si ya inició sesión, ir al dashboard
if (isset($_SESSION["id_usuario"])) {
    header("Location: dashboard.php");
    exit();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $correo = trim($_POST["correo"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($correo === "" || $password === "") {
        $error = "Completa todos los campos.";
    } else {
        $sql = $conexion->prepare(
            "SELECT * FROM usuarios WHERE correo = ?"
        );

        $sql->execute([$correo]);
        $usuario = $sql->fetch();

        if ($usuario && password_verify($password, $usuario["password"])) {

            session_regenerate_id(true);

            $_SESSION["id_usuario"] = $usuario["id"];
            $_SESSION["nombre"] = $usuario["nombre"];
            $_SESSION["correo"] = $usuario["correo"];

            header("Location: dashboard.php");
            exit();

        } else {
            $error = "Correo electrónico o contraseña incorrectos.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>

<body class="pagina-formulario">

    <main class="formulario-contenedor">
        <section class="formulario-card">

            <h1>Mi agenda personal</h1>
            <p>Ingresa a tu cuenta para administrar tus contactos.</p>

            <?php if ($error !== ""): ?>
                <div class="error">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="login.php">

                <label for="correo">Correo electrónico</label>
                <input
                    type="email"
                    id="correo"
                    name="correo"
                    placeholder="Ingresa tu correo"
                    value="<?= htmlspecialchars($_POST["correo"] ?? "") ?>"
                    required>

                <label for="password">Contraseña</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Ingresa tu contraseña"
                    required>

                <button type="submit">Iniciar sesión</button>

            </form>

            <a href="registro.php" class="formulario-enlace">
                ¿No tienes una cuenta? Regístrate aquí
            </a>

        </section>
    </main>

</body>
</html>

