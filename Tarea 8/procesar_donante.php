<?php
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');

    if (empty($nombre) || empty($email) || empty($direccion) || empty($telefono)) {
        die("Error: Todos los campos son obligatorios.");
    }


    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Error: El correo electrónico no es válido.");
    }


    $stmt = $conn->prepare("INSERT INTO DONANTE (nombre, email, direccion, telefono) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nombre, $email, $direccion, $telefono);

    if ($stmt->execute()) {

        header("Location: index.php?msg=Donante registrado correctamente");
        exit;
    } else {
        echo "Error al insertar donante: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Método no permitido.";
}
?>
