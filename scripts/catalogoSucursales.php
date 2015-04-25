<?php

/**
 * @author Jose Miguel Pantaleon
 * @copyright 2010
 */
header("Content-Type: text/Text; charset=ISO-8859-1 Cache-Control: no-store, no-cache, must-revalidate");
header ("Expires: Fri, 14 Mar 1980 20:53:00 GMT"); //la pagina expira en fecha pasada
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); //ultima actualizacion ahora cuando la cargamos
header ("Cache-Control: no-cache, must-revalidate"); //no guardar en CACHE
header ("Pragma: no-cache"); //PARANOIA, NO GUARDAR EN CACHE
include("bd.php");
$empresa=$_GET['empresa'];
$aGpups = $bd->Execute("SELECT cveSucursal, nombre FROM csucursales WHERE cveEmpresa='$empresa' ");

$respuesta = "[";
$total=count($aGpups);
if($total==0)
{
	$respuesta .= "{id: '$empresa', desc: 'Sin Sucursal'}";	
}
else
{	
	foreach ($aGpups as $gpup){
	$respuesta .= "{id: '" . $gpup["cveSucursal"] . "', desc: '" . $gpup["nombre"] . "'},";
	}
	$respuesta = substr($respuesta, 0, strlen($respuesta)-1);
}
$respuesta .= "]";
echo $respuesta;


?>