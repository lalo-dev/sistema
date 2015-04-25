<?php
if (!isset($_SESSION)) {
session_start();
}
/**
 * @author miguel
 * @copyright 2009
 */
	session_start();
if (!isset($_SESSION["usuario_valido"]))
{
    header("Location: login.php");
}
$usuario=$_SESSION["usuario_valido"];
$empresa=$_SESSION["cveEmpresa"];
$sucursal=$_SESSION["cveSucursal"];
$razon = $_GET["razon"];
if($razon==""){$razon="Razon Social";}

?>
