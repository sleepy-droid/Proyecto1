<?php
/**
 * PROBLEMA 1: Calculadora de Datos Estadísticos
 * 
 * Calcular la media, desviación estándar, número mínimo y máximo 
 * de los 5 primeros números positivos introducidos mediante formulario.
 */

include '../includes/Validaciones.php';
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
                } elseif ($valor <= 0) {
                    $errores[] = "El número $i debe ser positivo";
                } else {
                    $numeros[] = (int)$valor;
                }
            }
            
            // Si no hay errores, calcular estadísticas
            if (empty($errores)) {
                $cantidad = count($numeros);
                $suma = array_sum($numeros);
                $media = $suma / $cantidad;
                $minimo = min($numeros);
                $maximo = max($numeros);
                
                // Calcular desviación estándar
                $sumaCuadrados = 0;
                foreach ($numeros as $numero) {
                    $sumaCuadrados += pow($numero - $media, 2);
                }
                $desviacionEstandar = sqrt($sumaCuadrados / ($cantidad - 1));
                
                // Mostrar resultados
                echo "<div class='resultado'>";
                echo "<h3>✅ Resultados Estadísticos</h3>";
                echo "<p><strong>Números ingresados:</strong> " . implode(', ', $numeros) . "</p>";
                echo "<p><strong>Media (Promedio):</strong> " . number_format($media, 2) . "</p>";
                echo "<p><strong>Desviación Estándar:</strong> " . number_format($desviacionEstandar, 2) . "</p>";
                echo "<p><strong>Valor Mínimo:</strong> $minimo</p>";
                echo "<p><strong>Valor Máximo:</strong> $maximo</p>";
                echo "</div>";
                
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
            <h3>Ingrese 5 números positivos:</h3>
            
            <?php for ($i = 1; $i <= 5; $i++): ?>
            <div class="form-group">
                <label for="numero<?= $i ?>">Número <?= $i ?>:</label>
                <input type="number" 
                       id="numero<?= $i ?>" 
                       name="numero<?= $i ?>" 
                       min="1" 
                       required 
                       placeholder="Ingrese un número positivo">
            </div>
            <?php endfor; ?>
            
            <input type="submit" value="Calcular Estadísticas">
        </form>
        
        <?php volverAlMenu(); ?>
    </div>
    
    <?php include '../footer.php'; ?>
</body>
</html>