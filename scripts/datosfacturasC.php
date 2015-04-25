<?php

header("Content-Type: text/Text; charset=ISO-8859-1 Cache-Control: no-store, no-cache, must-revalidate");
header ("Expires: Fri, 14 Mar 1980 20:53:00 GMT"); //la pagina expira en fecha pasada
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); //ultima actualizacion ahora cuando la cargamos
header ("Cache-Control: no-cache, must-revalidate"); //no guardar en CACHE
header ("Pragma: no-cache"); //PARANOIA, NO GUARDAR EN CACHE

$operacion       = $_GET["op"];

if($operacion==4)
	$cveGuia         = $_GET['noGuia'];
else
{
	$cveCorresponsal = $_GET["corresponsal"];
	$cveFactura      = $_GET["factura"];
	$anyo            = $_GET["anyo"];
}

include("bd.php");

switch($operacion)
{

	case 1:
	{
		$sqlConsulta="SELECT anyoFactura,DATE_FORMAT(fechaFactura,'%d/%m/%Y') as fechaFac,porImpuesto,porRetencion,importeBruto,iva,retencion,importeNeto ".
					 "FROM cfacturascorresponsal ".
					 "WHERE cveCorresponsal='".$cveCorresponsal."' AND cveFactura='".$cveFactura."' AND anyoFactura='".$anyo."'";
					 
		$campos = $bd->Execute($sqlConsulta);
		
		$respuesta = "[";
		foreach($campos as $campo)
		{
			$respuesta .= "{  anyo:'".$campo["anyoFactura"]."', factura:'".$cveFactura."', fechaFac:'".$campo["fechaFac"]."' ,porIva:'".$campo["porImpuesto"].
						  "', porRet:'".$campo["porRetencion"]."', impBruto:'".$campo["importeBruto"]."', totIva:'".$campo["iva"].
						  "', totRet:'".$campo["retencion"]."', impNeto:'".$campo["importeNeto"]."'},";
		}
		$respuesta = substr($respuesta, 0, strlen($respuesta)-1);
		$respuesta .= "]";
		echo $respuesta;
	}
	break;
	case 2:
	{
		$sqlConsulta="SELECT cveDetalle,cveGuia,tipoEnvio,piezas,kg,tarifa,costoEntrega,sobrepeso,costoSobrepeso,distancia,".
					 "costoDistancia,costoEspecial,viaticos,guiaAerea,extra1,extra2,observaciones,total,cargoMinimo ".
					 "FROM cfacturasdetallecorresponsales ".
					 "WHERE cveCorresponsal='".$cveCorresponsal."' AND cveFactura='".$cveFactura."' AND anyoFactura='".$anyo."' ORDER BY cveDetalle ASC;";
					 
		$campos = $bd->Execute($sqlConsulta);

		$respuesta = "[";
		
		foreach($campos as $campo){
			$respuesta .= "{  detalle:'".$campo["cveDetalle"]."', noGuia:'".$campo["cveGuia"]."', tipoE:'".$campo["tipoEnvio"]."', pzas:'".$campo["piezas"].
						  "', peso:'".$campo["kg"]."', noTarifa:'".$campo["tarifa"]."', ctoEntrega:'".$campo["costoEntrega"].
						  "', sobrePeso:'".$campo["sobrepeso"]."', ctoSobrePeso:'".$campo["costoSobrepeso"] ."', noDistancia:'".$campo["distancia"].
						  "', ctoDistancia:'".$campo["costoDistancia"]."', ctoEspecial:'".$campo["costoEspecial"]."', noViaticos: '".$campo["viaticos"].
						  "', noGuiaAerea:'".$campo["guiaAerea"]."', noExtra1:'".$campo["extra1"]."', noExtra2:'".$campo["extra2"].
						  "', obs:'".$campo["observaciones"]."', noTotal:'".$campo["total"]."', cargoMin:'".$campo["cargoMinimo"]."'},";
		}		
		$respuesta = substr($respuesta, 0, strlen($respuesta)-1);
		$respuesta .= "]";
		echo $respuesta;
	}
	break;
	case 3:  //Pare verificar que no este en ceros el pago de la factura
	{
		$sqlConsulta="SELECT saldo ".
					 "FROM dedocta ".
					 "WHERE cveCliente='".$cveCorresponsal."' AND tipoEstadoCta='corresponsal' AND cveTipoDocumento='FAC' AND 
					  folioDocumento='".$cveFactura."' AND anyoDocumento='".$anyo."';";
					 
		$campos = $bd->Execute($sqlConsulta);

		$respuesta = "[";
		
		foreach($campos as $campo){
			$respuesta .= "{  saldo:'".$campo["saldo"]."'},";
		}		
		$respuesta = substr($respuesta, 0, strlen($respuesta)-1);
		$respuesta .= "]";
		echo $respuesta;
	}
	break;
	case 4:  //Información de la guía
	{
		$sqlConsulta="SELECT cveCorresponsal,cveFactura,anyoFactura,cfacturasdetallecorresponsales.cveGuia,".
					 "dedocta.montoNeto,dedocta.saldo,cguias.status ".
					 "FROM cfacturasdetallecorresponsales ".
					 "INNER JOIN dedocta ON ".
					 "cfacturasdetallecorresponsales.anyoFactura=dedocta.anyoDocumento AND ".
					 "cfacturasdetallecorresponsales.cveFactura=dedocta.folioDocumento AND ".
					 "cfacturasdetallecorresponsales.cveCorresponsal=dedocta.cveCliente ".					 
					 "INNER JOIN cguias ON cfacturasdetallecorresponsales.cveGuia=cguias.cveGuia ".
					 "WHERE cfacturasdetallecorresponsales.cveGuia='".$cveGuia."' AND dedocta.tipoEstadoCta='corresponsal' ".
					 "AND dedocta.cveTipoDocumento='FAC' LIMIT 1;";

		$campos = $bd->Execute($sqlConsulta);

		$respuesta = "[";

		if(count($campos)==0)
		{
			$sqlConsulta="SELECT cguias.status,IFNULL(cconsignatarios.estacion,'Sin destino') AS estacion,".
						 "IFNULL((SELECT cmunicipios.nombre FROM cmunicipios ".
						 "WHERE cconsignatarios.estado=cmunicipios.cveEntidadFederativa AND cconsignatarios.municipio=cmunicipios.cveMunicipio),'') AS municipio ".
						 "FROM cguias ".
						 "LEFT JOIN cconsignatarios ON cguias.cveConsignatario=cconsignatarios.cveConsignatario ".
						 "WHERE cguias.cveGuia='".$cveGuia."';";
				
			$camposCom=$bd->Execute($sqlConsulta);
			foreach($camposCom as $campos)
			{		 
				$respuesta .= "{ existe:'0',edoGuia:'".$campos['status']."',noGuia:'".$cveGuia."',estacion:'".$campos['estacion'].
							  "',municipio:'".$campos['municipio']."'}";
			}
		}		
		else
		{
			//Obtenemos estacion y muinicipio
			$sqlConsulta="SELECT IFNULL(cconsignatarios.estacion,'Sin destino') AS estacion,".
						 "IFNULL((SELECT cmunicipios.nombre FROM cmunicipios ".
						 "WHERE cconsignatarios.estado=cmunicipios.cveEntidadFederativa AND cconsignatarios.municipio=cmunicipios.cveMunicipio),'') AS municipio ".
						 "FROM cguias ".
						 "LEFT JOIN cconsignatarios ON cguias.cveConsignatario=cconsignatarios.cveConsignatario ".
						 "WHERE cguias.cveGuia='".$cveGuia."';";
			$datosGrales=$bd->Execute($sqlConsulta);
			
			foreach($datosGrales as $datos)
			{
				$estacion=$datos['estacion'];
				$municipio=$datos['municipio'];
			}
			
			foreach($campos as $campo)
			{
				$respuesta .= "{ existe:'1', edoGuia:'".$campo["status"]."', noGuia:'".$campo["cveGuia"]."', noCorr:'".$campo["cveCorresponsal"].
							  "', anyoFac:'".$campo["anyoFactura"]."', noFac:'".$campo["cveFactura"]."', importe:'".$campo["montoNeto"].
							  "', saldoFac:'".$campo["saldo"]."',estacion:'".$estacion."',municipio:'".$municipio."'}";
			}	
		}
		
		$respuesta .= "]";
		echo $respuesta;
	}
	break;
	case 5:  //Información de la factura
	{
		$sqlConsulta="SELECT montoNeto,saldo ".
					 "FROM dedocta ".					 
					 "WHERE cveCliente='".$cveCorresponsal."' AND tipoEstadoCta='corresponsal' AND cveTipoDocumento='FAC' ".
					 "AND folioDocumento='".$cveFactura."' AND anyoDocumento='".$anyo."';";	 
		
		$campos = $bd->Execute($sqlConsulta);

		$respuesta = "[";
		foreach($campos as $campo)
		{
			$respuesta .= "{ importe:'".$campo["montoNeto"]."', saldoFac:'".$campo["saldo"]."'}";
		}				
		
		$respuesta .= "]";		
		echo $respuesta;
	}
	default:
	break;
	
}
	

?>
