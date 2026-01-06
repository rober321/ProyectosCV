<?php
$conexion = mysqli_connect("localhost", "root", "", "roberbloster");

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}
?>
