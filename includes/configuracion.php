<?php
if ($_SERVER['HTTP_HOST'] === 'localhost') {
    // Local
    $host = 'localhost';
    $usuario = 'root';
    $password = '';
    $bd = 'tienda_padel';
} else {
    // InfinityFree
    $host = 'sql107.infinityfree.com';
    $usuario = 'if0_41953172';
    $password = 'IOpyoTtBZ06JCSd';
    $bd = 'if0_41953172_tienda_padel';
}

$conexion = mysqli_connect($host, $usuario, $password, $bd);

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}
?>