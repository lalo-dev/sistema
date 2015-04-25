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
	
	$opcion = $_GET["opc"];
	
	if($opcion==0){
		$tabla = $_GET["tabla"];
		$condicion = $_GET["condicion"];
		$sql="DELETE FROM $tabla WHERE $condicion ";
		$respuesta = $bd->ExecuteNonQuery($sql);
		echo "Se ha eliminado la guía exitosamente.";
	}

?>