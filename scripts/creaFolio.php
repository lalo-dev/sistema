<?php

/**
 * @author miguel
 * @copyright 2009
 */
header("Content-Type: text/Text; charset=ISO-8859-1 Cache-Control: no-store, no-cache, must-revalidate");
header ("Expires: Fri, 14 Mar 1980 20:53:00 GMT"); //la pagina expira en fecha pasada
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); //ultima actualizacion ahora cuando la cargamos
header ("Cache-Control: no-cache, must-revalidate"); //no guardar en CACHE
header ("Pragma: no-cache"); //PARANOIA, NO GUARDAR EN CACHE
include("bd.php");
$cveguia = $_GET["cveguia"];
$cveCliente = $_GET["cveCliente"];

$folios = $bd->Execute("SELECT folio  FROM cfolios WHERE cveCliente=$cveCliente ");//debe de ir la condicion del a�o y la empresa
	$respuesta = "[";

foreach($folios as $folio){
	$resultado=$folio["folio"]+1;
	$respuesta .= "{folio: '". $resultado ."'}";

	}
	$respuesta .= "]";
echo $respuesta;
?>