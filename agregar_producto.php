<?php
session_start();
include "conexion.php";

if (!isset($_SESSION['id_vendedor'])) {
    header("Location: Inicio.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id_vendedor = $_SESSION['id_vendedor'];
    $nombre = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $precio = floatval($_POST['precio'] ?? 0);

    if ($nombre === '' || $descripcion === '' || $precio <= 0) {
        die("Todos los campos son obligatorios y el precio debe ser mayor a 0.");
    }

    $stmt = $conexion->prepare("INSERT INTO productos (id_vendedor, nombre, descripcion, precio) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        die("Error en la preparación de la consulta: " . $conexion->error);
    }

    $stmt->bind_param("issd", $id_vendedor, $nombre, $descripcion, $precio);
    
    if ($stmt->execute()) {

        header("Location: PrimerPagina.php");
        exit;
    } else {
        die("Error al insertar el producto: " . $stmt->error);
    }

    $stmt->close();
}
?>
