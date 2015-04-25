<?php

/**
 * @author miguel
 * @copyright 2010
 */

include ("bd.php");

//Cachamos valores
$folioFactura = $_POST["folioFactura"];
$idCliente    = $_POST["idCliente"];
$saldoPen     = $_POST["saldo"];
$pago         = $_POST["pago"];
$impBruto     = $_POST["impBruto"];
$retencion    = $_POST["retencion"];
$porRetencion = $_POST["porRetencion"];
$iva          = $_POST["iva"];
$descripcion  = $_POST["descripcion"];
$porIva       = $_POST["porIva"];
$usuario      = $_POST["usuario"];
$empresa      = $_POST["empresa"];
$empresa      = str_replace("-","'",$empresa);
$opcion       = $_GET["opc"];

//Para agregar registro
if($opcion==0){

	//Obtenemos el folio de la nota de crédito
	$sql="SELECT folio+1 FROM cfoliosdocumentos WHERE tipoDocumento='NOTAS'";
	$folio = $bd->soloUno($sql);
	
	//Obtenemos el número siguiente de movimiento
	$sql="SELECT IFNULL(MAX(numeroMovimiento),0)+1 AS id FROM dedocta";
	$idNm=$bd->soloUno($sql);
			
	//Ingresar registro en dedocta
	$sql1 = "INSERT INTO dedocta ".
			"(cveEmpresa,cveSucursal,numeroMovimiento,cveTipoDocumento,tipoEstadoCta,folioDocumento,montoBruto,montoIva,montoNeto,tipoDocumentoRef,saldo,".
			"estatus,cveCliente,cveMoneda,cveBanco,referencia,cveIva,documentoReferencia,sentido,fecha,transaccionBancaria ,usuarioCreador,fechaCreacion) ".
			"VALUES ".
			"(".$empresa.",'$idNm','NCE','cliente','$folio','0','0','$pago','FAC','0','0','$idCliente','','','referencia','0','$folioFactura',".
			"'-1',NOW(),'','$usuario',NOW());";
	$res1=$bd->ExecuteNonQuery(utf8_decode($sql1));	

	if(empty($res1))
	{		
		list($noEmpresa,$noSucursal)=explode(",",$empresa);
		//Se actualiza el saldo de la deuda de la factura
		$saldo=$saldoPen-$pago;
		
		$sql2 = "UPDATE dedocta SET saldo='$saldo' WHERE cveEmpresa=$noEmpresa AND cveSucursal=$noSucursal ".
				"AND cveTipoDocumento='FAC' AND folioDocumento='$folioFactura' ";
		$res2=$bd->ExecuteNonQuery(utf8_decode($sql2));

		if(empty($res2))
		{
			//Se actualiza el folio del documento Pagos y el de Notas
			$sql3 = "UPDATE cfoliosdocumentos SET folio = folio+1 WHERE cveEmpresa = $noEmpresa AND cveSucursal  = $noSucursal AND tipoDocumento='PAG'";
			$res3=$bd->ExecuteNonQuery(utf8_decode($sql3));

			$sql4 = "UPDATE cfoliosdocumentos SET folio = folio+1 WHERE cveEmpresa = $noEmpresa AND cveSucursal  = $noSucursal AND tipoDocumento='NOTAS'";
			$res4=$bd->ExecuteNonQuery(utf8_decode($sql4));
		
			if(empty($res4))
			{
				//Se ingresa la nota de crédito en la base
				$sql5 = "INSERT INTO cnotas ".
						"(cveEmpresa,cveSucursal,noNota,cveCliente,cveFactura,saldo,importe,iva,retencion,porImpuesto,porRetencion,importeTotal,".
						"descripcion,usuarioCreador,fechaCreacion) ".
						"VALUES ".
						"(".$empresa.",'$folio','$idCliente','$folioFactura','$saldoPen','$impBruto','$iva','$retencion','$porIva','$porRetencion','$pago',".
						"'$descripcion','$usuario',NOW())";
				$res5=$bd->ExecuteNonQuery(utf8_decode($sql5));
				
				if(!empty($res5))
					$error="error";
				
			}else $error="error";
		}else $error="error";
	}else $error="error";
	
	if (!empty($error))
		echo "Error: Inténtelo más tarde.";
	else
		echo "La nota de crédito se ha registrado exitosamente.";
}
else
{
	
	$noNota         = $_POST["numNota"];
	
	//Se ingresa la nota de crédito en la base
	$sql1 = "UPDATE cnotas SET ".
			"saldo='$saldoPen',".
			"importe='$impBruto',".
			"iva='$iva',".
			"retencion='$retencion',".
			"porImpuesto='$porIva',".
			"porRetencion='$porRetencion',".
			"importeTotal='$pago',".
			"descripcion='$descripcion',".
			"usuarioModifico='$usuario',".
			"fechaModificacion=NOW() ".
			"WHERE noNota='$noNota'";
	$res1=$bd->ExecuteNonQuery(utf8_decode($sql1));
		
	if(empty($res1))
	{		
		list($noEmpresa,$noSucursal)=explode(",",$empresa);
		//Checar si se hará algún cambio en la deuda
		$saldoDeuda    = $_POST["saldoDeuda"];
		$aumento       = $_POST["aumento"];
		
		if($aumento==1)
		{
			$sql2 = "UPDATE dedocta SET saldo=saldo-$saldoDeuda WHERE cveEmpresa=$noEmpresa AND cveSucursal=$noSucursal ".
					"AND tipoEstadoCta='cliente' AND cveTipoDocumento='FAC' AND folioDocumento='$folioFactura' ";
			$res2=$bd->ExecuteNonQuery(utf8_decode($sql2));
			if(!empty($res2))
				$error="error";
	
		}
		else if($aumento==2)
		{
			$sql2 = "UPDATE dedocta SET saldo=saldo+$saldoDeuda WHERE cveEmpresa=$noEmpresa AND cveSucursal=$noSucursal ".
					"AND tipoEstadoCta='cliente' AND cveTipoDocumento='FAC' AND folioDocumento='$folioFactura' ";
			$res2=$bd->ExecuteNonQuery(utf8_decode($sql2));
			if(!empty($res2))
				$error="error";	
		}
		if(empty($res2))	
		{
			$sql3 = "UPDATE dedocta SET montoNeto=$pago WHERE cveEmpresa=$noEmpresa AND cveSucursal=$noSucursal ".
				"AND tipoEstadoCta='cliente' AND cveTipoDocumento='NCE' AND documentoReferencia='$folioFactura' ".
				"AND folioDocumento='$noNota' ";
			$res3=$bd->ExecuteNonQuery(utf8_decode($sql3));
			if(!empty($res3))
				$error="error";
	
		}	
	
	}else $error="error";	
	
	if (!empty($error))
		echo "Error: Inténtelo más tarde.";
	else
		echo "La nota de crédito se ha modificado exitosamente.";
}
?>
