<?php

/**
 * @author miguel
 * @copyright 2009
 */

header("Content-Type: text/Text; charset=ISO-8859-1");
include("bd.php");
$cvePais = $_GET["pais"];

$aGpups = $bd->Execute("SELECT cveEstado,nombre FROM cestados WHERE cvePais = '$cvePais'  ORDER BY nombre");

$respuesta = "[";

foreach ($aGpups as $gpup){
$respuesta .= "{id: '" . $gpup["cveEstado"] . "', desc: '" . $gpup["nombre"] . "'},";
}
$respuesta = substr($respuesta, 0, strlen($respuesta)-1);
$respuesta .= "]";
echo $respuesta;


?>