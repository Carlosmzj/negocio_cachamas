<?php
require_once "../config.php";
require_once "../controllers/verificar_acceso.php";


if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conexion->prepare("DELETE FROM gastos WHERE id = :id");
    $stmt->bindParam(":id", $id);
    $stmt->execute();

    header("Location: gastos.php?mensaje=Gasto eliminado");
    exit();
}
?>
