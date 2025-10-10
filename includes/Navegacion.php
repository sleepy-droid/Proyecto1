<?php
/**
 * Funciones de navegación para el proyecto
 */
function volverAlMenu($url = '../index.php') {
    echo "<div class='navegacion'>";
    echo "<a href='$url' class='btn-menu'>🏠 Volver al Menú Principal</a>";
    echo "</div>";
}

function generarMenu() {
    $problemas = [
        1 => "Estadísticas de 5 números",
        2 => "Múltiplos de 4", 
        3 => "Suma del 1 al 1000",
        4 => "Pares e impares 1-200",
        5 => "Clasificación de edades",
        6 => "Presupuesto hospital",
        7 => "Calculadora de notas",
        8 => "Estación del año", 
        9 => "Potencias de un número",
        10 => "Vendedores y productos"
    ];
    
    echo "<nav class='menu-principal'>";
    echo "<h2>Problemas Disponibles</h2>";
    echo "<ul>";
    foreach ($problemas as $numero => $descripcion) {
        echo "<li><a href='problemas/problema$numero.php'>Problema $numero: $descripcion</a></li>";
    }
    echo "</ul>";
    echo "</nav>";
}
?>