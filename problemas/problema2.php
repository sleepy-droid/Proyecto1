<?php
/**
 * PROBLEMA 2: Múltiplos de 4
 * 
 * Imprimir los N primeros múltiplos de 4, donde N es un valor introducido por teclado.
 */

include '../includes/Validaciones.php';
include '../includes/Navegacion.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Problema 2 - Múltiplos de 4</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="container">
        <h1>🔢 Problema 2: Múltiplos de 4</h1>
        
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $n = $_POST['cantidad'] ?? '';
            $errores = [];
            
            // Validaciones
            if (empty($n)) {
                $errores[] = "La cantidad es requerida";
            } elseif (!Validaciones::validarEntero($n)) {
                $errores[] = "La cantidad debe ser un número entero";
            } elseif (!Validaciones::validarRango($n, 1, 100)) {
                $errores[] = "La cantidad debe estar entre 1 y 100";
            }
            
            if (empty($errores)) {
                $n = (int)$n;
                echo "<div class='resultado'>";
                echo "<h3>✅ Los primeros $n múltiplos de 4 son:</h3>";
                
                for ($i = 1; $i <= $n; $i++) {
                    $multiplo = 4 * $i;
                    echo "<p>4 × $i = $multiplo</p>";
                    
                    // Detener si hay riesgo de desbordamiento (para números muy grandes)
                    if ($multiplo > PHP_INT_MAX / 2) {
                        echo "<p class='error'>⚠️ Advertencia: Posible desbordamiento en siguientes cálculos</p>";
                        break;
                    }
                }
                echo "</div>";
            } else {
                echo "<div class='error'>";
                echo "<h3>❌ Errores:</h3>";
                foreach ($errores as $error) {
                    echo "<p>$error</p>";
                }
                echo "</div>";
            }
        }
        ?>
        
        <form method="post">
            <div class="form-group">
                <label for="cantidad">¿Cuántos múltiplos de 4 desea generar?</label>
                <input type="number" 
                       id="cantidad" 
                       name="cantidad" 
                       min="1" 
                       max="100" 
                       required 
                       placeholder="Ej: 10">
            </div>
            <input type="submit" value="Generar Múltiplos">
        </form>
        
        <?php volverAlMenu(); ?>
    </div>
    
    <?php include '../footer.php'; ?>
</body>
</html>