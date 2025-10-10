<?php
/**
 * PROBLEMA 3: Suma de 1 al 1000
 * 
 * Calcular la suma de los números del 1 al 1,000.
 * Fórmula matemática: n(n+1)/2 = 1000*1001/2 = 500500
 * También implementado con ciclo for para demostración.
 */

include '../includes/Validaciones.php';
include '../includes/Navegacion.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Problema 3 - Suma 1 al 1000</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="container">
        <h1>🧮 Problema 3: Suma del 1 al 1,000</h1>
        
        <?php
        // Método 1: Usando fórmula matemática (eficiente)
        $sumaFormula = (1000 * 1001) / 2;
        
        // Método 2: Usando ciclo for (para demostración)
        $sumaCiclo = 0;
        for ($i = 1; $i <= 1000; $i++) {
            $sumaCiclo += $i;
        }
        
        // Método 3: Usando while
        $sumaWhile = 0;
        $contador = 1;
        while ($contador <= 1000) {
            $sumaWhile += $contador;
            $contador++;
        }
        
        echo "<div class='resultado'>";
        echo "<h3>✅ Resultados de la Suma</h3>";
        echo "<p><strong>Usando fórmula matemática:</strong> 1000 × 1001 ÷ 2 = $sumaFormula</p>";
        echo "<p><strong>Usando ciclo for:</strong> $sumaCiclo</p>";
        echo "<p><strong>Usando ciclo while:</strong> $sumaWhile</p>";
        
        // Verificar que todos los métodos den el mismo resultado
        if ($sumaFormula === $sumaCiclo && $sumaCiclo === $sumaWhile) {
            echo "<p style='color: #27ae60;'><strong>✓ Todos los métodos coinciden: 500500</strong></p>";
        }
        
        echo "<h4>🔍 Explicación Matemática:</h4>";
        echo "<p>La fórmula n(n+1)/2 fue descubierta por Carl Friedrich Gauss cuando era niño.</p>";
        echo "<p>Para n=1000: 1000 × 1001 ÷ 2 = 500500</p>";
        echo "</div>";
        ?>
        
        <?php volverAlMenu(); ?>
    </div>
    
    <?php include '../footer.php'; ?>
</body>
</html>