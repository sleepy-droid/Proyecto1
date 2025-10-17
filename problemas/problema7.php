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
 * Usa métodos reutilizables de la clase Utilidades.
 */

include '../includes/Validaciones.php';
include '../includes/Utilidades.php';
include '../includes/Navegacion.php';

// Variable para controlar qué mostrar
$mostrarFormularioInicial = true;
$mostrarFormularioNotas = false;
$mostrarResultados = false;

// Datos para resultados
$resultados = [];
$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
        }
        
        if (empty($errores)) {
            $mostrarFormularioInicial = false;
            $mostrarFormularioNotas = true;
            $cantidadNotas = (int)$cantidadNotas;
        }
        
    } elseif (isset($_POST['notas'])) {
        // Paso 2: Se ingresaron las notas
        $notasInput = $_POST['notas'];
        $cantidadNotas = (int)$_POST['cantidad_notas_hidden'];
        $notas = [];
        
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
        
        // Verificar que la cantidad coincida
        if (count($notas) !== $cantidadNotas) {
            $errores[] = "Debe ingresar exactamente $cantidadNotas notas";
        }
        
        if (empty($errores)) {
            // CALCULAR ESTADÍSTICAS USANDO MÉTODOS REUTILIZABLES DE UTILIDADES
            $estadisticas = Utilidades::calcularEstadisticasCompletas($notas);
            
            // Calcular coeficiente de variación usando método reutilizable
            $coefVariacion = Utilidades::calcularCoeficienteVariacion(
                $estadisticas['desviacionEstandar'], 
                $estadisticas['media']
            );
            
            // Obtener interpretación usando método reutilizable
            $interpretacionVariacion = Utilidades::interpretarCoeficienteVariacion($coefVariacion);
            
            // Categorizar promedio usando método reutilizable
            $categoria = Utilidades::categorizarPromedio($estadisticas['media']);
            
            // Guardar resultados
            $resultados = [
                'notas' => $notas,
                'estadisticas' => $estadisticas,
                'coefVariacion' => $coefVariacion,
                'interpretacionVariacion' => $interpretacionVariacion,
                'categoria' => $categoria
            ];
            
            $mostrarFormularioInicial = false;
            $mostrarResultados = true;
        } else {
            $mostrarFormularioInicial = false;
            $mostrarFormularioNotas = true;
            $cantidadNotas = (int)$_POST['cantidad_notas_hidden'];
        }
    }
}
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
        }
        
        // Mostrar formulario inicial para cantidad de notas
        if ($mostrarFormularioInicial) {
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
                <input type="submit" value="Continuar" class="btn-menu">
            </form>
            
            <div class="info">
                <h4>ℹ️ Información:</h4>
                <ul>
                    <li>Las notas deben estar en escala de 0 a 100</li>
                    <li>Puede ingresar decimales (ej: 85.5)</li>
                    <li>Se calculará: promedio, desviación estándar, mínima y máxima</li>
                    <li>Se categorizará el rendimiento según el promedio</li>
                    <li><strong>Usa métodos reutilizables</strong> de la clase Utilidades (los mismos que en Problema 1)</li>
                </ul>
            </div>
            <?php
        }
        
        // Mostrar formulario para ingresar notas
        if ($mostrarFormularioNotas) {
            ?>
            <form method="post">
                <input type="hidden" name="cantidad_notas_hidden" value="<?= $cantidadNotas ?>">
                <h3>Ingrese las <?= $cantidadNotas ?> notas (0-100):</h3>
                
                <?php for ($i = 0; $i < $cantidadNotas; $i++): ?>
                <div class="form-group">
                    <label for="nota<?= $i ?>">Nota <?= $i + 1 ?>:</label>
                    <input type="number" 
                           id="nota<?= $i ?>" 
                           name="notas[]" 
                           min="0" 
                           max="100" 
                           step="0.01" 
                           required 
                           placeholder="0-100"
                           value="<?= isset($_POST['notas'][$i]) ? htmlspecialchars($_POST['notas'][$i]) : '' ?>">
                </div>
                <?php endfor; ?>
                
                <div style="display: flex; gap: 15px; margin-top: 20px;">
                    <input type="submit" value="📊 Calcular Estadísticas" class="btn-menu">
                    <a href="problema7.php" class="btn-menu" style="background: #95a5a6;">↩️ Volver al inicio</a>
                </div>
            </form>
            <?php
        }
        
        // Mostrar resultados
        if ($mostrarResultados && !empty($resultados)) {
            $estadisticas = $resultados['estadisticas'];
            $categoria = $resultados['categoria'];
            ?>
            <div class="resultado">
                <h3>✅ Resultados Estadísticos</h3>
                
                <!-- Mostrar notas ingresadas -->
                <h4>📋 Notas Ingresadas (<?= $estadisticas['cantidad'] ?> notas):</h4>
                <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 10px 0;">
                    <p><strong>Notas:</strong> <?= implode(' | ', array_map(function($nota) {
                        return number_format($nota, 2);
                    }, $resultados['notas'])) ?></p>
                </div>
                
                <!-- Mostrar estadísticas en tabla -->
                <h4>📊 Estadísticas Descriptivas:</h4>
                <table class="tabla-resultados">
                    <tr><th>Estadística</th><th>Valor</th><th>Explicación</th></tr>
                    <tr>
                        <td><strong>Cantidad de Notas</strong></td>
                        <td><?= $estadisticas['cantidad'] ?></td>
                        <td>Total de notas analizadas</td>
                    </tr>
                    <tr>
                        <td><strong>Suma Total</strong></td>
                        <td><?= number_format($estadisticas['suma'], 2) ?></td>
                        <td>Sumatoria de todas las notas</td>
                    </tr>
                    <tr>
                        <td><strong>Promedio</strong></td>
                        <td><?= number_format($estadisticas['media'], 2) ?></td>
                        <td>Suma total ÷ Cantidad de notas</td>
                    </tr>
                    <tr>
                        <td><strong>Desviación Estándar</strong></td>
                        <td><?= number_format($estadisticas['desviacionEstandar'], 2) ?></td>
                        <td>Medida de dispersión de los datos</td>
                    </tr>
                    <tr>
                        <td><strong>Nota Mínima</strong></td>
                        <td><?= number_format($estadisticas['minimo'], 2) ?></td>
                        <td>Valor más bajo del conjunto</td>
                    </tr>
                    <tr>
                        <td><strong>Nota Máxima</strong></td>
                        <td><?= number_format($estadisticas['maximo'], 2) ?></td>
                        <td>Valor más alto del conjunto</td>
                    </tr>
                    <tr style="background: <?= $categoria['color'] ?>; color: white;">
                        <td><strong>📈 Categoría</strong></td>
                        <td><strong><?= $categoria['categoria'] ?></strong></td>
                        <td>Evaluación del rendimiento general</td>
                    </tr>
                </table>
                
                <!-- Análisis adicional -->
                <h4>🔍 Análisis Adicional:</h4>
                <div style="background: #e8f4fd; padding: 15px; border-radius: 5px;">
                    <p><strong>Rango:</strong> <?= number_format($estadisticas['rango'], 2) ?> 
                    <small>(Diferencia entre nota máxima y mínima)</small></p>
                    
                    <p><strong>Coeficiente de Variación:</strong> <?= number_format($resultados['coefVariacion'], 2) ?>%
                    <small>(Desviación estándar ÷ Promedio × 100)</small></p>
                    
                   <!-- <p><strong>Interpretación:</strong> <?= $resultados['interpretacionVariacion'] ?></p> -->
                </div>
                
                <!-- Información sobre métodos reutilizables 

                <div style="background: #f0f7f0; padding: 15px; border-radius: 5px; margin-top: 15px;">
                    <h4>🔄 Métodos Reutilizables Utilizados:</h4>
                    <p>Este problema usa los <strong>mismos métodos estadísticos</strong> que el <strong>Problema 1</strong>:</p>
                    <ul>
                        <li><code>Utilidades::calcularEstadisticasCompletas()</code> - Calcula todas las estadísticas</li>
                        <li><code>Utilidades::calcularCoeficienteVariacion()</code> - Calcula variabilidad relativa</li>
                        <li><code>Utilidades::interpretarCoeficienteVariacion()</code> - Interpreta la variabilidad</li>
                        <li><code>Utilidades::categorizarPromedio()</code> - Categoriza el rendimiento (específico para notas)</li>
                    </ul>
                    <p><strong>Beneficio:</strong> Código consistente, mantenible y libre de errores</p>
                </div>
                -->

                <!-- Fórmulas matemáticas -->
                <h4>🧮 Fórmulas Utilizadas:</h4>
                <div style="background: #fff8e1; padding: 15px; border-radius: 5px; font-family: monospace;">
                    <p><strong>Promedio:</strong> μ = Σx / n</p>
                    <p><strong>Desviación Estándar Muestral:</strong> s = √[Σ(x - μ)² / (n - 1)]</p>
                    <p><strong>Coeficiente de Variación:</strong> CV = (s / μ) × 100%</p>
                </div>
                
                <!-- Botones de acción -->
                <div style="display: flex; gap: 15px; margin-top: 20px;">
                    <a href="problema7.php" class="btn-menu" style="background: #3498db;">🔄 Calcular Nuevas Notas</a>
                    <button onclick="window.print()" class="btn-menu" style="background: #9b59b6;">🖨️ Imprimir Resultados</button>
                </div>
            </div>
            <?php
        }
        ?>
        
        <?php volverAlMenu(); ?>
    </div>
    
    <?php include '../footer.php'; ?>
</body>
</html>