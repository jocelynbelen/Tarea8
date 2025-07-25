<?php
session_start();

$carrito  = $_SESSION['carrito']  ?? [];
$projects = $_SESSION['projects'] ?? [];


if (isset($_GET['add'])) {
    $id = (int) $_GET['add'];
    $_SESSION['carrito'][$id] = ($_SESSION['carrito'][$id] ?? 0) + 1;
    header('Location: cart.php');  
    exit;
}
if (isset($_GET['remove'])) {
    $id = (int) $_GET['remove'];
    unset($_SESSION['carrito'][$id]);
    header('Location: cart.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Mi carrito de donaciones</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <h1 class="titulo">Mi carrito de donaciones</h1>

  <div id="cart-container">
  <?php if (!$carrito): ?>
      <p style="text-align:center;">No hay proyectos en el carrito.</p>
  <?php else: ?>
      <?php
        $total = 0;
        foreach ($carrito as $id => $cantidad):
            $p     = $projects[$id];
            $monto = $cantidad * 10000;   
            $total += $monto;
      ?>
        <div class="card">
          <h3><?= htmlspecialchars($p['nombre']) ?></h3>
          <p><strong>Unidades:</strong> <?= $cantidad ?></p>
          <p><strong>Monto:</strong> $<?= number_format($monto, 0, ',', '.') ?></p>
          <a href="cart.php?remove=<?= $id ?>" class="btn-cart">Quitar</a>
        </div>
      <?php endforeach; ?>

      <!-- Tarjeta resumen + botón Confirmar -->
      <div class="card">
        <h3>Total donación: $<?= number_format($total, 0, ',', '.') ?></h3>
        <form method="post" action="confirmar_donacion.php">
          <button type="submit" class="btn-filtrar" name="checkout">Confirmar donación</button>
        </form>
      </div>
  <?php endif; ?>

    <div style="text-align:center;margin-top:20px;">
      <a href="index.php" class="btn-filtrar">← Seguir explorando proyectos</a>
    </div>
  </div>
</body>
</html>


