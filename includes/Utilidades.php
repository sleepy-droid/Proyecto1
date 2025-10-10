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
        session_start();
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
     * Sanitizar cadena para prevenir XSS y SQL injection
     * @param string $dato
     * @return string
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
     * @param string $email
     * @return bool
     */
    public static function validarEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    /**
     * Validar URL
     * @param string $url
     * @return bool
     */
    public static function validarURL($url) {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }
    
    /**
     * Validar IP
     * @param string $ip
     * @return bool
     */
    public static function validarIP($ip) {
        return filter_var($ip, FILTER_VALIDATE_IP) !== false;
    }
    
    /**
     * Formatear número como moneda
     * @param float $monto
     * @param string $moneda
     * @return string
     */
    public static function formatearMoneda($monto, $moneda = null) {
        $moneda = $moneda ?: self::MONEDA;
        $formato = number_format($monto, 2, '.', ',');
        return "$formato $moneda";
    }
    
    /**
     * Formatear fecha en español
     * @param string $fecha
     * @return string
     */
    public static function formatearFecha($fecha = null) {
        $timestamp = $fecha ? strtotime($fecha) : time();
        
        $dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
        $meses = [
            'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
            'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
        ];
        
        return $dias[date('w', $timestamp)] . ', ' . 
               date('d', $timestamp) . ' de ' . 
               $meses[date('n', $timestamp) - 1] . ' de ' . 
               date('Y', $timestamp);
    }
    
    /**
     * Calcular edad desde fecha de nacimiento
     * @param string $fechaNacimiento
     * @return int
     */
    public static function calcularEdad($fechaNacimiento) {
        $nacimiento = new DateTime($fechaNacimiento);
        $hoy = new DateTime();
        $edad = $hoy->diff($nacimiento);
        return $edad->y;
    }
    
    /**
     * Generar contraseña segura
     * @param int $longitud
     * @return string
     */
    public static function generarPassword($longitud = 12) {
        $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()';
        $password = '';
        
        for ($i = 0; $i < $longitud; $i++) {
            $password .= $caracteres[random_int(0, strlen($caracteres) - 1)];
        }
        
        return $password;
    }
    
    /**
     * Validar fortaleza de contraseña
     * @param string $password
     * @return array
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
     * @param int $longitud
     * @param bool $incluirLetras
     * @return string
     */
    public static function generarCodigo($longitud = 6, $incluirLetras = true) {
        $caracteres = '0123456789';
        if ($incluirLetras) {
            $caracteres .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }
        
        $codigo = '';
        for ($i = 0; $i < $longitud; $i++) {
            $codigo .= $caracteres[random_int(0, strlen($caracteres) - 1)];
        }
        
        return $codigo;
    }
    
    /**
     * Convertir array a JSON seguro
     * @param array $datos
     * @return string
     */
    public static function arrayAJson($datos) {
        return json_encode($datos, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
    }
    
    /**
     * Validar y limpiar número de teléfono
     * @param string $telefono
     * @return string
     */
    public static function limpiarTelefono($telefono) {
        // Remover todo excepto números y +
        $telefono = preg_replace('/[^\d+]/', '', $telefono);
        return $telefono;
    }
    
    /**
     * Validar si un string es JSON válido
     * @param string $string
     * @return bool
     */
    public static function esJsonValido($string) {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }
    
    /**
     * Obtener la extensión de un archivo
     * @param string $nombreArchivo
     * @return string
     */
    public static function obtenerExtension($nombreArchivo) {
        return strtolower(pathinfo($nombreArchivo, PATHINFO_EXTENSION));
    }
    
    /**
     * Validar tipo de archivo por extensión
     * @param string $nombreArchivo
     * @param array $extensionesPermitidas
     * @return bool
     */
    public static function validarTipoArchivo($nombreArchivo, $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'gif', 'pdf']) {
        $extension = self::obtenerExtension($nombreArchivo);
        return in_array($extension, $extensionesPermitidas);
    }
    
    /**
     * Redondear a múltiplo más cercano
     * @param float $numero
     * @param int $multiplo
     * @return float
     */
    public static function redondearAMultiplo($numero, $multiplo = 5) {
        return round($numero / $multiplo) * $multiplo;
    }
    
    /**
     * Calcular porcentaje
     * @param float $porcion
     * @param float $total
     * @return float
     */
    public static function calcularPorcentaje($porcion, $total) {
        if ($total == 0) return 0;
        return ($porcion / $total) * 100;
    }
    
    /**
     * Obtener el valor de porcentaje de un número
     * @param float $porcentaje
     * @param float $total
     * @return float
     */
    public static function obtenerPorcentajeDe($porcentaje, $total) {
        return ($porcentaje / 100) * $total;
    }
    
    /**
     * Generar hash seguro para archivos o datos
     * @param string $dato
     * @return string
     */
    public static function generarHash($dato) {
        return hash('sha256', $dato . self::VERSION_APP);
    }
    
    /**
     * Validar si un número es par
     * @param int $numero
     * @return bool
     */
    public static function esPar($numero) {
        return $numero % 2 === 0;
    }
    
    /**
     * Validar si un número es primo
     * @param int $numero
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
     * @param int $inicio
     * @param int $fin
     * @return array
     */
    public static function obtenerPrimosEnRango($inicio, $fin) {
        $primos = [];
        for ($i = $inicio; $i <= $fin; $i++) {
            if (self::esPrimo($i)) {
                $primos[] = $i;
            }
        }
        return $primos;
    }
    
    /**
     * Calcular factorial de un número
     * @param int $numero
     * @return int
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
     * @param int $decimal
     * @return string
     */
    public static function decimalABinario($decimal) {
        return decbin($decimal);
    }
    
    /**
     * Convertir binario a decimal
     * @param string $binario
     * @return int
     */
    public static function binarioADecimal($binario) {
        return bindec($binario);
    }
    
    /**
     * Obtener la fecha y hora actual formateada
     * @return string
     */
    public static function fechaHoraActual() {
        return date('Y-m-d H:i:s');
    }
    
    /**
     * Calcular días entre dos fechas
     * @param string $fechaInicio
     * @param string $fechaFin
     * @return int
     */
    public static function diasEntreFechas($fechaInicio, $fechaFin) {
        $inicio = new DateTime($fechaInicio);
        $fin = new DateTime($fechaFin);
        $diferencia = $inicio->diff($fin);
        return $diferencia->days;
    }
    
    /**
     * Obtener el día de la semana en español
     * @param string $fecha
     * @return string
     */
    public static function obtenerDiaSemana($fecha = null) {
        $timestamp = $fecha ? strtotime($fecha) : time();
        $dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
        return $dias[date('w', $timestamp)];
    }
    
    /**
     * Limpiar y formatear texto para URL (slug)
     * @param string $texto
     * @return string
     */
    public static function generarSlug($texto) {
        $slug = strtolower($texto);
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        $slug = trim($slug, '-');
        return $slug;
    }
    
    /**
     * Validar código postal (formato básico)
     * @param string $codigoPostal
     * @return bool
     */
    public static function validarCodigoPostal($codigoPostal) {
        return preg_match('/^\d{4,10}$/', $codigoPostal);
    }
    
    /**
     * Obtener información del sistema
     * @return array
     */
    public static function obtenerInfoSistema() {
        return [
            'version_php' => PHP_VERSION,
            'version_app' => self::VERSION_APP,
            'servidor' => $_SERVER['SERVER_SOFTWARE'] ?? 'Desconocido',
            'sistema_operativo' => PHP_OS,
            'zona_horaria' => date_default_timezone_get(),
            'memoria_limite' => ini_get('memory_limit'),
            'tiempo_limite' => ini_get('max_execution_time')
        ];
    }
}
?>