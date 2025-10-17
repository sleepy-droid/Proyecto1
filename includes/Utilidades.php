<?php
/**
 * Clase de utilidades con métodos estáticos para el proyecto
 * Cumple con PSR-1: métodos en camelCase, constantes en MAYÚSCULAS
 */

class Utilidades {
    
    // Constantes de configuración
    const VERSION_APP = '1.0.0';
    const MAX_INTENTOS = 5;
    const FORMATO_FECHA = 'd/m/Y';
    const MONEDA = 'USD';
    
    /**
     * VALIDACIONES BÁSICAS
     */
    
    /**
     * Validar y obtener valor con valor por defecto (similar a NVL en Oracle)
     * @param mixed $var Variable a verificar
     * @param mixed $default Valor por defecto (opcional)
     * @return mixed
     */
    public static function nvl(&$var, $default = "") {
        return isset($var) ? $var : $default;
    }
    
    /**
     * Validar token CSRF básico
     * @return bool
     */
    public static function validateCSRF() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $tokenSesion = $_SESSION['csrf_token'] ?? '';
        $tokenForm = $_POST['csrf_token'] ?? '';
        
        if (empty($tokenSesion) || empty($tokenForm) || $tokenSesion !== $tokenForm) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Generar token CSRF
     * @return string
     */
    public static function generarCSRFToken() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $token = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $token;
        return $token;
    }
    
    /**
     * FUNCIONES ESTADÍSTICAS REUTILIZABLES
     * Para usar en Problema 1 y Problema 7
     */
    
    /**
     * Calcular la media (promedio) de un array de números
     * @param array $numeros Array de números
     * @return float Media calculada
     */
    public static function calcularMedia($numeros) {
        if (empty($numeros) || !is_array($numeros)) {
            return 0;
        }
        return array_sum($numeros) / count($numeros);
    }
    
    /**
     * Calcular la desviación estándar muestral de un array de números
     * @param array $numeros Array de números
     * @return float Desviación estándar
     */
    public static function calcularDesviacionEstandar($numeros) {
        $cantidad = count($numeros);
        if ($cantidad < 2) {
            return 0; // No se puede calcular desviación estándar con menos de 2 elementos
        }
        
        $media = self::calcularMedia($numeros);
        $sumaCuadrados = 0;
        
        foreach ($numeros as $numero) {
            $sumaCuadrados += pow($numero - $media, 2);
        }
        
        return sqrt($sumaCuadrados / ($cantidad - 1));
    }
    
    /**
     * Calcular el valor mínimo de un array de números
     * @param array $numeros Array de números
     * @return float Valor mínimo
     */
    public static function calcularMinimo($numeros) {
        if (empty($numeros) || !is_array($numeros)) {
            return 0;
        }
        return min($numeros);
    }
    
    /**
     * Calcular el valor máximo de un array de números
     * @param array $numeros Array de números
     * @return float Valor máximo
     */
    public static function calcularMaximo($numeros) {
        if (empty($numeros) || !is_array($numeros)) {
            return 0;
        }
        return max($numeros);
    }
    
    /**
     * Calcular todas las estadísticas básicas de un array de números
     * @param array $numeros Array de números
     * @return array Array con todas las estadísticas
     */
    public static function calcularEstadisticasCompletas($numeros) {
        if (empty($numeros) || !is_array($numeros)) {
            return [
                'cantidad' => 0,
                'suma' => 0,
                'media' => 0,
                'desviacionEstandar' => 0,
                'minimo' => 0,
                'maximo' => 0,
                'rango' => 0
            ];
        }
        
        return [
            'cantidad' => count($numeros),
            'suma' => array_sum($numeros),
            'media' => self::calcularMedia($numeros),
            'desviacionEstandar' => self::calcularDesviacionEstandar($numeros),
            'minimo' => self::calcularMinimo($numeros),
            'maximo' => self::calcularMaximo($numeros),
            'rango' => self::calcularMaximo($numeros) - self::calcularMinimo($numeros)
        ];
    }
    
    /**
     * Calcular el coeficiente de variación
     * @param float $desviacionEstandar Desviación estándar
     * @param float $media Media aritmética
     * @return float Coeficiente de variación en porcentaje
     */
    public static function calcularCoeficienteVariacion($desviacionEstandar, $media) {
        if ($media == 0) {
            return 0;
        }
        return ($desviacionEstandar / abs($media)) * 100;
    }
    
    /**
     * Interpretar el coeficiente de variación
     * @param float $coefVariacion Coeficiente de variación en porcentaje
     * @return string Interpretación del coeficiente
     */
    public static function interpretarCoeficienteVariacion($coefVariacion) {
        if ($coefVariacion < 15) {
            return "✅ Baja variabilidad - Los datos son consistentes";
        } elseif ($coefVariacion < 30) {
            return "⚠️ Variabilidad moderada - Dispersión aceptable";
        } else {
            return "❌ Alta variabilidad - Los datos están muy dispersos";
        }
    }
    
    /**
     * Categorizar el promedio según escala de notas
     * @param float $promedio Promedio a categorizar
     * @return array Array con categoría y color
     */
    public static function categorizarPromedio($promedio) {
        if ($promedio >= 90) {
            return [
                'categoria' => "Excelente (A)",
                'color' => "#27ae60"
            ];
        } elseif ($promedio >= 80) {
            return [
                'categoria' => "Bueno (B)", 
                'color' => "#3498db"
            ];
        } elseif ($promedio >= 70) {
            return [
                'categoria' => "Regular (C)",
                'color' => "#f39c12"
            ];
        } elseif ($promedio >= 60) {
            return [
                'categoria' => "Aprobatorio (D)",
                'color' => "#e67e22"
            ];
        } else {
            return [
                'categoria' => "Reprobatorio (F)",
                'color' => "#e74c3c"
            ];
        }
    }
    
    /**
     * OTRAS UTILIDADES
     */
    
    /**
     * Sanitizar cadena para prevenir XSS y SQL injection
     * @param string|array $dato Dato a sanitizar
     * @return string|array Dato sanitizado
     */
    public static function sanitizar($dato) {
        if (is_array($dato)) {
            return array_map([self::class, 'sanitizar'], $dato);
        }
        
        $dato = trim($dato);
        $dato = stripslashes($dato);
        $dato = htmlspecialchars($dato, ENT_QUOTES, 'UTF-8');
        
        return $dato;
    }
    
    /**
     * Validar email con filter_var
     * @param string $email Email a validar
     * @return bool
     */
    public static function validarEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    /**
     * Validar URL
     * @param string $url URL a validar
     * @return bool
     */
    public static function validarURL($url) {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }
    
    /**
     * Validar IP
     * @param string $ip IP a validar
     * @return bool
     */
    public static function validarIP($ip) {
        return filter_var($ip, FILTER_VALIDATE_IP) !== false;
    }
    
    /**
     * Formatear número como moneda
     * @param float $monto Monto a formatear
     * @param string|null $moneda Tipo de moneda
     * @return string
     */
    public static function formatearMoneda($monto, $moneda = null) {
        $moneda = $moneda ?: self::MONEDA;
        $formato = number_format($monto, 2, '.', ',');
        return "$formato $moneda";
    }
    
    /**
     * Formatear fecha en español
     * @param string|null $fecha Fecha a formatear
     * @return string
     */
    public static function formatearFecha($fecha = null) {
        $timestamp = $fecha ? strtotime($fecha) : time();
        
        if ($timestamp === false) {
            return "Fecha inválida";
        }
        
        $dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
        $meses = [
            'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
            'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
        ];
        
        $diaSemana = $dias[date('w', $timestamp)];
        $dia = date('d', $timestamp);
        $mes = $meses[date('n', $timestamp) - 1];
        $anio = date('Y', $timestamp);
        
        return "$diaSemana, $dia de $mes de $anio";
    }
    
    /**
     * Calcular edad desde fecha de nacimiento
     * @param string $fechaNacimiento Fecha de nacimiento
     * @return int Edad calculada
     */
    public static function calcularEdad($fechaNacimiento) {
        $nacimiento = new DateTime($fechaNacimiento);
        $hoy = new DateTime();
        $edad = $hoy->diff($nacimiento);
        return $edad->y;
    }
    
    /**
     * Generar contraseña segura
     * @param int $longitud Longitud de la contraseña
     * @return string Contraseña generada
     */
    public static function generarPassword($longitud = 12) {
        $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()';
        $password = '';
        $max = strlen($caracteres) - 1;
        
        for ($i = 0; $i < $longitud; $i++) {
            $password .= $caracteres[random_int(0, $max)];
        }
        
        return $password;
    }
    
    /**
     * Validar fortaleza de contraseña
     * @param string $password Contraseña a validar
     * @return array Resultado de la validación
     */
    public static function validarFortalezaPassword($password) {
        $errores = [];
        
        if (strlen($password) < 8) {
            $errores[] = "La contraseña debe tener al menos 8 caracteres";
        }
        
        if (!preg_match('/[A-Z]/', $password)) {
            $errores[] = "La contraseña debe contener al menos una mayúscula";
        }
        
        if (!preg_match('/[a-z]/', $password)) {
            $errores[] = "La contraseña debe contener al menos una minúscula";
        }
        
        if (!preg_match('/[0-9]/', $password)) {
            $errores[] = "La contraseña debe contener al menos un número";
        }
        
        if (!preg_match('/[!@#$%^&*()]/', $password)) {
            $errores[] = "La contraseña debe contener al menos un carácter especial";
        }
        
        return [
            'es_valida' => empty($errores),
            'errores' => $errores,
            'fortaleza' => empty($errores) ? 'fuerte' : 'débil'
        ];
    }
    
    /**
     * Generar código aleatorio
     * @param int $longitud Longitud del código
     * @param bool $incluirLetras Incluir letras en el código
     * @return string Código generado
     */
    public static function generarCodigo($longitud = 6, $incluirLetras = true) {
        $caracteres = '0123456789';
        if ($incluirLetras) {
            $caracteres .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }
        
        $codigo = '';
        $max = strlen($caracteres) - 1;
        
        for ($i = 0; $i < $longitud; $i++) {
            $codigo .= $caracteres[random_int(0, $max)];
        }
        
        return $codigo;
    }
    
    /**
     * Convertir array a JSON seguro
     * @param array $datos Datos a convertir
     * @return string JSON seguro
     */
    public static function arrayAJson($datos) {
        return json_encode($datos, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
    }
    
    /**
     * Validar y limpiar número de teléfono
     * @param string $telefono Teléfono a limpiar
     * @return string Teléfono limpio
     */
    public static function limpiarTelefono($telefono) {
        // Remover todo excepto números y +
        return preg_replace('/[^\d+]/', '', $telefono);
    }
    
    /**
     * Validar si un string es JSON válido
     * @param string $string String a validar
     * @return bool
     */
    public static function esJsonValido($string) {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }
    
    /**
     * Obtener la extensión de un archivo
     * @param string $nombreArchivo Nombre del archivo
     * @return string Extensión del archivo
     */
    public static function obtenerExtension($nombreArchivo) {
        return strtolower(pathinfo($nombreArchivo, PATHINFO_EXTENSION));
    }
    
    /**
     * Validar tipo de archivo por extensión
     * @param string $nombreArchivo Nombre del archivo
     * @param array $extensionesPermitidas Extensiones permitidas
     * @return bool
     */
    public static function validarTipoArchivo($nombreArchivo, $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'gif', 'pdf']) {
        $extension = self::obtenerExtension($nombreArchivo);
        return in_array($extension, $extensionesPermitidas);
    }
    
    /**
     * Redondear a múltiplo más cercano
     * @param float $numero Número a redondear
     * @param int $multiplo Múltiplo para redondear
     * @return float Número redondeado
     */
    public static function redondearAMultiplo($numero, $multiplo = 5) {
        return round($numero / $multiplo) * $multiplo;
    }
    
    /**
     * Calcular porcentaje
     * @param float $porcion Porción del total
     * @param float $total Total
     * @return float Porcentaje calculado
     */
    public static function calcularPorcentaje($porcion, $total) {
        if ($total == 0) return 0;
        return ($porcion / $total) * 100;
    }
    
    /**
     * Obtener el valor de porcentaje de un número
     * @param float $porcentaje Porcentaje a aplicar
     * @param float $total Total
     * @return float Valor del porcentaje
     */
    public static function obtenerPorcentajeDe($porcentaje, $total) {
        return ($porcentaje / 100) * $total;
    }
    
    /**
     * Generar hash seguro para archivos o datos
     * @param string $dato Dato a hashear
     * @return string Hash generado
     */
    public static function generarHash($dato) {
        return hash('sha256', $dato . self::VERSION_APP);
    }
    
    /**
     * Validar si un número es par
     * @param int $numero Número a validar
     * @return bool
     */
    public static function esPar($numero) {
        return $numero % 2 === 0;
    }
    
    /**
     * Validar si un número es primo
     * @param int $numero Número a validar
     * @return bool
     */
    public static function esPrimo($numero) {
        if ($numero < 2) return false;
        if ($numero == 2) return true;
        if ($numero % 2 == 0) return false;
        
        for ($i = 3; $i <= sqrt($numero); $i += 2) {
            if ($numero % $i == 0) return false;
        }
        
        return true;
    }
    
    /**
     * Obtener números primos en un rango
     * @param int $inicio Inicio del rango
     * @param int $fin Fin del rango
     * @return array Array de números primos
     */
    public static function obtenerPrimosEnRango($inicio, $fin) {
        $primos = [];
        for ($i = max(2, $inicio); $i <= $fin; $i++) {
            if (self::esPrimo($i)) {
                $primos[] = $i;
            }
        }
        return $primos;
    }
    
    /**
     * Calcular factorial de un número
     * @param int $numero Número para factorial
     * @return int Factorial calculado
     */
    public static function factorial($numero) {
        if ($numero < 0) return 0;
        if ($numero == 0 || $numero == 1) return 1;
        
        $resultado = 1;
        for ($i = 2; $i <= $numero; $i++) {
            $resultado *= $i;
        }
        return $resultado;
    }
    
    /**
     * Convertir decimal a binario
     * @param int $decimal Número decimal
     * @return string Representación binaria
     */
    public static function decimalABinario($decimal) {
        return decbin($decimal);
    }
    
    /**
     * Convertir binario a decimal
     * @param string $binario Número binario
     * @return int Número decimal
     */
    public static function binarioADecimal($binario) {
        return bindec($binario);
    }
    
    /**
     * Obtener la fecha y hora actual formateada
     * @return string Fecha y hora formateada
     */
    public static function fechaHoraActual() {
        return date('Y-m-d H:i:s');
    }
    
    /**
     * Calcular días entre dos fechas
     * @param string $fechaInicio Fecha de inicio
     * @param string $fechaFin Fecha de fin
     * @return int Días de diferencia
     */
    public static function diasEntreFechas($fechaInicio, $fechaFin) {
        try {
            $inicio = new DateTime($fechaInicio);
            $fin = new DateTime($fechaFin);
            $diferencia = $inicio->diff($fin);
            return $diferencia->days;
        } catch (Exception $e) {
            return 0;
        }
    }
    
    /**
     * Obtener el día de la semana en español
     * @param string|null $fecha Fecha a evaluar
     * @return string Día de la semana
     */
    public static function obtenerDiaSemana($fecha = null) {
        $timestamp = $fecha ? strtotime($fecha) : time();
        if ($timestamp === false) {
            return "Fecha inválida";
        }
        
        $dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
        return $dias[date('w', $timestamp)];
    }
    
    /**
     * Limpiar y formatear texto para URL (slug)
     * @param string $texto Texto a convertir
     * @return string Slug generado
     */
    public static function generarSlug($texto) {
        $slug = strtolower($texto);
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        $slug = trim($slug, '-');
        return $slug;
    }
    
    /**
     * Validar código postal (formato básico)
     * @param string $codigoPostal Código postal a validar
     * @return bool
     */
    public static function validarCodigoPostal($codigoPostal) {
        return preg_match('/^\d{4,10}$/', $codigoPostal);
    }
    
    /**
     * Obtener información del sistema
     * @return array Información del sistema
     */
    public static function obtenerInfoSistema() {
        return [
            'version_php' => PHP_VERSION,
            'version_app' => self::VERSION_APP,
            'servidor' => $_SERVER['SERVER_SOFTWARE'] ?? 'Desconocido',
            'sistema_operativo' => PHP_OS,
            'zona_horaria' => date_default_timezone_get(),
            'memoria_limite' => ini_get('memory_limit'),
            'tiempo_limite' => ini_get('max_execution_time'),
            'extensiones_cargadas' => get_loaded_extensions()
        ];
    }
    
    /**
     * FUNCIONES ESPECÍFICAS PARA EL PROYECTO
     */
    
    /**
     * Generar HTML para mostrar estadísticas en tabla
     * @param array $estadisticas Estadísticas a mostrar
     * @param string $titulo Título de la tabla
     * @return string HTML generado
     */
    public static function generarTablaEstadisticas($estadisticas, $titulo = 'Estadísticas') {
        $html = "<div class='resultado'>";
        $html .= "<h3>📊 $titulo</h3>";
        $html .= "<table class='tabla-resultados'>";
        $html .= "<tr><th>Estadística</th><th>Valor</th></tr>";
        
        foreach ($estadisticas as $nombre => $valor) {
            $html .= "<tr>";
            $html .= "<td><strong>" . ucfirst($nombre) . "</strong></td>";
            if (is_float($valor)) {
                $html .= "<td>" . number_format($valor, 2) . "</td>";
            } else {
                $html .= "<td>$valor</td>";
            }
            $html .= "</tr>";
        }
        
        $html .= "</table>";
        $html .= "</div>";
        
        return $html;
    }
    
    /**
     * Validar datos de formulario básicos
     * @param array $datos Datos del formulario
     * @param array $reglas Reglas de validación
     * @return array Errores de validación
     */
    public static function validarFormulario($datos, $reglas) {
        $errores = [];
        
        foreach ($reglas as $campo => $regla) {
            $valor = $datos[$campo] ?? '';
            $partes = explode('|', $regla);
            
            foreach ($partes as $parte) {
                if ($parte === 'required' && empty($valor)) {
                    $errores[$campo][] = "El campo $campo es requerido";
                }
                
                if ($parte === 'email' && !self::validarEmail($valor)) {
                    $errores[$campo][] = "El campo $campo debe ser un email válido";
                }
                
                if (strpos($parte, 'min:') === 0) {
                    $min = (int)str_replace('min:', '', $parte);
                    if (strlen($valor) < $min) {
                        $errores[$campo][] = "El campo $campo debe tener al menos $min caracteres";
                    }
                }
                
                if (strpos($parte, 'max:') === 0) {
                    $max = (int)str_replace('max:', '', $parte);
                    if (strlen($valor) > $max) {
                        $errores[$campo][] = "El campo $campo no puede tener más de $max caracteres";
                    }
                }
            }
        }
        
        return $errores;
    }
}
?>