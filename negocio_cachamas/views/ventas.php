<?php
require_once "../config.php";


// Función para crear mensaje personalizado de venta
function crearMensajeVenta($cliente, $lote, $peso, $precio, $total, $fecha) {
    $mensaje = "🐟 NUEVA VENTA REGISTRADA 🐟\n\n";
    $mensaje .= "👤 Cliente: $cliente\n";
    $mensaje .= "📦 Lote: $lote\n";
    $mensaje .= "⚖️ Peso: {$peso} kg\n";
    $mensaje .= "💰 Precio/kg: $" . number_format($precio, 2) . "\n";
    $mensaje .= "📅 Fecha: $fecha\n";
    $mensaje .= "💵 TOTAL: $" . number_format($total, 2) . "\n\n";
    $mensaje .= "✅ Venta procesada exitosamente\n";
    $mensaje .= "🕒 " . date('d/m/Y H:i:s');
    
    return $mensaje;
}

// Función para crear enlace de WhatsApp
function crearEnlaceWhatsApp($numero, $mensaje) {
    $mensaje_encoded = urlencode($mensaje);
    return "https://wa.me/$numero?text=$mensaje_encoded";
}

// Variable para controlar si se debe abrir WhatsApp
$abrirWhatsApp = false;
$enlaceWhatsApp = "";

// Procesar la inserción de ventas
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_cliente = $_POST["id_cliente"];
    $id_lote = $_POST["id_lote"];
    $peso_vendido = $_POST["peso_vendido"];
    $precio_por_kg = $_POST["precio_por_kg"];
    $fecha_venta = $_POST["fecha_venta"];
    
    try {
        // Obtener información del cliente y lote para el mensaje
        $stmt_cliente = $conexion->prepare("SELECT nombre, telefono FROM clientes WHERE id = :id");
        $stmt_cliente->bindParam(":id", $id_cliente);
        $stmt_cliente->execute();
        $cliente_info = $stmt_cliente->fetch(PDO::FETCH_ASSOC);
        
        $stmt_lote = $conexion->prepare("SELECT nombre FROM lotes WHERE id = :id");
        $stmt_lote->bindParam(":id", $id_lote);
        $stmt_lote->execute();
        $lote_info = $stmt_lote->fetch(PDO::FETCH_ASSOC);
        
        // Insertar la venta
        $stmt = $conexion->prepare("INSERT INTO ventas (id_cliente, id_lote, peso_vendido, precio_por_kg, fecha_venta) 
                                    VALUES (:id_cliente, :id_lote, :peso_vendido, :precio_por_kg, :fecha_venta)");
        $stmt->bindParam(":id_cliente", $id_cliente);
        $stmt->bindParam(":id_lote", $id_lote);
        $stmt->bindParam(":peso_vendido", $peso_vendido);
        $stmt->bindParam(":precio_por_kg", $precio_por_kg);
        $stmt->bindParam(":fecha_venta", $fecha_venta);
        
        if ($stmt->execute()) {
            // Calcular total
            $total = $peso_vendido * $precio_por_kg;
            
            // Crear mensaje personalizado
            $mensaje = crearMensajeVenta(
                $cliente_info['nombre'],
                $lote_info['nombre'],
                $peso_vendido,
                $precio_por_kg,
                $total,
                $fecha_venta
            );
            
            // Número del administrador
            $numero_admin = "573026524273";
            
            // Crear enlace de WhatsApp
            $enlaceWhatsApp = crearEnlaceWhatsApp($numero_admin, $mensaje);
            $abrirWhatsApp = true;
            
            // Redirigir con mensaje de éxito
            header("Location: ventas.php?mensaje=" . urlencode("Venta registrada exitosamente") . "&whatsapp=1");
            exit();
        } else {
            throw new Exception("Error al insertar la venta");
        }
        
    } catch (Exception $e) {
        header("Location: ventas.php?error=" . urlencode("Error al registrar venta: " . $e->getMessage()));
        exit();
    }
}

// Verificar si se debe abrir WhatsApp después de redirección
if (isset($_GET['whatsapp']) && $_GET['whatsapp'] == '1') {
    // Obtener la última venta para crear el mensaje
    $stmt_ultima = $conexion->prepare("SELECT ventas.*, clientes.nombre AS cliente, lotes.nombre AS lote 
                                       FROM ventas 
                                       JOIN clientes ON ventas.id_cliente = clientes.id 
                                       JOIN lotes ON ventas.id_lote = lotes.id
                                       ORDER BY ventas.id DESC LIMIT 1");
    $stmt_ultima->execute();
    $ultima_venta = $stmt_ultima->fetch(PDO::FETCH_ASSOC);
    
    if ($ultima_venta) {
        $total = $ultima_venta['peso_vendido'] * $ultima_venta['precio_por_kg'];
        $mensaje = crearMensajeVenta(
            $ultima_venta['cliente'],
            $ultima_venta['lote'],
            $ultima_venta['peso_vendido'],
            $ultima_venta['precio_por_kg'],
            $total,
            $ultima_venta['fecha_venta']
        );
        
        $enlaceWhatsApp = crearEnlaceWhatsApp("573026524273", $mensaje);
        $abrirWhatsApp = true;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Ventas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="/negocio_cachamas/assets/css/style.css" rel="stylesheet">
    
    <?php if ($abrirWhatsApp && !empty($enlaceWhatsApp)): ?>
    <script>
        // Abrir WhatsApp automáticamente después de cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                window.open('<?php echo $enlaceWhatsApp; ?>', '_blank');
            }, 1500); // Esperar 1.5 segundos para que el usuario vea la confirmación
        });
    </script>
    <?php endif; ?>
</head>
<body>

<?php include("header.php"); ?>

<div class="container-fluid mt-4">
    <!-- Mostrar mensajes de éxito o error -->
    <?php if (isset($_GET['mensaje'])): ?>
        <div class="alert whatsapp-notification alert-dismissible fade show pulse-animation" role="alert">
            <div class="d-flex align-items-center">
                <i class="fab fa-whatsapp me-3 fs-3"></i>
                <div>
                    <strong><i class="fas fa-check-circle me-2"></i><?php echo htmlspecialchars($_GET['mensaje']); ?></strong>
                    <br>
                    <small>Abriendo WhatsApp para enviar notificación...</small>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        
        <?php if ($abrirWhatsApp && !empty($enlaceWhatsApp)): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>¿No se abrió WhatsApp automáticamente?</strong>
                </div>
                <a href="<?php echo $enlaceWhatsApp; ?>" target="_blank" class="btn btn-success btn-sm">
                    <i class="fab fa-whatsapp me-1"></i>
                    Abrir WhatsApp Manualmente
                </a>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>
    <?php endif; ?>
    
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <?php echo htmlspecialchars($_GET['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <h2 class="section-title text-center w-100">
        <i class="fas fa-cash-register me-2 text-success"></i>
        Registrar Venta
    </h2>

    <div class="row justify-content-center">
        <div class="col-md-6 col-sm-12">
            <form action="ventas.php" method="POST" class="form-container p-4">
                <div class="mb-3">
                    <label for="id_cliente" class="form-label">
                        <i class="fas fa-user me-1 text-primary"></i>Cliente:
                    </label>
                    <select name="id_cliente" class="form-control" required>
                        <option value="">Seleccionar cliente...</option>
                        <?php
                        $stmt = $conexion->prepare("SELECT id, nombre FROM clientes ORDER BY nombre ASC");
                        $stmt->execute();
                        $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($clientes as $cliente) {
                            echo "<option value='{$cliente['id']}'>{$cliente['nombre']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="id_lote" class="form-label">
                        <i class="fas fa-fish me-1 text-info"></i>Lote:
                    </label>
                    <select name="id_lote" class="form-control" required>
                        <option value="">Seleccionar lote...</option>
                        <?php
                        $stmt = $conexion->prepare("SELECT id, nombre FROM lotes WHERE estado = 'Activo' ORDER BY nombre ASC");
                        $stmt->execute();
                        $lotes = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($lotes as $lote) {
                            echo "<option value='{$lote['id']}'>{$lote['nombre']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="peso_vendido" class="form-label">
                        <i class="fas fa-weight me-1 text-success"></i>Peso vendido (kg):
                    </label>
                    <input type="number" step="0.01" name="peso_vendido" class="form-control" required min="0.01">
                </div>
                <div class="mb-3">
                    <label for="precio_por_kg" class="form-label">
                        <i class="fas fa-dollar-sign me-1 text-success"></i>Precio por kg:
                    </label>
                    <input type="number" step="0.01" name="precio_por_kg" class="form-control" required min="0.01">
                </div>
                <div class="mb-3">
                    <label for="fecha_venta" class="form-label">
                        <i class="fas fa-calendar me-1 text-danger"></i>Fecha de venta:
                    </label>
                    <input type="date" name="fecha_venta" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                </div>
                <button type="submit" class="btn btn-success btn-lg w-100 mb-3">
                    <i class="fab fa-whatsapp me-2"></i>
                    Registrar Venta y Notificar por WhatsApp
                </button>
                <small class="text-muted text-center">
                    <i class="fas fa-info-circle me-1"></i>
                    Se enviará automáticamente un mensaje a WhatsApp después de registrar la venta
                </small>
            </form>
        </div>
    </div>
</div>

<h2 class="section-title text-center w-100 mt-5" style=" margin-left:10%;">
    <i class="fa-solid fa-money-bill-1-wave text-success"></i>                    
    Lista de Ventas
</h2>

<div class="table-container" style="width: 85%; margin:auto;">
    <div class="table-responsive">
        <table class="table table-bordered mb-0">
            <thead class="text-center text-white">
                <tr>
                    <th><i class="fas fa-hashtag me-1"></i>ID</th>
                    <th>Cliente</th>
                    <th><i class="fas fa-tag me-1"></i> Lote</th>
                    <th><i class="fas fa-weight me-1"></i> Peso Vendido</th>
                    <th><i class='fa-solid fa-coins'></i> Precio por kg</th>
                    <th><i class="fas fa-calendar-alt me-1"></i> Fecha</th>
                    <th><i class="fa-solid fa-equals"></i> Total</th>
                    <th><i class="fas fa-cogs me-1"></i> Acciones</th>
                </tr>
            </thead>
            <tbody class="text-center">
                <?php
                $stmt = $conexion->prepare("SELECT ventas.*, clientes.nombre AS cliente, lotes.nombre AS lote 
                                            FROM ventas 
                                            JOIN clientes ON ventas.id_cliente = clientes.id 
                                            JOIN lotes ON ventas.id_lote = lotes.id
                                            ORDER BY fecha_venta DESC");
                $stmt->execute();
                $ventas = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $totalGeneral = 0;
                foreach ($ventas as $venta) {
                    $total = $venta['peso_vendido'] * $venta['precio_por_kg'];
                    $totalGeneral += $total;

                    echo "<tr>
                            <td class='fw-bold'>{$venta['id']}</td>
                            <td>
                                <i class='fas fa-user-circle text-primary me-2'></i>
                                {$venta['cliente']}
                            </td>
                            <td>
                                <i class='fa-solid fa-tags text-danger'></i>
                                {$venta['lote']}
                            </td>
                            <td>
                            <i class='fa-solid fa-scale-unbalanced-flip text-info'></i>
                            {$venta['peso_vendido']} kg
                            </td>
                            <td>
                            <i class='fa-solid fa-comment-dollar text-success'></i>
                            $ " . number_format($venta['precio_por_kg'], 2) . "
                            </td>
                            <td>
                            <i class='fa-regular fa-calendar-minus text-primary'></i>
                            {$venta['fecha_venta']}

                            </td>
                            <td class='fw-bold text-success'>$ " . number_format($total, 2) . "</td>
                            <td class='d-flex gap-2 justify-content-center'>
                                <a href='editar_venta.php?id={$venta['id']}' class='btn btn-warning btn-sm me-1'>
                                    <i class='fas fa-edit'></i>
                             </a>
                             <a href='eliminar_venta.php?id={$venta['id']}' class='btn btn-danger btn-sm' 
                                 onclick='return confirm(\"¿Eliminar esta venta?\")'>
                                    <i class='fas fa-trash'></i>
                                </a>
                            </td>
                        </tr>";
             }
                ?>
            </tbody>
            <tfoot>
                <tr class="table-secondary text-center">
                    <th colspan="6"><i class="fa-solid fa-file-export"></i> Total de Ventas:</th>
                    <th class="fw-bold text-primary">$ <?php echo number_format($totalGeneral, 2); ?></th>
                    <th></th>
                </tr>
            </tfoot>
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
