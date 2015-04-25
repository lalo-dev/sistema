<?php

	include_once("libreriaGeneral.php");

	$opcion =  $_GET["opc"];

	switch($opcion){

		case 1:
		{	
			$fechaI=$_GET['fechaI'];
			$fechaF=$_GET['fechaF'];	
			
			$contador= restaFechas($fechaF,$fechaI)+1;
			
			echo "[{total: " . $contador."}]";
			
		}break;
		default:
		break;
	}

?>