<?php
/**
 * PROBLEMA 9: Potencias de un Número
 * 
 * Solicitar un número (1 al 9) y generar las 15 primeras potencias del número.
 * Ejemplo: 4 elevado a la 1, 4 elevado a la 2, ... hasta 4 elevado a la 15
 * 
 * Usar ciclo for y funciones matemáticas.
 */

include '../includes/Validaciones.php';
include '../includes/Navegacion.php';

/**
 * Función para calcular potencia sin usar pow() (para demostración)
 */
function calcularPotencia($base, $exponente) {
    $resultado = 1;
    for ($i = 0; $i < $exponente; $i++) {
        $resultado *= $base;
    }
    return $resultado;
}

/**
 * Función para formatear números grandes
 */
function formatearNumeroGrande($numero) {
    if ($numero >= 1000000) {
        return number_format($numero) . " (" . number_format($numero, 0, '.', ',') . ")";
    }
    return number_format($numero, 0, '.', ',');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Problema 9 - Potencias</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="container">
        <h1>⚡ Problema 9: Generador de Potencias</h1>
        
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $numeroBase = $_POST['numero_base'] ?? '';
            $errores = [];
            
            // Validaciones
            if (empty($numeroBase)) {
                $errores[] = "El número base es requerido";
            } elseif (!Validaciones::validarEntero($numeroBase)) {
                $errores[] = "El número base debe ser un entero";
            } elseif (!Validaciones::validarRango($numeroBase, 1, 9)) {
                $errores[] = "El número base debe estar entre 1 y 9";
            }
            
            if (empty($errores)) {
                $numeroBase = (int)$numeroBase;
                $totalPotencias = 15;
                
                echo "<div class='resultado'>";
                echo "<h3>✅ Las $totalPotencias primeras potencias de $numeroBase</h3>";
                
                echo "<table class='tabla-resultados'>";
                echo "<tr><th>Potencia</th><th>Operación</th><th>Resultado</th><th>Notación Científica</th></tr>";
                
                $resultados = [];
                for ($exponente = 1; $exponente <= $totalPotencias; $exponente++) {
                    // Calcular usando función personalizada
                    $resultado = calcularPotencia($numeroBase, $exponente);
                    $resultados[] = $resultado;
                    
                    // Verificar con función pow() de PHP
                    $verificacion = pow($numeroBase, $exponente);
                    $coincide = ($resultado == $verificacion) ? "✓" : "✗";
                    
                    // Formatear resultados
                    $operacion = "$numeroBase<sup>$exponente</sup>";
                    $resultadoFormateado = formatearNumeroGrande($resultado);
                    $notacionCientifica = number_format($resultado, 0, '.', ',');
                    
                    // Si es muy grande, usar notación científica
                    if ($resultado >= 1000000) {
                        $notacionCientifica = sprintf("%.2e", $resultado);
                    }
                    
                    echo "<tr>
                            <td>$exponente</td>
                            <td>$operacion</td>
                            <td>$resultadoFormateado $coincide</td>
                            <td>$notacionCientifica</td>
                          </tr>";
                }
                echo "</table>";
                
                // Análisis de los resultados
                echo "<h4>📈 Análisis de las Potencias:</h4>";
                
                $crecimiento = [];
                for ($i = 1; $i < count($resultados); $i++) {
                    $factor = $resultados[$i] / $resultados[$i - 1];
                    $crecimiento[] = $factor;
                }
                
                echo "<p><strong>Factor de crecimiento constante:</strong> $numeroBase</p>";
                echo "<p><strong>Patrón:</strong> Cada potencia es $numeroBase veces la anterior</p>";
                
                // Mostrar propiedades matemáticas
                echo "<h4>🔢 Propiedades Matemáticas:</h4>";
                echo "<ul>";
                echo "<li><strong>Primera potencia:</strong> $numeroBase<sup>1</sup> = " . $resultados[0] . "</li>";
                echo "<li><strong>Última potencia:</strong> $numeroBase<sup>$totalPotencias</sup> = " . formatearNumeroGrande(end($resultados)) . "</li>";
                
                $sumaPotencias = array_sum($resultados);
                echo "<li><strong>Suma de todas las potencias:</strong> " . formatearNumeroGrande($sumaPotencias) . "</li>";
                
                // Crecimiento porcentual
                $crecimientoTotal = (end($resultados) / $resultados[0]) * 100;
                echo "<li><strong>Crecimiento total:</strong> " . number_format($crecimientoTotal, 0) . "%</li>";
                echo "</ul>";
                
                // Advertencia sobre desbordamiento
                if (end($resultados) > PHP_INT_MAX / 100) {
                    echo "<div class='error'>";
                    echo "<h4>⚠️ Advertencia de Desbordamiento</h4>";
                    echo "<p>Las siguientes potencias podrían causar desbordamiento numérico en PHP.</p>";
                    echo "<p><strong>Límite máximo de PHP:</strong> " . number_format(PHP_INT_MAX) . "</p>";
                    echo "</div>";
                }
                
                echo "</div>";
                
            } else {
                echo "<div class='error'>";
                echo "<h3>❌ Errores encontrados:</h3>";
                echo "<ul>";
                foreach ($errores as $error) {
                    echo "<li>$error</li>";
                }
                echo "</ul>";
                echo "</div>";
            }
        }
        ?>
        
        <form method="post">
            <div class="form-group">
                <label for="numero_base">Ingrese un número base (1-9):</label>
                <input type="number" 
                       id="numero_base" 
                       name="numero_base" 
                       min="1" 
                       max="9" 
                       required 
                       placeholder="Ej: 4">
            </div>
            
            <input type="submit" value="Generar Potencias">
        </form>
        
        <div class="info">
            <h4>ℹ️ Información sobre Potencias:</h4>
            <p><strong>Definición:</strong> Una potencia es una operación matemática que consiste 
            en multiplicar un número (base) por sí mismo varias veces (exponente).</p>
            
            <p><strong>Fórmula:</strong> a<sup>n</sup> = a × a × a × ... × a (n veces)</p>
            
            <h4>🎯 Ejemplos Interesantes:</h4>
            <ul>
                <li><strong>2<sup>10</sup> = 1,024</strong> (Base de sistema binario)</li>
                <li><strong>3<sup>5</sup> = 243</strong></li>
                <li><strong>5<sup>4</sup> = 625</strong></li>
                <li><strong>9<sup>6</sup> = 531,441</strong> (Crecimiento rápido)</li>
            </ul>
            
            <h4>⚠️ Nota sobre Desbordamiento:</h4>
            <p>Para bases mayores a 1, las potencias crecen exponencialmente y pueden 
            alcanzar números muy grandes rápidamente, potencialmente causando 
            desbordamiento en los cálculos.</p>
        </div>
        
        <?php volverAlMenu(); ?>
    </div>
    
    <?php include '../footer.php'; ?>
</body>
</html>