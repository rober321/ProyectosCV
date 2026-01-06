<?php
session_start();
include "conexion.php";

if (!isset($_SESSION['id_vendedor'])) {
    header("Location: Inicio.php");
    exit;
}

$id_vendedor = $_SESSION['id_vendedor'];

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID de producto inválido.");
}

$id_producto = intval($_GET['id']);

$stmt = $conexion->prepare("SELECT * FROM productos WHERE id = ?");
$stmt->bind_param("i", $id_producto);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Producto no encontrado.");
}

$producto = $result->fetch_assoc();

if ($producto['id_vendedor'] != $id_vendedor) {
    die(" No puedes editar un producto que no es tuyo.");
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $precio = floatval($_POST['precio'] ?? 0);

    if ($nombre === '' || $descripcion === '' || $precio <= 0) {
        die("Todos los campos son obligatorios y el precio debe ser mayor a 0.");
    }

    $stmt = $conexion->prepare("UPDATE productos SET nombre = ?, descripcion = ?, precio = ? WHERE id = ?");
    $stmt->bind_param("ssdi", $nombre, $descripcion, $precio, $id_producto);

    if ($stmt->execute()) {
        header("Location: PrimerPagina.php");
        exit;
    } else {
        die("Error al actualizar producto: " . $stmt->error);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Editar Producto</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-white">

<div class="container mt-5">
    <h2> Editar Producto</h2>

    <form action="" method="POST">
        <div class="mb-3">
            <label class="form-label">Nombre del producto</label>
            <input type="text" name="nombre" class="form-control" value="<?php echo htmlspecialchars($producto['nombre']); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea name="descripcion" class="form-control" required><?php echo htmlspecialchars($producto['descripcion']); ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Precio</label>
            <input type="number" step="0.01" name="precio" class="form-control" value="<?php echo htmlspecialchars($producto['precio']); ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Actualizar Producto</button>
        <a href="PrimerPagina.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

</body>
</html>
