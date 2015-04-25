<?php

/**
 * @author miguel
 * @copyright 2009
 */
include_once('conexion.php');
$tabla=$_GET["tabla"];
 if($tabla=="cliente")
 {
 	$tabla="ccontactoscliente";
 	$campoClave="cveCliente";
 }
 else
 {
 	$tabla="ccontactosproveedores";
 	$campoClave="cveCorresponsal";
 }
$wb_id =  $_POST["wb_id"];


//Borra el registro seleccionado de la tabla 
$sql1 = "delete from $tabla where cveContacto = '$wb_id'";

$res1 = mysql_query($sql1,$conexion);
$my_error1 = mysql_error($conexion);

// Verifica si existe error en la sintaxis en MySql
if(!empty($my_error1)){
echo "Error: Ocurrio un error ";
echo "Error: $my_error1";
}
else {
echo "Registro Eliminado Satisfactoriamente...";
}


?>