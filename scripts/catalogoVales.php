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
	$cliente=$_GET["cliente"];

	if ($caracteres != ""){
		
		$filtro="INNER JOIN cguias ON cvalessoporte.cveGuia = cguias.cveGuia
		WHERE valeSoporte LIKE '$caracteres%' 
		AND cguias.cveCliente='$cliente'
		LIMIT 0,10";	
		
		$sql="SELECT DISTINCT valeSoporte FROM cvalessoporte ".$filtro.";";
		
		$dests = $bd->ExecuteFieldf($sql);
		$respuesta = "<ul>";
		foreach ($dests as $dest){
			$respuesta .= "<li>" . $dest . "</li>";
		}
		$respuesta .= "</ul>";
		echo $respuesta;
		
	}

?>