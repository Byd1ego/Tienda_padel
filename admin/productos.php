<?php
session_start();

// Si no hay usuario logeado o no es admin, redirige al login
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../login.php?redirigido=true");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tienda - Administración de productos</title>
    <link rel="stylesheet" href="../resources/estilos.css">
</head>

<body>

    <div class="contenedor">
    <h1>🛒 Administración de productos</h1>

        <?php
            require_once '../includes/conexion.php';
            require_once '../includes/funciones.php';            
        ?>

            <!-- Barra superior -->
            <div class="barra-superior">
                <div class="botones-derecha">
                     <a href="../index.php" class="btn-admin">Tienda</a>
                     <a href="producto_nuevo.php" class="btn btn-nuevo">➕ Nuevo producto</a>
                     <a href="../logout.php" class="btn btn-borrar">Cerrar sesión</a>
                </div>                        
            </div>

        <?php
            $sql = "SELECT cod, nombre_corto, descripcion, pvp FROM producto";

            $stmt = $conexion->prepare($sql);
            $stmt->execute();
            $productos = $stmt->fetchAll();

            if (count($productos) > 0) {
                echo "<table>";
                echo "<tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>PVP</th>
                        <th>Acciones</th>
                     </tr>";

                foreach ($productos as $p) {
                    echo "<tr>";
                    echo " <td>" . $p['cod'] . "</td>";
                    echo " <td>" . $p['nombre_corto'] . "</td>";
                    echo " <td>" . $p['descripcion'] . "</td>";
                    echo " <td class='precio'>" . number_format($p['pvp'], 2) . " €</td>";
                    echo " <td class='acciones'>
                               <a class='btn btn-editar' href='producto_editar.php?cod=" . $p['cod'] . "'>Editar</a><br>
                               <a class='btn btn-borrar' href='producto_borrar.php?cod=" . $p['cod'] . "'>Borrar</a>
                           </td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p>No hay productos para mostrar.</p>";
            }            
        ?>
    </div>
</body>
</html>
