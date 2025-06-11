<?php
session_start();
require_once "../config.php";
require_once "../controllers/verificar_acceso.php";


// Verificar acceso de administrador
if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit();
}

// Obtener lista de usuarios
$stmt = $conexion->query("SELECT usuarios.id, usuarios.usuario, roles.nombre FROM usuarios INNER JOIN roles on usuarios.rol_id = roles.id ");
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<?php include("header.php"); ?>

<h2 class="mb-4 mt-5" style=" margin-left:10%;">
<i class="fa-solid fa-users-gear text-primary"></i>
Gestión de Usuarios
</h2>

<div class="table-container" style="max-width: 50%; margin:auto;">
    

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="text-center text-white">
                <tr>
                    <th>
                        <i class="fas fa-hashtag me-1"></i>
                        ID
                    </th>
                    <th>
                        <i class="fa-solid fa-user-tie"></i>
                        Usuario
                    </th>
                    <th>
                        <i class="fa-solid fa-dice"></i>
                        Rol
                    </th>
                    <th>
                        <i class="fas fa-cogs me-1"></i>
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <td class="text-center fw-bold">
                            <?= $usuario["id"]; ?>
                        </td>
                        <td>
                            <i class='fas fa-user-circle text-primary me-2'></i>
                            <?= htmlspecialchars($usuario["usuario"]); ?>
                        </td>
                        <td>
                            <i class="fa-solid fa-user-shield"></i>
                            <?= htmlspecialchars($usuario["nombre"]); ?>
                        </td>
                        <td class="text-center">
                            <a href="editar_usuario.php?id=<?= $usuario["id"]; ?>" class="btn btn-warning btn-sm me-1" title="editar usuario">
                                <i class='fas fa-edit'></i>
                            </a>
                            <a href="../controllers/procesar_usuario.php?eliminar=<?= $usuario["id"]; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar usuario?');" title="eliminar usuario">
                                <i class='fas fa-trash'></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>
<div class="text-center back-btn-container">
        <a href="/negocio_cachamas/index.php" class="btn btn-secondary btn-lg">
            <i class="fas fa-home me-2"></i>
            Volver al Inicio
        </a>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

