<?php
/**
 * Clase de validaciones estáticas para el proyecto
 * Cumple con PSR-1: métodos en camelCase
 */
class Validaciones {
    
    /**
     * Validar si un valor es entero
     */
    public static function validarEntero($valor) {
        return filter_var($valor, FILTER_VALIDATE_INT) !== false;
    }
    
    /**
     * Validar rango de números
     */
    public static function validarRango($valor, $min, $max) {
        return $valor >= $min && $valor <= $max;
    }
    
    /**
     * Sanitizar cadena para prevenir XSS
     */
    public static function sanitizarCadena($cadena) {
        return htmlspecialchars(trim($cadena), ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Validar si un valor está en una lista permitida
     */
    public static function validarEnLista($valor, $listaPermitida) {
        return in_array($valor, $listaPermitida);
    }
    
    /**
     * Validar fecha en formato YYYY-MM-DD
     */
    public static function validarFecha($fecha) {
        $partes = explode('-', $fecha);
        if (count($partes) !== 3) return false;
        
        list($anio, $mes, $dia) = $partes;
        return checkdate($mes, $dia, $anio);
    }
    
    /**
     * Validar número decimal
     */
    public static function validarDecimal($valor) {
        return filter_var($valor, FILTER_VALIDATE_FLOAT) !== false;
    }
    
    /**
     * Validar que no esté vacío (incluye 0 y '0')
     */
    public static function validarNoVacio($valor) {
        if (is_numeric($valor)) return true;
        if (is_string($valor)) return trim($valor) !== '';
        return !empty($valor);
    }
    
    /**
     * Validar longitud de cadena
     */
    public static function validarLongitud($cadena, $min, $max) {
        $longitud = strlen(trim($cadena));
        return $longitud >= $min && $longitud <= $max;
    }
    
    /**
     * Validar formato de nombre (solo letras y espacios)
     */
    public static function validarNombre($nombre) {
        return preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $nombre);
    }
}
?>