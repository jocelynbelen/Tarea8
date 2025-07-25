<?php
require_once 'conexion.php';

$sql = "SELECT p.nombre, COUNT(d.id_donacion) AS total_donaciones, SUM(d.monto) AS total_recaudado
        FROM PROYECTO p
        JOIN DONACION d ON p.id_proyecto = d.id_proyecto
        GROUP BY p.id_proyecto
        HAVING total_donaciones > 2";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Reporte de Donaciones</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <h1 class="titulo">Proyectos con más de 2 donaciones</h1>

  <div id="results-container">
    <?php if ($result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <div class="card">
          <h3><?= htmlspecialchars($row['nombre']) ?></h3>
          <p><strong>Total de donaciones:</strong> <?= $row['total_donaciones'] ?></p>
          <p><strong>Monto recaudado:</strong> $<?= number_format($row['total_recaudado'], 0, ',', '.') ?></p>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p style="text-align:center;">No hay proyectos con más de 2 donaciones.</p>
    <?php endif; ?>
  </div>

  <div style="text-align:center;">
    <a href="index.php" class="btn-filtrar">← Volver al inicio</a>
  </div>
</body>
</html>
<?php $conn->close(); ?>
