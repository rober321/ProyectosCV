<?php
session_start();
include "conexion.php";

if (!isset($_SESSION['id_vendedor'])) {
    header("Location: Inicio.php");
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID de producto inválido.");
}

$id_producto = intval($_GET['id']);
$id_vendedor = $_SESSION['id_vendedor'];

$stmt = $conexion->prepare("SELECT id_vendedor FROM productos WHERE id = ?");
$stmt->bind_param("i", $id_producto);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("El producto no existe.");
}

$producto = $result->fetch_assoc();

if ($producto['id_vendedor'] != $id_vendedor) {
    die("No puedes eliminar un producto que no es tuyo.");
}

$stmt = $conexion->prepare("DELETE FROM productos WHERE id = ?");
$stmt->bind_param("i", $id_producto);

if ($stmt->execute()) {
    header("Location: PrimerPagina.php");
    exit;
} else {
    die("Error al eliminar el producto: " . $stmt->error);
}

$stmt->close();
?>
