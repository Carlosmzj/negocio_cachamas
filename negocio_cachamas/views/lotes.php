<?php
require_once "../config.php";
require_once "../controllers/verificar_acceso.php";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $fecha_inicio = $_POST["fecha_inicio"];
    $cantidad_inicial = $_POST["cantidad_inicial"];
    $peso_promedio = $_POST["peso_promedio"];
    $estado = $_POST["estado"];

    $stmt = $conexion->prepare("INSERT INTO lotes (nombre, fecha_inicio, cantidad_inicial, peso_promedio, estado) VALUES (:nombre, :fecha_inicio, :cantidad_inicial, :peso_promedio, :estado)");
    $stmt->bindParam(":nombre", $nombre);
    $stmt->bindParam(":fecha_inicio", $fecha_inicio);
    $stmt->bindParam(":cantidad_inicial", $cantidad_inicial);
    $stmt->bindParam(":peso_promedio", $peso_promedio);
    $stmt->bindParam(":estado", $estado);
    $stmt->execute();

    header("Location: lotes.php?mensaje=Lote registrado");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Lotes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="/negocio_cachamas/assets/css/style.css" rel="stylesheet">
</head>
<body>

<?php include("header.php"); ?>

<div class="container-fluid mt-4">
    <h2 class="section-title text-center w-100">
        <i class="fas fa-boxes text-primary"></i>
        Registrar Lote
    </h2>

    <div class="row justify-content-center">
        <div class="col-md-6 col-sm-12">
            <form action="lotes.php" method="POST" class="form-container p-4">
                <div class="mb-3">
                    <label for="nombre" class="form-label">
                        <i class="fas fa-tag text-primary"></i>
                        Nombre del lote:
                    </label>
                    <input type="text" name="nombre" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="fecha_inicio" class="form-label">
                        <i class="fas fa-calendar-alt text-success"></i>
                        Fecha de inicio:
                    </label>
                    <input type="date" name="fecha_inicio" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="cantidad_inicial" class="form-label">
                        <i class="fas fa-fish text-info"></i>
                        Cantidad inicial:
                    </label>
                    <input type="number" name="cantidad_inicial" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="peso_promedio" class="form-label">
                        <i class="fas fa-weight-hanging text-warning"></i>
                        Peso promedio (kg):
                    </label>
                    <input type="text" name="peso_promedio" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="estado" class="form-label">
                        <i class="fas fa-flag text-danger"></i>
                        Estado:
                    </label>
                    <select name="estado" class="form-select">
                        <option value="Activo">
                            <i class="fas fa-play-circle"></i> Activo
                        </option>
                        <option value="Finalizado">
                            <i class="fas fa-check-circle"></i> Finalizado
                        </option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary btn-lg w-100">
                    <i class="fas fa-save me-2"></i>
                    Guardar Lote
                </button>
            </form>
        </div>
    </div>

    <h2 class="section-title text-center w-100 mt-5" style=" margin-left:10%;">
        <i class="fas fa-layer-group text-primary"></i>
        Lista de Lotes
    </h2>

    <div class="table-container" style="max-width: 85%; margin:auto;">
        <div class="table-responsive">
            <table class="table table-bordered mb-0">
                <thead class="text-center text-white">
                    <tr>
                        <th><i class="fas fa-hashtag me-1"></i>ID</th>
                        <th><i class="fas fa-tag me-1"></i>Nombre</th>
                        <th><i class="fas fa-calendar-alt me-1"></i>Fecha de Inicio</th>
                        <th><i class="fas fa-fish me-1"></i>Cantidad Inicial</th>
                        <th><i class="fas fa-weight-hanging me-1"></i>Peso Promedio</th>
                        <th><i class="fas fa-flag me-1"></i>Estado</th>
                        <th><i class="fas fa-cogs me-1"></i>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $conexion->prepare("SELECT * FROM lotes ORDER BY id DESC");
                    $stmt->execute();
                    $lotes = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($lotes as $lote) {
                        $estadoClass = $lote['estado'] == 'Activo' ? 'estado-activo' : 'estado-finalizado';
                        $estadoIcon = $lote['estado'] == 'Activo' ? 'fas fa-play-circle' : 'fas fa-check-circle';
                        
                        echo "<tr>
                            <td class='text-center fw-bold'>{$lote['id']}</td>
                            <td>
                                <i class='fas fa-box text-primary me-2'></i>
                                {$lote['nombre']}
                            </td>
                            <td class='text-center'>
                                <i class='fas fa-calendar text-success me-2'></i>
                                {$lote['fecha_inicio']}
                            </td>
                            <td class='text-center'>
                                <i class='fas fa-fish text-info me-2'></i>
                                {$lote['cantidad_inicial']}
                            </td>
                            <td class='text-center'>
                                <i class='fas fa-weight text-warning me-2'></i>
                                {$lote['peso_promedio']} kg
                            </td>
                            <td class='text-center'>
                                <span class='{$estadoClass}'>
                                    <i class='{$estadoIcon} me-1'></i>
                                    {$lote['estado']}
                                </span>
                            </td>
                            <td class='text-center'>
                                <a href='editar_lote.php?id={$lote['id']}' class='btn btn-warning btn-sm me-1' title='Editar Lote'>
                                    <i class='fas fa-edit'></i>
                                </a>
                                <a href='eliminar_lote.php?id={$lote['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"¿Eliminar este lote?\")' title='Eliminar Lote'>
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
