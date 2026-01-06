<?php
session_start();
include "conexion.php";

// Redirigir si no hay sesión
if (!isset($_SESSION['id_vendedor'])) {
    header("Location: Inicio.html");
    exit;
}

$idVendedor = $_SESSION['id_vendedor'];
$nombreUsuario = $_SESSION['nombre'];

// Consulta para obtener todos los productos
$query = "SELECT * FROM productos ORDER BY id DESC";
$resultado = mysqli_query($conexion, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - RobeBuster</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .navbar-custom { background-color: #00b4ff; color: white; padding: 1rem; }
        .card-producto { transition: transform 0.2s; }
        .card-producto:hover { transform: scale(1.02); }
    </style>
</head>
<body>

<div class="navbar-custom shadow-sm mb-4 text-center">
    <h1>🎬 RobeBuster</h1>
    <p>Bienvenido, <strong><?php echo htmlspecialchars($nombreUsuario); ?></strong></p>
    <a href="logout.php" class="btn btn-outline-light btn-sm">Cerrar sesión</a>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="card-title">+ Publicar Producto</h5>
                    <form action="agregar_producto.php" method="POST">
                        <input type="text" name="nombre" class="form-control mb-2" placeholder="Nombre" required>
                        <textarea name="descripcion" class="form-control mb-2" placeholder="Descripción" required></textarea>
                        <input type="number" step="0.01" name="precio" class="form-control mb-3" placeholder="Precio $" required>
                        <button class="btn btn-primary w-100">Publicar</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <h3>Catálogo de Productos</h3>
            <div class="row">
                <?php while ($prod = mysqli_fetch_assoc($resultado)): ?>
                <div class="col-md-6 mb-3">
                    <div class="card card-producto h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($prod['nombre']); ?></h5>
                            <p class="card-text small text-muted"><?php echo htmlspecialchars($prod['descripcion']); ?></p>
                            <h6 class="text-success">$<?php echo number_format($prod['precio'], 2); ?></h6>
                        </div>
                        <div class="card-footer bg-transparent border-top-0 text-end">
                            <?php if ($prod['id_vendedor'] == $idVendedor): ?>
                                <a href="editar_producto.php?id=<?php echo $prod['id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                                <a href="eliminar_producto.php?id=<?php echo $prod['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar?');">Eliminar</a>
                            <?php else: ?>
                                <small class="text-muted italic">Vendedor ID: <?php echo $prod['id_vendedor']; ?></small>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
</div>

</body>
</html>