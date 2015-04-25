<?php

/**
 * @author Jose Miguel Pantaleon
 * @copyright 2010
 */


	header("Content-Type: text/Text; charset=ISO-8859-1 Cache-Control: no-store, no-cache, must-revalidate");
	header("Expires: Fri, 14 Mar 1980 20:53:00 GMT"); //la pagina expira en fecha pasada
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); //ultima actualizacion ahora cuando la cargamos
	header("Cache-Control: no-cache, must-revalidate"); //no guardar en CACHE
	header("Pragma: no-cache"); //PARANOIA, NO GUARDAR EN CACHE
	include ("bd.php");
	$cve_estado = $_GET["cve_estado"];
	$opcion=$_GET['opcion'];
	if($opcion==0)
	{
		$sql="SELECT IFNULL(razonSocial,'Sin Definir')AS razon,cveDestino AS idDestino, descripcion AS des,IF(cdestinos.estatus=0,'Desactivado','Activado') AS estado
	FROM cdestinos
	LEFT JOIN ccorresponsales ON ccorresponsales.cveCorresponsal = cdestinos.cveCorresponsal ORDER BY descripcion ASC;";							

	}else if($opcion==1){
		$sql="SELECT IFNULL(razonSocial,'Sin Definir')AS razon,cveDestino AS idDestino, descripcion AS des,IF(cdestinos.estatus=0,'Desactivado','Activado') AS estado
	FROM cdestinos
	LEFT JOIN ccorresponsales ON ccorresponsales.cveCorresponsal = cdestinos.cveCorresponsal
	WHERE estado=".$cve_estado." ORDER BY descripcion ASC;";							
	}

	$campos = $bd->Execute($sql);
	$respuesta = "[";
	foreach($campos as $campo){
		$respuesta .= "{cveDestino: '" . $campo["idDestino"] . "', razon: '" . $campo["razon"]. "', descripcion: '" . $campo["des"]. "', estatus: '" . $campo["estado"] ."'},";
	}
	$respuesta = substr($respuesta, 0, strlen($respuesta)-1);
	$respuesta .= "]";
	echo $respuesta;

	
?>
