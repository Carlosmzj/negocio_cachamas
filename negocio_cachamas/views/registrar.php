<?php
require_once "../config.php";
require_once "../controllers/verificar_acceso.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST["usuario"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $rol_id = $_POST["rol_id"]; // Capturar el rol seleccionado

    // Insertar usuario con rol
    $stmt = $conexion->prepare("INSERT INTO usuarios (usuario, password, rol_id) VALUES (:usuario, :password, :rol_id)");
    $stmt->bindParam(":usuario", $usuario);
    $stmt->bindParam(":password", $password);
    $stmt->bindParam(":rol_id", $rol_id);

    if ($stmt->execute()) {
        header("Location: login.php?mensaje=Registro exitoso");
        exit();
    } else {
        echo "Error al registrar usuario.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="/negocio_cachamas/assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include("header.php"); ?>

<div class="container-fluid d-flex justify-content-center align-items-center vh-100">
    <div class="form-container p-4" style="max-width: 400px; width: 100%;">
        <h3 class="text-center mb-3">
            <i class="fa-solid fa-user-gear"></i>
            Registro de Usuario
        </h3>

        <form method="POST" class="d-flex flex-column gap-3">
            <div>
                <label for="usuario" class="form-label">
                    <i class="fa-solid fa-user-plus"></i>
                    Usuario
                </label>
                <input type="text" name="usuario" class="form-control" placeholder="Ingrese un nombre de usuario" required>
            </div>
            <div>
                <label for="password" class="form-label">
                    <i class="fa-solid fa-key"></i>
                    Contraseña
                </label>
                <input type="password" name="password" class="form-control" placeholder="Ingrese una contraseña segura" required>
                <small class="text-muted">Debe contener al menos 8 caracteres</small>
            </div>
            <div>
                <label for="rol_id" class="form-label">
                    <i class="fa-solid fa-dice"></i>
                    Rol de usuario
                </label>
                <select name="rol_id" class="form-control" required>
                    <option value="1">Administrador</option>
                    <option value="2">Vendedor</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success btn-lg w-100">
                <i class="fa-solid fa-registered"></i>
                Registrar
            </button>
        </form>

        <div class="text-center mt-3">
            <a href="../index.php" class="text-decoration-none text-muted">
                <i class="fa-solid fa-house"></i>
                Volver al inicio
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>



