<?php

/**
 * @author miguel
 * @copyright 2010
 */

include ("bd.php");
include_once('conexion.php');
$fechaPago = date("Y/m/d");
$usuario=$_POST["usuario"];
$empresa=$_POST["empresa"];
$empresa=str_replace ( "-", "'", $empresa);
$fecha = date("Y/m/d");
$folios = $bd->Execute("SELECT folio  FROM cfoliosdocumentos WHERE tipoDocumento='NCE' ");//deve de ir la condicion del año y la empresa
				
			foreach($folios as $folio){
				$resultado=$folio["folio"]+1;
							
				}
$txtPago=$_POST['txtPago'];
$txtFolioDocumento=$_POST['txtFolioDocumento'];
$txtSaldo=$_POST['txtSaldo'];
$cveCliente = $_GET['cveCliente'];

			$sql1 = "INSERT INTO dedocta (cveEmpresa ,cveSucursal  ,numeroMovimiento ,cveTipoDocumento ,folioDocumento ,montoBruto ,montoIva ,montoNeto ,tipoDocumentoRef ,saldo ,estatus ,cveCliente ,cveMoneda ,cveBanco ,referencia ,cveIva ,documentoReferencia ,sentido ,
fecha ,transaccionBancaria ,usuarioCreador,fechaCreacion )VALUES (".$empresa.", '', 'NCE', '$resultado', '0', '0', '$txtPago', 'FAC', '0', '0', '$cveCliente', '', '','referencia', '0', '$txtFolioDocumento', '-1', '$fechaPago' , '','$usuario','$fecha' );";
	$res = mysql_query($sql1, $conexion);
			$my_error = mysql_error($conexion);
			$saldo=$txtSaldo-$txtPago;
			$sql1 = "UPDATE dedocta SET saldo = '$saldo' WHERE cveEmpresa = '1' AND cveSucursal  = '1' AND cveTipoDocumento='FAC' AND folioDocumento='$txtFolioDocumento' ";

			$res = mysql_query($sql1, $conexion);
			$my_error = mysql_error($conexion);
			
$sql = "UPDATE cfoliosdocumentos SET folio = '$resultado' WHERE cveEmpresa = '1' AND cveSucursal  = '1' AND tipoDocumento='PAG'";

$res = mysql_query($sql, $conexion);
$my_error = mysql_error($conexion);

if (!empty($my_error1))
{
				echo "Error: Sintaxis MySql, verifique";
				echo "Error: $my_error1";

}
else
{
				echo "La nota de credito se ha registrado exitosamente...";
}
mysql_close($conexion);
?>