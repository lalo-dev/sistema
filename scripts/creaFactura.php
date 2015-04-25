<?php

/**
 * @author miguel
 * @copyright 2010
 */

header("Content-Type: text/Text; charset=ISO-8859-1 Cache-Control: no-store, no-cache, must-revalidate");
header ("Expires: Fri, 14 Mar 1980 20:53:00 GMT"); //la pagina expira en fecha pasada
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); //ultima actualizacion ahora cuando la cargamos
header ("Cache-Control: no-cache, must-revalidate"); //no guardar en CACHE
header ("Pragma: no-cache"); //PARANOIA, NO GUARDAR EN CACHE

include("bd.php");
$cveguia = $_GET["cveguia"];
$cveCliente = $_GET["cveCliente"];
$operacion=$_GET["operacion"];
switch($operacion){

		case 1:
		{
			$folios = $bd->Execute("SELECT (folio+1) AS folio FROM cfoliosdocumentos WHERE tipoDocumento='FAC' ");//Debe ir la condicion del año y la empresa
			$respuesta = "[";
			foreach($folios as $folio){
					$respuesta .= "{folio: '". $folio["folio"] ."'}";
			
			}
			$respuesta .= "]";
			echo $respuesta;
		}
		break;
		
		
		default:break;
		}
?>