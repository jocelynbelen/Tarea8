<?php
$servername = "localhost"; // o 127.0.0.1
$username = "root";        // usuario de MySQL
$password = "";            // contraseña, normalmente vacío en local
$dbname = "ORGANIZACION";  // base de datos creada

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname, 3307);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
