<?php

header("Content-Type: text/Text; charset=ISO-8859-1 Cache-Control: no-store, no-cache, must-revalidate");
header ("Expires: Fri, 14 Mar 1980 20:53:00 GMT"); //la pagina expira en fecha pasada
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); //ultima actualizacion ahora cuando la cargamos
header ("Cache-Control: no-cache, must-revalidate"); //no guardar en CACHE
header ("Pragma: no-cache"); //PARANOIA, NO GUARDAR EN CACHE

require_once("bd.php");
$operacion  = $_GET["operacion"];
$caracteres =  $_POST["caracteres"];

if($caracteres!=""){

	switch ($operacion)
	{
		case 1:
		{
			$campo="noContrarecibo";
			$condicion="noContrarecibo LIKE '".$caracteres."%' LIMIT 0,5";
			$contrarecibos = $bd->ExecuteField("ccontrarecibo",$campo,$condicion);
		}
		break;
		case 2:
		{
			$campo="noNota";
			$condicion="noNota LIKE '".$caracteres."%' LIMIT 0,5";
			$contrarecibos = $bd->ExecuteField("cnotas",$campo,$condicion);
		}
		break;		
		default:
		break;
	}
						
	//Mandamos la respuesta
	$respuesta = "<ul>";
	foreach ($contrarecibos as $contrarecibo)
	{
		$respuesta .= "<li>" . $contrarecibo . "</li>";
	}
	$respuesta .= "</ul>";
	echo $respuesta;
}

?>
