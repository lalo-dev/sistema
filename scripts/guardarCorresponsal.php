<?php

/**
 * @author miguel
 * @copyright 2009
 */
 include("bd.php");
 include_once('conexion.php');
extract($_REQUEST); 
//Limpiar Datos
$empresa=trim($empresa);
$txtRazonSocial=trim($txtRazonSocial);
$txtNombreComenrcial=trim($txtNombreComenrcial);
$txtRfc=trim($txtRfc);
$txtPaginaWeb=trim($txtPaginaWeb);
$rdoMoral=trim($rdoMoral);
$txtImpuesto=trim($txtImpuesto);
$slcMonedas=trim($slcMonedas);
$txtCondicionesP=trim($txtCondicionesP);
$txtLada=trim($txtLada);
$txtTelefono=trim($txtTelefono);
$txtFax=trim($txtFax);
$txtCurp=trim($txtCurp);
$usuario=trim($usuario);

$fecha = date("Y/m/d");
$empresa=str_replace ( "-", "'", $empresa);
$empresaS=explode(',',$empresa);

//Se hará query para ver si se ingresa una Razón Social del Cliente, para que no se repita con una existente

$sql="(SELECT COUNT(*) AS existe,razonSocial FROM ccorresponsales 
  	WHERE razonSocial='$txtRazonSocial'
	AND cveEmpresa=".$empresaS[0]." AND cveSucursal=".$empresaS[1].")";
	  

$i=1;  
$sql=utf8_decode($sql);
$res1 = mysql_query($sql,$conexion);
while($row=mysql_fetch_assoc($res1))
{	
	$total=$row['existe'];
}


if($total>0){						//Significa que se ha repetido la Razón Social
	echo "No se pudo ingresar el corresponsal; la razón social ya existe.";
}
else{
	$sql="SELECT IFNULL(MAX(cveCorresponsal),0)+1 AS id FROM ccorresponsales";
	$res1 = mysql_query($sql,$conexion);
	while($row=mysql_fetch_assoc($res1))
	{
		$id=$row['id'];	
	}

	$my_error1 = mysql_error($conexion);


	$sql1="INSERT INTO ccorresponsales (cveEmpresa ,cveSucursal ,cveCorresponsal ,razonSocial ,nombreComercial ,rfc ,paginaWeb ,tipoCliente ,cveImpuesto ,cveMoneda ,condicionesPago ,estatus ,lada ,telefono ,fax ,curp,usuarioCreador,fechaCreacion )
	VALUES (".$empresa.", $id , '$txtRazonSocial', '$txtNombreComenrcial', '$txtRfc', '$txtPaginaWeb', '$rdoMoral', '$txtImpuesto', '$slcMonedas', '$txtCondicionesP', '1', '$txtLada', '$txtTelefono', '$txtFax', '$txtCurp','$usuario',NOW())";

	$res1 = mysql_query($sql1,$conexion);
	$my_error1 = mysql_error($conexion);


	// Verifica si existe error en la sintaxis en MySql
	if(!empty($my_error1)){
		echo "Error: Sintaxis MySql, verifique";
	}
	else {
		echo "El corresponsal se ha registrado exitosamente.";
	}
}
mysql_close($conexion);


?>
