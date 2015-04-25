<?php

header("Content-Type: text/Text; charset=ISO-8859-1 Cache-Control: no-store, no-cache, must-revalidate");
header ("Expires: Fri, 14 Mar 1980 20:53:00 GMT"); //la pagina expira en fecha pasada
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); //ultima actualizacion ahora cuando la cargamos
header ("Cache-Control: no-cache, must-revalidate"); //no guardar en CACHE
header ("Pragma: no-cache"); //PARANOIA, NO GUARDAR EN CACHE 

require_once("bd.php");

$numContrarecibo = $_GET["numContrarecibo"];
$operacion       = $_GET["operacion"];

switch($operacion){

	case 1:
	{
		
		$sql="SELECT ccontrarecibo.cveCliente
		FROM ccontrarecibo				
		WHERE ccontrarecibo.noContrarecibo='".$numContrarecibo."' LIMIT 0,1;";
		
		$cliente = $bd->soloUno($sql);		
		$respuesta = "[{numCliente: '".$cliente."'}]";
		
		echo $respuesta;
	}
	break;	
	case 2:
	{
		$sql="SELECT DISTINCT(ccontrarecibo.cveContrarecibo),dedocta.cveCliente, ccliente.razonSocial, ccontrarecibo.importe, dedocta.folioDocumento, ".
			 "IF( dedocta.estatus =0,  'Pagada',  'Falta Pago' ) AS estado,". 			
			 "dedocta.fecha ".
			 "FROM ccontrarecibo,dedocta ".
			 "INNER JOIN ccliente ON dedocta.cveCliente = ccliente.cveCliente ".
			 "WHERE ccontrarecibo.cveCliente=dedocta.cveCliente AND ccontrarecibo.cveFactura=dedocta.folioDocumento	".
			 "AND dedocta.tipoEstadoCta	='cliente' AND dedocta.cveTipoDocumento='FAC' AND dedocta.estatus!=0 ".
			 "AND ccontrarecibo.cveContrarecibo IN(SELECT cveContrarecibo FROM ccontrarecibo ".
			 "WHERE noContrarecibo='".$numContrarecibo."')";	 
			 
		$campos = $bd->ExecuteFieldfn($sql);		
		
		$respuesta="[";
					 
		foreach ($campos as $campo){
				$respuesta .= "{cveCliente:'".$campo[1]."',razonSocial:'".$campo[2]."',monto:'".$campo[3]."',folioDocumento:'".$campo[4].
				"',estado:'".$campo[5]."',fecha:'".$campo[6]."',idContr:'".$campo[0]."'},";
		}
		$respuesta = substr($respuesta, 0, strlen($respuesta)-1);
		$respuesta .= "]";
	
		echo $respuesta;
	}
	break;			
	default:break;
}
	

?>
