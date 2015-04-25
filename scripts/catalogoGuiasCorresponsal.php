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
$estacion=$_GET["estacion"];

	if ($caracteres != ""){
		switch($operacion){

			case 1://Consulta las guías que pertenecen al Correponsal por estación, Cada Corresponsal tiene una cta por estación
			{
				
				$condicion="INNER JOIN cconsignatarios ON
				cguias.cveConsignatario=cconsignatarios.cveConsignatario
				WHERE cconsignatarios.estacion='".$estacion."' AND facturada='0' AND status NOT IN('Carga Documentandose','Cancelada','Entregada','Concluida') AND (cveGuia LIKE '$caracteres%' OR nombreRemitente LIKE '$caracteres%') LIMIT 0,5";
				
				$sql="SELECT DISTINCT cveGuia,nombreRemitente FROM cguias $condicion";
				$dests = $bd->ExecuteFieldfn($sql);
			}
			break;	
		default:break;
		}
		
		$respuesta = "<ul>";
		if($operacion==1)
		{
			foreach ($dests as $dest){
				$respuesta .= "<li>". $dest[0] ." - ".$dest[1]. "</li>";
			}
		}
		$respuesta .= "</ul>";
		echo $respuesta;
		
	}
	
?>
