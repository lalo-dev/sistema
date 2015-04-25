<?php

/**
 * @author miguel
 * @copyright 2009
 */
include_once('conexion.php');

extract($_REQUEST);

//Limpiar Datos
$txtRazonSocial=trim($txtRazonSocial);
$txtNombreComenrcial=trim($txtNombreComenrcial);
$txtRfc=trim($txtRfc);
$txtPaginaWeb=trim($txtPaginaWeb);
$txtImpuesto=trim($txtImpuesto);
$slcMonedas=trim($slcMonedas);
$txtCondicionesP=trim($txtCondicionesP);
$estado=trim($estado);
$txtLada=trim($txtLada);
$txtTelefono=trim($txtTelefono);
$txtFax=trim($txtFax);
$txtCurp=trim($txtCurp);
$txtdiasFactura=trim($txtdiasFactura);
$txtDiasCobro=trim($txtDiasCobro);
$txtPlazo=trim($txtPlazo);
$txaRequisitosC=trim($txaRequisitosC);
$txtProveedor=trim($txtProveedor);
$txaFactura=trim($txaFactura);
$tipoCliente=trim($tipoCliente);
$usuario=trim($usuario);
$txtCodigo=trim($txtCodigo);


$fecha = date("Y/m/d");
$empresa=str_replace ( "-", "'", $empresa);
$empresaS=explode(',',$empresa);
$txaRequisitosC=str_replace("\n","[br]",$txaRequisitosC);
$txaRequisitosC=str_replace("\r","[br]",$txaRequisitosC);
$txaRequisitosC=str_replace("[br][br]","[br]",$txaRequisitosC);

$txaFactura=str_replace("\n","[br]",$txaFactura);
$txaFactura=str_replace("\r","[br]",$txaFactura);
$txaFactura=str_replace("[br][br]","[br]",$txaFactura);

//Se hará query para ver si está modificando la Razón Social del Cliente, para que en dado caso no se repita con una existente

$sql="(SELECT COUNT(*) AS existe,razonSocial FROM ccliente 
	  WHERE razonSocial='$txtRazonSocial'
	  AND cveEmpresa=".$empresaS[0]." AND cveSucursal=".$empresaS[1].")
	  UNION
	  (SELECT COUNT(*) AS existe,razonSocial FROM ccliente 
	  WHERE cveCliente = '$txtCodigo'
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
	echo utf8_encode("No se pudo realizar la Modificación; la razón social ya existe.");
}
else
{
	//Actualiza el folio del Cliente
	$sql1="UPDATE cfolios SET folio= '".$folioCliente."',usuarioModifico='$usuario',fechaModificacion=NOW()".
	" WHERE cveCliente = '".$txtCodigo."'";
	$res1 = mysql_query($sql1,$conexion);

	$sql1 = "UPDATE ccliente SET
	 razonSocial='$txtRazonSocial',
	 nombreComercial = '$txtNombreComenrcial' ,
	 rfc = '$txtRfc' ,
	 paginaWeb = '$txtPaginaWeb' ,
	 tipoCliente = '$rdoMoral',
	 cveImpuesto = '$txtImpuesto' ,
	 cveMoneda = '$slcMonedas' ,
	 condicionesPago = '$txtCondicionesP',
	 estatus = '$estado' ,
	 lada = '$txtLada' ,
	 telefono = '$txtTelefono' ,
	 fax = '$txtFax' ,
	 curp = '$txtCurp',
	 diasFactura = '$txtdiasFactura',
	 diasCobro = '$txtDiasCobro',
	 plazoCobro = '$txtPlazo',
	 requisitosCobro = '$txaRequisitosC',
	 noProveedor = '$txtProveedor',
	 revicionFactura = '$txaFactura',
	 cveTipoCliente='$tipoCliente',
	 usuarioModifico='$usuario',
	 fechaModificacion=NOW()
	 WHERE cveCliente = '$txtCodigo' AND cveEmpresa=".$empresaS[0]." AND cveSucursal=".$empresaS[1];
		 
		 	 
	$sql1=utf8_decode($sql1);	 
	$res1 = mysql_query($sql1,$conexion);
	$my_error1 = mysql_error($conexion);
	// Verifica si existe error en la sintaxis en MySql
	if(!empty($my_error1)){
		echo "Error: Sintaxis MySql, verifique";
	}
	else {
	   
	echo utf8_encode("La Modificación se ha realizado exitosamente.");
	}
}
mysql_close($conexion);

?>
