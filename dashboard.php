
<?php

require_once __DIR__ . "/includes/verificar_sesion.php";
require_once __DIR__ . "/includes/csrf.php";
require_once __DIR__ . "/config/conexion.php";

// Obtener los filtros enviados
$busqueda = trim($_GET["buscar"] ?? "");
$orden = $_GET["orden"] ?? "recientes";

// Definir el ordenamiento permitido
switch ($orden) {
    case "nombre":
        $ordenSQL = "nombre ASC";
        break;

    case "ciudad":
        $ordenSQL = "ciudad ASC";
        break;

    default:
        $ordenSQL = "id DESC";
        $orden = "recientes";
        break;
}

// Consultar solamente los contactos del usuario autenticado
if ($busqueda !== "") {

    $sql = $conexion->prepare(
        "SELECT * FROM contactos
         WHERE id_usuario = ?
         AND (
             nombre LIKE ?
             OR correo LIKE ?
             OR ciudad LIKE ?
         )
         ORDER BY $ordenSQL"
    );

    $filtro = "%" . $busqueda . "%";

    $sql->execute([
        $_SESSION["id_usuario"],
        $filtro,
        $filtro,
        $filtro
    ]);

} else {

    $sql = $conexion->prepare(
        "SELECT * FROM contactos
         WHERE id_usuario = ?
         ORDER BY $ordenSQL"
    );

    $sql->execute([$_SESSION["id_usuario"]]);

}

$contactos = $sql->fetchAll();

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Mi agenda personal</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>

<body>

    <header class="encabezado">
        <div class="encabezado-contenido">

            <h1>Mi agenda personal</h1>

            <form method="POST" action="logout.php">
                <input
                    type="hidden"
                    name="csrf_token"
                    value="<?= htmlspecialchars($_SESSION["csrf_token"]) ?>">

                <button type="submit" class="btn-cerrar">
                    Cerrar sesión
                </button>
            </form>

        </div>
    </header>

    <main class="contenedor">

        <section class="tarjeta bienvenida">

            <div>
                <span class="etiqueta">PANEL PERSONAL</span>

                <h2>
                    ¡Bienvenido,
                    <?= htmlspecialchars($_SESSION["nombre"]) ?>!
                </h2>

                <p>
                    Correo electrónico:
                    <?= htmlspecialchars($_SESSION["correo"]) ?>
                </p>
            </div>

            <div class="icono-agenda">📒</div>

        </section>

        <section class="tarjeta">

            <div class="titulo-contactos">

                <div>
                    <h2>Mis contactos</h2>
                    <p>Administra tus contactos personales desde aquí.</p>
                </div>

                <a href="agregar_contacto.php" class="btn-agregar">
                    + Agregar contacto
                </a>

            </div>

            <!-- Buscador y ordenamiento -->
            <form method="GET" action="dashboard.php" class="buscador">

                <input
                    type="text"
                    name="buscar"
                    placeholder="Buscar por nombre, correo o ciudad..."
                    value="<?= htmlspecialchars($busqueda, ENT_QUOTES, 'UTF-8') ?>">

                <select name="orden">
                    <option value="recientes"
                        <?= $orden === "recientes" ? "selected" : "" ?>>
                        Más recientes
                    </option>

                    <option value="nombre"
                        <?= $orden === "nombre" ? "selected" : "" ?>>
                        Ordenar por nombre (A-Z)
                    </option>

                    <option value="ciudad"
                        <?= $orden === "ciudad" ? "selected" : "" ?>>
                        Ordenar por ciudad (A-Z)
                    </option>
                </select>

                <button type="submit" class="btn-agregar">
                    Aplicar
                </button>

                <?php if ($busqueda !== "" || $orden !== "recientes"): ?>
                    <a href="dashboard.php" class="btn-editar">
                        Limpiar
                    </a>
                <?php endif; ?>

            </form>

            <?php if (count($contactos) > 0): ?>

                <div class="tabla-contenedor">

                    <table>

                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Teléfono</th>
                                <th>Correo electrónico</th>
                                <th>Ciudad</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php foreach ($contactos as $contacto): ?>

                                <tr>

                                    <td>
                                        <strong>
                                            <?= htmlspecialchars($contacto["nombre"]) ?>
                                        </strong>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($contacto["telefono"]) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($contacto["correo"] ?? "") ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($contacto["ciudad"] ?? "") ?>
                                    </td>

                                    <td class="acciones">

                                        <a
                                            href="editar_contacto.php?id=<?= (int)$contacto["id"] ?>"
                                            class="btn-editar">
                                            Editar
                                        </a>

                                        <form
                                            method="POST"
                                            action="eliminar_contacto.php"
                                            onsubmit="return confirm('¿Estás seguro de que deseas eliminar este contacto?');">

                                            <input
                                                type="hidden"
                                                name="csrf_token"
                                                value="<?= htmlspecialchars($_SESSION["csrf_token"]) ?>">

                                            <input
                                                type="hidden"
                                                name="id"
                                                value="<?= (int)$contacto["id"] ?>">

                                            <button type="submit" class="btn-eliminar">
                                                Eliminar
                                            </button>

                                        </form>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

            <?php else: ?>

                <div class="sin-contactos">

                    <div class="icono-vacio">📇</div>

                    <?php if ($busqueda !== ""): ?>

                        <h3>No se encontraron contactos</h3>

                        <p>
                            No hay contactos que coincidan con:
                            <strong><?= htmlspecialchars($busqueda) ?></strong>
                        </p>

                    <?php else: ?>

                        <h3>Aún no tienes contactos</h3>

                        <p>
                            Agrega tu primer contacto para comenzar a organizar tu agenda.
                        </p>

                    <?php endif; ?>

                    <a href="dashboard.php" class="btn-agregar">
                        Ver todos los contactos
                    </a>

                </div>

            <?php endif; ?>

        </section>

        <footer class="pie">
            Mi agenda personal de contactos
        </footer>

    </main>

</body>
</html>

