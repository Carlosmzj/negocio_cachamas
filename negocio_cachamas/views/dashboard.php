
<?php
require_once __DIR__ . "/../config.php";




$stmt = $conexion->prepare("
    SELECT fecha_venta, SUM(peso_vendido * precio_por_kg) AS total FROM ventas GROUP BY fecha_venta ORDER BY fecha_venta ASC;
    SELECT fecha, SUM(monto) AS total FROM gastos GROUP BY fecha ORDER BY fecha ASC;
");
$stmt->execute();


$ventas = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt->nextRowset(); 
$gastos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$labelsVentas = array_column($ventas, 'fecha_venta');
$dataVentas = array_column($ventas, 'total');

$labelsGastos = array_column($gastos, 'fecha');
$dataGastos = array_column($gastos, 'total');


$totalGeneralVentas = array_sum($dataVentas);
$totalGeneralGastos = array_sum($dataGastos);
$gananciaNeta = $totalGeneralVentas - $totalGeneralGastos;


$colorGanancia = ($gananciaNeta >= 0) ? 'bg-primary' : 'bg-warning';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control Financiero</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="/negocio_cachamas/assets/css/style.css" rel="stylesheet">
    <style>
        .chart-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            border: 1px solid var(--gray-200);
            box-shadow: var(--shadow-sm);
            margin-bottom: 2rem;
        }
    </style>
</head>
<body class="bg-light">

<div class="container-fluid mt-4">

    <div class="row text-center mb-4">
        <div class="col-md-6 col-sm-12 mb-2">
            <div class="card bg-success text-white shadow">
                <div class="card-body">
                    <h4>Ventas Totales</h4>
                    <h2>$ <?= number_format($totalGeneralVentas, 2); ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-12 mb-2">
            <div class="card bg-danger text-white shadow">
                <div class="card-body">
                    <h4>Gastos Totales</h4>
                    <h2>$ <?= number_format($totalGeneralGastos, 2); ?></h2>
                </div>
            </div>
        </div>
        <?php if (isset($_SESSION["rol_id"]) && $_SESSION["rol_id"] == 1): ?>
        <div class="col-md-12">
            <div class="card <?= $colorGanancia ?> text-white shadow">
                <div class="card-body">
                    <h4>Ganancia Neta</h4>
                    <h2>$ <?= number_format($gananciaNeta, 2); ?></h2>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <div class="row">
        <div class="col-md-6 col-sm-12 mb-3">
            <div class="card shadow">
                <div class="card-body">
                    <h4 class="card-title text-center">Ingresos por Ventas</h4>
                    <canvas id="ventasChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-12 mb-3">
            <div class="card shadow">
                <div class="card-body">
                    <h4 class="card-title text-center">Gastos del Negocio</h4>
                    <canvas id="gastosChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <hr>

    <h4 class="text-center">Últimas Ventas</h4>
    <div class="table-responsive">
        <table id="tablaVentas" class="display table table-striped">
            <thead class="table-dark text-center">
                <tr>
                    <th>Fecha</th>
                    <th>Total de Venta ($)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ventas as $venta): ?>
                    <tr>
                        <td><?= $venta['fecha_venta']; ?></td>
                        <td><?= number_format($venta['total'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr class="table-secondary">
                    <th class="text-end">Total de Ventas:</th>
                    <th class="text-center"><strong>$ <?= number_format($totalGeneralVentas, 2); ?></strong></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function () {
    $('#tablaVentas').DataTable();

    new Chart(document.getElementById('ventasChart'), {
        type: 'line',
        data: {
            labels: <?php echo json_encode($labelsVentas); ?>,
            datasets: [{
                label: "Ingresos ($)",
                data: <?php echo json_encode($dataVentas); ?>,
                borderColor: "green",
                backgroundColor: "rgba(0, 255, 0, 0.2)",
                fill: true
            }]
        }
    });

    new Chart(document.getElementById('gastosChart'), {
        type: 'line',
        data: {
            labels: <?php echo json_encode($labelsGastos); ?>,
            datasets: [{
                label: "Gastos ($)",
                data: <?php echo json_encode($dataGastos); ?>,
                borderColor: "red",
                backgroundColor: "rgba(255, 0, 0, 0.2)",
                fill: true
            }]
        }
    });
});
</script>

</body>
</html>



