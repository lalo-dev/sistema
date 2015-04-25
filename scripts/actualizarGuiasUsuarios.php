<?php

	include("bd.php");


	$Sello        = $_POST["Sello"];
	$Firma        = $_POST["Firma"];
	$Respaldo     = $_POST["Respaldo"];
	$slcStatus       = $_POST["slcStatus"];
	$fechaEntrega = $_POST["fechaEntrega"];
	$cveGuia         = $_POST["cveGuia"];
	$usuario         = $_POST["usuario"];	
	$cveEstadoGuia   = $_POST["cveEstadoGuia"];
	$recibio     = $_POST["recibio"];
	$cveGuia         = $_POST["cveGuia"];

	//Checaremos, en caso de no cambiar el estado de la Guía checaremos si se ingreso una fecha de Entrega, para asignar estos estados respectivamente
	if($cveEstadoGuia==$slcStatus)
	{
		if ($fechaEntrega != "" && $fechaEntrega != "--")  //Significa que ingresaron una Fecha, por tanto sin importar el Estado a elegir, este será Entregada
    	{
			$fechas = explode('-', $fechaEntrega);
			if ($fechas[0] != "00")
			{
				$slcStatus = "Entregada";
			}
    	}
	}
//Conectarse a la BD
if($fechaEntrega=="--")
	$fechaEntrega="";
$sql1 = "UPDATE cguias SET status =	 '$slcStatus',recibio =	 '$recibio',sello =	 '$Sello',firma =	 '$Sello',fechaEntrega =	 '$fechaEntrega',indicadorRespaldos= '$Respaldo',usuarioModifico='$usuario',fechaModificacion=NOW()  WHERE cveGuia =	'$cveGuia'";

$sql1=utf8_decode($sql1);
$conexion = mysql_connect("localhost","webcom","webcom") or die (mysql_error());
$db = mysql_select_db("cargayex",$conexion) or die (mysql_error());

$res1 = mysql_query($sql1, $conexion);
$my_error1 = $my_error1.mysql_error($conexion);

// Verifica si existe error en la sintaxis en MySql
if (!empty($my_error1))
{
	echo "Error: Sintaxis MySql, verifique";
	echo "Error: $my_error1";
}
else
{  echo "La modificación se ha realizado exitosamente.";}

?>
