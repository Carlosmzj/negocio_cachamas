<?php
require_once "../config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $telefono = $_POST["telefono"];
    $email = $_POST["email"];
    $direccion = $_POST["direccion"];

    $stmt = $conexion->prepare("INSERT INTO clientes (nombre, telefono, email, direccion) VALUES (:nombre, :telefono, :email, :direccion)");
    $stmt->bindParam(":nombre", $nombre);
    $stmt->bindParam(":telefono", $telefono);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":direccion", $direccion);
    $stmt->execute();

    header("Location: clientes.php?mensaje=Cliente registrado");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Clientes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="/negocio_cachamas/assets/css/style.css" rel="stylesheet">
</head>
<body>

<?php include("header.php"); ?>

<div class="container-fluid mt-4">
    <h2 class="section-title text-center w-100">
        <i class="fas fa-user-plus text-primary"></i>
        Registrar Cliente
    </h2>
    
    <div class="row justify-content-center">
        <div class="col-md-6 col-sm-12">
            <form action="clientes.php" method="POST" class="form-container p-4">
                <div class="mb-3">
                    <label for="nombre" class="form-label">
                        <i class="fas fa-user text-primary"></i>
                        Nombre:
                    </label>
                    <input type="text" name="nombre" id="nombre" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="telefono" class="form-label">
                        <i class="fas fa-phone text-success"></i>
                        Teléfono:
                    </label>
                    <input type="text" name="telefono" id="telefono" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">
                        <i class="fas fa-envelope text-info"></i>
                        Email:
                    </label>
                    <input type="email" name="email" id="email" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="direccion" class="form-label">
                        <i class="fas fa-map-marker-alt text-warning"></i>
                        Dirección:
                    </label>
                    <textarea name="direccion" id="direccion" class="form-control" rows="3"></textarea>
                </div>
                <button type="submit" class="btn btn-primary btn-lg w-100">
                    <i class="fas fa-save me-2"></i>
                    Guardar Cliente
                </button>
            </form>
        </div>
    </div>

   
    <h2 class="section-title text-center w-100 mt-5" style=" margin-left:10%;">
            <i class="fas fa-users text-primary"></i>
            Lista de Clientes
        </h2>
    <div class="table-container" style="max-width: 85%; margin:auto;">
        
        <div class="table-responsive">
            <table class="table table-bordered mb-0">
                <thead class="text-center text-white">
                    <tr>
                        <th><i class="fas fa-hashtag me-1"></i>ID</th>
                        <th><i class="fas fa-user me-1"></i>Nombre</th>
                        <th><i class="fas fa-phone me-1"></i>Teléfono</th>
                        <th><i class="fas fa-envelope me-1"></i>Email</th>
                        <th><i class="fas fa-map-marker-alt me-1"></i>Dirección</th>
                        <th><i class="fas fa-cogs me-1"></i>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $conexion->prepare("SELECT * FROM clientes ORDER BY id DESC");
                    $stmt->execute();
                    $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($clientes as $cliente) {
                        echo "<tr>
                            <td class='text-center fw-bold'>{$cliente['id']}</td>
                            <td>
                                <i class='fas fa-user-circle text-primary me-2'></i>
                                {$cliente['nombre']}
                            </td>
                            <td>
                                <i class='fas fa-phone text-success me-2'></i>
                                {$cliente['telefono']}
                            </td>
                            <td>
                                <i class='fas fa-envelope text-info me-2'></i>
                                {$cliente['email']}
                            </td>
                            <td>
                                <i class='fas fa-map-marker-alt text-warning me-2'></i>
                                {$cliente['direccion']}
                            </td>
                            <td class='text-center'>
                                <a href='editar_cliente.php?id={$cliente['id']}' class='btn btn-warning btn-sm me-1' title='Editar Cliente'>
                                    <i class='fas fa-edit'></i>
                                </a>
                                <a href='eliminar_cliente.php?id={$cliente['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"¿Eliminar este cliente?\")' title='Eliminar Cliente'>
                                    <i class='fas fa-trash'></i>
                                </a>
                            </td>
                            </tr>";
                    }
                    ?>
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
</div>

<?php include("footer.php"); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


