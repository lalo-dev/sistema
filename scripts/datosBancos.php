<?php


header("Content-Type: text/Text; charset=ISO-8859-1 Cache-Control: no-store, no-cache, must-revalidate");
header ("Expires: Fri, 14 Mar 1980 20:53:00 GMT"); //la pagina expira en fecha pasada
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); //ultima actualizacion ahora cuando la cargamos
header ("Cache-Control: no-cache, must-revalidate"); //no guardar en CACHE
header ("Pragma: no-cache"); //PARANOIA, NO GUARDAR EN CACHE
include("bd.php");

$sql="SELECT cveBanco,descripcion,IF(estatus=1,'Activado','Desactivado') AS estatus ".
"FROM cbancos ORDER BY estatus ASC;";

$campos = $bd->Execute($sql);

	$respuesta = "[";
 	$total=count($campos);
	if($total==0)
	{	$respuesta .= "{total: '0'}";
		}else{
		foreach($campos as $campo){
			$respuesta .= "{total:'".$total."',cveBanco: '" . $campo["cveBanco"] . "', descripcion: '" . $campo["descripcion"] ."', estatus: '" . $campo["estatus"] ."', empresa: '" . $campo["empresa"] ."'},";
		}
		$respuesta = substr($respuesta, 0, strlen($respuesta)-1);
	}
$respuesta .= "]";
echo $respuesta;

?>