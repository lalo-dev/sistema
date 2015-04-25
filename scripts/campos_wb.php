<?php
	header("Content-Type: text/Text; charset=ISO-8859-1"); 
include("BD.php");
$wb_id = $_GET["wb_id"];

$campos = $bd->Execute("select * from cguias where cveGuia = '$wb_id'");
	$respuesta = "[";
	foreach($campos as $campo){
	$respuesta .= "{wb_airline: '".$campo["cveLineaArea"]."', wb_flightnum: '" . $campo["noVuelo"] ."'},";
		}
		$respuesta = substr($respuesta, 0, strlen($respuesta)-1);
		$respuesta .= "]";
		echo $respuesta;
?>