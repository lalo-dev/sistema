<?php

/**
 * @author miguel
 * @copyright 2009
 */
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

//Se hare query para ver si está modificando la Razón Social del Cliente, para que en dado caso no se repita con una existente

$sql="(SELECT COUNT(*) AS existe,razonSocial FROM ccorresponsales
	  WHERE razonSocial='$txtRazonSocial'
	  AND cveEmpresa=".$empresaS[0]." AND cveSucursal=".$empresaS[1].")
	  UNION
	  (SELECT COUNT(*) AS existe,razonSocial FROM ccorresponsales 
	  WHERE cveCorresponsal = '$wb_id'
	  AND cveEmpresa=".$empresaS[0]." AND cveSucursal=".$empresaS[1]." )";  
	  
	  
$i=1;  
$sql=utf8_decode($sql);
$res1 = mysql_query($sql,$conexion);
$total=mysql_num_rows($res1);
if($total!=1){								//Significa que pudo haberse repetido la Razón Social
	while($row=mysql_fetch_assoc($res1))
	{
		if($i==1)
		{ $existe1=$row['existe']; $razon1=$row['razonSocial'];}
		else
		{ $existe2=$row['existe']; $razon2=$row['razonSocial'];}
		$i++;
	}
}

if(($total!=0)&&($razon2!=$txtRazonSocial)&&($existe1!=0)) //Significa que cambió el valor del RFC e intrujo uno igual, a otro existente
{
	echo "No se pudo realizar la Modificación; la razón social ya existe.";
}
else
{
	$sql1 = "UPDATE ccorresponsales SET
	 razonSocial='$txtRazonSocial',
	 nombreComercial = '$txtNombreComenrcial' ,
	 rfc = '$txtRfc' ,
	 paginaWeb = '$txtPaginaWeb' ,
	 tipoCliente = '$rdoMoral',
	 cveImpuesto = '$txtImpuesto' ,
	 cveMoneda = '$slcMonedas' ,
	 condicionesPago = '$txtCondicionesP',
	 lada = '$txtLada' ,
	 telefono = '$txtTelefono' ,
	 fax = '$txtFax' ,
	 curp = '$txtCurp',				 
	 usuarioModifico='$usuario',
	 fechaModificacion=NOW(),
	 estatus='$estado'
	 WHERE cveCorresponsal = '$wb_id'" ;
	
	$sql1=utf8_decode($sql1);
	$res1 = mysql_query($sql1,$conexion);
	$my_error1 = mysql_error($conexion);
	// Verifica si existe error en la sintaxis en MySql
	if(!empty($my_error1)){
	echo "Error: Sintaxis MySql, verifique";

	}
	else {
	   
	echo "La Modificación se ha realizado exitosamente.";
	}
}
mysql_close($conexion);

?>
