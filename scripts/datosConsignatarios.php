<?php

	header("Content-Type: text/Text; charset=ISO-8859-1 Cache-Control: no-store, no-cache, must-revalidate");
	header ("Expires: Fri, 14 Mar 1980 20:53:00 GMT"); //la pagina expira en fecha pasada
	header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); //ultima actualizacion ahora cuando la cargamos
	header ("Cache-Control: no-cache, must-revalidate"); //no guardar en CACHE
	header ("Pragma: no-cache"); //PARANOIA, NO GUARDAR EN CACHE
	
	include("bd.php");
	$cveConsignatario = $_GET["consignatario"];
	$operacion = $_GET["operacion"];

	switch($operacion){

			case 1:
			{
				
				$cveCorresponsal=$_GET['corresponsal'];					

				$sql="SELECT  cconsignatarios.cveConsignatario,cconsignatarios.estacion ,cconsignatarios.nombre ,cconsignatarios.estado ,cconsignatarios.municipio ,
				cconsignatarios.colonia ,cconsignatarios.calle ,cconsignatarios.codigoPostal ,cconsignatarios.telefono
				FROM  cconsignatarios WHERE cconsignatarios.cveConsignatario=".$cveConsignatario.";";
			
				$campos = $bd->Execute($sql);
				$respuesta = "[";
							   
				foreach($campos as $campo){
					$respuesta .="{cve: '" . $campo["cveConsignatario"] . "', estacion: '" . $campo["estacion"] ."', nombre: '" . $campo["nombre"] ."', estado: '" . $campo["estado"] ."', municipio: '" . $campo["municipio"] ."', colonia: '" . $campo["colonia"] ."', calle: '" . $campo["calle"] ."', codigoPostal: '" . $campo["codigoPostal"] ."', telefono: '" . $campo["telefono"] ."'},";
				}
				
				$respuesta = substr($respuesta, 0, strlen($respuesta)-1);
				$respuesta .= "]";
				echo $respuesta;
				}
			break;				
			default:break;
		
		}

?>