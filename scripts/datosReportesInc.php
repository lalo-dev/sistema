<?php

	header("Content-Type: text/Text; charset=ISO-8859-1 Cache-Control: no-store, no-cache, must-revalidate");
	header ("Expires: Fri, 14 Mar 1980 20:53:00 GMT"); //la pagina expira en fecha pasada
	header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); //ultima actualizacion ahora cuando la cargamos
	header ("Cache-Control: no-cache, must-revalidate"); //no guardar en CACHE
	header ("Pragma: no-cache"); //PARANOIA, NO GUARDAR EN CACHE
	
	include("bd.php");

	$guia=$_GET['guia'];
	
	$sqlConsulta="SELECT ".
				 "cveReporte,DATE_FORMAT(fechaReporte,'%d/%m/%Y') AS fechaRep,".
				 "IF(tipoIncidente=0,'Entrega Extempornánea','Daños y Faltantes') AS incidente,".
				 "IFNULL(estacion,'') AS estacion,".
				 "IFNULL(municipio,'') AS municipio,".
				 "IFNULL(lineaAerea,'') AS lAerea,".
				 "IFNULL(guiaAerea,'') AS gAerea,".
				 "IFNULL(noVuelo,'') AS noVuelo,".
				 "LEFT(descripcionProblema,20) AS descripcion ".
				 "FROM creporteincidencias ".
				 "WHERE cveGuia=".$guia." ORDER BY cveReporte DESC,estacion ASC;";
				 	
	$campos = $bd->Execute($sqlConsulta);
	
	$respuesta = "[";	
	$total=count($campos);
	
	if($total==0)
		$respuesta .= "{total: '0'}";
	else
	{
		//Para poder llevarnos los saltos de línea
		$sustitucion="<br>";
		$patron		= "/\n|\r\n/";		

		foreach($campos as $campo)
		{
			$descripcion=preg_replace($patron,$sustitucion,$campo['descripcion']);
			
			$respuesta .= "{total:'".$total."',cveRep:'".$campo["cveReporte"]."',fechaRep:'".$campo["fechaRep"]."',incidente:'".$campo["incidente"] .
						  "',estacion: '".$campo["estacion"]."',municipio:'".$campo["municipio"]."',lAerea:'".$campo["lAerea"].
						  "',gAerea: '".$campo["gAerea"]."',noVuelo:'".$campo["noVuelo"]."',descripcion:'".$descripcion."'},";					  
		}
		$respuesta = substr($respuesta, 0, strlen($respuesta)-1);
	}
		
	$respuesta .= "]";
	echo $respuesta;

?>
