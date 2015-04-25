<?php

/**
 * @author miguel
 * @copyright 2009
 */
include_once('bd.php');
extract($_REQUEST);
//Limpiar Datos
$empresa=trim($empresa);
$cveCliente=trim($cveCliente);
$slcSucursal=trim($slcSucursal);
$txtCalle=trim($txtCalle);
$txtNumeroInterior=trim($txtNumeroInterior);
$txtNumeroExterior=trim($txtNumeroExterior);
$txtColonia=trim($txtColonia);
$slcMunicipios=trim($slcMunicipios);
$slcEstados=trim($slcEstados);
$txtCodigoPostal=trim($txtCodigoPostal);
$slcTiposDireccion=trim($slcTiposDireccion);
$slcPaises=trim($slcPaises);
$txtTelefono=trim($txtTelefono);
$usuario=trim($usuario);

$fecha = date("Y/m/d");
$empresa=str_replace ( "-", "'", $empresa);
$empresaS=explode(',',$empresa);
if($txtTabla=="cliente")
{
	$tabla="cdireccionescliente";
	$campoClave="cveCliente";
	$sqlConsulta="SELECT IFNULL(MAX(cveDireccion),0)+1 AS id FROM $tabla WHERE cveCliente='$cveCliente'";
}
else
{
	$tabla="cdireccionesprovedores";
	$campoClave="cveCorresponsal";
	$sqlConsulta="SELECT IFNULL(MAX(cveDireccion),0)+1 AS id FROM $tabla WHERE cveCorresponsal='$cveCliente'";
}

$txtNumeroD=$bd->soloUno($sqlConsulta);

$sql1="INSERT INTO $tabla (cveEmpresa ,cveSucursal , $campoClave,cveDireccion ,sucursalCliente ,calle ,numeroInterior ,numeroexterior ,colonia ,cveMunicipio ,cveEstado ,codigoPostal ,tipoDireccion ,cvePais ,telefono,usuarioCreador,fechaCreacion,estatus)
VALUES (".$empresa.", '$cveCliente',$txtNumeroD, '$slcSucursal', '$txtCalle', '$txtNumeroInterior','$txtNumeroExterior', '$txtColonia', '$slcMunicipios', '$slcEstados', '$txtCodigoPostal', '$slcTiposDireccion', '$slcPaises', '$txtTelefono','$usuario',NOW(),'1');";

$res1  = $bd->ExecuteNonQuery($sql1);

// Verifica si existe error en la sintaxis en MySql
if(!empty($res1))
{
	echo "Error: Sintaxis MySql, verifique";
	echo "Error: $my_error1";
}
else 
	echo "La Dirección se ha registrado exitosamente.";

?>
