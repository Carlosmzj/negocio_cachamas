<?php
require_once "../config.php";
require_once "../controllers/verificar_acceso.php";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $descripcion = $_POST["descripcion"];
    $monto = $_POST["monto"];
    $fecha = $_POST["fecha"];
    $categoria = $_POST["categoria"];

    $stmt = $conexion->prepare("INSERT INTO gastos (descripcion, monto, fecha, categoria) VALUES (:descripcion, :monto, :fecha, :categoria)");
    $stmt->bindParam(":descripcion", $descripcion);
    $stmt->bindParam(":monto", $monto);
    $stmt->bindParam(":fecha", $fecha);
    $stmt->bindParam(":categoria", $categoria);
    $stmt->execute();

    header("Location: gastos.php?mensaje=Gasto registrado");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Gastos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="/negocio_cachamas/assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-light">

<?php include("header.php"); ?>

<div class="container-fluid mt-4">
    <h2 class="section-title text-center w-100" style=" margin-left:10%;">
        <i class="fa-solid fa-money-bill-1-wave text-danger"></i>
        Registrar Gasto
    </h2>

    <div class="row justify-content-center">
        <div class="col-md-6 col-sm-12">
            <form action="gastos.php" method="POST" class="form-container p-4">
                <div class="mb-3">
                    <label for="descripcion" class="form-label">
                        <i class="fa-solid fa-pen-to-square text-primary"></i>
                        Descripción:
                    </label>
                    <input type="text" name="descripcion" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="monto" class="form-label">
                        <i class="fa-solid fa-circle-dollar-to-slot text-danger"></i>
                        Monto:
                    </label>
                    <input type="number" name="monto" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="fecha" class="form-label">
                        <i class="fas fa-calendar-alt text-success"></i>
                        Fecha:
                    </label>
                    <input type="date" name="fecha" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="categoria" class="form-label">
                        <i class="fa-solid fa-layer-group text-primary"></i>
                        Categoría:
                    </label>
                    <select name="categoria" class="form-control">
                        <option value="Alimentación">Alimentación</option>
                        <option value="Mantenimiento">Mantenimiento</option>
                        <option value="Servicios">Servicios</option>
                        <option value="Otros">Otros</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary btn-lg w-100">
                    <i class="fas fa-save me-2"></i> Guardar Gasto
                </button>
            </form>
        </div>
    </div>

    <h2 class="section-title text-center w-100 mt-5" style="max-width: 50%; margin-left:10%;">
        <i class="fa-solid fa-rectangle-list text-primary"></i>
        Lista de Gastos
    </h2>
    <div class="table-container" style="max-width: 85%; margin:auto;">
    <div class="table-responsive">
        <table class="table table-bordered mb-0">
            <thead class="text-center text-white">
                <tr>
                    <th><i class="fas fa-hashtag me-1"></i> ID</th>
                    <th><i class="fa-solid fa-pen-to-square"></i> Descripción</th>
                    <th><i class="fa-solid fa-circle-dollar-to-slot"></i> Monto</th>
                    <th><i class="fas fa-calendar-alt me-1"></i> Fecha</th>
                    <th><i class="fa-solid fa-list"></i> Categoría</th>
                    <th><i class="fas fa-cogs me-1"></i> Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $conexion->prepare("SELECT * FROM gastos ORDER BY id DESC");
                $stmt->execute();
                $gastos = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($gastos as $gasto) {
                    echo "<tr>
                        <td class='text-center fw-bold'>{$gasto['id']}</td>
                        <td class='text-center'>
                            <i class='fa-solid fa-cash-register text-primary'></i>
                            {$gasto['descripcion']}
                        </td>
                        <td class='text-center'>
                            <i class='fa-solid fa-coins text-danger'></i>
                            {$gasto['monto']}
                        </td >
                        <td class='text-center'>
                            <i class='fas fa-calendar text-success me-2'></i>
                            {$gasto['fecha']}
                        </td>
                        <td class='text-center'>
                            <i class='fa-solid fa-bell-concierge'></i>
                            {$gasto['categoria']}
                        </td>
                        <td class='text-center'>
                            <a href='editar_gasto.php?id={$gasto['id']}' class='btn btn-warning btn-sm me-1'title='editar gasto'>
                                <i class='fas fa-edit'></i>
                            </a>
                            <a href='eliminar_gasto.php?id={$gasto['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"¿Eliminar este gasto?\")' title='Eliminar gasto'>
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

<?php include("footer.php"); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

