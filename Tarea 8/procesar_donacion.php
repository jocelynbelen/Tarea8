<?php
require_once 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre    = trim($_POST['nombre']);
    $email     = trim($_POST['email']);
    $direccion = trim($_POST['direccion']);
    $telefono  = trim($_POST['telefono']);
    $monto     = (float)$_POST['monto'];
    $proyecto  = (int)$_POST['proyecto'];

    if (!$nombre || !$email || !$direccion || !$telefono || !$monto || !$proyecto) {
        die("Todos los campos son obligatorios.");
    }

    $stmt = $conn->prepare("SELECT id_donante FROM DONANTE WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $id_donante = $row['id_donante'];
    } else {
        $stmt = $conn->prepare("INSERT INTO DONANTE (nombre, email, direccion, telefono) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nombre, $email, $direccion, $telefono);
        $stmt->execute();
        $id_donante = $stmt->insert_id;
    }

    $stmt = $conn->prepare("INSERT INTO DONACION (monto, fecha, id_proyecto, id_donante) VALUES (?, CURDATE(), ?, ?)");
    $stmt->bind_param("dii", $monto, $proyecto, $id_donante);
    $stmt->execute();

    header("Location: index.php?msg=¡Donación registrada exitosamente!");
    exit;
} else {
    echo "Acceso no permitido.";
}
?>
