<?php
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre        = trim($_POST['nombre']        ?? '');
    $descripcion   = trim($_POST['descripcion']   ?? '');
    $presupuesto   = (float)($_POST['presupuesto'] ?? 0);
    $fecha_inicio  = $_POST['fecha_inicio']       ?? '';
    $fecha_fin     = $_POST['fecha_fin']          ?? '';

    if ($nombre && $descripcion && $presupuesto > 0 && $fecha_inicio && $fecha_fin) {
        $sql  = "INSERT INTO PROYECTO (nombre, descripcion, presupuesto, fecha_inicio, fecha_fin)
                 VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdss", $nombre, $descripcion, $presupuesto, $fecha_inicio, $fecha_fin);
        $stmt->execute();
        $msg  = "Proyecto registrado correctamente";
    } else {
        $msg = "Datos incompletos o inválidos";
    }
    header("Location: index.php?msg=" . urlencode($msg));
    exit;
}
?>
