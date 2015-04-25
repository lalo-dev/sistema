<?php

/**
 * @author miguel
 * @copyright 2010
 */


header("Content-Type: text/Text; charset=ISO-8859-1 Cache-Control: no-store, no-cache, must-revalidate");
header ("Expires: Fri, 14 Mar 1980 20:53:00 GMT"); //la pagina expira en fecha pasada
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); //ultima actualizacion ahora cuando la cargamos
header ("Cache-Control: no-cache, must-revalidate"); //no guardar en CACHE
header ("Pragma: no-cache"); //PARANOIA, NO GUARDAR EN CACHE
include("bd.php");
$cveCorresponsal = $_GET["cveCorresponsal"];
$codigo=$_GET["codigo"];
$cveDireccion = $_GET["cveDireccion"];
$datos = $_GET["datos"];
$operacion=$_GET["operacion"];
	switch($operacion){

			case 1:
			{
				$campos = $bd->Execute("SELECT cveCorresponsal ,razonSocial ,nombreComercial ,rfc ,paginaWeb ,tipoCliente ,cveImpuesto ,cveMoneda ,condicionesPago ,estatus ,lada ,telefono ,fax ,curp FROM ccorresponsales WHERE razonSocial = '$cveCorresponsal' OR cveCorresponsal='$codigo'");
				
					$respuesta = "[";
				foreach($campos as $campo){
				$respuesta .= "{cveCorresponsal: '" . $campo["cveCorresponsal"] . "', razonSocial: '" . $campo["razonSocial"] ."', nombreComercial: '" . $campo["nombreComercial"] ."', rfc: '" . $campo["rfc"] ."', paginaWeb: '" . $campo["paginaWeb"] ."', tipoCliente: '" . $campo["tipoCliente"] ."', cveImpuesto: '" . $campo["cveImpuesto"] ."', cveMoneda: '" . $campo["cveMoneda"] ."', condicionesPago: '" . $campo["condicionesPago"] ."', estatus: '" . $campo["estatus"] ."', lada: '" . $campo["lada"] ."', telefono: '" . $campo["telefono"] ."', fax: '" . $campo["fax"] ."', curp: '" . $campo["curp"] ."', diasFactura: '" . $campo["diasFactura"] ."', diasCobro: '" . $campo["diasCobro"] ."', plazoCobro: '" . $campo["plazoCobro"] ."', requisitosCobro: '" . $campo["requisitosCobro"] ."', noProveedor: '" . $campo["noProveedor"] ."', revicionFactura: '" . $campo["revicionFactura"] ."'},";
				}
				$respuesta = substr($respuesta, 0, strlen($respuesta)-1);
				$respuesta .= "]";
				echo $respuesta;
				}
				break;
			case 2:
				{
				$separar = explode('-',$datos);
				$sql="SELECT cdireccionesprovedores.cveCorresponsal, cveDireccion, sucursalCliente, calle, colonia, cveMunicipio, cveEstado, codigoPostal, cdireccionesprovedores.telefono,ccorresponsales.razonSocial,rfc FROM cdireccionesprovedores INNER JOIN ccorresponsales ON cdireccionesprovedores.cveCorresponsal=ccorresponsales.cveCorresponsal WHERE cdireccionesprovedores.cveCorresponsal=".$separar[0]." AND cveDireccion=".$separar[1];
			
				$campos = $bd->Execute($sql);
				$respuesta = "[";
					foreach($campos as $campo){
				$respuesta .="{cveCorresponsal: '" . $campo["cveCorresponsal"] . "', razonSocial: '" . $campo["razonSocial"] ."', calle: '" . $campo["calle"] ."', colonia: '" . $campo["colonia"] ."', cveMunicipio: '" . $campo["cveMunicipio"] ."', cveEstado: '" . $campo["cveEstado"] ."', codigoPostal: '" . $campo["codigoPostal"] ."', telefono: '" . $campo["telefono"] ."', rfc: '" . $campo["rfc"] ."', cveDireccion: '" . $separar[1] ."'},";
					}
				$respuesta = substr($respuesta, 0, strlen($respuesta)-1);
				$respuesta .= "]";
				echo $respuesta;
				}
			break;
			case 3:
			{
				$opcion=$_GET['opc'];
				list($val1,$val2)=explode(" - ",$codigo);
				if($opcion==0)       $codigo=$val1;
				else if($opcion==1)  $codigo=$val2;
				$sql="SELECT cveCorresponsal ,razonSocial,cveImpuesto FROM ccorresponsales WHERE razonSocial = '$codigo' OR cveCorresponsal='$codigo'";

				$campos = $bd->Execute($sql);
				
				$respuesta = "[";
				foreach($campos as $campo){
					$respuesta .= "{cveCorresponsal: '" . $campo["cveCorresponsal"] . "', razonSocial: '" . $campo["razonSocial"] . "', cveImp: '" . $campo["cveImpuesto"] ."'},";
				}
				$respuesta = substr($respuesta, 0, strlen($respuesta)-1);
				$respuesta .= "]";
				echo $respuesta;
			}
			break;
			case 4: //Agregada
				{
					
					$cveCorresponsal=$_GET['corresponsal'];
					$cveDireccion=$_GET['direccion'];
					

				$sql="SELECT cdireccionesprovedores.cveCorresponsal, cveDireccion, sucursalCliente, calle, colonia, cveMunicipio, cveEstado, codigoPostal, cdireccionesprovedores.telefono,ccorresponsales.razonSocial,rfc FROM cdireccionesprovedores INNER JOIN ccorresponsales ON cdireccionesprovedores.cveCorresponsal=ccorresponsales.cveCorresponsal WHERE cdireccionesprovedores.cveCorresponsal=".$cveCorresponsal." AND cveDireccion=".$cveDireccion;
			
				$campos = $bd->Execute($sql);
				$respuesta = "[";
					foreach($campos as $campo){
				$respuesta .="{cveCorresponsal: '" . $campo["cveCorresponsal"] . "', razonSocial: '" . $campo["razonSocial"] ."', calle: '" . $campo["calle"] ."', colonia: '" . $campo["colonia"] ."', cveMunicipio: '" . $campo["cveMunicipio"] ."', cveEstado: '" . $campo["cveEstado"] ."', codigoPostal: '" . $campo["codigoPostal"] ."', telefono: '" . $campo["telefono"] ."', rfc: '" . $campo["rfc"] ."', cveDireccion: '" . $cveDireccion ."'},";
					}
				$respuesta = substr($respuesta, 0, strlen($respuesta)-1);
				$respuesta .= "]";
				echo $respuesta;
				}
			break;				
			default:break;
	
		
		}

?>
