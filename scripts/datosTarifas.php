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

$operacion   = $_GET["operacion"];
$origen      = $_GET["origen"];
$zona        = $_GET["zona"];
$estado      = $_GET["estado"];
$destino     = $_GET["destino"];
$tipoTarifa  = $_GET["tabla"];
$cveTarifa   = $_GET["tarifa"];
$tipoCliente = $_GET["tipoCliente"];
$tipoEnvio   = $_GET["tipoEnvio"];

switch($operacion){

		case 1: $condicion=" WHERE ctarifas.estadoOrigen=".$origen;
				break;
				
		case 2:	$condicion=" WHERE ctarifas.estadoOrigen='$origen' AND origen ='$zona'";		
				break;
				
		case 3:	$condicion=" WHERE ctarifas.estadoOrigen='$origen' AND origen ='$zona' AND estadoDestino='$estado'";	
				break;
				
		case 4:	$condicion=" WHERE ctarifas.estadoOrigen='$origen' AND origen ='$zona' AND estadoDestino='$estado' AND destino='$destino'";	
				break;
		case 5:	$condicion=" WHERE ctarifas.cveTarifa='$cveTarifa'";	
				break;				
		case 6:	$condicion=" WHERE ctarifas.estadoOrigen='$origen' AND origen ='$zona' AND estadoDestino='$estado' AND destino='$destino' AND cveTipoc='$tipoCliente'";	
				break;
		case 7:	{
					$condicion=" WHERE ctarifas.estadoOrigen='$origen' AND origen ='$zona' AND estadoDestino='$estado' AND destino='$destino' AND ".
						   	   "cveTipoc='$tipoCliente' AND tipoEnvio='$tipoEnvio'";	
				}
				break;			
		default:break;
		}
$sql="SELECT cveTarifa,
(SELECT cestados.nombre FROM cestados WHERE ctarifas.estadoOrigen=cestados.cveEstado) AS origen,
(SELECT cestados.nombre FROM cestados WHERE ctarifas.estadoDestino=cestados.cveEstado) AS destino,
(SELECT cmunicipios.nombre FROM cmunicipios WHERE ctarifas.origen=cmunicipios.cveMunicipio AND ctarifas.estadoOrigen=cmunicipios.cveEntidadFederativa) AS morigen,
(SELECT cmunicipios.nombre FROM cmunicipios WHERE ctarifas.destino=cmunicipios.cveMunicipio AND ctarifas.estadoDestino=cmunicipios.cveEntidadFederativa) AS mdestino,
ctarifas.cveTipoc, ctarifas.tipoEnvio, ctarifas.cargo99, ctarifas.cargo299, ctarifas.cargo300,ctarifas.cuartoRango, ctarifas.cargoMinimo,estatus 
FROM ctarifas ";

$sql=$sql.$condicion;

$campos = $bd->Execute($sql);

	$respuesta = "[";
	foreach($campos as $campo){
		$respuesta .= "{origen: '" . $campo["origen"] . "', cveTarifa: '" . $campo["cveTarifa"] ."', morigen: '" . $campo["morigen"] ."', destino: '" . $campo["destino"] ."', mdestino: '" . $campo["mdestino"] ."', cveTipoc: '" . $campo["cveTipoc"] ."', tipoEnvio: '" . $campo["tipoEnvio"] ."', estatus: '" . $campo["estatus"] ."', cargo99: '" . $campo["cargo99"] ."', cargo299: '" . $campo["cargo299"] ."', cargo300: '" . $campo["cargo300"] ."', cuartoRango: '" . $campo["cuartoRango"] ."', cargoMinimo: '" . $campo["cargoMinimo"] ."', indice:'0'},";
}
$respuesta = substr($respuesta, 0, strlen($respuesta)-1);
$respuesta .= "]";

if($respuesta=="]")
{
	$respuesta="[{indice:'1'}]";
}
echo $respuesta;

?>
