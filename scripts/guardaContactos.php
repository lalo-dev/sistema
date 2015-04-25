<?php

/**
 * @author miguel
 * @copyright 2009
 */
include_once('conexion.php');
extract($_REQUEST);
//Limpiar Datos
$empresa=trim($empresa);
$cveCliente=trim($cveCliente);
$slcSucursal=trim($slcSucursal);
$txtNombre=trim($txtNombre);
$txtApellidoPaterno=trim($txtApellidoPaterno);
$txtApellidoMaterno=trim($txtApellidoMaterno);
$txtCargo=trim($txtCargo);
$txtMail=trim($txtMail);
$txtTelefonoContacto=trim($txtTelefonoContacto);
$txtLada=trim($txtLada);
$txtCelular=trim($txtCelular);
$txtDepartamento=trim($txtDepartamento);
$contactoF=trim($contactoF);
$usuario=trim($usuario);


$fecha = date("Y/m/d");
$empresa=str_replace ( "-", "'", $empresa);
$empresaS=explode(',',$empresa);
$tabla=$_GET["tabla"];
if($tabla=="cliente")
 {
 	$tabla="cdireccionescliente";
    $tablaContactos="ccontactoscliente";
 	$campoClave="cveCliente";
 }
 else
 {
 	$tabla="cdireccionesprovedores";
    $tablaContactos="ccontactosproveedores";
 	$campoClave="cveCorresponsal";
 }
$sql1="INSERT INTO $tablaContactos (cveEmpresa ,cveSucursal  ,$campoClave ,sucursalCliente ,cveContacto ,nombre ,apellidoPaterno ,apellidoMaterno ,puesto ,email ,telefono ,lada ,celular ,departamento ,contactoFacturacion,usuarioCreador,fechaCreacion )
VALUES (".$empresa.",'$cveCliente','$slcSucursal',NULL,'$txtNombre','$txtApellidoPaterno', '$txtApellidoMaterno','$txtCargo','$txtMail','$txtTelefonoContacto','$txtLada', '$txtCelular', '$txtDepartamento', '$contactoF','$usuario',NOW())";
$sql1=utf8_decode($sql1);
$res1 = mysql_query($sql1,$conexion);
$my_error1 = mysql_error($conexion);


// Verifica si existe error en la sintaxis en MySql
if(!empty($my_error1)){
	echo "Error: Sintaxis MySql, verifique";
}
else {
	echo "El contacto se ha registrado exitosamente.";
}
mysql_close($conexion);

?>
