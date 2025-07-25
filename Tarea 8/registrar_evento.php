<?php
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre      = trim($_POST['nombre']      ?? '');
    $tipo        = trim($_POST['tipo']        ?? '');
    $lugar       = trim($_POST['lugar']       ?? '');
    $fecha       = $_POST['fecha']            ?? '';
    $hora        = $_POST['hora']             ?? '';
    $descripcion = trim($_POST['descripcion'] ?? '');

    if ($nombre && $tipo && $lugar && $fecha && $hora && $descripcion) {
        $sql  = "INSERT INTO EVENTO (nombre, tipo, lugar, fecha, hora, descripcion)
                 VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $nombre, $tipo, $lugar, $fecha, $hora, $descripcion);
        $stmt->execute();
        $msg  = "Evento registrado correctamente";
    } else {
        $msg = "Datos incompletos o inválidos";
    }
    header("Location: index.php?msg=" . urlencode($msg));
    exit;
}
?>

