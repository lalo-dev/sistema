<?php

	header("Content-Type: text/Text; charset=ISO-8859-1 Cache-Control: no-store, no-cache, must-revalidate");
	header("Expires: Fri, 14 Mar 1980 20:53:00 GMT"); //la pagina expira en fecha pasada
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); //ultima actualizacion ahora cuando la cargamos
	header("Cache-Control: no-cache, must-revalidate"); //no guardar en CACHE
	header("Pragma: no-cache"); //PARANOIA, NO GUARDAR EN CACHE

	//Primero obtener el permiso del Usuario
	session_start();
	$permiso=$_SESSION["permiso"];

	include ("bd.php");
	$operacion = $_GET["operacion"];

	switch ($operacion)
	{
			case 1:
				{
					//En este caso solo se regresará el valor, ya que al inciar sesión se obtiene la estación del Corresponsal
					$estacion=$_SESSION["estacion"];
					$respuesta .= "[{estacion: '" . $estacion."'}]";
					echo $respuesta;
	
				}
				break;
			default:
			break;
	}
				



?>
