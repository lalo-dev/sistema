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
$cliente=$_GET["cliente"];
$tabla=$_GET["tabla"];
//$caracteres="13";
 if($tabla=="cliente")
 {
 	$tabla="cdireccionescliente";
 	$campoClave="cveCliente";
 }
 else
 {
 	$tabla="cdireccionesprovedores";
 	$campoClave="cveCorresponsal";
 }

$operacion=$_GET["operacion"];

//$caracteres='ABB';

	if ($caracteres != ""){
		switch($operacion){

			case 1:
			{
				$dests = $bd->ExecuteFieldn($tabla, "cveDireccion,calle",$campoClave."=".$cliente. " AND (cveDireccion LIKE '$caracteres%%' OR calle LIKE '$caracteres%%') LIMIT 0,10");
				$respuesta = "<ul>";
				
				foreach ($dests as $dest){
					$respuesta .= "<li>" . $dest[0] ." - ".$dest[1]. "</li>";
				}
				$respuesta .= "</ul>";
				echo $respuesta;
		
			}
			break;
			case 2:
			{
				$separar = explode('-',$caracteres);
				if($separar[1]!="")
				{
					$query="SELECT cveCliente,cveDireccion FROM cdireccionescliente WHERE cveCliente LIKE '".$separar[0]."%%' AND cveDireccion LIKE '".$separar[1]."%%' LIMIT 0,5";
				}
				else
				{
					$query="SELECT cveCliente,cveDireccion FROM cdireccionescliente WHERE cveCliente LIKE '$caracteres%%' LIMIT 0,5";
				}
				$dests = $bd->Execute($query);
				$respuesta = "<ul>";
								
				foreach ($dests as $dest){
					$respuesta .= "<li>" . $dest["cveCliente"] ."-" .$dest["cveDireccion"] . "</li>";
				}
				$respuesta .= "</ul>";
				echo $respuesta;
		
			}
			break;
			case 3://Agregada
			{
				
				$campos="ccliente.cveCliente AS cve,ccliente.nombreComercial AS nombre,cdireccionescliente.calle AS calle,cdireccionescliente.cveDireccion AS cveD";
				$condicion="INNER JOIN cdireccionescliente ON ccliente.cveCliente = cdireccionescliente.cveCliente
WHERE ccliente.estatus='1' AND cdireccionescliente.estatus='1' 
AND ccontactoscliente.contactoFacturacion=1
AND cdireccionescliente.cveCliente= ccontactoscliente.cveCliente
AND cdireccionescliente. cveDireccion= ccontactoscliente.sucursalCliente
AND (ccliente.cveCliente LIKE '$caracteres%%' OR ccliente.razonSocial LIKE '$caracteres%%' OR ccliente.nombreComercial LIKE '$caracteres%%')
LIMIT 0 , 5;";
				$dests = $bd->ExecuteFieldInner("ccontactoscliente,ccliente", $campos,$condicion);			
				

				$respuesta = "<ul>";
								
				foreach ($dests as $dest){
					$respuesta .= "<li>" . $dest["cve"] ." - " .$dest["nombre"]  ." - " .$dest["calle"] . " - " .$dest["cveD"] . "</li>";
				}
				$respuesta .= "</ul>";
				echo $respuesta;
		
			}
			break;

			default:break;
		}
		
		}
	
	?>
