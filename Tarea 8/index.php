<?php
declare(strict_types=1);
ini_set('display_errors', '1');
error_reporting(E_ALL);

session_start();
require_once __DIR__ . '/donaciones.php';
require_once __DIR__ . '/EventManager.php';

/* ───── Datos iniciales ───── */
if (!isset($_SESSION['projects'])) {
    $_SESSION['projects'] = [
        [
            'nombre'      => 'Agua para Todos',
            'descripcion' => 'Llevar agua potable a comunidades rurales',
            'meta'        => 500000,
            'recaudado'   => 0
        ],
        [
            'nombre'      => 'Educación Digital',
            'descripcion' => 'Capacitación tecnológica a jóvenes',
            'meta'        => 750000,
            'recaudado'   => 0
        ],
    ];
}
if (!isset($_SESSION['gestorEventos'])) {
    $gestor = new EventManager();
    $gestor->add(new Evento('Campaña de Invierno',  'Campaña',  'Santiago',   '2025-07-10', '10:00', 'Entrega de ropa y alimentos'));
    $gestor->add(new Evento('Maratón Solidaria',    'Deportivo','Valparaíso', '2025-08-15', '08:00', 'Corre 10K solidario'));
    $gestor->add(new Evento('Feria de Voluntariado','Feria',    'Online',     '2025-09-01', '16:00', 'Conecta con ONGs'));
    $_SESSION['gestorEventos'] = serialize($gestor);
} else {
    $gestor = unserialize($_SESSION['gestorEventos']);
}

/* ───── Procesar donación ───── */
$mensaje = '';
if (isset($_POST['donar'])) {
    $mensaje = simularDonacion(
        (int)($_POST['projectId'] ?? -1),
        (float)($_POST['monto'] ?? 0)
    );
}

/* ───── Filtro de eventos ───── */
$criteria = [
    'tipo'  => $_GET['tipo']  ?? '',
    'lugar' => $_GET['lugar'] ?? '',
    'desde' => $_GET['desde'] ?? '',
    'hasta' => $_GET['hasta'] ?? '',
];
$eventos = array_filter($criteria) ? $gestor->filtrar($criteria) : $gestor->all();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Organización sin fines de lucro</title>
  <link rel="stylesheet" href="styles.css" />
</head>
<body>
  <h1 class="titulo">Plataforma de Proyectos y Donaciones</h1>

  <!-- Buscador de eventos -->
  <div class="search-container">
    <form method="get" class="filter-box">
      <input type="text"  name="lugar" placeholder="Lugar…" value="<?= htmlspecialchars($criteria['lugar']) ?>">
      <input type="text"  name="tipo"  placeholder="Tipo…"  value="<?= htmlspecialchars($criteria['tipo'])  ?>">
      <input type="date"  name="desde" value="<?= htmlspecialchars($criteria['desde']) ?>">
      <input type="date"  name="hasta" value="<?= htmlspecialchars($criteria['hasta']) ?>">
      <button class="btn-filtrar">Filtrar eventos</button>
    </form>
  </div>

  <!-- Notificaciones -->
  <div id="notifications">
      <?= htmlspecialchars($_GET['msg'] ?? $mensaje) ?>
  </div>

  <!-- Eventos -->
  <div id="results-container">
    <h2>Eventos</h2>
    <?php if (!$eventos): ?>
        <p>No se encontraron eventos.</p>
    <?php else: ?>
        <?php foreach ($eventos as $ev): ?>
        <div class="card">
          <h3><?= htmlspecialchars($ev->nombre) ?></h3>
          <p><strong>Tipo:</strong> <?= htmlspecialchars($ev->tipo) ?>
             | <strong>Lugar:</strong> <?= htmlspecialchars($ev->lugar) ?></p>
          <p><strong>Fecha:</strong> <?= htmlspecialchars($ev->fecha) ?> – <?= htmlspecialchars($ev->hora) ?></p>
          <p><?= htmlspecialchars($ev->descripcion) ?></p>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
  </div>

  <!-- Proyectos activos y donación -->
  <div id="projects-container">
    <h2>Proyectos activos</h2>
    <?php foreach ($_SESSION['projects'] as $i => $p): ?>
      <div class="card">
        <h3><?= htmlspecialchars($p['nombre']) ?></h3>
        <p><?= htmlspecialchars($p['descripcion']) ?></p>
        <p><strong>Meta:</strong> $<?= number_format($p['meta'],0,',','.') ?></p>
        <p><strong>Recaudado:</strong> $<?= number_format($p['recaudado'],0,',','.') ?></p>

        <!-- Donación directa -->
        <form method="post" style="display:flex;gap:8px;flex-wrap:wrap">
          <input type="hidden" name="projectId" value="<?= $i ?>">
          <input type="number" name="monto" min="1000" step="1000" value="10000" required>
          <button name="donar">Donar</button>
        </form>

        <!-- Añadir al carrito -->
        <a href="cart.php?add=<?= $i ?>" class="btn-cart">Añadir al carrito</a>
      </div>
    <?php endforeach; ?>
  </div>

  <!-- Formulario: Registrar nueva donación -->
<div id="results-container">
  <h2>Registrar donación</h2>
  <form action="procesar_donacion.php" method="post" style="display:grid;gap:10px">
    <input type="text" name="nombre" placeholder="Nombre del donante" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="text" name="direccion" placeholder="Dirección" required>
    <input type="text" name="telefono" placeholder="Teléfono" required>
    <input type="number" name="monto" placeholder="Monto donado" required>
    <select name="proyecto" required>
      <option value="">Selecciona un proyecto</option>
      <?php
        // Cargar proyectos desde la base de datos
        require_once 'conexion.php';
        $res = $conn->query("SELECT id_proyecto, nombre FROM PROYECTO");
        while ($row = $res->fetch_assoc()) {
          echo "<option value='{$row['id_proyecto']}'>{$row['nombre']}</option>";
        }
      ?>
    </select>
    <button class="btn-registrar" type="submit">Registrar donación</button>
  </form>
</div>


  <!-- Formulario: Registrar nuevo evento -->
  <div id="results-container">
    <h2>Registrar nuevo evento</h2>
    <form action="registrar_evento.php" method="post" style="display:grid;gap:10px">
      <input type="text" name="nombre" placeholder="Nombre del evento" required>
      <input type="text" name="tipo" placeholder="Tipo de evento" required>
      <input type="text" name="lugar" placeholder="Lugar" required>
      <input type="date" name="fecha" required>
      <input type="time" name="hora" required>
      <textarea name="descripcion" rows="3" placeholder="Descripción" required></textarea>
      <button class="btn-registrar" type="submit">Registrar evento</button>
    </form>
  </div>

  <!-- Formulario: Registrar nuevo proyecto -->
  <div id="results-container">
    <h2>Registrar nuevo proyecto</h2>
    <form action="procesar_proyecto.php" method="post" style="display:grid;gap:10px">
      <input type="text" name="nombre" placeholder="Nombre del proyecto" required>
      <textarea name="descripcion" placeholder="Descripción" required></textarea>
      <input type="number" name="presupuesto" placeholder="Presupuesto" required>
      <input type="date" name="fecha_inicio" required>
      <input type="date" name="fecha_fin" required>
      <button class="btn-registrar" type="submit">Registrar proyecto</button>
    </form>
  </div>

  <!-- Formulario: Registrar nuevo donante -->
  <div id="results-container">
    <h2>Registrar nuevo donante</h2>
    <form action="procesar_donante.php" method="post" style="display:grid;gap:10px">
      <input type="text" name="nombre" placeholder="Nombre del donante" required>
      <input type="email" name="email" placeholder="Email" required>
      <input type="text" name="direccion" placeholder="Dirección" required>
      <input type="text" name="telefono" placeholder="Teléfono" required>
      <button class="btn-registrar" type="submit">Registrar donante</button>
    </form>
  </div>

  <div style="text-align:center; margin-top: 20px;">
  <a href="reporte_donaciones.php" class="btn-filtrar"> Ver reporte de donaciones</a>
</div>


  <script src="script.js"></script>
</body>
</html>
