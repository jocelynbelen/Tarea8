<?php
require_once 'conexion.php';

$monto = $_POST['monto'];
$fecha = $_POST['fecha'];
$id_proyecto = $_POST['id_proyecto'];
$id_donante = $_POST['id_donante'];

$sql = "INSERT INTO DONACION (monto, fecha, id_proyecto, id_donante)
        VALUES (?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("dsii", $monto, $fecha, $id_proyecto, $id_donante);

if ($stmt->execute()) {
    echo "Donación registrada correctamente.";
} else {
    echo "Error: " . $stmt->error;
}

$conn->close();
?>
