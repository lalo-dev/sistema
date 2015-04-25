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
	$dests = $bd->ExecuteFieldn("cdicecontener","idContenido,titulo","titulo LIKE '$caracteres%' LIMIT 0,10");
	$respuesta = "<ul>";
	
	foreach ($dests as $dest)
	{
		$respuesta .= "<li>" . $dest[0] ." - ".$dest[1]. "</li>";
	}
	$respuesta .= "</ul>";
	echo $respuesta;
		
?>