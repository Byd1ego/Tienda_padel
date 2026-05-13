<?php
include_once 'includes/header.php';
?>

<main>
    <div class="banner-grid">
    <div class="banner-imagen">
        <img src="static/img/Almejorprecio.png" alt="Empieza a jugar al mejor precio">
    </div>
    <div class="banner-texto">
        <span class="banner-tag">Tienda de pádel</span>
        <h1 class="banner-titulo">Palas más vendidas</h1>
        <p class="banner-desc">Descubre nuestra selección de palas para todos los niveles. Calidad profesional al mejor precio.</p>
        <div class="banner-stats">
            <div class="banner-stat">
                <strong>+20</strong>
                <span>Modelos</span>
            </div>
            <div class="banner-stat">
                <strong>3</strong>
                <span>Niveles</span>
            </div>
            <div class="banner-stat">
                <strong>6</strong>
                <span>Marcas</span>
            </div>
        </div>
    </div>
</div>

    <?php
    require_once 'includes/conexion.php';

    $sql = "SELECT cod, nombre_corto, pvp, imagen FROM producto WHERE exclusiva = FALSE";
    $stmt = $conexion->prepare($sql);
    $stmt->execute();
    $ofertas = $stmt->fetchAll();
    ?>

    <div class="contenedorgrid">
        <?php foreach ($ofertas as $p): ?>
            <div class="card">
                <?php if ($p['imagen']): ?>
                    <img src="static/img/<?php echo htmlspecialchars($p['imagen']); ?>"
                        alt="<?php echo htmlspecialchars($p['nombre_corto']); ?>" height="300px" width="300px">
                <?php else: ?>
                    <img src="static/img/default.jpg" alt="Sin imagen" height="300px" width="300px">
                <?php endif; ?>
                <p><?php echo htmlspecialchars($p['nombre_corto']); ?> <br>
                    <?php echo number_format($p['pvp'], 2, ',', '.'); ?>€</p>
                <?php if (isset($_SESSION['usuario']) && $_SESSION['rol'] === 'usuario'): ?>
                    <form method="post" action="añadir_carrito.php">
                        <input type="hidden" name="cod" value="<?php echo htmlspecialchars($p['cod']); ?>">
                        <input type="hidden" name="origen" value="index.php">
                        <button type="submit" class="boton-carrito">Añadir al carrito</button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</main>

<?php include_once 'includes/footer.php'; ?>