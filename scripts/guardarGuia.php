<?php

	//Inserta los registros en la BD waybill_mstr: Maestro de Guias
	
	$usuario=trim($_POST["usuario"]);
	$empresa=trim($_POST["empresa"]);

	$slcLineaA=  trim($_POST["cveLineaArea"]);
	$txtGuiaAerea=  trim($_POST["guiaArea"]);
	$txtNumeroVuelo=  trim($_POST["noVuelo"]);
	$txtFechaVuelo=  trim($_POST["fechaVuelo"]);
	$txtRecepcioncye=  trim($_POST["recepcionCYE"]);
	$txtRemitente=  trim($_POST["nombreRemitente"]);
	$txtCalleR=  trim($_POST["calleRemitente"]);
	$txtTelefonoR=  trim($_POST["telefonoRemitente"]);
	$txtRfcR=  trim($_POST["rfcRemitente"]);
	$txtNombredo=  trim($_POST["estadoRemitente"]);
	$txtColR=  trim($_POST["coloniaRemitente"]);
	$txtMunR=  trim($_POST["municipioRemitente"]);
	$txtCodigoPr=  trim($_POST["codigoPR"]);
	$txtNombreDes=  trim($_POST["destinatario"]);
	$txtPiezas=  trim($_POST["piezas"]);
	$txtKg=  trim($_POST["kg"]);
	$txtVol=  trim($_POST["volumen"]);
	$txtVigencia=  trim($_POST["validezDias"]);
	$slcSucursal=  trim($_POST["sucursalDestino"]);
	$txtCalleD  =  trim($_POST["calleD"]);
	$txtCodigoPD=  trim($_POST["codigoPD"]);
	$txtColoniaD=  trim($_POST["ColoniaD"]);
	$txtMunicipioD=  trim($_POST["MunicipioD"]);
	$chkSello=  trim($_POST["Sello"]);
	$chkFirma=  trim($_POST["Firma"]);
	$chkRespaldo=  trim($_POST["Respaldo"]);
	$txtEstadoD=  trim($_POST["EstadoD"]);
	$txtTelefonoD=  trim($_POST["TelefonoD"]);
	$slcStatus=  trim($_POST["status"]);
	$txtRecibio=  trim($_POST["recibio"]);
	$slcTipoe=  trim($_POST["TipoEnvio"]);
	$slcRecoleccion=  trim($_POST["Recoleccion"]);
	$txtFechaA=  trim($_POST["llegadaacuse"]);
	$txtFechaEntrega=  trim($_POST["fechaEntrega"]);
	$txtDContener=  trim($_POST["txtDContener"]);	
	$txaObservaciones=  utf8_decode(trim($_POST["observaciones"]));
	$observacionesC=  utf8_decode(trim($_POST["observacionesC"]));

	$cveGuia=  trim($_POST["cveGuia"]);
	$txtValord=trim($_POST["valorD"]);
	$vales=  trim($_POST["vales"]);
	$recibos=  trim($_POST["recibos"]);
	$facturas=trim($_POST["facturas"]);
	$cliente=trim($_POST["txtCodigoC"]);
	$cveDireccion=trim($_POST["cveDireccion"]);
	$reexpedicion=trim($_POST["Reexpedicion"]);
	$empresa=str_replace ( "-", "'", $empresa);

	$fecha = date("Y/m/d");
	$txthNombreDes=trim($_POST["txthNombreDes"]);
	$txthNombreDesP=trim($_POST["txthNombreDesP"]);
	$txthAlta=trim($_POST["txthAlta"]);
	
	$ctoGuia=trim($_POST["txtCtoGuia"]);
	
	if(is_numeric($cveGuia))
	{
		$cveGuiaInt = $cveGuia;
	}
	else
	{
		$cveGuiaInt = 0;		
	}

	//Aunque no haya habido un cambio de estado, si insertó una fecha de Entra o una de Acuse se cambiará el estado
	if(strlen($txtFechaEntrega) == 10 && $txtFechaEntrega != "") {$slcStatus = "Entregada";}
	if(strlen($txtFechaA) == 10 && $txtFechaA != "" ){$slcStatus = "Concluida";}
	
	$conexion = mysql_connect("localhost","webcom","webcom") or die (mysql_error());
	$db = mysql_select_db("cargayex",$conexion) or die (mysql_error());
	//mysql_query("SET NAMES 'utf8'");


	if($txthAlta==1){
		//Primero evaluaremos el txthNombreDes, para rectificar si es un registro Nuevo o uno ya existente

		if($txthNombreDes==0) //Nuevo
		{
			$sql="SELECT COUNT(*) AS total FROM cconsignatarios WHERE nombre='$txtNombreDes';";
			$res1 = mysql_query($sql,$conexion);
			while($row=mysql_fetch_assoc($res1)){
				$total=$row['total'];	
			}
			if($total>0)
			{
				echo "El nombre de destinatario ya existe (la guía no fue dada de alta).";
				exit();
			}
		
			$sql="SELECT IFNULL(MAX(cveConsignatario),0)+1 AS id FROM cconsignatarios";
			$res1 = mysql_query($sql,$conexion);
			while($row=mysql_fetch_assoc($res1)){
				$id=$row['id'];	
			}
			
			$sql="INSERT INTO cconsignatarios (cveEmpresa ,cveSucursal,cveConsignatario,estacion,nombre,estado,municipio,colonia,calle,codigoPostal,telefono,usuarioCreador,fechaCreacion)
			VALUES(".$empresa.",'".$id."','".$slcSucursal."','".$txtNombreDes."','".$txtEstadoD."','".$txtMunicipioD."','".$txtColoniaD."','".$txtCalleD."','".$txtCodigoPD."','".$txtTelefonoD."','".$usuario."',NOW())";
			
			$cveConsignatario=$id;

		}
		else  //Registrado
		{
		
			if($txthNombreDesP!=$txtNombreDes)
			{
				$sql="SELECT COUNT(*) AS total FROM cconsignatarios WHERE nombre='$txtNombreDes';";
				$res1 = mysql_query($sql,$conexion);
				while($row=mysql_fetch_assoc($res1)){
					$total=$row['total'];	
				}
				if($total>0)
				{
					echo "El nombre de destinatario ya existe (la guía no fue dada de alta).";
					exit();
				}
			}
			
			$id=$txthNombreDes;	
			$sql="UPDATE cconsignatarios SET
			estacion='$slcSucursal',
			nombre='$txtNombreDes',
			estado='$txtEstadoD',
			municipio='$txtMunicipioD',
			colonia='$txtColoniaD',
			calle='$txtCalleD',
			codigoPostal='$txtCodigoPD',
			telefono='$txtTelefonoD',
			usuarioModifico='$usuario',
			fechaModificacion=NOW()
			WHERE cveConsignatario=$txthNombreDes";
			
			$cveConsignatario=$txthNombreDes;
		
		}
	}else $cveConsignatario=0;
	

	$sql=utf8_decode($sql);	
	$res = mysql_query($sql,$conexion);
	$my_error += mysql_error($conexion);
	
	$txaObservaciones=utf8_encode($txaObservaciones); //SI no se cambia el formato marca error en la Actualización
	
	$sql1 = "INSERT INTO cguias (cveEmpresa ,cveSucursal ,cveGuia, cveGuiaInt ,cveCliente,guiaArea ,cveLineaArea ,noVuelo ,fechaVuelo ,recepcionCYE ,nombreRemitente ,calleRemitente ,coloniaRemitente ,municipioRemitente ,estadoRemitente ,codigoPostalRemitente ,telefonoRemitente ,rfcRemitente ,piezas ,kg ,volumen ,validezDias ,status ,recibio ,llegadaacuse ,observaciones ,indicadorRespaldos ,sello ,firma ,fechaEntrega ,recoleccion ,tipoEnvio ,valorDeclarado,cveDireccion,reexpedicion,usuarioCreador,fechaCreacion,estatus,cveConsignatario,contCarga,obsRemitente)
	VALUES (".$empresa.", '$cveGuia', $cveGuiaInt, '$cliente', '$txtGuiaAerea', '$slcLineaA', '$txtNumeroVuelo', '$txtFechaVuelo', '$txtRecepcioncye', '$txtRemitente', '$txtCalleR', '$txtColR', '$txtMunR', '$txtNombredo', '$txtCodigoPr', '$txtTelefonoR', '$txtRfcR', '$txtPiezas', '$txtKg', '$txtVol', '$txtVigencia', '$slcStatus', '$txtRecibio', '$txtFechaA', '$txaObservaciones', '$chkRespaldo', '$chkSello', '$chkFirma', '$txtFechaEntrega', '$slcRecoleccion', '$slcTipoe', '$txtValord','$cveDireccion','$reexpedicion','$usuario',NOW(),'1','$cveConsignatario','$txtDContener','$observacionesC')";
	caracteres($sql1);
	$sql1=utf8_decode($sql1);	
	$res1 = mysql_query($sql1,$conexion);
	$my_error1 += mysql_error($conexion);
	
	if($facturas!="")
		$facturas=chkArr($facturas);
	if($recibos!="")
		$recibos=chkArr($recibos);
	
	
	if ($vales != "")
	{
		$sql="INSERT INTO cvalessoporte (cveEmpresa ,cveSucursal ,cveGuia ,valeSoporte,usuarioCreador,fechaCreacion )
		VALUES (".$empresa.", '$cveGuia', '$vales','$usuario',NOW())";
		$res1 = mysql_query($sql,$conexion);
		$my_error1 += mysql_error($conexion);
	}

	if ($facturas != "")
	{
		$sql="INSERT INTO cfacturassoporte (cveEmpresa ,cveSucursal ,cveGuia ,facturaSoporte,usuarioCreador,fechaCreacion )
		VALUES (".$empresa.", '$cveGuia', '$facturas','$usuario',NOW())";
		$res1 = mysql_query($sql,$conexion);
		$my_error1 += mysql_error($conexion);
	}
	
	if ($recibos != "")
	{
		$sql="INSERT INTO centregassoporte (cveEmpresa ,cveSucursal ,cveGuia ,entregasSoporte,usuarioCreador,fechaCreacion )
		VALUES (".$empresa.", '$cveGuia', '$recibos','$usuario',NOW())";
		$res1 = mysql_query($sql,$conexion);
		$my_error1 += mysql_error($conexion);
	}
	
	
	//Actualizaremos ahora el costo de todas las guías con el mismo número de guía aérea
	if($txtGuiaAerea!='') //Si se ingreso guía , afectará a todas
	{
		if($ctoGuia=='')
			$ctoGuia=0;
			
		$sqlUpdate  = "UPDATE cguias SET costoGuia='$ctoGuia',costoGuiaParcial=0 WHERE guiaArea='$txtGuiaAerea';";
		$my_error2  = mysql_query($sqlUpdate,$conexion);	

		if($ctoGuia!=0)
		{
			$sqlConsulta="SELECT SUM(IF(kg>volumen,kg,volumen)) AS pesoTotal FROM cguias WHERE guiaArea='$txtGuiaAerea'";
			//Esta variable será nuestro 100% de peso, por lo tanto al división del costo, se hará sobre este peso
			$res1 = mysql_query($sqlConsulta,$conexion);
			while($row=mysql_fetch_assoc($res1))
			{	
				$totalPeso=$row['pesoTotal'];
			}
			
			if($totalPeso!=0){
				//Primero seleccionaremos las guías que cuentan con el mismo número de Guía Aérea
				$sqlConsulta="SELECT cveGuia,IF(kg>volumen,kg,volumen) AS peso FROM cguias WHERE guiaArea='$txtGuiaAerea' ORDER BY peso DESC;";
				$res1 		= mysql_query($sqlConsulta,$conexion);
	
				while($dato=mysql_fetch_assoc($res1))
				{
					$costoParcial=($dato['peso']*$ctoGuia)/$totalPeso;
					$sqlUpdate   = "UPDATE cguias SET costoGuiaParcial='$costoParcial' WHERE cveGuia='".$dato['cveGuia']."';";
					$res3        = mysql_query($sqlUpdate,$conexion);
				}
			}
		}	
	}
	
	// Verifica si existe error en la sintaxis en MySql
	if(!empty($my_error1)){
		echo "Error: Sintaxis MySql, verifique";
	}
	else {
		echo "La guía se ha registrado exitosamente.";
	}
	mysql_close($conexion);
	
	function caracteres($cadena){
		return htmlspecialchars($cadena,ENT_QUOTES,'UTF-8');
	}
	
	function chkArr($cadena){
		
		$cadena = str_replace("\n","",$cadena);		
		$nuevoarreglo=explode(",",$cadena);
		$final="";
		for($i=0;$i<count($nuevoarreglo);$i++)
		{
			if($nuevoarreglo[$i]!=""){
				$final=$final.$nuevoarreglo[$i].",";
			}
		}
		$final = substr($final, 0, strlen($final)-1);
		return $final;
	}
?>
