<?php

/**
 * @author miguel
 * @copyright 2010
 */

include("bd.php");

//Obtenemos los datos

$usuario     = $_POST["usuario"];
$empresas  = $_POST["empresa"];
$empresas  = str_replace("-","'",$empresas);
list($cveEmpresa,$cveSucursal)=explode(",",$empresas);

$slcMoneda=$_POST['slcMoneda'];
$slcTipoPago=$_POST['slcTipoPago'];
$slcBanco=$_POST['slcBanco'];
$txtDocumento=$_POST['txtDocumento'];

$contador = $_GET['contador'];
$tabla=$_GET['tabla'];
$cveCliente = $_GET['cveCliente'];


//YEAR(fechaCreacion)
$sql="SELECT folio+1  FROM cfoliosdocumentos WHERE tipoDocumento='PAG' ";
$folio=$bd->soloUno($sql);
				
for ($i = 1; $i <= $contador; $i++)
{
	$txtFolioDocumento=$_POST['txtFolioDocumento_' . $i];
	$txtCveTipoDocumento=$_POST['txtCveTipoDocumento_' . $i];
	$txtSaldo=$_POST['txtSaldo_' . $i];
	$txtPago=$_POST['txtPago_' . $i];
	
	//Ingresa en dedocta los datos del pago que se cobrará
	$sql="SELECT IFNULL(MAX(numeroMovimiento),0)+1 AS id FROM dedocta";
	$idNm=$bd->soloUno($sql);
			
	$sql1 = "INSERT INTO dedocta ".
			"(cveEmpresa,cveSucursal,numeroMovimiento,cveTipoDocumento,folioDocumento,montoBruto,montoIva,montoNeto,".
			"tipoDocumentoRef,saldo,estatus,cveCliente,cveMoneda,cveBanco,referencia,cveIva,documentoReferencia,sentido,".
			"fecha,transaccionBancaria,usuarioCreador,fechaCreacion,tipoEstadoCta) ".
			"VALUES ".
			"(".$cveEmpresa.",".$cveSucursal.",'".$idNm."','PAG','".$folio."','0','0','".$txtPago."','".$txtCveTipoDocumento."','0','0',".
			"'".$cveCliente."','".$slcMoneda."','".$slcBanco."','referencia',".
			"'0','".$txtFolioDocumento."','-1',NOW(),'".$txtDocumento."','".$usuario."','NOW()','".$tabla."');";
	$res1=$bd->ExecuteNonQuery($sql1);
	
	//Le quitamos las comas al número
	$txtSaldo=str_replace(",","",$txtSaldo)+0;
	$txtPago=str_replace(",","",$txtPago)+0;
	
	$saldo=$txtSaldo-$txtPago;
	$sql2 = "UPDATE dedocta SET saldo='".$saldo."' WHERE cveTipoDocumento='".$txtCveTipoDocumento."' AND folioDocumento='".$txtFolioDocumento.
			"' AND tipoEstadoCta='".$tabla."' ";
	$res2=$bd->ExecuteNonQuery($sql2);

	$error += $res1+$res2;
}

if (empty($error)){

	$sql3 = "UPDATE cfoliosdocumentos SET folio=folio+1 WHERE cveEmpresa='1' AND cveSucursal= '1' AND tipoDocumento='PAG'";
	$res3=$bd->ExecuteNonQuery($sql3);
}


if (!empty($res3))
	echo "Error: Ocurrió un error verifique.";
else
	echo "El pago se ha registrado exitosamente.";


?>
