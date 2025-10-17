<?php
/**
 * PROBLEMA 1: Calculadora de Datos Estadísticos
 * 
 * Calcular la media, desviación estándar, número mínimo y máximo 
 * de los 5 primeros números positivos introducidos mediante formulario.
 * 
 * Usa métodos reutilizables de la clase Utilidades.
 */

include '../includes/Validaciones.php';
include '../includes/Utilidades.php';
include '../includes/Navegacion.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Problema 1 - Estadísticas</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="container">
        <h1>📊 Problema 1: Calculadora de Datos Estadísticos</h1>
        
        <?php
        // Procesar formulario
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $numeros = [];
            $errores = [];
            
            // Validar y recolectar los 5 números
            for ($i = 1; $i <= 5; $i++) {
                $campo = "numero$i";
                $valor = $_POST[$campo] ?? '';
                
                if (empty($valor)) {
                    $errores[] = "El número $i es requerido";
                } elseif (!Validaciones::validarEntero($valor)) {
                    $errores[] = "El número $i debe ser un entero válido";
                } elseif (!Validaciones::validarRango($valor, 1, 1000)) {
                    $errores[] = "El número $i debe estar entre 1 y 1000";
                } else {
                    $numeros[] = (int)$valor;
                }
            }
            
            // Si no hay errores, calcular estadísticas usando Utilidades
            if (empty($errores)) {
                // Usar métodos reutilizables de Utilidades para calcular estadísticas
                $estadisticas = Utilidades::calcularEstadisticasCompletas($numeros);
                
                // Calcular coeficiente de variación usando método reutilizable
                $coefVariacion = Utilidades::calcularCoeficienteVariacion(
                    $estadisticas['desviacionEstandar'], 
                    $estadisticas['media']
                );
                
                // Obtener interpretación usando método reutilizable
                $interpretacionVariacion = Utilidades::interpretarCoeficienteVariacion($coefVariacion);
                
                // Mostrar resultados
                echo "<div class='resultado'>";
                echo "<h3>✅ Resultados Estadísticos</h3>";
                echo "<p><strong>Números ingresados:</strong> " . implode(', ', $numeros) . "</p>";
                
                echo "<h4>📊 Medidas Estadísticas:</h4>";
                echo "<table class='tabla-resultados'>";
                echo "<tr><th>Estadística</th><th>Valor</th><th>Fórmula</th></tr>";
                echo "<tr><td>Cantidad de Números</td><td>{$estadisticas['cantidad']}</td><td>n</td></tr>";
                echo "<tr><td>Suma Total</td><td>" . number_format($estadisticas['suma'], 2) . "</td><td>Σx</td></tr>";
                echo "<tr><td>Media (Promedio)</td><td>" . number_format($estadisticas['media'], 2) . "</td><td>Σx / n</td></tr>";
                echo "<tr><td>Desviación Estándar</td><td>" . number_format($estadisticas['desviacionEstandar'], 2) . "</td><td>√[Σ(x - μ)² / (n-1)]</td></tr>";
                echo "<tr><td>Valor Mínimo</td><td>{$estadisticas['minimo']}</td><td>min(x)</td></tr>";
                echo "<tr><td>Valor Máximo</td><td>{$estadisticas['maximo']}</td><td>max(x)</td></tr>";
                echo "<tr><td>Rango</td><td>" . number_format($estadisticas['rango'], 2) . "</td><td>max(x) - min(x)</td></tr>";
                echo "</table>";
                
                echo "<h4>🔍 Análisis de Variabilidad:</h4>";
                echo "<div style='background: #e8f4fd; padding: 15px; border-radius: 5px;'>";
                echo "<p><strong>Coeficiente de Variación:</strong> " . number_format($coefVariacion, 2) . "%</p>";
                echo "<p><strong>Interpretación:</strong> $interpretacionVariacion</p>";
                echo "</div>";
                
                /*
                // Información sobre métodos reutilizables
                echo "<div style='background: #f0f7f0; padding: 15px; border-radius: 5px; margin-top: 15px;'>";
                echo "<h4>🔄 Métodos Reutilizables Utilizados:</h4>";
                echo "<p>Este problema usa métodos estadísticos centralizados en <strong>Utilidades.php</strong>:</p>";
                echo "<ul>";
                echo "<li><code>Utilidades::calcularEstadisticasCompletas()</code></li>";
                echo "<li><code>Utilidades::calcularCoeficienteVariacion()</code></li>";
                echo "<li><code>Utilidades::interpretarCoeficienteVariacion()</code></li>";
                echo "</ul>";
                echo "</div>";
                
                echo "</div>";
                */

            } else {
                // Mostrar errores
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
        
        <!-- Formulario para ingresar números -->
        <form method="post">
            <h3>Ingrese 5 números positivos (1-1000):</h3>
            
            <?php for ($i = 1; $i <= 5; $i++): ?>
            <div class="form-group">
                <label for="numero<?= $i ?>">Número <?= $i ?>:</label>
                <input type="number" 
                       id="numero<?= $i ?>" 
                       name="numero<?= $i ?>" 
                       min="1" 
                       max="1000"
                       required 
                       placeholder="Ingrese un número entre 1-1000">
            </div>
            <?php endfor; ?>
            
            <input type="submit" value="Calcular Estadísticas" class="btn-menu">
        </form>
        
        <div class="info">
            <h4>ℹ️ Métodos Estadísticos Utilizados:</h4>
            <ul>
                <li><strong>Media:</strong> Promedio aritmético de los números</li>
                <li><strong>Desviación Estándar:</strong> Medida de dispersión de los datos</li>
                <li><strong>Coeficiente de Variación:</strong> Desviación estándar relativa a la media</li>
                <li><strong>Métodos reutilizables</strong> de la clase Utilidades (usados también en Problema 7)</li>
            </ul>
            
            <h4>🎯 Fórmulas Matemáticas:</h4>
            <div style="font-family: monospace; background: #f8f9fa; padding: 10px; border-radius: 5px;">
                <p><strong>Media:</strong> μ = Σx / n</p>
                <p><strong>Desviación Estándar Muestral:</strong> s = √[Σ(x - μ)² / (n - 1)]</p>
                <p><strong>Coeficiente de Variación:</strong> CV = (s / μ) × 100%</p>
            </div>
        </div>
        
        <?php volverAlMenu(); ?>
    </div>
    
    <?php include '../footer.php'; ?>
</body>
</html>