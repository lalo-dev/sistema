<?php

/**
 * @author miguel
 * @copyright 2009
 */
$campo =  $_POST["nombreCampo1"];
$valor =  $_POST["Campo1"];
$tabla =  $_POST["tabla"];

$sql1 = "delete from $tabla where $campo = '$valor'";

$conexion = mysql_connect("localhost","root","") or die (mysql_error());
$db = mysql_select_db("cargayex",$conexion) or die (mysql_error());
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