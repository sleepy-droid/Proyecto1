<?php
/**
 * PROBLEMA 6: Presupuesto Hospital
 * 
 * En un hospital existen tres áreas: Ginecología, Pediatría y Traumatología.
 * El presupuesto anual se reparte:
 * - Ginecología: 40%
 * - Traumatología: 35% 
 * - Pediatría: 25%
 * 
 * Integrar gráficas de distribución.
 */

include '../includes/Validaciones.php';
include '../includes/Navegacion.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Problema 6 - Presupuesto Hospital</title>
    <link rel="stylesheet" href="../css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container">
        <h1>🏥 Problema 6: Distribución de Presupuesto Hospitalario</h1>
        
        <?php
        // Definir porcentajes de distribución
        $porcentajes = [
            'Ginecología' => 40,
            'Traumatología' => 35,
            'Pediatría' => 25
        ];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $presupuestoTotal = $_POST['presupuesto'] ?? '';
            $errores = [];
            
            // Validaciones
            if (empty($presupuestoTotal)) {
                $errores[] = "El presupuesto total es requerido";
            } elseif (!is_numeric($presupuestoTotal)) {
                $errores[] = "El presupuesto debe ser un número válido";
            } elseif ($presupuestoTotal <= 0) {
                $errores[] = "El presupuesto debe ser mayor a 0";
            } elseif ($presupuestoTotal > 1000000000) {
                $errores[] = "El presupuesto no puede exceder $1,000,000,000";
            }
            
            if (empty($errores)) {
                $presupuestoTotal = (float)$presupuestoTotal;
                
                echo "<div class='resultado'>";
                echo "<h3>✅ Distribución del Presupuesto Anual</h3>";
                echo "<p><strong>Presupuesto Total:</strong> $" . number_format($presupuestoTotal, 2) . "</p>";
                
                // Calcular distribución
                echo "<h4>📋 Distribución por Área:</h4>";
                echo "<table class='tabla-resultados'>";
                echo "<tr><th>Área</th><th>Porcentaje</th><th>Monto Asignado</th></tr>";
                
                $distribucion = [];
                foreach ($porcentajes as $area => $porcentaje) {
                    $monto = ($presupuestoTotal * $porcentaje) / 100;
                    $distribucion[$area] = $monto;
                    
                    echo "<tr>
                            <td>$area</td>
                            <td>$porcentaje%</td>
                            <td>$" . number_format($monto, 2) . "</td>
                          </tr>";
                }
                
                // Verificación de total
                $sumaDistribucion = array_sum($distribucion);
                echo "<tr style='background: #2c3e50; color: white; font-weight: bold;'>
                        <td>TOTAL</td>
                        <td>100%</td>
                        <td>$" . number_format($sumaDistribucion, 2) . "</td>
                      </tr>";
                echo "</table>";
                
                // Mostrar verificación
                if (abs($sumaDistribucion - $presupuestoTotal) < 0.01) {
                    echo "<p style='color: #27ae60;'>✓ La distribución suma exactamente el presupuesto total</p>";
                }
                
                echo "</div>";
                
                // Gráfica de pastel
                echo "<div style='margin: 30px 0;'>";
                echo "<h4>📈 Gráfica de Distribución del Presupuesto</h4>";
                echo "<canvas id='graficaPresupuesto' width='400' height='200'></canvas>";
                echo "</div>";
                
                // Script para la gráfica
                echo "<script>
                const ctx = document.getElementById('graficaPresupuesto').getContext('2d');
                const graficaPresupuesto = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: " . json_encode(array_keys($porcentajes)) . ",
                        datasets: [{
                            data: " . json_encode(array_values($distribucion)) . ",
                            backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56'],
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
                                text: 'Distribución del Presupuesto Hospitalario'
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const label = context.label || '';
                                        const value = context.raw || 0;
                                        const percentage = (value / $presupuestoTotal * 100).toFixed(1);
                                        return `$` + value.toLocaleString() + ` (` + percentage + `%)`;
                                    }
                                }
                            }
                        }
                    }
                });
                </script>";
                
                // Gráfica de barras alternativa
                echo "<div style='margin: 30px 0;'>";
                echo "<h4>📊 Distribución en Barras</h4>";
                echo "<canvas id='graficaBarras' width='400' height='200'></canvas>";
                echo "</div>";
                
                echo "<script>
                const ctxBarras = document.getElementById('graficaBarras').getContext('2d');
                const graficaBarras = new Chart(ctxBarras, {
                    type: 'bar',
                    data: {
                        labels: " . json_encode(array_keys($porcentajes)) . ",
                        datasets: [{
                            label: 'Monto Asignado ($)',
                            data: " . json_encode(array_values($distribucion)) . ",
                            backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56'],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            title: {
                                display: true,
                                text: 'Presupuesto por Área Hospitalaria'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return '$' + value.toLocaleString();
                                    }
                                }
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
            <div class="form-group">
                <label for="presupuesto">Presupuesto Anual Total del Hospital:</label>
                <input type="number" 
                       id="presupuesto" 
                       name="presupuesto" 
                       min="1" 
                       max="1000000000" 
                       step="0.01" 
                       required 
                       placeholder="Ej: 20000.00">
            </div>
            
            <h4>ℹ️ Porcentajes de Distribución:</h4>
            <ul>
                <li><strong>Ginecología:</strong> 40%</li>
                <li><strong>Traumatología:</strong> 35%</li>
                <li><strong>Pediatría:</strong> 25%</li>
            </ul>
            
            <input type="submit" value="Calcular Distribución">
        </form>
        
        <?php volverAlMenu(); ?>
    </div>
    
    <?php include '../footer.php'; ?>
</body>
</html>