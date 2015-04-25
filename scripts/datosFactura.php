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
$respuesta = "[";
$cliente =  $_GET["cveCliente"];
$operacion = $_GET["operacion"];

			switch ($operacion)
				{

					case 1:
					{
					
						$campos = $bd->Execute("SELECT dedocta.folioDocumento,dedocta.fecha, dedocta.montoNeto, dedocta.saldo
					    FROM dedocta
						WHERE dedocta.cveCliente='$cliente' AND dedocta.cveTipoDocumento='FAC' AND saldo>0 AND tipoEstadoCta='cliente' ORDER BY folioDocumento ASC");
					
					}
					break;
					case 2:
					{
						$campos = $bd->ExecuteField("ccliente", "cveCliente", "cveCliente LIKE '$caracteres%%' LIMIT 0,5");
					}
					break;
					default:
					break;
				}
										
	$total=count($campos);
	if($total==0)
	{
			$respuesta .= "{existe: '0'}";
	
	}
	else{
		foreach($campos as $campo){
		$respuesta .= "{folioDocumento: '" . $campo["folioDocumento"] . "', fecha: '" . $campo["fecha"] ."', montoNeto: '" . $campo["montoNeto"] ."', saldo: '" . $campo["saldo"] ."'},";
		}
		$respuesta = substr($respuesta, 0, strlen($respuesta)-1);
	}
$respuesta .= "]";
echo $respuesta;



?>
