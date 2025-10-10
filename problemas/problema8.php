<?php
/**
 * PROBLEMA 8: Estación del Año
 * 
 * Al ingresar una fecha, devolver la estación del año según:
 * - Verano: Del 21 de diciembre al 20 de marzo
 * - Otoño: Del 21 de marzo al 21 de junio  
 * - Invierno: Del 22 de junio al 22 de septiembre
 * - Primavera: Del 23 de septiembre al 20 de diciembre
 * 
 * Usar switch y estructuras de fecha de PHP.
 */

include '../includes/Validaciones.php';
include '../includes/Navegacion.php';

/**
 * Función para determinar la estación del año
 */
function obtenerEstacion($mes, $dia) {
    // Convertir a números enteros
    $mes = (int)$mes;
    $dia = (int)$dia;
    
    // Determinar estación basado en mes y día
    switch ($mes) {
        case 1:  // Enero
        case 2:  // Febrero
            return 'Verano';
            
        case 3:  // Marzo
            if ($dia < 21) {
                return 'Verano';
            } else {
                return 'Otoño';
            }
            
        case 4:  // Abril
        case 5:  // Mayo
            return 'Otoño';
            
        case 6:  // Junio
            if ($dia < 22) {
                return 'Otoño';
            } else {
                return 'Invierno';
            }
            
        case 7:  // Julio
        case 8:  // Agosto
            return 'Invierno';
            
        case 9:  // Septiembre
            if ($dia < 23) {
                return 'Invierno';
            } else {
                return 'Primavera';
            }
            
        case 10: // Octubre
        case 11: // Noviembre
            return 'Primavera';
            
        case 12: // Diciembre
            if ($dia < 21) {
                return 'Primavera';
            } else {
                return 'Verano';
            }
            
        default:
            return 'Desconocida';
    }
}

/**
 * Función para obtener emoji de la estación
 */
function obtenerEmojiEstacion($estacion) {
    switch ($estacion) {
        case 'Verano': return '☀️';
        case 'Otoño': return '🍂'; 
        case 'Invierno': return '❄️';
        case 'Primavera': return '🌸';
        default: return '❓';
    }
}

/**
 * Función para obtener descripción de la estación
 */
function obtenerDescripcionEstacion($estacion) {
    $descripciones = [
        'Verano' => 'Estación más calurosa del año, ideal para playa y actividades al aire libre',
        'Otoño' => 'Estación de transición donde las hojas caen y el clima se vuelve más fresco',
        'Invierno' => 'Estación más fría del año, caracterizada por bajas temperaturas',
        'Primavera' => 'Estación del renacimiento, donde florecen las flores y el clima es templado'
    ];
    
    return $descripciones[$estacion] ?? 'Estación no definida';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Problema 8 - Estación del Año</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="container">
        <h1>🌸 Problema 8: Determinador de Estación del Año</h1>
        
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fechaInput = $_POST['fecha'] ?? '';
            $errores = [];
            
            // Validar fecha
            if (empty($fechaInput)) {
                $errores[] = "La fecha es requerida";
            } else {
                // Validar formato de fecha
                $fechaPartes = explode('-', $fechaInput);
                if (count($fechaPartes) !== 3) {
                    $errores[] = "Formato de fecha inválido. Use YYYY-MM-DD";
                } else {
                    list($anio, $mes, $dia) = $fechaPartes;
                    
                    if (!checkdate($mes, $dia, $anio)) {
                        $errores[] = "La fecha ingresada no es válida";
                    }
                }
            }
            
            if (empty($errores)) {
                // Obtener componentes de la fecha
                list($anio, $mes, $dia) = explode('-', $fechaInput);
                
                // Determinar estación
                $estacion = obtenerEstacion($mes, $dia);
                $emoji = obtenerEmojiEstacion($estacion);
                $descripcion = obtenerDescripcionEstacion($estacion);
                
                // Formatear fecha en español
                $nombresMeses = [
                    1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
                    5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto', 
                    9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
                ];
                
                $fechaFormateada = "$dia de " . $nombresMeses[(int)$mes] . " de $anio";
                
                echo "<div class='resultado'>";
                echo "<h3>✅ Resultado</h3>";
                echo "<div style='text-align: center; padding: 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 10px; margin: 20px 0;'>";
                echo "<h2 style='font-size: 2.5em; margin: 10px 0;'>$emoji</h2>";
                echo "<h3 style='font-size: 1.8em; margin: 10px 0;'>$estacion</h3>";
                echo "<p style='font-size: 1.2em;'><strong>Fecha ingresada:</strong> $fechaFormateada</p>";
                echo "</div>";
                
                echo "<h4>📖 Descripción:</h4>";
                echo "<p>$descripcion</p>";
                
                // Mostrar información adicional sobre la estación
                echo "<h4>📅 Período de la Estación:</h4>";
                $periodos = [
                    'Verano' => '21 de Diciembre - 20 de Marzo',
                    'Otoño' => '21 de Marzo - 21 de Junio', 
                    'Invierno' => '22 de Junio - 22 de Septiembre',
                    'Primavera' => '23 de Septiembre - 20 de Diciembre'
                ];
                
                echo "<p><strong>$estacion</strong>: " . $periodos[$estacion] . "</p>";
                
                // Mostrar próximas estaciones
                echo "<h4>🔄 Calendario de Estaciones:</h4>";
                echo "<table class='tabla-resultados'>";
                echo "<tr><th>Estación</th><th>Período</th><th>Duración Aprox.</th></tr>";
                
                $duraciones = [
                    'Verano' => '89 días',
                    'Otoño' => '93 días',
                    'Invierno' => '93 días', 
                    'Primavera' => '90 días'
                ];
                
                foreach ($periodos as $est => $periodo) {
                    $emojiEst = obtenerEmojiEstacion($est);
                    $claseFila = ($est === $estacion) ? "style='background: #d5f5e3;'" : "";
                    echo "<tr $claseFila>
                            <td>$emojiEst $est</td>
                            <td>$periodo</td>
                            <td>{$duraciones[$est]}</td>
                          </tr>";
                }
                echo "</table>";
                
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
                <label for="fecha">Seleccione una fecha:</label>
                <input type="date" 
                       id="fecha" 
                       name="fecha" 
                       required 
                       value="<?php echo date('Y-m-d'); ?>"
                       min="2000-01-01"
                       max="2030-12-31">
            </div>
            
            <input type="submit" value="Determinar Estación">
        </form>
        
        <div class="info">
            <h4>ℹ️ Estaciones del Año (Hemisferio Sur):</h4>
            <table class="tabla-resultados">
                <tr><th>Estación</th><th>Período</th><th>Características</th></tr>
                <tr><td>☀️ Verano</td><td>21 Dic - 20 Mar</td><td>Calor, días largos</td></tr>
                <tr><td>🍂 Otoño</td><td>21 Mar - 21 Jun</td><td>Hojas caen, clima fresco</td></tr>
                <tr><td>❄️ Invierno</td><td>22 Jun - 22 Sep</td><td>Frío, días cortos</td></tr>
                <tr><td>🌸 Primavera</td><td>23 Sep - 20 Dic</td><td>Flores, clima templado</td></tr>
            </table>
            
            <h4>🎯 Fechas de Prueba Sugeridas:</h4>
            <ul>
                <li><strong>15 de Enero:</strong> Verano ☀️</li>
                <li><strong>25 de Abril:</strong> Otoño 🍂</li>
                <li><strong>15 de Julio:</strong> Invierno ❄️</li>
                <li><strong>15 de Octubre:</strong> Primavera 🌸</li>
                <li><strong>25 de Septiembre:</strong> Cambio a Primavera</li>
            </ul>
        </div>
        
        <?php volverAlMenu(); ?>
    </div>
    
    <?php include '../footer.php'; ?>
</body>
</html>