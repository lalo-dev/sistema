<?php

/**
 * @author miguel
 * @copyright 2009
 */
header("Content-Type: text/Text; charset=ISO-8859-1 Cache-Control: no-store, no-cache, must-revalidate");
header ("Expires: Fri, 14 Mar 1980 20:53:00 GMT"); //la pagina expira en fecha pasada
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); //ultima actualizacion ahora cuando la cargamos
header ("Cache-Control: no-cache, must-revalidate"); //no guardar en CACHE
header ("Pragma: no-cache"); //PARANOIA, NO GUARDAR EN CACHE 

include("bd.php");
$cveCliente = $_GET["cveCliente"];
$codigo=$_GET["codigo"];
$cveDireccion = $_GET["cveDireccion"];
$datos = $_GET["datos"];
$operacion=$_GET["operacion"];
	switch($operacion){

			case 1:
			{

				$campos = $bd->Execute("SELECT ccliente.cveCliente ,ccliente.razonSocial ,ccliente.nombreComercial ,
				ccliente.rfc ,ccliente.paginaWeb ,ccliente.tipoCliente ,
				ccliente.cveImpuesto ,ccliente.cveMoneda ,ccliente.condicionesPago ,
				ccliente.estatus ,ccliente.lada ,ccliente.telefono ,ccliente.fax ,ccliente.curp,
				ccliente.diasFactura,
				diasCobro,plazoCobro,noProveedor,cveTipoCliente,requisitosCobro,
				revicionFactura,cfolios.folio
				FROM ccliente				
				LEFT JOIN cfolios ON ccliente.cveCliente=cfolios.cveCliente
				WHERE ccliente.cveCliente='$codigo'");
						
				$respuesta = "[";
				foreach($campos as $campo){
					
					$respuesta .= "{cveCliente: '" . $campo["cveCliente"] . "', razonSocial: '" . $campo["razonSocial"] ."', nombreComercial: '" . $campo["nombreComercial"] ."', rfc: '" . $campo["rfc"] ."', paginaWeb: '" . $campo["paginaWeb"] ."', tipoCliente: '" . $campo["tipoCliente"] ."', cveImpuesto: '" . $campo["cveImpuesto"] ."', cveMoneda: '" . $campo["cveMoneda"] ."', condicionesPago: '" . $campo["condicionesPago"] ."', estado: '" . $campo["estatus"] ."', lada: '" . $campo["lada"] ."', telefono: '" . $campo["telefono"] ."', fax: '" . $campo["fax"] ."', curp: '" . $campo["curp"] ."', diasFactura: '" . $campo["diasFactura"] ."', diasCobro: '" . $campo["diasCobro"] ."', plazoCobro: '" . $campo["plazoCobro"] ."', noProveedor: '" . $campo["noProveedor"] ."', requisitosCobro: '". $campo["requisitosCobro"] ."', revicionFactura: '". $campo["revicionFactura"] . "', cveTipoC: '".$campo["cveTipoCliente"] ."',folioCliente: '" . $campo["folio"]."'},";
				}
				$respuesta = substr($respuesta, 0, strlen($respuesta)-1);
				$respuesta .= "]";
			
				echo $respuesta;
				
				}
				break;
			case 2:
				{
				$separar = explode('-',$datos);
				$sql="SELECT IF( cdireccionescliente.numeroInterior =  '', cdireccionescliente.numeroExterior, cdireccionescliente.numeroInterior ) AS numero,cdireccionescliente.cveCliente, cveDireccion, sucursalCliente, calle, colonia, cveMunicipio, cveEstado, codigoPostal, cdireccionescliente.telefono,ccliente.razonSocial,rfc FROM cdireccionescliente INNER JOIN ccliente ON cdireccionescliente.cveCliente=ccliente.cveCliente WHERE cdireccionescliente.cveCliente=".$separar[0]." AND cveDireccion=".$separar[1];
			
				$campos = $bd->Execute($sql);
				$respuesta = "[";
					foreach($campos as $campo){
				$respuesta .="{cveCliente: '" . $campo["cveCliente"] . "', razonSocial: '" . $campo["razonSocial"] ."', calle: '" . $campo["calle"] ."', colonia: '" . $campo["colonia"] ."', cveMunicipio: '" . $campo["cveMunicipio"] ."', cveEstado: '" . $campo["cveEstado"] ."', codigoPostal: '" . $campo["codigoPostal"] ."', telefono: '" . $campo["telefono"] ."', rfc: '" . $campo["rfc"] ."', cveDireccion: '" . $separar[1] ."', numero: '" . $campo["numero"]."'},";
					}
				$respuesta = substr($respuesta, 0, strlen($respuesta)-1);
				$respuesta .= "]";
				echo $respuesta;
				}
			break;
		case 3:
			{
				list($cve,$razon)=explode(" - ",$cveCliente);
			    	$valor=$razon;

				$campos = $bd->Execute("SELECT cveCliente ,razonSocial,cveImpuesto FROM ccliente WHERE razonSocial = '$valor' ");
				
					$respuesta = "[";
					foreach($campos as $campo){
				$respuesta .= "{cveCliente: '" . $campo["cveCliente"] . "', razonSocial: '" . $campo["razonSocial"]."', impuesto: '" . $campo["cveImpuesto"] ."'},";
				}
				$respuesta = substr($respuesta, 0, strlen($respuesta)-1);
				$respuesta .= "]";
				echo $respuesta;
				}
				break;
		case 4:
			{
				$opcion=$_GET['opc'];
				list($val1,$val2)=explode(" - ",$codigo);
				if($opcion==0)       $valor=$val1;
				else if($opcion==1)  $valor=$val2;
				$sql="SELECT ccliente.cveCliente,ccliente.razonSocial,cfolios.folio FROM ccliente 
				LEFT JOIN cfolios ON ccliente.cveCliente=cfolios.cveCliente					
				WHERE ccliente.razonSocial = '$valor' OR ccliente.cveCliente='$valor'";
				$campos = $bd->Execute($sql);				
				$respuesta = "[";
				foreach($campos as $campo){
					$respuesta .= "{cveCliente: '" . $campo["cveCliente"] . "', razonSocial: '" . $campo["razonSocial"].
					"', folioCliente: '" . $campo["folio"]."'},";
				}
				$respuesta = substr($respuesta, 0, strlen($respuesta)-1);
				$respuesta .= "]";
				echo $respuesta;
				}
				break;				
			case 5:
			{
				//$campos = $bd->Execute("SELECT cveCliente ,razonSocial ,nombreComercial ,rfc ,paginaWeb ,tipoCliente ,cveImpuesto ,cveMoneda ,condicionesPago ,estatus ,lada ,telefono ,fax ,curp,diasFactura,diasCobro,plazoCobro,requisitosCobro,noProveedor,revicionFactura,cveTipoC FROM ccliente WHERE cveCliente='$codigo'");
				$opcion=$_GET['opc'];
				list($val1,$val2)=explode(" - ",$codigo);
				if($opcion==0)       $valor=$val1;
				else if($opcion==1)  $valor=$val2;
								
				$campos = $bd->Execute("SELECT cveCliente ,razonSocial ,nombreComercial ,rfc ,paginaWeb ,tipoCliente ,cveImpuesto ,cveMoneda ,condicionesPago ,estatus ,lada ,telefono ,fax ,curp,diasFactura,diasCobro,plazoCobro,noProveedor,cveTipoCliente FROM ccliente WHERE cveCliente='$valor'");
				
					$respuesta = "[";
				foreach($campos as $campo){
				$respuesta .= "{cveCliente: '" . $campo["cveCliente"] . "', razonSocial: '" . $campo["razonSocial"] ."', nombreComercial: '" . $campo["nombreComercial"] ."', rfc: '" . $campo["rfc"] ."', paginaWeb: '" . $campo["paginaWeb"] ."', tipoCliente: '" . $campo["tipoCliente"] ."', cveImpuesto: '" . $campo["cveImpuesto"] ."', cveMoneda: '" . $campo["cveMoneda"] ."', condicionesPago: '" . $campo["condicionesPago"] ."', estado: '" . $campo["estatus"] ."', lada: '" . $campo["lada"] ."', telefono: '" . $campo["telefono"] ."', fax: '" . $campo["fax"] ."', curp: '" . $campo["curp"] ."', diasFactura: '" . $campo["diasFactura"] ."', diasCobro: '" . $campo["diasCobro"] ."', plazoCobro: '" . $campo["plazoCobro"] ."', noProveedor: '" . $campo["noProveedor"] ."', cveTipoC: '" . $campo["cveTipoCliente"] ."'},";
				}
				$respuesta = substr($respuesta, 0, strlen($respuesta)-1);
				$respuesta .= "]";
				echo $respuesta;
				}
				break;		
		case 6:
				{
				$separar = explode('-',$datos);
				$sql="SELECT cdireccionescliente.cveCliente, cveDireccion, sucursalCliente, calle, colonia, cveMunicipio, cveEstado, codigoPostal, cdireccionescliente.telefono,ccliente.razonSocial,rfc FROM cdireccionescliente INNER JOIN ccliente ON cdireccionescliente.cveCliente=ccliente.cveCliente WHERE cdireccionescliente.cveCliente=".$separar[0]." AND cveDireccion=".$separar[1];
			
				$campos = $bd->Execute($sql);
				$respuesta = "[";
					foreach($campos as $campo){
				$respuesta .="{cveCliente: '" . $campo["cveCliente"] . "', razonSocial: '" . $campo["razonSocial"] ."', calle: '" . $campo["calle"] ."', colonia: '" . $campo["colonia"] ."', cveMunicipio: '" . $campo["cveMunicipio"] ."', cveEstado: '" . $campo["cveEstado"] ."', codigoPostal: '" . $campo["codigoPostal"] ."', telefono: '" . $campo["telefono"] ."', rfc: '" . $campo["rfc"] ."', cveDireccion: '" . $separar[1] ."'},";
					}
				$respuesta = substr($respuesta, 0, strlen($respuesta)-1);
				$respuesta .= "]";
				echo $respuesta;
				}
			break;				
				default:break;
	
		
		}
	

?>
