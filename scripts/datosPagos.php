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
$operacion = $_GET["operacion"];

			switch ($operacion)
				{

								case 1: //Deudas de Clientes
									{
										$cliente =  $_GET["cliente"];
										$sql="SELECT dedocta.cveTipoDocumento,dedocta.saldo, dedocta.montoNeto, FolioDocumento,dedocta.fecha,".
											 "cmonedas.descripcion FROM dedocta LEFT OUTER JOIN cmonedas ON dedocta.cveMoneda = cmonedas.cvemoneda ".
											 "WHERE dedocta.cveCliente='$cliente' AND dedocta.cveTipoDocumento!='PAG' AND dedocta.cveTipoDocumento!='NCE' AND dedocta.tipoEstadoCta='cliente';";
										$campos = $bd->Execute($sql);		
									}
								break;
								case 2: //Deudas con Corresponsales
									{
										$cliente =  $_GET["corresponsal"];
										$campos = $bd->ExecuteField("ccliente", "cveCliente", "cveCliente LIKE '$caracteres%%' LIMIT 0,5");
										$sql="SELECT dedocta.cveTipoDocumento,dedocta.saldo, dedocta.montoNeto, FolioDocumento,dedocta.fecha,".
											 "cmonedas.descripcion FROM dedocta LEFT OUTER JOIN cmonedas ON dedocta.cveMoneda = cmonedas.cvemoneda ".
											 "WHERE dedocta.cveCliente='$cliente' AND dedocta.cveTipoDocumento!='PAG' AND saldo>0  AND dedocta.tipoEstadoCta='corresponsal';";
										$campos = $bd->Execute($sql);
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
		$respuesta .= "{cveTipoDocumento: '" . $campo["cveTipoDocumento"] . "', saldo: '" . $campo["saldo"] ."', montoNeto: '" . $campo["montoNeto"] ."', FolioDocumento: '" . $campo["FolioDocumento"] ."', fecha: '" . $campo["fecha"] ."', descripcion: '" . $campo["descripcion"] ."'},";
		}
		$respuesta = substr($respuesta, 0, strlen($respuesta)-1);
	}
$respuesta .= "]";
echo $respuesta;



?>
