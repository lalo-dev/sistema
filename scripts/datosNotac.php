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

$codigo=$_GET["codigo"];
$datos = $_GET["datos"];
$operacion=$_GET["operacion"];

	switch($operacion){

			case 1:
			{
				$sql="SELECT cfacturas.porRetencion,cfacturas.porImpuesto,dedocta.saldo FROM cfacturas ".
					 "INNER JOIN dedocta ON cfacturas.cveFactura=dedocta.folioDocumento ".
					 "WHERE cfacturas.cveFactura='$codigo' AND tipoEstadoCta='cliente'"; 
					 
				$campos = $bd->Execute($sql);
				
				$respuesta = "[";
				foreach($campos as $campo){
					$respuesta .= "{porRet: '" .$campo["porRetencion"]."', porImpuesto: '" .$campo["porImpuesto"]."', saldo: '".$campo["saldo"]."'},";
				}
				$respuesta = substr($respuesta, 0, strlen($respuesta)-1);
				$respuesta .= "]";
			}
			break;
			case 2:
			{
				$sql="SELECT cnotas.noNota,cnotas.cveCliente,cnotas.cveFactura,".
					 "cnotas.importe,cnotas.iva,cnotas.retencion,cnotas.importeTotal,cnotas.porImpuesto,".
					 "cnotas.porRetencion,cnotas.descripcion,dedocta.saldo,ccliente.razonSocial ".
				  	 "FROM cnotas,dedocta,ccliente ".
					 "WHERE cnotas.noNota='".$codigo."' AND dedocta.folioDocumento=cveFactura AND dedocta.cveTipoDocumento='FAC' ".
					 "AND cnotas.cveCliente=ccliente.cveCliente AND tipoEstadoCta='cliente'";
			
				$campos = $bd->Execute($sql);
								
				$respuesta = "[";
				foreach($campos as $campo){
					$respuesta .="{folioFactura: '" . $campo["cveFactura"] . "', idCliente: '" . $campo["cveCliente"] .
								 "',numNota: '" . $campo["noNota"]."', canImporte: '" . $campo["importe"] ."',canIva: '" . $campo["iva"].
								 "',canRetencion: '" . $campo["retencion"] ."', canImporteTotal: '" . $campo["importeTotal"] .
								 "',porcentajeImp: '" . $campo["porImpuesto"] ."', porcentajeRet: '" .  $campo["porRetencion"] .
								 "',saldoDeuda: '" .  $campo["saldo"] ."',razonCliente: '" .  $campo["razonSocial"] .
								 "',descripcionNota: '" .  $campo["descripcion"] ."'},";
				}
				$respuesta = substr($respuesta, 0, strlen($respuesta)-1);
				$respuesta .= "]";
			}
			break;
			default:break;
		}
		
		echo $respuesta;
	
?>
