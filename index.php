<?php
/**
 * MENÚ PRINCIPAL - Mini Proyecto #2
 * Universidad Tecnológica de Panamá
 * Ingeniería Web
 * 
 * Integrantes: [Nombres de los estudiantes]
 * Fecha: <?php echo date('d/m/Y'); ?>
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mini Proyecto #1 - PHP</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Mini Proyecto #1 - PHP</h1>
            <h2>Estructuras de Control, Funciones y Clases</h2>
            <p><strong>Universidad Tecnológica de Panamá</strong> - Facultad de Ingeniería en Sistemas Computacionales</p>
            <p><strong>Integrantes:</strong> Sebastián Sanchez, Iván Ramírez López</p>
            <hr>
        </header>

        <?php include 'includes/Navegacion.php'; ?>
        <?php generarMenu(); ?>

        <section class="descripcion">
            <h3>📋 Descripción del Proyecto</h3>
            <p>Este proyecto implementa 10 problemas algorítmicos utilizando PHP, aplicando:</p>
            <ul>
                <li>Estructuras de control (if, switch, while, for, foreach)</li>
                <li>Funciones y clases con métodos estáticos</li>
                <li>Validación y sanitización de datos</li>
                <li>Arreglos unidimensionales y bidimensionales</li>
                <li>Formularios HTML y procesamiento PHP</li>
                <li>Buena prácticas PSR-1</li>
            </ul>
        </section>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>