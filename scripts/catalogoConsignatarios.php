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
$operacion=$_GET["operacion"];


		switch($operacion){

			case 1:
			{
						
				if ($caracteres != ""){				
					$tabla="cconsignatarios";
					$campos="cveConsignatario,estacion,nombre";

					$condicion="(cconsignatarios.cveConsignatario LIKE '$caracteres%' OR cconsignatarios.estacion LIKE '$caracteres%'
OR cconsignatarios.nombre LIKE '$caracteres%' ) LIMIT 0,5";
				
					$sql= "";
					$dests = $bd->ExecuteFieldn($tabla,$campos,$condicion);
					$respuesta = "<ul>";
					
					foreach ($dests as $dest){
						$respuesta .= "<li>" . $dest[0] ."-". $dest[2] ."-". $dest[1]."</li>";
					}
					$respuesta .= "</ul>";
					echo $respuesta;
				  }
			}
			break;
			case 2:
			{
						
				if ($caracteres != ""){				
					$estacion=$_GET['estacion'];
					
					if(isset($_GET['codigo']) && $_GET['codigo']!="")
					{ 
						$codigo=$_GET['codigo'];
						$extra="codigoPostal='".$codigo."' AND";
					}
					else
					{ $extra=""; }
					
					$condicion=$extra." estacion='".$estacion."' AND (cconsignatarios.cveConsignatario LIKE '$caracteres%' OR cconsignatarios.estacion LIKE '$caracteres%'
OR cconsignatarios.nombre LIKE '$caracteres%' ) LIMIT 0,10";
					
					$tabla="cconsignatarios";
					$campos="cveConsignatario,estacion,nombre";

					$sql= "";
					$dests = $bd->ExecuteFieldn($tabla,$campos,$condicion);
					$respuesta = "<ul>";
					
					foreach ($dests as $dest){
						$respuesta .= "<li>" . $dest[0] ."-". $dest[2] ."-". $dest[1]."</li>";
					}
					$respuesta .= "</ul>";
					echo $respuesta;
				  }
			}
			break;
			case 3:
			{
				if ($caracteres != ""){
					$condicion="cconsignatarios.cveConsignatario LIKE '$caracteres%' OR cconsignatarios.estacion LIKE '$caracteres%'
					OR cconsignatarios.nombre LIKE '$caracteres%' LIMIT 0,5";
					$tabla="cconsignatarios";
					$campos="cveConsignatario,estacion,nombre";

					$sql= "";
					$dests = $bd->ExecuteFieldn($tabla,$campos,$condicion);
					$respuesta = "<ul>";
					foreach ($dests as $dest){
						$respuesta .= "<li>" . $dest[0] ."/". $dest[2] ."/". $dest[1]."</li>";
					}
					$respuesta .= "</ul>";
					echo $respuesta;
				}
			}
			break;
			default:break;
		
	  }
	
?>
	
