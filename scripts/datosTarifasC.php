<?php

/**
 * @author Jose Miguel Pantaleon
 * @copyright 2010
 */

/*header("Content-Type: text/Text; charset=ISO-8859-1 Cache-Control: no-store, no-cache, must-revalidate");
header ("Expires: Fri, 14 Mar 1980 20:53:00 GMT"); //la pagina expira en fecha pasada
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); //ultima actualizacion ahora cuando la cargamos
header ("Cache-Control: no-cache, must-revalidate"); //no guardar en CACHE
header ("Pragma: no-cache"); //PARANOIA, NO GUARDAR EN CACHE*/
include("bd.php");
$corresponsal = $_GET["corresponsal"];
$cveTarifa=$_GET["tarifa"];
$operacion=$_GET["operacion"];

	switch($operacion){

			case 1:
			{
				$campos = $bd->Execute("SELECT primerRango,segundoRango,tercerRango,cuartoRango,estadoOrigen,municipioOrigen,estadoDestino,municipioDestino FROM ctarifascorresponsales WHERE cveTarifa=$cveTarifa AND cveCorresponsal=$corresponsal");
				
					$respuesta = "[";
				foreach($campos as $campo){
				$respuesta .= "{primerRango: '" . $campo["primerRango"] . "', segundoRango: '" . $campo["segundoRango"] ."', tercerRango: '" . $campo["tercerRango"] ."', cuartoRango: '" . $campo["cuartoRango"] ."', estadoOrigen: '" . $campo["estadoOrigen"] ."', municipioOrigen: '" . $campo["municipioOrigen"] ."', estadoDestino: '" . $campo["estadoDestino"] ."', municipioDestino: '" . $campo["municipioDestino"] ."'},";
				}
				$respuesta = substr($respuesta, 0, strlen($respuesta)-1);
				$respuesta .= "]";
				echo $respuesta;
				}
				break;
			case 2:
				{
				$respuesta = "[";
                $sql="SELECT COUNT(cveTarifa) AS existe FROM ctarifascorresponsales WHERE cveTarifa='$cveTarifa' AND cveCorresponsal='$corresponsal' ";
				$exists = $bd->Execute($sql);
                foreach($exists as $existe){
                if($existe["existe"]>0)
				{
                    
        				$sql="SELECT COUNT(cveTarifa) AS existe, estadoOrigen,estadoDestino FROM ctarifascorresponsales WHERE cveTarifa='$cveTarifa' AND cveCorresponsal='$corresponsal' GROUP BY estadoOrigen ";
        				$campos = $bd->Execute($sql);
        				foreach($campos as $campo){
        				        $respuesta .= "{existe: '".$campo["existe"]."', estadoOrigen: '".$campo["estadoOrigen"]."', estadoDestino: '".$campo["estadoDestino"]."'},";
        					}
                
			
                }
               	else
				{
						$respuesta .= "{existe: '".$existe["existe"]."'},";
				}
		
				}
                
            	$respuesta = substr($respuesta, 0, strlen($respuesta)-1);
				$respuesta .= "]";
				echo $respuesta;
				}
			break;
		case 3:
			{
				$sql="SELECT cveDetalle,tipoEnvio, primerRango, segundoRango, Tercerrango, cuartoRango, sobrepeso, distancia, cargoMinimo,costoSobrepeso,
				costoDistancia,costoEntrega,costoEspecial,costoViaticos
				FROM cdetalletarifa WHERE cveTarifa='$cveTarifa' AND cveCorresponsal='$corresponsal'";
                $campos = $bd->Execute($sql);
                $respuesta = "[";
                foreach($campos as $campo){
                         $respuesta .= "{cveDetalle: '" . $campo["cveDetalle"] . "',tipoEnvio: '" . $campo["tipoEnvio"] . "', primerRango: '" . $campo["primerRango"] ."', segundoRango: '" . $campo["segundoRango"] ."', Tercerrango: '" . $campo["Tercerrango"] ."', cuartoRango: '" . $campo["cuartoRango"] ."', sobrepeso: '" . $campo["sobrepeso"] ."', distancia: '" . $campo["distancia"] ."', cargoMinimo: '" . $campo["cargoMinimo"] ."', costoSobrepeso: '" . $campo["costoSobrepeso"] ."', costoDistancia: '" . $campo["costoDistancia"] ."', costoEntrega: '" . $campo["costoEntrega"] ."', costoEspecial: '" . $campo["costoEspecial"] ."', costoViaticos: '" . $campo["costoViaticos"] ."', indice:'0'},";
                }
                $respuesta = substr($respuesta, 0, strlen($respuesta)-1);
                $respuesta .= "]";
                if($respuesta=="]")
                {
                	$respuesta="[{indice:'1'}]";
                }
                echo $respuesta;

				}
				break;
    case 4:
			{
				$sql="SELECT cveDetalle,tipoEnvio, primerRango, segundoRango, Tercerrango, cuartoRango, sobrepeso, distancia, cargoMinimo,costoSobrepeso,
				costoDistancia,costoEntrega,costoEspecial,costoViaticos FROM cdetalletarifa WHERE cveDetalle='$cveTarifa'";
                $campos = $bd->Execute($sql);
                $respuesta = "[";
                foreach($campos as $campo){
                         $respuesta .= "{cveDetalle: '" . $campo["cveDetalle"] . "',tipoEnvio: '" . $campo["tipoEnvio"] . "', primerRango: '" . $campo["primerRango"] ."', segundoRango: '" . $campo["segundoRango"] ."', Tercerrango: '" . $campo["Tercerrango"] ."', cuartoRango: '" . $campo["cuartoRango"] ."', sobrepeso: '" . $campo["sobrepeso"] ."', distancia: '" . $campo["distancia"] ."', cargoMinimo: '" . $campo["cargoMinimo"] ."', costoSobrepeso: '" . $campo["costoSobrepeso"] ."', costoDistancia: '" . $campo["costoDistancia"] ."', costoEntrega: '" . $campo["costoEntrega"] ."', costoEspecial: '" . $campo["costoEspecial"] ."', costoViaticos: '" . $campo["costoViaticos"] ."', indice:'0'},";
                }
                $respuesta = substr($respuesta, 0, strlen($respuesta)-1);
                $respuesta .= "]";
                if($respuesta=="]")
                {
                	$respuesta="[{indice:'1'}]";
                }
                echo $respuesta;

				}
				break;
     case 5:
			{
				$sql="SELECT primerRango,segundoRango,tercerRango,cuartoRango,estadoOrigen,municipioOrigen,estadoDestino,municipioDestino FROM ctarifascorresponsales WHERE cveTarifa='1' AND cveCorresponsal='0'";
                $campos = $bd->Execute($sql);
                $respuesta = "[";
                	foreach($campos as $campo){
				$respuesta .= "{primerRango: '" . $campo["primerRango"] . "', segundoRango: '" . $campo["segundoRango"] ."', tercerRango: '" . $campo["tercerRango"] ."', cuartoRango: '" . $campo["cuartoRango"] ."'},";
				}
				$respuesta = substr($respuesta, 0, strlen($respuesta)-1);
				$respuesta .= "]";
				echo $respuesta;
				}
                break;
      case 6:
			{
				$sql="SELECT origen,destino,estadoOrigen,estacionDestino,estacionOrigen,destino,estadoDestino, tipoEnvio, cargo99, cargo299, cargo300, cuartoRango, cargoMinimo,cveTipoc,estatus
FROM ctarifas
WHERE cveTarifa ='$cveTarifa'";
                $campos = $bd->Execute($sql);
                $respuesta = "[";
                foreach($campos as $campo){
                         $respuesta .= "{origen: '" . $campo["origen"]."',destino:'" . $campo["destino"] . "',estadoOrigen: '" . $campo["estadoOrigen"] . "', estacionOrigen: '" . $campo["estacionOrigen"] . "', estacionDestino: '" . $campo["estacionDestino"] ."', estatus: '" . $campo["estatus"] ."', destino: '" . $campo["destino"] ."', estadoDestino: '" . $campo["estadoDestino"] ."', tipoEnvio: '" . $campo["tipoEnvio"] ."', cargo99: '" . $campo["cargo99"] ."', cargo299: '" . $campo["cargo299"] ."', cargo300: '" . $campo["cargo300"] ."', cuartoRango: '" . $campo["cuartoRango"] ."', cargoMinimo: '" . $campo["cargoMinimo"] ."', cveTipoc: '" . $campo["cveTipoc"] ."'},";
                }
                $respuesta = substr($respuesta, 0, strlen($respuesta)-1);
                $respuesta .= "]";
                if($respuesta=="]")
                {
                	$respuesta="[{indice:'1'}]";
                }
                echo $respuesta;

				}
				break;
      case 7: //Agregada parar cargar datos cuando se acaba de Agregar Tarifa
			{
				
				
					$sql="SELECT IFNULL(MAX(cveTarifa),0)+1 AS id FROM ctarifascorresponsales WHERE cveCorresponsal=".$corresponsal.";";
					$campos = $bd->Execute($sql);
	                $respuesta = "[{";
    	            foreach($campos as $campo){
							 $respuesta.=" cve: '".$campo['id']."'"; 
					}
					$respuesta.="}]";
					
					echo $respuesta;

				}
				break;				
				default:break;
	
		
		}

?>