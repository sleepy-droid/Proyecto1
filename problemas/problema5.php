<?php
/**
 * PROBLEMA 5: Clasificación de Edades
 * 
 * Leer la edad de 5 personas y clasificar cada una en categorías:
 * - Niño: 0-12 años
 * - Adolescente: 13-17 años  
 * - Adulto: 18-64 años
 * - Adulto mayor: 65+ años
 * 
 * Generar estadísticas de repetición de edades e integrar gráficas.
 */

include '../includes/Validaciones.php';
include '../includes/Navegacion.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Problema 5 - Clasificación de Edades</title>
    <link rel="stylesheet" href="../css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container">
        <h1>👥 Problema 5: Clasificación de Edades</h1>
        
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $edades = [];
            $categorias = [
                'Niño (0-12)' => 0,
                'Adolescente (13-17)' => 0,
                'Adulto (18-64)' => 0,
                'Adulto mayor (65+)' => 0
            ];
            
            $errores = [];
            $datosGrafica = [];
            
            // Procesar y validar las 5 edades
            for ($i = 1; $i <= 5; $i++) {
                $campo = "edad$i";
                $edad = $_POST[$campo] ?? '';
                
                if (empty($edad)) {
                    $errores[] = "La edad $i es requerida";
                } elseif (!Validaciones::validarEntero($edad)) {
                    $errores[] = "La edad $i debe ser un número entero";
                } elseif (!Validaciones::validarRango($edad, 0, 120)) {
                    $errores[] = "La edad $i debe estar entre 0 y 120 años";
                } else {
                    $edad = (int)$edad;
                    $edades[] = $edad;
                    
                    // Clasificar en categorías
                    if ($edad <= 12) {
                        $categorias['Niño (0-12)']++;
                    } elseif ($edad <= 17) {
                        $categorias['Adolescente (13-17)']++;
                    } elseif ($edad <= 64) {
                        $categorias['Adulto (18-64)']++;
                    } else {
                        $categorias['Adulto mayor (65+)']++;
                    }
                }
            }
            
            if (empty($errores)) {
                echo "<div class='resultado'>";
                echo "<h3>✅ Clasificación de Edades</h3>";
                
                // Mostrar clasificación individual
                echo "<h4>📋 Clasificación Individual:</h4>";
                echo "<table class='tabla-resultados'>";
                echo "<tr><th>#</th><th>Edad</th><th>Categoría</th></tr>";
                
                foreach ($edades as $indice => $edad) {
                    $numero = $indice + 1;
                    if ($edad <= 12) {
                        $categoria = 'Niño (0-12)';
                    } elseif ($edad <= 17) {
                        $categoria = 'Adolescente (13-17)';
                    } elseif ($edad <= 64) {
                        $categoria = 'Adulto (18-64)';
                    } else {
                        $categoria = 'Adulto mayor (65+)';
                    }
                    
                    echo "<tr><td>$numero</td><td>$edad años</td><td>$categoria</td></tr>";
                }
                echo "</table>";
                
                // Mostrar estadísticas
                echo "<h4>📊 Estadísticas por Categoría:</h4>";
                echo "<table class='tabla-resultados'>";
                echo "<tr><th>Categoría</th><th>Cantidad</th><th>Porcentaje</th></tr>";
                
                $totalPersonas = count($edades);
                foreach ($categorias as $categoria => $cantidad) {
                    $porcentaje = ($cantidad / $totalPersonas) * 100;
                    echo "<tr>
                            <td>$categoria</td>
                            <td>$cantidad</td>
                            <td>" . number_format($porcentaje, 1) . "%</td>
                          </tr>";
                }
                echo "</table>";
                
                // Estadísticas de repetición de edades
                $conteoEdades = array_count_values($edades);
                $edadesRepetidas = array_filter($conteoEdades, function($conteo) {
                    return $conteo > 1;
                });
                
                if (!empty($edadesRepetidas)) {
                    echo "<h4>🔄 Edades que se Repiten:</h4>";
                    echo "<ul>";
                    foreach ($edadesRepetidas as $edad => $veces) {
                        echo "<li><strong>$edad años:</strong> se repite $veces veces</li>";
                    }
                    echo "</ul>";
                } else {
                    echo "<p>✅ No hay edades repetidas</p>";
                }
                
                // Preparar datos para gráfica
                $datosGrafica = [
                    'labels' => array_keys($categorias),
                    'data' => array_values($categorias),
                    'colors' => ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0']
                ];
                
                echo "</div>";
                
                // Mostrar gráfica
                echo "<div style='margin: 30px 0;'>";
                echo "<h4>📈 Gráfica de Distribución por Categorías</h4>";
                echo "<canvas id='graficaEdades' width='400' height='200'></canvas>";
                echo "</div>";
                
                // Script para la gráfica
                echo "<script>
                const ctx = document.getElementById('graficaEdades').getContext('2d');
                const graficaEdades = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: " . json_encode($datosGrafica['labels']) . ",
                        datasets: [{
                            data: " . json_encode($datosGrafica['data']) . ",
                            backgroundColor: " . json_encode($datosGrafica['colors']) . ",
                            borderWidth: 2,
                            borderColor: '#fff'
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom',
                            },
                            title: {
                                display: true,
                                text: 'Distribución por Categorías de Edad'
                            }
                        }
                    }
                });
                </script>";
                
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
            <h3>Ingrese las edades de 5 personas:</h3>
            
            <?php for ($i = 1; $i <= 5; $i++): ?>
            <div class="form-group">
                <label for="edad<?= $i ?>">Edad de la persona <?= $i ?>:</label>
                <input type="number" 
                       id="edad<?= $i ?>" 
                       name="edad<?= $i ?>" 
                       min="0" 
                       max="120" 
                       required 
                       placeholder="Entre 0 y 120 años">
            </div>
            <?php endfor; ?>
            
            <input type="submit" value="Clasificar Edades">
        </form>
        
        <div class="info">
            <h4>ℹ️ Categorías de Edad:</h4>
            <ul>
                <li><strong>Niño:</strong> 0 - 12 años</li>
                <li><strong>Adolescente:</strong> 13 - 17 años</li>
                <li><strong>Adulto:</strong> 18 - 64 años</li>
                <li><strong>Adulto mayor:</strong> 65+ años</li>
            </ul>
        </div>
        
        <?php volverAlMenu(); ?>
    </div>
    
    <?php include '../footer.php'; ?>
</body>
</html>