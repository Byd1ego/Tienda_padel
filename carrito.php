<?php
include_once 'includes/header.php';

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'usuario') {
    header("Location: login.php?redirigido=true");
    exit();
}

require_once 'includes/conexion.php';

$usuario = $_SESSION['usuario'];

// Borrar producto del carrito
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['borrar'])) {
    $sql = "DELETE FROM carrito WHERE id = :id AND usuario = :usuario";
    $stmt = $conexion->prepare($sql);
    $stmt->bindValue(':id',      $_POST['borrar'], PDO::PARAM_INT);
    $stmt->bindValue(':usuario', $usuario);
    $stmt->execute();
    header("Location: carrito.php");
    exit();
}

// Pagar: descontar stock y vaciar carrito
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pagar'])) {
    $sql = "SELECT c.id, c.producto, c.unidades FROM carrito c WHERE c.usuario = :usuario";
    $stmt = $conexion->prepare($sql);
    $stmt->bindValue(':usuario', $usuario);
    $stmt->execute();
    $items_pagar = $stmt->fetchAll();

    $error_stock = false;

    foreach ($items_pagar as $item) {
        // Comprobar stock suficiente
        $sql_check = "SELECT unidades FROM stock WHERE producto = :cod AND tienda = 1";
        $stmt_check = $conexion->prepare($sql_check);
        $stmt_check->bindValue(':cod', $item['producto']);
        $stmt_check->execute();
        $stock = $stmt_check->fetch();

        if (!$stock || $stock['unidades'] < $item['unidades']) {
            $error_stock = true;
            break;
        }
    }

    if (!$error_stock) {
        foreach ($items_pagar as $item) {
            $sql_update = "UPDATE stock SET unidades = unidades - :unidades WHERE producto = :cod AND tienda = 1";
            $stmt_update = $conexion->prepare($sql_update);
            $stmt_update->bindValue(':unidades', $item['unidades'], PDO::PARAM_INT);
            $stmt_update->bindValue(':cod',      $item['producto']);
            $stmt_update->execute();
        }

        $sql_vaciar = "DELETE FROM carrito WHERE usuario = :usuario";
        $stmt_vaciar = $conexion->prepare($sql_vaciar);
        $stmt_vaciar->bindValue(':usuario', $usuario);
        $stmt_vaciar->execute();

        $pagado = true;
    }
}

// Obtener productos del carrito
$sql = "SELECT c.id, c.unidades, p.nombre_corto, p.pvp, p.imagen
        FROM carrito c
        JOIN producto p ON c.producto = p.cod
        WHERE c.usuario = :usuario";
$stmt = $conexion->prepare($sql);
$stmt->bindValue(':usuario', $usuario);
$stmt->execute();
$items = $stmt->fetchAll();

$total = 0;
foreach ($items as $item) {
    $total += $item['pvp'] * $item['unidades'];
}
?>

<main>
    <div class="carrito-contenedor">
        <h1 class="carrito-titulo">Mi carrito</h1>

        <?php if (isset($pagado) && $pagado): ?>
            <p class="contacto-ok">Compra realizada correctamente.</p>
        <?php endif; ?>

        <?php if (isset($error_stock) && $error_stock): ?>
            <p class="error">No hay suficiente stock para alguno de los productos.</p>
        <?php endif; ?>

        <?php if (count($items) === 0): ?>
            <p class="carrito-vacio">Tu carrito está vacío.</p>
        <?php else: ?>
            <div class="carrito-lista">
                <?php foreach ($items as $item): ?>
                    <div class="carrito-item">
                        <?php if ($item['imagen']): ?>
                            <img src="static/img/<?php echo htmlspecialchars($item['imagen']); ?>" alt="">
                        <?php else: ?>
                            <img src="static/img/default.jpg" alt="">
                        <?php endif; ?>
                        <div class="carrito-info">
                            <p class="carrito-nombre"><?php echo htmlspecialchars($item['nombre_corto']); ?></p>
                            <p class="carrito-precio"><?php echo number_format($item['pvp'], 2, ',', '.'); ?>€ x <?php echo $item['unidades']; ?> ud</p>
                            <p class="carrito-subtotal">Subtotal: <?php echo number_format($item['pvp'] * $item['unidades'], 2, ',', '.'); ?>€</p>
                        </div>
                        <form method="post">
                            <input type="hidden" name="borrar" value="<?php echo $item['id']; ?>">
                            <button type="submit" class="boton-borrar"> Borrar</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="carrito-total">
                <p>Total: <strong><?php echo number_format($total, 2, ',', '.'); ?>€</strong></p>
                <form method="post">
                    <input type="hidden" name="pagar" value="1">
                    <button type="submit" class="boton-pagar">Pagar</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php include_once 'includes/footer.php'; ?>