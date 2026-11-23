<?php
$host = "sql302.infinityfree.com";
$usuario = "if0_43502824";
$password = "9USqr5l0OY8OJQ";
$base_datos = "if0_43502824_agenda";

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
