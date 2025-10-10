<?php
/**
 * PROBLEMA 7: Calculadora de Notas
 * 
 * Pedir la cantidad de notas que desea ingresar el usuario.
 * Luego pedir esas notas y calcular:
 * - Promedio
 * - Desviación estándar  
 * - Nota mínima
 * - Nota máxima
 * 
 * Usar foreach para recorrer la colección.
 */

include '../includes/Validaciones.php';
include '../includes/Navegacion.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Problema 7 - Calculadora de Notas</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="container">
        <h1>📚 Problema 7: Calculadora de Notas Estadísticas</h1>
        
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errores = [];
            $notas = [];
            
            // Determinar en qué paso estamos
            if (isset($_POST['cantidad_notas'])) {
                // Paso 1: Se ingresó la cantidad de notas
                $cantidadNotas = $_POST['cantidad_notas'];
                
                // Validar cantidad de notas
                if (empty($cantidadNotas)) {
                    $errores[] = "La cantidad de notas es requerida";
                } elseif (!Validaciones::validarEntero($cantidadNotas)) {
                    $errores[] = "La cantidad debe ser un número entero";
                } elseif (!Validaciones::validarRango($cantidadNotas, 1, 50)) {
                    $errores[] = "La cantidad debe estar entre 1 y 50";
                } else {
                    $cantidadNotas = (int)$cantidadNotas;
                    
                    // Mostrar formulario para ingresar las notas
                    echo "<form method='post'>";
                    echo "<input type='hidden' name='cantidad_notas' value='$cantidadNotas'>";
                    echo "<h3>Ingrese las $cantidadNotas notas (0-100):</h3>";
                    
                    for ($i = 1; $i <= $cantidadNotas; $i++) {
                        echo "<div class='form-group'>";
                        echo "<label for='nota$i'>Nota $i:</label>";
                        echo "<input type='number' 
                                     id='nota$i' 
                                     name='notas[]' 
                                     min='0' 
                                     max='100' 
                                     step='0.01' 
                                     required 
                                     placeholder='0-100'>";
                        echo "</div>";
                    }
                    
                    echo "<input type='submit' value='Calcular Estadísticas'>";
                    echo "</form>";
                }
                
            } elseif (isset($_POST['notas'])) {
                // Paso 2: Se ingresaron las notas
                $notasInput = $_POST['notas'];
                $cantidadNotas = count($notasInput);
                
                // Validar cada nota
                foreach ($notasInput as $indice => $nota) {
                    $numeroNota = $indice + 1;
                    
                    if (empty($nota) && $nota !== '0') {
                        $errores[] = "La nota $numeroNota es requerida";
                    } elseif (!is_numeric($nota)) {
                        $errores[] = "La nota $numeroNota debe ser un número válido";
                    } elseif (!Validaciones::validarRango($nota, 0, 100)) {
                        $errores[] = "La nota $numeroNota debe estar entre 0 y 100";
                    } else {
                        $notas[] = (float)$nota;
                    }
                }
                
                if (empty($errores)) {
                    // Calcular estadísticas
                    $cantidad = count($notas);
                    $suma = array_sum($notas);
                    $promedio = $suma / $cantidad;
                    $minima = min($notas);
                    $maxima = max($notas);
                    
                    // Calcular desviación estándar muestral
                    $sumaCuadrados = 0;
                    foreach ($notas as $nota) {
                        $sumaCuadrados += pow($nota - $promedio, 2);
                    }
                    $desviacionEstandar = sqrt($sumaCuadrados / ($cantidad - 1));
                    
                    // Determinar categoría del promedio
                    if ($promedio >= 90) {
                        $categoria = "Excelente (A)";
                        $colorCategoria = "#27ae60";
                    } elseif ($promedio >= 80) {
                        $categoria = "Bueno (B)";
                        $colorCategoria = "#3498db";
                    } elseif ($promedio >= 70) {
                        $categoria = "Regular (C)";
                        $colorCategoria = "#f39c12";
                    } elseif ($promedio >= 60) {
                        $categoria = "Aprobatorio (D)";
                        $colorCategoria = "#e67e22";
                    } else {
                        $categoria = "Reprobatorio (F)";
                        $colorCategoria = "#e74c3c";
                    }
                    
                    echo "<div class='resultado'>";
                    echo "<h3>✅ Resultados Estadísticos</h3>";
                    
                    // Mostrar notas ingresadas
                    echo "<h4>📋 Notas Ingresadas ($cantidad notas):</h4>";
                    echo "<p>" . implode(' | ', $notas) . "</p>";
                    
                    // Mostrar estadísticas en tabla
                    echo "<h4>📊 Estadísticas Descriptivas:</h4>";
                    echo "<table class='tabla-resultados'>";
                    echo "<tr><th>Estadística</th><th>Valor</th></tr>";
                    echo "<tr><td>Cantidad de Notas</td><td>$cantidad</td></tr>";
                    echo "<tr><td>Suma Total</td><td>" . number_format($suma, 2) . "</td></tr>";
                    echo "<tr><td>Promedio</td><td>" . number_format($promedio, 2) . "</td></tr>";
                    echo "<tr><td>Desviación Estándar</td><td>" . number_format($desviacionEstandar, 2) . "</td></tr>";
                    echo "<tr><td>Nota Mínima</td><td>" . number_format($minima, 2) . "</td></tr>";
                    echo "<tr><td>Nota Máxima</td><td>" . number_format($maxima, 2) . "</td></tr>";
                    echo "<tr style='background: $colorCategoria; color: white;'>
                            <td><strong>Categoría</strong></td>
                            <td><strong>$categoria</strong></td>
                          </tr>";
                    echo "</table>";
                    
                    // Análisis adicional
                    echo "<h4>🔍 Análisis Adicional:</h4>";
                    
                    // Rango intercuartílico aproximado
                    $rango = $maxima - $minima;
                    echo "<p><strong>Rango:</strong> " . number_format($rango, 2) . "</p>";
                    
                    // Coeficiente de variación
                    $coefVariacion = ($desviacionEstandar / $promedio) * 100;
                    echo "<p><strong>Coeficiente de Variación:</strong> " . number_format($coefVariacion, 2) . "%</p>";
                    
                    // Interpretación del coeficiente de variación
                    if ($coefVariacion < 15) {
                        echo "<p>✅ Baja variabilidad - Las notas son consistentes</p>";
                    } elseif ($coefVariacion < 30) {
                        echo "<p>⚠️ Variabilidad moderada - Dispersión aceptable</p>";
                    } else {
                        echo "<p>❌ Alta variabilidad - Las notas están muy dispersas</p>";
                    }
                    
                    echo "</div>";
                }
            }
            
            // Mostrar errores si los hay
            if (!empty($errores)) {
                echo "<div class='error'>";
                echo "<h3>❌ Errores encontrados:</h3>";
                echo "<ul>";
                foreach ($errores as $error) {
                    echo "<li>$error</li>";
                }
                echo "</ul>";
                echo "</div>";
                
                // Volver a mostrar el formulario inicial
                echo "<p><a href='problema7.php' class='btn-menu'>↩️ Volver a intentar</a></p>";
            }
            
        } else {
            // Mostrar formulario inicial para cantidad de notas
            ?>
            <form method="post">
                <div class="form-group">
                    <label for="cantidad_notas">¿Cuántas notas desea ingresar?</label>
                    <input type="number" 
                           id="cantidad_notas" 
                           name="cantidad_notas" 
                           min="1" 
                           max="50" 
                           required 
                           placeholder="Entre 1 y 50">
                </div>
                <input type="submit" value="Continuar">
            </form>
            
            <div class="info">
                <h4>ℹ️ Información:</h4>
                <ul>
                    <li>Las notas deben estar en escala de 0 a 100</li>
                    <li>Puede ingresar decimales (ej: 85.5)</li>
                    <li>Se calculará: promedio, desviación estándar, mínima y máxima</li>
                    <li>Se categorizará el rendimiento según el promedio</li>
                </ul>
            </div>
            <?php
        }
        ?>
        
        <?php volverAlMenu(); ?>
    </div>
    
    <?php include '../footer.php'; ?>
</body>
</html>