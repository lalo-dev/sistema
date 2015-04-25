<?php
header("Content-Type: text/Text; charset=ISO-8859-1");
include("BD.php");
$cvelinea = $_GET["airline_id"];

$campos = $bd->Execute("select * from airline_mstr where airline_id = '$cvelinea'");
	$respuesta = "[";
	foreach($campos as $campo){
	$respuesta .= "{descripcion: '".$campo["descripcion"]."', contacto: '" . $campo["contacto"] . "',telefono: '" . $campo["telefono"] . "'},";
		}
		$respuesta = substr($respuesta, 0, strlen($respuesta)-1);
		$respuesta .= "]";
		echo $respuesta;
?>