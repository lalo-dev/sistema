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

$sql="SELECT cveLineaArea,descripcion,contacto,cempresas.razonSocial AS empresa FROM `clineasaereas`,cempresas WHERE clineasaereas.cveEmpresa=cempresas.cveEmpresa ORDER BY descripcion ASC,cveLineaArea ASC;";

$campos = $bd->Execute($sql);

	$respuesta = "[";
 	$total=count($campos);
	if($total==0)
	{	$respuesta .= "{total: '0'}";
		}else{
		foreach($campos as $campo){
			$respuesta .= "{total:'".$total."',cveLinea: '" . $campo["cveLineaArea"] . "', descripcion: '" . $campo["descripcion"] ."', contacto: '" . $campo["contacto"] ."', empresa: '" . $campo["empresa"] ."'},";
		}
		$respuesta = substr($respuesta, 0, strlen($respuesta)-1);
	}
$respuesta .= "]";
echo $respuesta;

?>
