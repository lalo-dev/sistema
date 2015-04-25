<?php

	header('Content-Type: text/Text; charset=utf-8');
	header('Expires: Fri, 14 Mar 1980  GMT');
	header('Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT');
	header('Cache-Control: no-store, no-cache, must-revalidate');
	header('Cache-Control: post-check=0, pre-check=0", false');
	header('Pragma: no-cache');
	
	include_once('bd.php');

	extract($_REQUEST);

	//Limpiar Datos
	$consignado = trim($consignado);
	$desProb    = trim($desProb);
	$desProbSol = trim($desProbSol);
	$perDet     = trim($perDet);
	$perSol     = trim($perSol);
	$descSol    = trim($descSol);

	$empresa=str_replace("-","",$empresa);
	list($empresa,$sucursal)=explode(',',$empresa);
	$operacion = $_GET["operacion"];


	switch ($operacion)
	{
		case 1:
		{
			$cveReporte=$bd->get_Id("creporteincidencias","cveReporte");
			$sql="INSERT INTO creporteincidencias (cveEmpresa,cveSucursal,cveReporte,cveGuia,tipoIncidente,fechaReporte,estacion,municipio,remitente,consignado,".
				 "lineaAerea,guiaAerea,noVuelo,pzasEnviadas,kgEnviados,pzasEntregadas,kgEntregados,incidentes,descripcionProblema,elaboro,corroboro,".
				 "descripcionProblemaSol,fechaDeteccion,personaDetecta,tecnicaSolucion,fechaSolucion,personaSoluciona,descripcionSolucion,usuarioCreador,fechaCreacion) ".
				 "VALUES ('".$empresa."','".$sucursal."','".$cveReporte."','".$guia."','".$tipoInc."','".$fechaRep."','".$destino."','".$municipio."','".$remitente."',".
				 "'".$consignado."','".$lineaA."','".$guiaA."','".$vueloA."','".$piezasEnv."','".$kgEnv."','".$piezasEnt."','".$kgEnt."','".$incidentes."','".$desProb."',".	
				 "'".$elabora."','".$corrobora."','".$desProbSol."','".$fechaDet."','".$perDet."','".$tecnica."','".$fechaSol."','".$perSol."','".$descSol."',".
				 "'".$usuario."',NOW());";
			
			$mensaje="El reporte se creó exitosamente.";
		}
		break;
		case 2:
		{					
			$sql="UPDATE creporteincidencias SET 
				 tipoIncidente ='".$tipoInc."',
				 fechaReporte ='".$fechaRep."',
				 estacion ='".$destino."',
				 municipio ='".$municipio."',
				 remitente ='".$remitente."',
				 consignado ='".$consignado."',
				 lineaAerea ='".$lineaA."',				 
 				 guiaAerea ='".$guiaA."',
				 noVuelo ='".$vueloA."',
				 pzasEnviadas ='".$piezasEnv."',
				 kgEnviados ='".$kgEnv."',
				 pzasEntregadas ='".$piezasEnt."',
				 kgEntregados ='".$kgEnt."',
				 incidentes ='".$incidentes."',			 
				 descripcionProblema ='".$desProb."',
				 elaboro ='".$elabora."',
				 corroboro ='".$corrobora."',				 
				 descripcionProblemaSol ='".$desProbSol."',
				 fechaDeteccion ='".$fechaDet."',
				 personaDetecta ='".$perDet."',
				 tecnicaSolucion ='".$tecnica."',
				 fechaSolucion ='".$fechaSol."',
				 personaSoluciona ='".$perSol."',				 
				 descripcionSolucion ='".$descSol."',
				 usuarioModifico='".$usuario."',
				 fechaModificacion=NOW()
				 WHERE cveReporte='".$reporte."' AND cveEmpresa=".$empresa." AND cveSucursal=".$sucursal;
		
			$mensaje="El reporte se modificó exitosamente.";
				
		}
		break;
  		default:
		break;
	}
	
	$sql=utf8_decode($sql);
	$my_error1 =$bd->ExecuteNonQuery($sql);
	
	// Verifica si existe error en la sintaxis en MySql
	if(!empty($my_error1))
		echo "Ocurrió un error.-1";		
	else
		echo $mensaje."-0";

?>
