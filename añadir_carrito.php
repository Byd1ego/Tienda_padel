<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'usuario') {
    header("Location: login.php?redirigido=true");
    exit();
}

require_once 'includes/conexion.php';

$cod     = $_POST['cod']     ?? '';
$usuario = $_SESSION['usuario'];

if ($cod) {
    // Si ya existe el producto en el carrito, suma una unidad
    $sql = "SELECT id, unidades FROM carrito WHERE usuario = :usuario AND producto = :cod";
    $stmt = $conexion->prepare($sql);
    $stmt->bindValue(':usuario', $usuario);
    $stmt->bindValue(':cod',     $cod);
    $stmt->execute();
    $existe = $stmt->fetch();

    if ($existe) {
        $sql = "UPDATE carrito SET unidades = unidades + 1 WHERE id = :id";
        $stmt = $conexion->prepare($sql);
        $stmt->bindValue(':id', $existe['id'], PDO::PARAM_INT);
        $stmt->execute();
    } else {
        $sql = "INSERT INTO carrito (usuario, producto, unidades) VALUES (:usuario, :cod, 1)";
        $stmt = $conexion->prepare($sql);
        $stmt->bindValue(':usuario', $usuario);
        $stmt->bindValue(':cod',     $cod);
        $stmt->execute();
    }
}
$origen = $_POST['origen'] ?? 'index.php';
header("Location: " . $origen);
exit();