<?php
define ("EXP",6000000);
setlocale (LC_CTYPE, 'es_ES');
ini_set ("display_errors","0");
ini_set ("memory_limit","-1");

include_once '../../mysqlclass.php';

$conexion = new ConexionBd();
$conexion->open();

$resultado = $conexion->consulta("DROP TABLE usuario");
$resultado = $conexion->consulta("DROP TABLE encuesta");
$resultado = $conexion->consulta("DROP TABLE pregunta");
$resultado = $conexion->consulta("DROP TABLE respuesta");
$resultado = $conexion->consulta("DROP TABLE resultado");
$resultado = $conexion->consulta("DROP TABLE resultadodetalle");
?>