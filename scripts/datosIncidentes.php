<?php

	header("Content-Type: text/Text; charset=ISO-8859-1 Cache-Control: no-store, no-cache, must-revalidate");
	header ("Expires: Fri, 14 Mar 1980 20:53:00 GMT"); //la pagina expira en fecha pasada
	header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); //ultima actualizacion ahora cuando la cargamos
	header ("Cache-Control: no-cache, must-revalidate"); //no guardar en CACHE
	header ("Pragma: no-cache"); //PARANOIA, NO GUARDAR EN CACHE

	include("bd.php");
	
	$opcion = $_GET["opc"];
	$valor  = $_GET["valor"];
		
	$campo=($opcion==1) ? 'cveReporte' : 'cveGuia';
	
	$sqlConsulta = "SELECT cveReporte,cveGuia,tipoIncidente,fechaReporte,estacion,municipio,".
				   "remitente,consignado,lineaAerea,guiaAerea,noVuelo,pzasEnviadas,kgEnviados,pzasEntregadas,".
				   "kgEntregados,incidentes,elaboro,corroboro,descripcionProblema,descripcionProblemaSol,fechaDeteccion,personaDetecta,".
				   "tecnicaSolucion,fechaSolucion,personaSoluciona,descripcionSolucion ".
				   "FROM creporteincidencias ".
				   "WHERE creporteincidencias.".$campo."=".$valor;
	
	$campos = $bd->Execute($sqlConsulta);
	$total  = count($campos);

	$respuesta = "[";
	
	if($total==0)
		$respuesta .= "{total:'0'}";
	else
	{
		//Para poder llevarnos los saltos de línea
		$sustitucion="<br>";
		$patron		= "/\n|\r\n/";
	
		foreach($campos as $campo)
		{
			$descProblema=preg_replace($patron,$sustitucion,$campo['descripcionProblema']);
			$descProblemaSol=preg_replace($patron,$sustitucion,$campo['descripcionProblemaSol']);
			$solucion=preg_replace($patron,$sustitucion,$campo['descripcionSolucion']);
			
			$respuesta .= "{total: '".$total."',cveR: '".$campo["cveReporte"]."',cveG: '".$campo["cveGuia"]."',tipoInc: '".$campo["tipoIncidente"]."',".
					  "fecReporte: '".$campo["fechaReporte"]."',destino: '".$campo["estacion"]."',municipio: '".$campo["municipio"]."',remitente: '".$campo["remitente"]."',".
					  "consignado: '".$campo["consignado"]."',lAerea: '".$campo["lineaAerea"]."',gAerea: '".$campo["guiaAerea"]."',noVuelo: '".$campo["noVuelo"]."',".
					  "pEnviadas: '".$campo["pzasEnviadas"]."',kEnviados: '".$campo["kgEnviados"]."', pEntregadas: '".$campo["pzasEntregadas"]."',".
					  "kEntregados: '".$campo["kgEntregados"]."',incidentes: '".$campo["incidentes"]."', elabora: '".$campo["elaboro"]."',".
					  "corrobora: '".$campo["corroboro"]."',dProblema: '".$descProblema."',".
					  "dProblemaSol: '".$descProblemaSol."',fecDeteccion: '".$campo["fechaDeteccion"]."',perDetecta: '".$campo["personaDetecta"]."',".
					  "tecSol: '".$campo["tecnicaSolucion"]."',fecSolucion: '".$campo["fechaSolucion"]."',perSol: '".$campo["personaSoluciona"]."',".
					  "desSol: '".$solucion."'},";
		}
		$respuesta = substr($respuesta, 0, strlen($respuesta)-1);
	}
	$respuesta .= "]";
	
	echo $respuesta;

?>
