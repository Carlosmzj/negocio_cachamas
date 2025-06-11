<?php
require_once "config.php";
$rol = $_SESSION["rol_id"] ?? null;
if (!isset($_SESSION["usuario_id"])) {
    header("Location: views/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Negocio de Cachamas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="/negocio_cachamas/assets/css/style.css" rel="stylesheet">
</head>
<body>

    <?php include("views/header.php"); ?>

    <div class="container">
        <div class="main-container">
            
            <!-- Hero Section -->
            <div class="hero-section">
                <i class="fas fa-fish fish-icon"></i>
                <h1>Sistema de Gestión Acuícola</h1>
                <p class="subtitle">Administra tu negocio acuícola de manera eficiente</p>
            </div>

            <!-- Módulos Principales -->
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="card-module">
                        <h4 class="text-center mb-3">
                            <i class="fas fa-users" style="color: #667eea;"></i>
                            Gestión de Clientes
                        </h4>
                        <a href="views/clientes.php" class="module-btn btn-clientes">
                            <i class="fas fa-address-book"></i>
                            Administrar Clientes
                        </a>
                        <p class="text-muted text-center small">Registra y gestiona tu base de clientes</p>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card-module">
                        <h4 class="text-center mb-3">
                            <i class="fas fa-receipt" style="color: #ff6b6b;"></i>
                            Registro de Gastos
                        </h4>
                        <?php if ($rol == 1): ?>
                            <a href="views/gastos.php" class="module-btn btn-gastos">
                                <i class="fas fa-money7-bill-wave"></i>
                                Registrar Gastos
                            </a>
                            <p class="text-muted text-center small">Controla todos los gastos del negocio</p>
                        <?php else: ?>
                            <button class="module-btn btn-disabled" onclick="mostrarModal()">
                                <i class="fas fa-lock"></i>
                                Acceso Restringido
                            </button>
                            <p class="text-muted text-center small">Solo para administradores</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card-module">
                        <h4 class="text-center mb-3">
                            <i class="fas fa-layer-group" style="color: #2c3e50;"></i>
                            Control de Lotes
                        </h4>
                        <?php if ($rol == 1): ?>
                            <a href="views/lotes.php" class="module-btn btn-lotes">
                                <i class="fas fa-boxes"></i>
                                Gestionar Lotes
                            </a>
                            <p class="text-muted text-center small">Administra los lotes de cachamas</p>
                        <?php else: ?>
                            <button class="module-btn btn-disabled" onclick="mostrarModal()">
                                <i class="fas fa-lock"></i>
                                Acceso Restringido
                            </button>
                            <p class="text-muted text-center small">Solo para administradores</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card-module">
                        <h4 class="text-center mb-3">
                            <i class="fas fa-chart-line" style="color: #00b894;"></i>
                            Registro de Ventas
                        </h4>
                        <a href="views/ventas.php" class="module-btn btn-ventas">
                            <i class="fas fa-shopping-cart"></i>
                            Registrar Ventas
                        </a>
                        <p class="text-muted text-center small">Gestiona las ventas y facturación</p>
                    </div>
                </div>
            </div>

            <hr class="section-divider">

            <!-- Dashboard Section -->
            <div class="dashboard-section">
                <h2 class="dashboard-title">
                    <i class="fas fa-chart-pie" style="color: #667eea;"></i>
                    Panel de Control Financiero
                </h2>
                <?php include("views/dashboard.php"); ?>
            </div>
            
        </div>
    </div>

    <?php include("views/footer.php"); ?>

    <!-- Modal de Acceso Denegado -->
    <div class="modal fade access-denied-modal" id="accesoDenegado" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-shield-alt me-2"></i>
                        Acceso Denegado
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body text-center">
                    <i class="fas fa-lock lock-icon"></i>
                    <h5>Permisos Insuficientes</h5>
                    <p class="text-muted">No tienes los permisos necesarios para acceder a esta sección. Solo los administradores pueden acceder a esta funcionalidad.</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <a href="index.php" class="btn btn-primary">
                        <i class="fas fa-home me-2"></i>
                        Volver al Inicio
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function mostrarModal() {
            var accesoModal = new bootstrap.Modal(document.getElementById("accesoDenegado"));
            accesoModal.show();
            setTimeout(() => { 
                window.location.href = "index.php"; 
            }, 4000);
        }

        // Efecto de carga suave
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.card-module');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.6s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 200);
            });
        });
    </script>
</body>
</html>



