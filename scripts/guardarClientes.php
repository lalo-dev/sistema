<?php

/**
 * @author miguel
 * @copyright 2009
 */
include("bd.php");
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


$fecha = date("Y/m/d");
$empresa=str_replace ( "-", "'", $empresa);
$empresaS=explode(',',$empresa);
$txaRequisitosC=str_replace("\n","[br]",$txaRequisitosC);
$txaRequisitosC=str_replace("\r","[br]",$txaRequisitosC);
$txaRequisitosC=str_replace("[br][br]","[br]",$txaRequisitosC);

$txaFactura=str_replace("\n","[br]",$txaFactura);
$txaFactura=str_replace("\r","[br]",$txaFactura);
$txaFactura=str_replace("[br][br]","[br]",$txaFactura);


//Se hará query para ver si se ingresa una Razón Social del Cliente, para que no se repita con una existente

$sql="(SELECT COUNT(*) AS existe,razonSocial FROM ccliente 
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
	echo "No se pudo ingresar el cliente; la razón social ya existe.";
}
else{
	$sql="SELECT IFNULL(MAX(cveCliente),0)+1 AS id FROM ccliente";
	$res1 = mysql_query($sql,$conexion);
	while($row=mysql_fetch_assoc($res1))
	{
		$id=$row['id'];	
	}

	$sql1="INSERT INTO ccliente (cveEmpresa ,cveSucursal ,cveCliente ,razonSocial ,nombreComercial ,rfc ,paginaWeb ,tipoCliente ,cveImpuesto ,cveMoneda ,condicionesPago ,estatus ,lada ,telefono ,fax ,curp,diasFactura,diasCobro,plazoCobro,requisitosCobro,noProveedor,revicionFactura,cveTipoCliente,usuarioCreador,fechaCreacion )
	VALUES (".$empresa.", $id , '$txtRazonSocial', '$txtNombreComenrcial', '$txtRfc', '$txtPaginaWeb', '$rdoMoral', '$txtImpuesto', '$slcMonedas', '$txtCondicionesP',1, '$txtLada', '$txtTelefono', '$txtFax', '$txtCurp', '$txtdiasFactura','$txtDiasCobro','$txtPlazo','$txaRequisitosC','$txtProveedor','$txaFactura','$tipoCliente','$usuario',NOW())";
	$sql1=utf8_decode($sql1);
	$res1 = mysql_query($sql1,$conexion);
	$my_error1 = mysql_error($conexion);

	$qry="SELECT COUNT(*) AS existe, cveCliente FROM ccliente WHERE razonSocial='$txtRazonSocial' GROUP BY cveCliente";

	$exists = $bd->Execute($qry);
				foreach ($exists as $existe){
			
				if($existe["existe"]>0)
				{
					$sql1="INSERT INTO cfolios (cveEmpresa ,cveSucursal ,folio ,cveCliente,usuarioCreador,fechaCreacion )VALUES (".$empresa.", '".$folioCliente."', '".$existe["cveCliente"]."','$usuario',NOW());";

					$sql1=utf8_decode($sql1);
					$res1 = mysql_query($sql1,$conexion);
					$my_error1 = mysql_error($conexion);
				
				}
			
					}

	// Verifica si existe error en la sintaxis en MySql
	if(!empty($my_error1)){
	echo "Error: Sintaxis MySql, verifique";


	}
	else {
	echo "El cliente se ha registrado exitosamente.";
	}
}
mysql_close($conexion);


?>
