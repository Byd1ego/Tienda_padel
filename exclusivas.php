<?php
include_once 'includes/header.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php?redirigido=true");
    exit();
}
?>

<main>
    <h2>Exclusivas</h2>

    <?php
    require_once 'includes/conexion.php';

    $sql = "SELECT cod, nombre_corto, pvp, imagen FROM producto WHERE exclusiva = TRUE";
    $stmt = $conexion->prepare($sql);
    $stmt->execute();
    $exclusivas = $stmt->fetchAll();
    ?>

    <div class="contenedorgrid">
        <?php foreach ($exclusivas as $p): ?>
            <div class="card">
                <?php if ($p['imagen']): ?>
                    <img src="static/img/<?php echo htmlspecialchars($p['imagen']); ?>"
                        alt="<?php echo htmlspecialchars($p['nombre_corto']); ?>" height="300px" width="300px">
                <?php else: ?>
                    <img src="static/img/default.jpg" alt="Sin imagen" height="300px" width="300px">
                <?php endif; ?>
                <p><?php echo htmlspecialchars($p['nombre_corto']); ?> <br>
                    <?php echo number_format($p['pvp'], 2, ',', '.'); ?>€</p>
                <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'usuario'): ?>
                    <form method="post" action="añadir_carrito.php">
                        <input type="hidden" name="cod" value="<?php echo htmlspecialchars($p['cod']); ?>">
                        <input type="hidden" name="origen" value="exclusivas.php">
                        <button type="submit" class="boton-carrito">Añadir al carrito</button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</main>

<?php include_once 'includes/footer.php'; ?>