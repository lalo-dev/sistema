<?php

/**
 * @author miguel
 * @copyright 2010
 */

/*header("Content-Type: text/Text; charset=ISO-8859-1 Cache-Control: no-store, no-cache, must-revalidate");
header ("Expires: Fri, 14 Mar 1980 20:53:00 GMT"); //la pagina expira en fecha pasada
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); //ultima actualizacion ahora cuando la cargamos
header ("Cache-Control: no-cache, must-revalidate"); //no guardar en CACHE
header ("Pragma: no-cache"); //PARANOIA, NO GUARDAR EN CACHE*/
include("bd.php");
$respuesta = "[";
$operacion = $_GET["operacion"];
switch ($operacion)
{

	case 1:
	{//nos trae los valores de el catalogo de tipos de pagos
		$aGpups = $bd->ExecuteTable("ctipopago", "cveTipoPago");
		foreach ($aGpups as $gpup){
			$respuesta .= "{id: '" . $gpup["cveTipoPago"] . "', desc: '" . $gpup["descripcion"] . "'},";
		}
	}
	break;
	case 2:
	{//nos trae los valores de el catalogo de monedas
		$aGpups = $bd->ExecuteTable("cmonedas", "cveMoneda");
		foreach ($aGpups as $gpup){
			$respuesta .= "{id: '" . $gpup["cveMoneda"] . "', desc: '" . $gpup["descripcion"] . "'},";
		}
	}
	break;
	case 3:
	{//nos trae los valores de el catalogo de monedas
		$aGpups = $bd->ExecuteTable("cbancos", "cveBanco");
		foreach ($aGpups as $gpup){
			$respuesta .= "{id: '" . $gpup["cveBanco"] . "', desc: '" . $gpup["descripcion"] . "'},";
		}
	}
	break;
	default:
	break;
}

$respuesta = substr($respuesta, 0, strlen($respuesta)-1);
$respuesta .= "]";
echo $respuesta;

?>
