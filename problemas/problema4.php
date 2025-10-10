<?php
/**
 * PROBLEMA 4: Suma de pares e impares
 * 
 * Calcular independientemente la suma de los números pares e impares 
 * comprendidos entre 1 y 200.
 */

include '../includes/Validaciones.php';
include '../includes/Navegacion.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Problema 4 - Pares e Impares</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="container">
        <h1>🔄 Problema 4: Suma de Pares e Impares (1-200)</h1>
        
        <?php
        $sumaPares = 0;
        $sumaImpares = 0;
        $pares = [];
        $impares = [];
        $contadorPares = 0;
        $contadorImpares = 0;
        
        // Calcular usando for
        for ($i = 1; $i <= 200; $i++) {
            if ($i % 2 == 0) {
                // Número par
                $sumaPares += $i;
                $contadorPares++;
                if ($contadorPares <= 5) {
                    $pares[] = $i; // Guardar primeros 5 para mostrar
                }
            } else {
                // Número impar
                $sumaImpares += $i;
                $contadorImpares++;
                if ($contadorImpares <= 5) {
                    $impares[] = $i; // Guardar primeros 5 para mostrar
                }
            }
        }
        
        // Fórmulas matemáticas para verificación
        // Pares: 2 + 4 + ... + 200 = 2(1 + 2 + ... + 100) = 2 * (100*101/2)
        $sumaParesFormula = 2 * (100 * 101 / 2);
        // Impares: 1 + 3 + ... + 199 = (1 + 2 + ... + 200) - (2 + 4 + ... + 200)
        $sumaImparesFormula = (200 * 201 / 2) - $sumaParesFormula;
        
        echo "<div class='resultado'>";
        echo "<h3>✅ Resultados</h3>";
        
        echo "<h4>Números Pares (1-200):</h4>";
        echo "<p><strong>Cantidad:</strong> $contadorPares números</p>";
        echo "<p><strong>Primeros 5:</strong> " . implode(', ', $pares) . ", ...</p>";
        echo "<p><strong>Últimos 5:</strong> ..., 192, 194, 196, 198, 200</p>";
        echo "<p><strong>Suma total:</strong> $sumaPares</p>";
        echo "<p><strong>Verificación fórmula:</strong> $sumaParesFormula ✓</p>";
        
        echo "<h4>Números Impares (1-200):</h4>";
        echo "<p><strong>Cantidad:</strong> $contadorImpares números</p>";
        echo "<p><strong>Primeros 5:</strong> " . implode(', ', $impares) . ", ...</p>";
        echo "<p><strong>Últimos 5:</strong> ..., 191, 193, 195, 197, 199</p>";
        echo "<p><strong>Suma total:</strong> $sumaImpares</p>";
        echo "<p><strong>Verificación fórmula:</strong> $sumaImparesFormula ✓</p>";
        
        echo "<h4>🔍 Análisis Comparativo:</h4>";
        echo "<p><strong>Diferencia:</strong> " . abs($sumaPares - $sumaImpares) . "</p>";
        echo "<p><strong>Suma total (pares + impares):</strong> " . ($sumaPares + $sumaImpares) . "</p>";
        echo "<p><strong>Verificación total:</strong> " . (200 * 201 / 2) . " ✓</p>";
        
        echo "</div>";
        ?>
        
        <?php volverAlMenu(); ?>
    </div>
    
    <?php include '../footer.php'; ?>
</body>
</html>