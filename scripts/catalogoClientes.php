<?php

/**
 * @author miguel
 * @copyright 2009
 */
header("Content-Type: text/Text; charset=ISO-8859-1 Cache-Control: no-store, no-cache, must-revalidate");
header ("Expires: Fri, 14 Mar 1980 20:53:00 GMT"); //la pagina expira en fecha pasada
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); //ultima actualizacion ahora cuando la cargamos
header ("Cache-Control: no-cache, must-revalidate"); //no guardar en CACHE
header ("Pragma: no-cache"); //PARANOIA, NO GUARDAR EN CACHE**/
include("bd.php");
$caracteres =  $_POST["caracteres"];
$operacion = $_GET["operacion"];
$tabla=$_GET["tabla"];



	if ($caracteres != ""){
			switch ($operacion)
				{

								case 1:
									{//necesitamos la variable tabla por que utilizamos esta intruccion para 2 tablas corresponsales y clientes
										$dests = $bd->ExecuteField($tabla, "razonSocial", "razonSocial LIKE '$caracteres%%' LIMIT 0,5");
		
									}
								break;
								case 2:
									{//esta instruccion crea una lista a partir de la clave del cliente
/*										$opcion=$_GET['opt'];
										if($opcion==1) */$campos="cveCliente,razonSocial";
										$condicion="cveCliente LIKE '".$caracteres."%' OR razonSocial LIKE '".$caracteres."%' OR nombreComercial LIKE '".$caracteres."%' LIMIT 0,5";
										$dests = $bd->ExecuteField("ccliente",$campos,$condicion);
									}
									break;
								case 3:
									{//esta instruccion crea una lista a partir de la clave del corresponsal
										$dests = $bd->ExecuteField("ccorresponsales", "cveCorresponsal", "cveCorresponsal LIKE '$caracteres%%' LIMIT 0,5");
									}
									break;
								case 4:
									{
										$opcion=$_GET['opt'];
										$campo="cveCliente,razonSocial";
										$condicion="cveCliente LIKE '".$caracteres."%' OR razonSocial LIKE '".$caracteres."%' OR nombreComercial LIKE '".$caracteres."%' LIMIT 0,5";
										$dests = $bd->ExecuteFieldn("ccliente",$campo,$condicion);
									}
									break;
								case 5:
									{//esta instruccion crea una lista a partir de la clave del corresponsal
										$campo="cveCorresponsal,razonSocial";
										$condicion="cveCorresponsal LIKE '".$caracteres."%' OR razonSocial LIKE '".$caracteres."%' OR nombreComercial LIKE '".$caracteres."%' LIMIT 0,5";
										$dests = $bd->ExecuteFieldn("ccorresponsales",$campo,$condicion);
									}
									break;	
								case 6: //Agregada
									{
										$cliente=$_GET['cvecliente'];
										$direccion=$_GET['cvedireccion'];
										$opcion=$_GET['opc'];
				
										if($opcion==0)
										{
											$tabla="ccontactoscliente,cdireccionescliente";
											$campo="ccontactoscliente.cveContacto,CONCAT(nombre,' ',apellidoPaterno,' ',apellidoMaterno) AS nombre";
											
											$condicion="ccontactoscliente.cveCliente=cdireccionescliente.cveCliente
	AND  ccontactoscliente.sucursalCliente=cdireccionescliente.cveDireccion 
	AND cdireccionescliente.cveDireccion=$direccion AND cdireccionescliente.cveCliente=$cliente AND (ccontactoscliente.cveContacto LIKE '".$caracteres."%' OR nombre LIKE '".$caracteres."%' OR apellidoPaterno LIKE '".$caracteres."%' OR apellidoMaterno LIKE '".$caracteres."%')";
										}
										else
										{
											
											$tabla="ccontactosproveedores,cdireccionesprovedores";
											$campo="ccontactosproveedores.cveContacto,CONCAT(nombre,' ',apellidoPaterno,' ',apellidoMaterno) AS nombre";
											
											$condicion="ccontactosproveedores.cveCorresponsal=cdireccionesprovedores.cveCorresponsal
											AND  ccontactosproveedores.sucursalCliente=cdireccionesprovedores.cveDireccion 
											AND cdireccionesprovedores.cveDireccion=$direccion AND cdireccionesprovedores.cveCorresponsal=$cliente AND (ccontactosproveedores.cveContacto LIKE '".$caracteres."%' OR nombre LIKE '".$caracteres."%' OR apellidoPaterno LIKE '".$caracteres."%' OR apellidoMaterno LIKE '".$caracteres."%')";
										} 

									$dests = $bd->ExecuteFieldn($tabla,$campo,$condicion);
									}
									break;		
								case 7:
									{//esta instruccion crea una lista a partir de la clave del corresponsal
									
									
										$tabla.=",cdireccionesprovedores,cestados";
										$campo=" ccorresponsales.cveCorresponsal,ccorresponsales.razonSocial,cdireccionesprovedores.cveDireccion,
(SELECT cestados.nombre FROM cestados WHERE cestados.cveEstado = cdireccionesprovedores.cveEstado) AS estado,IF(cdireccionesprovedores.codigoPostal='','Sin C.P',cdireccionesprovedores.codigoPostal)";
				
					$condicion="cdireccionesprovedores.estatus='1' AND cdireccionesprovedores.cveEstado=cestados.cveEstado 
AND cdireccionesprovedores.cveCorresponsal=ccorresponsales.cveCorresponsal AND (ccorresponsales.cveCorresponsal LIKE '".$caracteres."%' OR razonSocial LIKE '".$caracteres."%' OR nombreComercial LIKE '".$caracteres."%' OR cdireccionesprovedores.codigoPostal LIKE '".$caracteres."%') LIMIT 0,5;";

									
										//$campo="cveCorresponsal,razonSocial";
										//$condicion="cveCorresponsal LIKE '".$caracteres."%' OR razonSocial LIKE '".$caracteres."%' OR nombreComercial LIKE '".$caracteres."%' LIMIT 0,5";
										$dests = $bd->ExecuteFieldn($tabla,$campo,$condicion);
									}									
										
								default:
									break;
				}
		if(($operacion==4)||($operacion==5)||($operacion==6))
		{
			$respuesta = "<ul>";
			foreach ($dests as $dest)
			{
						$respuesta .= "<li>" . $dest[0] ." - ".$dest[1]. "</li>";
			}
			$respuesta .= "</ul>";
		}else if($operacion==7)
		{
			$respuesta = "<ul>";
			foreach ($dests as $dest)
			{
						$respuesta .= "<li>" . $dest[0] ." - ".$dest[1]. " - ".$dest[2]. " - ".$dest[3]. " - ".$dest[4]."</li>";
			}
			$respuesta .= "</ul>";
		}
		else
		{								
			$respuesta = "<ul>";
			foreach ($dests as $dest){
				$respuesta .= "<li>" . $dest . "</li>";
			}
			$respuesta .= "</ul>";
		}
		echo $respuesta;
		
	}


?>
