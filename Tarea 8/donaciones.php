<?php
if (session_status()===PHP_SESSION_NONE){session_start();}

function simularDonacion(int $projectId, float $amount): string
{ //Función para donar, validar y acumular las donaciones
    // Validación
    if ($amount <= 0) {
        return '⛔ Monto de donación inválido.';
    }
    if (!isset($_SESSION['projects'][$projectId])) {
        return '⛔ Proyecto no encontrado.';
    }

    $_SESSION['projects'][$projectId]['recaudado'] += $amount;

    return '💰 ¡Gracias! Donaste $' . 
           number_format($amount, 0, ',', '.') .
           ' al proyecto “' . $_SESSION['projects'][$projectId]['nombre'] . '”.';
}
