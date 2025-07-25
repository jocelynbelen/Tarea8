<?php
session_start();

if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
    header('Location: cart.php?msg=No hay proyectos en el carrito.');
    exit;
}

$projects = $_SESSION['projects'] ?? [];
$carrito = $_SESSION['carrito'];
$total = 0;

foreach ($carrito as $id => $cantidad) {
    $monto = $cantidad * 10000;
    $total += $monto;
    $_SESSION['projects'][$id]['recaudado'] += $monto;
}

unset($_SESSION['carrito']);

$msg = "Donación confirmada por un total de $" . number_format($total, 0, ',', '.') . ". ¡Gracias por tu apoyo!";
header("Location: index.php?msg=" . urlencode($msg));
exit;
