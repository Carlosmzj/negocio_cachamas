<?php
require_once __DIR__ . "/../config.php";

// Obtener la página actual
$pagina_actual = basename($_SERVER["PHP_SELF"]);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Negocio de Cachamas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="/negocio_cachamas/assets/css/style.css" rel="stylesheet">
    
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <div class="container-fluid pt-0 pb-0" style="background-color: #fff; padding: 1rem; border-bottom: 1px solid #dee2e6;">
        <a class="navbar-brand fs-4 fw-bold text-primary" href="/negocio_cachamas/index.php"> <img src="\negocio_cachamas\assets\img\Picsart_25-06-08_21-37-49-724.png" alt ="logo" width="100" height="100"> </a>
        <button class="navbar-toggler" type="button" id="navbarToggler">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div id="navbarNav" class="navbar-menu">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link nav-menu-item <?= ($pagina_actual == 'index.php') ? 'active' : ''; ?>" href="/negocio_cachamas/index.php">
                        <i class="fas fa-home me-1"></i>Inicio
                    </a>
                </li>

                <?php if (isset($_SESSION["rol_id"]) && $_SESSION["rol_id"] == 1): // Administrador ?>
                    <li class="nav-item">
                        <a class="nav-link nav-menu-item <?= ($pagina_actual == 'usuarios.php') ? 'active' : ''; ?>" href="/negocio_cachamas/views/usuarios.php">
                            <i class="fas fa-users me-1"></i>Usuarios
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-menu-item <?= ($pagina_actual == 'registrar.php') ? 'active' : ''; ?>" href="/negocio_cachamas/views/registrar.php">
                            <i class="fa-solid fa-address-card"></i> Registrar
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-menu-item <?= ($pagina_actual == 'gastos.php') ? 'active' : ''; ?>" href="/negocio_cachamas/views/gastos.php">
                            <i class="fas fa-receipt me-1"></i>Gastos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-menu-item <?= ($pagina_actual == 'lotes.php') ? 'active' : ''; ?>" href="/negocio_cachamas/views/lotes.php">
                            <i class="fas fa-fish me-1"></i>Lotes
                        </a>
                    </li>
                <?php elseif (isset($_SESSION["rol_id"]) && $_SESSION["rol_id"] == 2): // Vendedor ?>
                    <li class="nav-item">
                        <span class="nav-link text-muted">
                            <i class="fas fa-users me-1"></i>Usuarios
                        </span>
                    </li>
                    <li class="nav-item">
                        <span class="nav-link text-muted">
                            <i class="fas fa-receipt me-1"></i>Gastos
                        </span>
                    </li>
                    <li class="nav-item">
                        <span class="nav-link text-muted">
                            <i class="fas fa-fish me-1"></i>Lotes
                        </span>
                    </li>
                <?php endif; ?>

                <?php if (isset($_SESSION["rol_id"])): ?>
                    <li class="nav-item">
                        <a class="nav-link nav-menu-item <?= ($pagina_actual == 'clientes.php') ? 'active' : ''; ?>" href="/negocio_cachamas/views/clientes.php">
                            <i class="fas fa-address-book me-1"></i>Clientes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-menu-item <?= ($pagina_actual == 'ventas.php') ? 'active' : ''; ?>" href="/negocio_cachamas/views/ventas.php">
                            <i class="fas fa-shopping-cart me-1"></i>Ventas
                        </a>
                    </li>
                <?php endif; ?>

                <li class="nav-item">
                    <a class="nav-link nav-menu-item text-danger" href="/negocio_cachamas/controllers/logout.php">
                        <i class="fas fa-sign-out-alt me-1"></i>Cerrar Sesión
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<script>
// Implementación completamente manual sin dependencias de Bootstrap
document.addEventListener('DOMContentLoaded', function() {
    console.log('Script cargado'); // Para debug
    
    const toggler = document.getElementById('navbarToggler');
    const menu = document.getElementById('navbarNav');
    const menuItems = document.querySelectorAll('.nav-menu-item');
    
    console.log('Elementos encontrados:', {
        toggler: !!toggler,
        menu: !!menu,
        menuItems: menuItems.length
    }); // Para debug
    
    // Estado del menú
    let isMenuOpen = false;
    
    // Función para abrir menú
    function openMenu() {
        menu.style.display = 'block';
        menu.style.maxHeight = '500px';
        menu.style.opacity = '1';
        isMenuOpen = true;
        console.log('Menú abierto'); // Para debug
    }
    
    // Función para cerrar menú
    function closeMenu() {
        menu.style.maxHeight = '0';
        menu.style.opacity = '0';
        setTimeout(() => {
            if (!isMenuOpen) {
                menu.style.display = 'none';
            }
        }, 300);
        isMenuOpen = false;
        console.log('Menú cerrado'); // Para debug
    }
    
    // Toggle del menú
    function toggleMenu() {
        if (isMenuOpen) {
            closeMenu();
        } else {
            openMenu();
        }
    }
    
    // Event listener para el botón
    if (toggler) {
        toggler.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Botón clickeado'); // Para debug
            toggleMenu();
        });
    }
    
    // Cerrar menú al hacer clic en enlaces
    menuItems.forEach(function(item) {
        item.addEventListener('click', function() {
            console.log('Enlace clickeado'); // Para debug
            closeMenu();
        });
    });
    
    // Cerrar menú al hacer clic fuera
    document.addEventListener('click', function(event) {
        const navbar = document.querySelector('.navbar');
        if (!navbar.contains(event.target) && isMenuOpen) {
            console.log('Click fuera del menú'); // Para debug
            closeMenu();
        }
    });
    
    // Inicializar estilos del menú
    menu.style.transition = 'all 0.3s ease';
    menu.style.overflow = 'hidden';
    
    // Responsive: mostrar/ocultar según el tamaño de pantalla
    function handleResize() {
        if (window.innerWidth >= 992) { // lg breakpoint
            menu.style.display = 'block';
            menu.style.maxHeight = 'none';
            menu.style.opacity = '1';
        } else {
            if (!isMenuOpen) {
                menu.style.display = 'none';
            }
        }
    }
    
    window.addEventListener('resize', handleResize);
    handleResize(); // Ejecutar al cargar
});
</script>

<style>

@media (max-width: 991.98px) {
    .navbar-menu {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background-color: #f8f9fa;
        border-top: 1px solid #dee2e6;
        z-index: 1000;
        display: none;
        max-height: 0;
        overflow: hidden;
        opacity: 0;
    }
    
    .navbar-menu .navbar-nav {
        flex-direction: column;
        padding: 1rem;
    }
    
    .navbar-menu .nav-item {
        width: 100%;
        text-align: center;
        margin-bottom: 0.5rem;
    }
}

@media (min-width: 992px) {
    .navbar-menu {
        display: block !important;
        max-height: none !important;
        opacity: 1 !important;
        position: static;
        background-color: transparent;
        border: none;
    }
    
    .navbar-menu .navbar-nav {
        flex-direction: row;
    }
}
</style>

</body>
</html>