<?php

/**
 * @author Jose Miguel Pantaleon
 * @copyright 2010
 */

$conexion = mysql_connect("localhost","webcom","webcom") or die (mysql_error());
$db = mysql_select_db("cargayex",$conexion) or die (mysql_error());


?>
