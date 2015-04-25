<?php
//header("Content-Type: text/Text; charset=utf-8 Cache-Control: no-store, no-cache, must-revalidate");
//include("conectar.php");

//if($wb_validity == ""){$wb_validity = 0;}
//Conectarse a la BD
//$link = conectarse();
//Inserta los registros en la BD waybill_mstr: Maestro de Guias
$slcLineaA=  $_POST["cveLineaArea"];
$txtGuiaAerea=  $_POST["guiaArea"];
$txtNumeroVuelo=  $_POST["noVuelo"];
$txtFechaVuelo=  $_POST["fechaVuelo"];
$txtRecepcioncye=  $_POST["recepcionCYE"];
$txtRemitente=  $_POST["nombreRemitente"];
$txtCalleR=  $_POST["calleRemitente"];
$txtTelefonoR=  $_POST["telefonoRemitente"];
$txtRfcR=  $_POST["rfcRemitente"];
$txtNombredo=  $_POST["estadoRemitente"];
$txtColR=  $_POST["coloniaRemitente"];
$txtMunR=  $_POST["municipioRemitente"];
$txtCodigoPr=  $_POST["codigoPR"];
$txtNombreDes=  $_POST["destinatario"];
$txtPiezas=  $_POST["piezas"];
$txtKg=  $_POST["kg"];
$txtVol=  $_POST["volumen"];
$txtVigencia=  $_POST["validezDias"];
$slcSucursal=  $_POST["sucursalDestino"];
$txtCalleD  =  $_POST["calleD"];
$txtCodigoPD=  $_POST["codigoPD"];
$txtColoniaD=  $_POST["ColoniaD"];
$txtMunicipioD=  $_POST["MunicipioD"];
$chkSello=  $_POST["Sello"];
$chkFirma=  $_POST["Firma"];
$chkRespaldo=  $_POST["Respaldo"];
$txtEstadoD=  $_POST["EstadoD"];
$txtTelefonoD=  $_POST["TelefonoD"];
$slcStatus=  $_POST["status"];
$txtRecibio=  $_POST["recibio"];
$slcTipoe=  $_POST["TipoEnvio"];
$slcRecoleccion=  $_POST["Recoleccion"];
$txtFechaA=  $_POST["llegadaacuse"];
$txtFechaEntrega=  $_POST["fechaEntrega"];
$txaObservaciones=  $_POST["observaciones"];
$cveGuia=  $_POST["cveGuia"];
$txtValord=$_POST["valorD"];
$vales=  $_POST["vales"];
$recibos=  $_POST["recibos"];
$facturas=$_POST["facturas"];
$cliente=$_POST["txtCodigoC"];
$cveDireccion=$_POST["cveDireccion"];
$reexpedicion=$_POST["chkReexpedicion"];
$usuario=$_POST["usuario"];
$empresa=$_POST["empresa"];
$empresa=str_replace ( "-", "'", $empresa);
$fecha = date("Y/m/d");
if((strlen($txtFechaEntrega) == 10 and $txtFechaEntrega != "0000/00/00") ){$slcStatus = "Entregada";}
if((strlen($txtFechaA) == 10 and $txtFechaA != "0000-00-00") ){$slcStatus = "Concluida";}
$sql1 = "INSERT INTO cguias (cveEmpresa ,cveSucursal ,cveGuia ,cveCliente,guiaArea ,cveLineaArea ,noVuelo ,fechaVuelo ,recepcionCYE ,nombreRemitente ,calleRemitente ,coloniaRemitente ,municipioRemitente ,estadoRemitente ,codigoPostalRemitente ,telefonoRemitente ,rfcRemitente ,sucursalDestino ,nombreDestinatario ,calleDestinatario ,coloniaDestinatario ,municipioDestinatario ,estadoDestinatario ,codigoPostaldestinatario ,piezas ,kg ,volumen ,validezDias ,status ,recibio ,llegadaacuse ,observaciones ,indicadorRespaldos ,sello ,firma ,fechaEntrega ,recoleccion ,tipoEnvio ,telefonoDestinatario,valorDeclarado,cveDireccion,reexpedicion,usuarioCreador,fechaCreacion)
VALUES (".$empresa.", '$cveGuia', '$cliente', '$txtGuiaAerea', '$slcLineaA', '$txtNumeroVuelo', '$txtFechaVuelo', '$txtRecepcioncye', '$txtRemitente', '$txtCalleR', '$txtColR', '$txtMunR', '$txtNombredo', '$txtCodigoPr', '$txtTelefonoR', '$txtRfcR', '$slcSucursal', '$txtNombreDes', '$txtCalleD', '$txtColoniaD', '$txtMunicipioD', '$txtEstadoD', '$txtCodigoPD', '$txtPiezas', '$txtKg', '$txtVol', '$txtVigencia', '$slcStatus', '$txtRecibio', '$txtFechaA', '$txaObservaciones', '$chkRespaldo', '$chkSello', '$chkFirma', '$txtFechaEntrega', '$slcRecoleccion', '$slcTipoe', '$txtTelefonoD','$txtValord','$cveDireccion','$reexpedicion','$usuario','$fecha')";
echo $sql1;
$conexion = mysql_connect("localhost","webcom","webcom") or die (mysql_error());
$db = mysql_select_db("cargayex",$conexion) or die (mysql_error());

$res1 = mysql_query($sql1,$conexion);
$my_error1 += mysql_error($conexion);


 $my_error1 = mysql_error($conexion);
 $sql="INSERT INTO cvalessoporte (cveEmpresa ,cveSucursal ,cveGuia ,valeSoporte,usuarioCreador,fechaCreacion )
 VALUES (".$empresa.", '$cveGuia', '$vales','$usuario','$fecha')";
  $res1 = mysql_query($sql,$conexion);
  $my_error1 += mysql_error($conexion);
  if ($facturas != "")
  {
  	$separar = explode('
',$facturas);
  foreach($separar as  $indice => $palabra){
  	$sql="INSERT INTO cfacturassoporte (cveEmpresa ,cveSucursal ,cveGuia ,facturaSoporte,usuarioCreador,fechaCreacion )
  VALUES (".$empresa.", '$cveGuia', '$palabra','$usuario','$fecha')";
  $res1 = mysql_query($sql,$conexion);
 $my_error1 += mysql_error($conexion);
 } 
 	
 }
  if ($recibos != "")
 {
 		$separar = explode('
',$recibos);
 foreach($separar as  $indice => $palabra){
 	$sql="INSERT INTO centregassoporte (cveEmpresa ,cveSucursal ,cveGuia ,entregasSoporte,usuarioCreador,fechaCreacion )
  VALUES (".$empresa.", '$cveGuia', '$palabra','$usuario','$fecha')";
   $res1 = mysql_query($sql,$conexion);
  $my_error1 += mysql_error($conexion);
  }	
  }


// Verifica si existe error en la sintaxis en MySql
if(!empty($my_error1)){
echo "Error: Sintaxis MySql, verifique";
echo "Error: $my_error1";

}
else {
echo "La guнa se ha registrado exitosamenteссс...";
}
mysql_close($conexion);
?>