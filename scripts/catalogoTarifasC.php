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
$caracteres =  $_POST["caracteres"];
$corresponsal=$_GET["corresponsal"];
$operacion=$_GET["operacion"];


	if ($caracteres != ""){
		switch($operacion){

			case 1:
			{
					$dests = $bd->ExecuteField("cguias", "cveTarifa","facturada='0' AND cveTarifa LIKE '$caracteres%%' LIMIT 0,5");
				}
			break;
			case 2:
			{
					$dests = $bd->ExecuteField("ctarifascorresponsales", "cveTarifa","cveCorresponsal=".$corresponsal. " AND estatus='1' AND  cveTarifa LIKE '$caracteres%%' LIMIT 0,5");
				}
			break;
			case 3:
			{
					$dests = $bd->ExecuteFieldf("SELECT DISTINCT(cguias.cveGuia) FROM cguias WHERE cveGuia NOT IN(SELECT DISTINCT(cacuse.cveGuia) FROM cacuse WHERE cacuse.cveCliente='".$corresponsal. "') AND cguias.cveCliente='".$corresponsal. "' AND cguias.facturada='0' AND estatus='1' AND  cguias.cveGuia LIKE '$caracteres%%' LIMIT 0,5");
				}
			break;
			case 4:
			{
					$dests = $bd->ExecuteField("cguias", "cveGuia","cvecorresponsal='".$corresponsal. "' AND facturada='0' AND estatus='1' AND  cveGuia LIKE '$caracteres%%' LIMIT 0,5");
				}
			break;
			case 5: //Agregada
			{
					$tabla="ctarifascorresponsales,cestados";
					$campos=" ctarifascorresponsales.cveTarifa,(SELECT cestados.nombre
FROM cestados WHERE cestados.cveEstado = ctarifascorresponsales.estadoOrigen) AS origen,(SELECT cestados.nombre FROM cestados WHERE cestados.cveEstado = ctarifascorresponsales.estadoDestino) AS destino,(SELECT cmunicipios.nombre FROM cmunicipios WHERE cmunicipios.cveMunicipio= ctarifascorresponsales.municipioDestino AND cmunicipios.cveEntidadFederativa=ctarifascorresponsales.estadoDestino) AS destinoMuni";
				
					$condicion="(ctarifascorresponsales.estadoOrigen=cestados.cveEstado OR ctarifascorresponsales.estadoDestino=cestados.cveEstado) AND
cveCorresponsal=".$corresponsal. "  AND (cveTarifa LIKE '$caracteres%%' OR cestados.nombre LIKE '$caracteres%') LIMIT 0,5;";


				$dests = $bd->ExecuteFieldn($tabla,$campos, $condicion);

				}
		default:break;
			case 6: //Agregada
			{
					$tabla="ctarifas,cestados,cmunicipios";
					$campos="
ctarifas.cveTarifa,(SELECT cmunicipios.nombre FROM cmunicipios WHERE cmunicipios.cveMunicipio = ctarifas.origen AND cmunicipios.cveEntidadFederativa = ctarifas.estadoOrigen) AS morigen, (SELECT cmunicipios.nombre FROM cmunicipios WHERE cmunicipios.cveMunicipio = ctarifas.destino AND cmunicipios.cveEntidadFederativa = ctarifas.estadoDestino) AS mdestino, (SELECT cestados.nombre FROM cestados WHERE cestados.cveEstado = ctarifas.estadoOrigen) AS eorigen, (SELECT cestados.nombre
FROM cestados WHERE cestados.cveEstado = ctarifas.estadoDestino) AS edestino,ctarifas.tipoEnvio AS tipo";

					$condicion=" (ctarifas.estadoOrigen=cestados.cveEstado OR ctarifas.estadoDestino=cestados.cveEstado) AND ((ctarifas.origen=cmunicipios.cveMunicipio AND cestados.cveEstado=cmunicipios.cveEntidadFederativa) OR (ctarifas.destino=cmunicipios.cveMunicipio AND cestados.cveEstado=cmunicipios.cveEntidadFederativa)) AND (ctarifas.cveTarifa LIKE '$caracteres%%'
OR cestados.nombre LIKE '$caracteres%%' OR cmunicipios.nombre LIKE '$caracteres%%') LIMIT 0,6";

					$dests = $bd->ExecuteFieldn($tabla,$campos, $condicion);

				}
		default:break;
		}
		if($operacion==5)
		{
			$respuesta = "<ul>";
			foreach ($dests as $dest)
			{
						$respuesta .= "<li>" . $dest[0] ." - ".$dest[1]." - ".$dest[2]." - ".$dest[3]. "</li>";
			}
			$respuesta .= "</ul>";
		}
		else if($operacion==6)
		{
			$respuesta = "<ul>";
			foreach ($dests as $dest)
			{
						$respuesta .= "<li>" . $dest[0] ." - ".$dest[3]." - ".$dest[1]." - ".$dest[4]." - ".$dest[2]." - ".$dest[5]. "</li>";
			}
			$respuesta .= "</ul>";
		}
		else{
			$respuesta = "<ul>";
			foreach ($dests as $dest){
				$respuesta .= "<li>" . $dest . "</li>";
			}
			$respuesta .= "</ul>";
		}
		echo $respuesta;
		
	}
	

?>
