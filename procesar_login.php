<?php
session_start();
include "conexion.php";

$nombre = trim($_POST['nombre']);
$correo = trim($_POST['correo']);

if ($nombre === '' || $correo === '') {
    die("Todos los campos son obligatorios.");
}

$stmt = $conexion->prepare("SELECT id, nombre FROM vendedores WHERE correo = ?");
$stmt->bind_param("s", $correo);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $usuario = $result->fetch_assoc();
    $_SESSION['id_vendedor'] = $usuario['id'];
    $_SESSION['nombre'] = $usuario['nombre'];
} else {
    $stmt = $conexion->prepare("INSERT INTO vendedores (nombre, correo) VALUES (?, ?)");
    $stmt->bind_param("ss", $nombre, $correo);

    if ($stmt->execute()) {
        $_SESSION['id_vendedor'] = $stmt->insert_id;
        $_SESSION['nombre'] = $nombre;
    } else {
        die("Error al crear usuario: " . $stmt->error);
    }
}

header("Location: PrimerPagina.php");
exit;
?>
