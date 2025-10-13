<?php
/**
 * PROBLEMA 10: Sistema de Vendedores y Productos
 * 
 * Una empresa tiene 4 vendedores (1-4) que venden 5 productos diferentes (1-5).
 * Cada vendedor reporta ventas diarias de productos.
 * 
 * Resumen de ventas totales por vendedor y por producto usando arreglo bidimensional.
 * Mostrar resultados en formato tabular con totales por fila y columna.
 */

include '../includes/Validaciones.php';
include '../includes/Navegacion.php';

/**
 * Clase para manejar el sistema de ventas
 */
class SistemaVentas {
    private $ventas; // Arreglo bidimensional: [vendedor][producto]
    private $vendedores;
    private $productos;
    
    public function __construct() {
        // Inicializar arreglo bidimensional de ventas
        $this->ventas = array_fill(1, 4, array_fill(1, 5, 0));
        
        // Datos de ejemplo para vendedores
        $this->vendedores = [
            1 => ['nombre' => 'Ana', 'apellido' => 'Gómez'],
            2 => ['nombre' => 'Luis', 'apellido' => 'Martínez'],
            3 => ['nombre' => 'Marta', 'apellido' => 'Rodríguez'],
            4 => ['nombre' => 'Carlos', 'apellido' => 'López']
        ];
        
        // Nombres de productos
        $this->productos = [
            1 => 'Laptops',
            2 => 'Tablets', 
            3 => 'Smartphones',
            4 => 'Accesorios',
            5 => 'Software'
        ];
    }
    
    /**
     * Agregar venta al sistema
     */
    public function agregarVenta($vendedorId, $productoId, $monto) {
        if (isset($this->ventas[$vendedorId][$productoId])) {
            $this->ventas[$vendedorId][$productoId] += $monto;
            return true;
        }
        return false;
    }
    
    /**
     * Obtener vendedores
     */
    public function getVendedores() {
        return $this->vendedores;
    }
    
    /**
     * Obtener productos
     */
    public function getProductos() {
        return $this->productos;
    }
    
    /**
     * Obtener ventas
     */
    public function getVentas() {
        return $this->ventas;
    }
    
    /**
     * Generar reporte tabular
     */
    public function generarReporte() {
        $html = '';
        $vendedores = $this->getVendedores();
        $productos = $this->getProductos();
        $ventas = $this->getVentas();
        
        // Encabezado de la tabla
        $html .= "<table class='tabla-resultados'>";
        $html .= "<tr><th>Producto ↓ / Vendedor →</th>";
        
        // Encabezados de vendedores
        foreach ($vendedores as $id => $vendedor) {
            $html .= "<th>{$vendedor['nombre']} {$vendedor['apellido']}<br><small>(Vendedor $id)</small></th>";
        }
        $html .= "<th><strong>Total Producto</strong></th></tr>";
        
        // Filas de productos
        $totalesVendedores = array_fill(1, 4, 0);
        $granTotal = 0;
        
        foreach ($productos as $productoId => $productoNombre) {
            $html .= "<tr><td><strong>$productoNombre</strong><br><small>(Producto $productoId)</small></td>";
            
            $totalProducto = 0;
            
            // Ventas por vendedor para este producto
            foreach ($vendedores as $vendedorId => $vendedor) {
                $venta = $ventas[$vendedorId][$productoId];
                $totalProducto += $venta;
                $totalesVendedores[$vendedorId] += $venta;
                
                $html .= "<td>$" . number_format($venta, 2) . "</td>";
            }
            
            $granTotal += $totalProducto;
            $html .= "<td style='background: #e8f6f3; font-weight: bold;'>$" . number_format($totalProducto, 2) . "</td>";
            $html .= "</tr>";
        }
        
        // Fila de totales por vendedor
        $html .= "<tr style='background: #2c3e50; color: white;'>";
        $html .= "<td><strong>TOTAL VENDEDOR</strong></td>";
        
        foreach ($totalesVendedores as $totalVendedor) {
            $html .= "<td><strong>$" . number_format($totalVendedor, 2) . "</strong></td>";
        }
        
        $html .= "<td><strong>$" . number_format($granTotal, 2) . "</strong></td>";
        $html .= "</tr>";
        
        $html .= "</table>";
        
        return $html;
    }
    
    /**
     * Generar estadísticas adicionales
     */
    public function generarEstadisticas() {
        $vendedores = $this->getVendedores();
        $productos = $this->getProductos();
        $ventas = $this->getVentas();
        
        $estadisticas = [
            'mejor_vendedor' => ['id' => 0, 'total' => 0, 'nombre' => 'No disponible'],
            'producto_mas_vendido' => ['id' => 0, 'total' => 0, 'nombre' => 'No disponible'],
            'total_general' => 0
        ];
        
        $totalesVendedores = array_fill(1, 4, 0);
        $totalesProductos = array_fill(1, 5, 0);
        
        // Calcular totales
        foreach ($vendedores as $vendedorId => $vendedor) {
            foreach ($productos as $productoId => $producto) {
                $venta = $ventas[$vendedorId][$productoId];
                $totalesVendedores[$vendedorId] += $venta;
                $totalesProductos[$productoId] += $venta;
                $estadisticas['total_general'] += $venta;
            }
        }
        
        // Encontrar mejor vendedor
        foreach ($totalesVendedores as $vendedorId => $total) {
            if ($total > $estadisticas['mejor_vendedor']['total']) {
                $estadisticas['mejor_vendedor'] = [
                    'id' => $vendedorId, 
                    'total' => $total,
                    'nombre' => $vendedores[$vendedorId]['nombre'] . ' ' . $vendedores[$vendedorId]['apellido']
                ];
            }
        }
        
        // Encontrar producto más vendido
        foreach ($totalesProductos as $productoId => $total) {
            if ($total > $estadisticas['producto_mas_vendido']['total']) {
                $estadisticas['producto_mas_vendido'] = [
                    'id' => $productoId, 
                    'total' => $total,
                    'nombre' => $productos[$productoId]
                ];
            }
        }
        
        return $estadisticas;
    }
    
    /**
     * Obtener datos para gráficas
     */
    public function obtenerDatosGrafica() {
        $vendedores = $this->getVendedores();
        $productos = $this->getProductos();
        $ventas = $this->getVentas();
        
        $datos = [
            'vendedores' => [],
            'productos' => []
        ];
        
        $totalesVendedores = array_fill(1, 4, 0);
        $totalesProductos = array_fill(1, 5, 0);
        
        foreach ($vendedores as $vendedorId => $vendedor) {
            foreach ($productos as $productoId => $producto) {
                $venta = $ventas[$vendedorId][$productoId];
                $totalesVendedores[$vendedorId] += $venta;
                $totalesProductos[$productoId] += $venta;
            }
        }
        
        // Preparar datos de vendedores
        foreach ($vendedores as $vendedorId => $vendedor) {
            $datos['vendedores']['labels'][] = $vendedor['nombre'];
            $datos['vendedores']['data'][] = $totalesVendedores[$vendedorId];
        }
        
        // Preparar datos de productos
        foreach ($productos as $productoId => $producto) {
            $datos['productos']['labels'][] = $producto;
            $datos['productos']['data'][] = $totalesProductos[$productoId];
        }
        
        return $datos;
    }
}

// Crear instancia del sistema de ventas
$sistemaVentas = new SistemaVentas();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Problema 10 - Sistema de Vendedores</title>
    <link rel="stylesheet" href="../css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container">
        <h1>🏢 Problema 10: Sistema de Vendedores y Productos</h1>
        
        <?php
        // Procesar formulario de ventas
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar_venta'])) {
            $vendedorId = $_POST['vendedor'] ?? '';
            $productoId = $_POST['producto'] ?? '';
            $monto = $_POST['monto'] ?? '';
            
            $errores = [];
            
            // Validaciones
            if (empty($vendedorId)) {
                $errores[] = "El vendedor es requerido";
            }
            if (empty($productoId)) {
                $errores[] = "El producto es requerido";
            }
            if (empty($monto)) {
                $errores[] = "El monto es requerido";
            } elseif (!is_numeric($monto) || $monto <= 0) {
                $errores[] = "El monto debe ser un número positivo";
            }
            
            if (empty($errores)) {
                $vendedorId = (int)$vendedorId;
                $productoId = (int)$productoId;
                $monto = (float)$monto;
                
                if ($sistemaVentas->agregarVenta($vendedorId, $productoId, $monto)) {
                    echo "<div class='resultado'>";
                    echo "<h3>✅ Venta agregada exitosamente</h3>";
                    echo "<p><strong>Vendedor:</strong> Vendedor $vendedorId</p>";
                    echo "<p><strong>Producto:</strong> Producto $productoId</p>";
                    echo "<p><strong>Monto:</strong> $" . number_format($monto, 2) . "</p>";
                    echo "</div>";
                } else {
                    echo "<div class='error'>";
                    echo "<h3>❌ Error al agregar venta</h3>";
                    echo "<p>Los datos proporcionados no son válidos.</p>";
                    echo "</div>";
                }
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
        
        // Mostrar reporte principal
        echo "<h2>📊 Reporte de Ventas del Mes</h2>";
        echo $sistemaVentas->generarReporte();
        
        // Generar estadísticas
        $estadisticas = $sistemaVentas->generarEstadisticas();
        
        echo "<div class='resultado'>";
        echo "<h3>🏆 Estadísticas Destacadas</h3>";
        echo "<table class='tabla-resultados'>";
        echo "<tr><th>Métrica</th><th>Resultado</th><th>Valor</th></tr>";
        echo "<tr>
                <td>Mejor Vendedor</td>
                <td>{$estadisticas['mejor_vendedor']['nombre']}</td>
                <td>$" . number_format($estadisticas['mejor_vendedor']['total'], 2) . "</td>
              </tr>";
        echo "<tr>
                <td>Producto Más Vendido</td>
                <td>{$estadisticas['producto_mas_vendido']['nombre']}</td>
                <td>$" . number_format($estadisticas['producto_mas_vendido']['total'], 2) . "</td>
              </tr>";
        echo "<tr>
                <td>Ventas Totales</td>
                <td>General</td>
                <td>$" . number_format($estadisticas['total_general'], 2) . "</td>
              </tr>";
        echo "</table>";
        echo "</div>";
        
        // Obtener datos para gráficas
        $datosGrafica = $sistemaVentas->obtenerDatosGrafica();
        ?>
        
        <!-- Gráficas -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin: 30px 0;">
            <div>
                <h4>📈 Ventas por Vendedor</h4>
                <canvas id="graficaVendedores"></canvas>
            </div>
            <div>
                <h4>📊 Ventas por Producto</h4>
                <canvas id="graficaProductos"></canvas>
            </div>
        </div>
        
        <script>
        // Gráfica de vendedores
        const ctxVendedores = document.getElementById('graficaVendedores').getContext('2d');
        new Chart(ctxVendedores, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($datosGrafica['vendedores']['labels']); ?>,
                datasets: [{
                    label: 'Ventas ($)',
                    data: <?php echo json_encode($datosGrafica['vendedores']['data']); ?>,
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Desempeño por Vendedor'
                    }
                }
            }
        });
        
        // Gráfica de productos
        const ctxProductos = document.getElementById('graficaProductos').getContext('2d');
        new Chart(ctxProductos, {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode($datosGrafica['productos']['labels']); ?>,
                datasets: [{
                    data: <?php echo json_encode($datosGrafica['productos']['data']); ?>,
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Distribución por Producto'
                    }
                }
            }
        });
        </script>
        
        <!-- Formulario para agregar ventas -->
        <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0;">
            <h3>➕ Agregar Nueva Venta</h3>
            <form method="post">
                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px;">
                    <div class="form-group">
                        <label for="vendedor">Vendedor:</label>
                        <select id="vendedor" name="vendedor" required>
                            <option value="">Seleccione vendedor</option>
                            <?php 
                            $vendedores = $sistemaVentas->getVendedores();
                            foreach ($vendedores as $id => $vendedor): 
                            ?>
                            <option value="<?= $id ?>">
                                <?= $vendedor['nombre'] ?> <?= $vendedor['apellido'] ?> (Vendedor <?= $id ?>)
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="producto">Producto:</label>
                        <select id="producto" name="producto" required>
                            <option value="">Seleccione producto</option>
                            <?php 
                            $productos = $sistemaVentas->getProductos();
                            foreach ($productos as $id => $producto): 
                            ?>
                            <option value="<?= $id ?>"><?= $producto ?> (Producto <?= $id ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="monto">Monto de Venta ($):</label>
                        <input type="number" 
                               id="monto" 
                               name="monto" 
                               min="0.01" 
                               step="0.01" 
                               required 
                               placeholder="0.00">
                    </div>
                </div>
                
                <input type="submit" name="agregar_venta" value="Agregar Venta">
            </form>
        </div>
        
        <!-- Información del sistema -->
        <div class="info">
            <h4>ℹ️ Estructura del Sistema:</h4>
            <p><strong>Vendedores:</strong> 4 vendedores (1-4) con nombres y apellidos</p>
            <p><strong>Productos:</strong> 5 productos diferentes (1-5) con categorías específicas</p>
            <p><strong>Arreglo Bidimensional:</strong> ventas[vendedor][producto] almacena montos acumulados</p>
            
            <h4>🎯 Características Implementadas:</h4>
            <ul>
                <li>Arreglo bidimensional para almacenar ventas</li>
                <li>Reporte tabular con totales por fila y columna</li>
                <li>Gráficas de barras y doughnut para visualización</li>
                <li>Estadísticas automáticas (mejor vendedor, producto más vendido)</li>
                <li>Formulario interactivo para agregar ventas</li>
                <li>Validación de datos de entrada</li>
            </ul>
        </div>
        
        <?php volverAlMenu(); ?>
    </div>
    
    <?php include '../footer.php'; ?>
</body>
</html>