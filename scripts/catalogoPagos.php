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
include("BD.php");
$respuesta = "[";
$cliente =  $_GET["cveCliente"];
$operacion = $_GET["operacion"];

			switch ($operacion)
				{

								case 1:
									{
										$campos = $bd->Execute("SELECT DEdoCta.cveTipoDocumento,DEdoCta.saldo, DEdoCta.montoNeto, FolioDocumento,DEdoCta.fecha,CMonedas.descripcion FROM DEdoCta LEFT OUTER JOIN CMonedas ON DEdoCta.cveMoneda = CMonedas.cvemoneda WHERE DEdoCta.cveCliente='$cliente' AND DEdoCta.cveTipoDocumento!='pag' AND saldo>0");
		
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
										
		foreach($campos as $campo){
$respuesta .= "{cveTipoDocumento: '" . $campo["cveTipoDocumento"] . "', saldo: '" . $campo["saldo"] ."', montoNeto: '" . $campo["montoNeto"] ."', FolioDocumento: '" . $campo["FolioDocumento"] ."', fecha: '" . $campo["fecha"] ."', descripcion: '" . $campo["descripcion"] ."'},";
}
$respuesta = substr($respuesta, 0, strlen($respuesta)-1);
$respuesta .= "]";
echo $respuesta;
?>