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


$empresa=str_replace ( "-", "'", $empresa);
$fecha = date("Y/m/d");
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
$empresaS=explode(',',$empresa);
		$sql1 = "UPDATE $tabla 
                SET nombre = '$txtNombre',
                contactoFacturacion='$contactoF',  
                apellidoPaterno = '$txtApellidoPaterno',
                apellidoMaterno = '$txtApellidoMaterno',
                puesto = '$txtCargo',
                email = '$txtMail',
                telefono = '$txtTelefonoContacto',
                lada = '$txtLada',
                celular = '$txtCelular',
                departamento = '$txtDepartamento',
                usuarioModifico='$usuario',
                fechaModificacion='$fecha' 
                WHERE cveContacto =$hdncvecontacto AND cveEmpresa=".$empresaS[0]." AND cveSucursal=".$empresaS[1];
$sql1=utf8_decode($sql1);
	
$res1 = mysql_query($sql1,$conexion);
$my_error1 = mysql_error($conexion);
// Verifica si existe error en la sintaxis en MySql
if(!empty($my_error1)){
echo "Error: Sintaxis MySql, verifique";
echo "Error: $my_error1";
}
else {
echo "La modificación se ha realizado exitosamente.";
}
mysql_close($conexion);

?>
