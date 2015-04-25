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
$caracteres =  $_POST["caracteres"];
$cveCliente=$_GET["cliente"];
$operacion=$_GET["operacion"];
$opcion=$_GET['opc'];

if($operacion==5 && $opcion==1) $caracteres="0";
else if($operacion==7) 			$caracteres="0";



	if ($caracteres != ""){
		switch($operacion){

			case 1:
			{
					$dests = $bd->ExecuteFieldf("SELECT DISTINCT(cveFactura) FROM cfacturasdetalle WHERE cveFactura LIKE '$caracteres%%' LIMIT 0,5");
			}
			break;
			case 2:
			{
				$dests = $bd->ExecuteField("dedocta", "folioDocumento", "cveCliente='$cveCliente' AND cveTipoDocumento='FAC' AND saldo > 0 AND folioDocumento LIKE '$caracteres%%' LIMIT 0,5");
			}
			break;
			case 3:
			{
					$dests = $bd->ExecuteFieldf("SELECT DISTINCT(cveFactura) FROM cfoliosdocumentos WHERE folio LIKE '$caracteres%%' LIMIT 0,10");
				}
			break;
			case 4:
			{
					$cliente=$_GET['cliente'];
					$dests = $bd->ExecuteFieldf("SELECT DISTINCT(cveFactura) FROM `cfacturas` WHERE cveCliente='".$cliente."' AND cveFactura LIKE '$caracteres%%' LIMIT 0,5");
			}
			break;
			case 5: //Para contrarecibo
			{
					if($opcion==0){
						$sql="SELECT folioDocumento FROM dedocta WHERE cveTipoDocumento='FAC' AND tipoEstadoCta='cliente' AND estatus!=0 AND folioDocumento LIKE '".$caracteres."%' LIMIT 0,5";
						$dests = $bd->ExecuteFieldf($sql);
					}
					else if($opcion==1) {
						$caracteres=$_GET['cveFactura'];
						$sql="SELECT dedocta.cveCliente, ccliente.razonSocial, dedocta.saldo, dedocta.montoNeto, dedocta.folioDocumento, ".
						"IF( dedocta.estatus =0,  'Pagada',  'Falta Pago' ) ".
						"AS estado, dedocta.fecha, cfacturasdetalle.observacion
						FROM  `dedocta` 
						INNER JOIN ccliente ON dedocta.cveCliente = ccliente.cveCliente
						INNER JOIN cfacturasdetalle ON dedocta.folioDocumento = cfacturasdetalle.cveFactura
						WHERE dedocta.cveTipoDocumento='FAC' AND dedocta.estatus!=0 AND tipoEstadoCta='cliente' AND dedocta.folioDocumento='".$caracteres."' LIMIT 1";
						$dests = $bd->ExecuteFieldfn($sql);					
					}
					else
					{
						$cliente=$_GET['cliente'];
						
						$sql="SELECT folioDocumento FROM dedocta WHERE cveCliente='".$cliente."' AND cveTipoDocumento='FAC' AND estatus!=0 ".
							 "AND folioDocumento LIKE '".$caracteres."%' LIMIT 0,5";
						$dests = $bd->ExecuteFieldf($sql);
					}

					
			}
			break;
			case 6: //Para contrarecibo
			{
						$cliente=$_GET['cliente'];
						$sql="SELECT folioDocumento FROM `dedocta` WHERE `cveTipoDocumento`='FAC' AND cveCliente='$cliente' AND estatus!=0 AND folioDocumento LIKE '$caracteres%' LIMIT 0,5";

						$dests = $bd->ExecuteFieldfn($sql);
			}
			break;
			case 7: //Para factura, para rectificar si es una factura principal, una de corte o de seguro
			{
						$factura=$_GET['f1value'];
						$sql="SELECT referencia,seguro FROM `cfacturas` WHERE `cveFactura`='".$factura."';";
						$dests = $bd->ExecuteFieldfn($sql);
			}
			break;
		
		default:break;
		}
		if($operacion==5 && $opcion==1)
		{
			$respuesta="[{cveCliente:'";
			foreach ($dests as $dest){
				$respuesta .= $dest[0]."',razonSocial:'".$dest[1]."',saldo:'".$dest[2]."',monto:'".$dest[3]."',folioDocumento:'".$dest[4]."',estado:'".$dest[5]."',fecha:'".$dest[6]."',observacion:'".$dest[7];
			}
			$respuesta.="'}]";
		}else if($operacion==7)
		{
			$respuesta="[";
			foreach ($dests as $dest){
				$respuesta .= "{referencia:'".$dest[0]."',seguro:'".$dest[1]."'}";
			}
			$respuesta.="]";
		}
		else{
			$respuesta = "<ul>";
			foreach ($dests as $dest){
				$respuesta .= "<li>" . $dest . "</li>";
			}
			$respuesta .= "</ul>";
		}
		
		echo $respuesta;
		
	}
	
	?>
