<?php
	include("bd.php");
	$slcLineaA = trim($_POST["cveLineaArea"]);
	$txtGuiaAerea = trim($_POST["guiaArea"]);
	$txtNumeroVuelo = trim($_POST["noVuelo"]);
	$txtFechaVuelo = trim($_POST["fechaVuelo"]);
	$txtRecepcioncye = trim($_POST["recepcionCYE"]);
	$txtRemitente = trim($_POST["nombreRemitente"]);
	$txtCalleR = trim($_POST["calleRemitente"]);
	$txtTelefonoR = trim($_POST["telefonoRemitente"]);
	$txtRfcR = trim($_POST["rfcRemitente"]);
	$txtNombredo = trim($_POST["estadoRemitente"]);
	$txtColR = trim($_POST["coloniaRemitente"]);
	$txtMunR = trim($_POST["municipioRemitente"]);
	$txtCodigoPr = trim($_POST["codigoPR"]);
	$txtNombreDes = trim($_POST["destinatario"]);
	$txtPiezas = trim($_POST["piezas"]);
	$txtKg = trim($_POST["kg"]);
	$txtVol = trim($_POST["volumen"]);
	$txtVigencia = trim($_POST["validezDias"]);
	$slcSucursal = trim($_POST["sucursalDestino"]);
	$txtCalleD = trim($_POST["calleD"]);
	$txtCodigoPD = trim($_POST["codigoPD"]);
	$txtColoniaD = trim($_POST["ColoniaD"]);
	$txtMunicipioD = trim($_POST["MunicipioD"]);
	$chkSello = trim($_POST["Sello"]);
	$chkFirma = trim($_POST["Firma"]);
	$chkRespaldo = trim($_POST["Respaldo"]);
	$txtEstadoD = trim($_POST["EstadoD"]);
	$txtTelefonoD = trim($_POST["TelefonoD"]);
	$slcStatus = trim($_POST["status"]);
	$txtRecibiov = trim($_POST["recibio"]);
	$slcTipoe = trim($_POST["TipoEnvio"]);
	$slcRecoleccion = trim($_POST["Recoleccion"]);
	$txtFechaA = trim($_POST["llegadaacuse"]);
	$txtFechaEntrega = trim($_POST["fechaEntrega"]);
	$txaObservaciones=  utf8_decode(trim($_POST["observaciones"]));
	$cveGuia = trim($_POST["cveGuia"]);
	$txtValord = trim($_POST["valorD"]);
	$facturas = trim($_POST["facturas"]);
	$cveFacturas = trim($_POST["cveFacturas"]);
	$recibos = trim($_POST["recibos"]);
	$vales = trim($_POST["vales"]);
	$cveRecibos = trim($_POST["hdncveEntregas"]);
	$cliente = trim($_POST["txtCodigoC"]);
	$cveDireccion = trim($_POST["cveDireccion"]);
	$reexpedicion = trim($_POST["Reexpedicion"]);
	$empresa=trim($_POST["empresa"]);
	$empresa=str_replace ( "-", "'", $empresa);
	$usuario = trim($_POST["usuario"]);
	$cveVale=trim($_POST["cveVale"]);
	$fecha = date("Y/m/d");
	$estatus = trim($_POST["estatus"]);
	$cveEstadoGuia = trim($_POST["cveEstadoGuia"]);
	$txthNombreDes=trim($_POST["txthNombreDes"]);
	$txthNombreDesP=trim($_POST["txthNombreDesP"]);
	$txthAlta=trim($_POST["txthAlta"]);
	$ctoGuia=trim($_POST["txtCtoGuia"]);
	
	$txtDContener=  trim($_POST["txtDContener"]);	
	$observacionesC=  utf8_decode(trim($_POST["observacionesC"]));	
	
	
	//Checaremos, en caso de no cambiar el estado de la Guía checaremos si se ingreso una fecha de Entrega o de Acuse, para asignar estos estados respectivamente
	if($cveEstadoGuia==$slcStatus)
	{
		if(strlen($txtFechaEntrega) == 10 && $txtFechaEntrega != "") //Significa que ingresaron una Fecha, por tanto sin importar el Estado a elegir, este será con con cita para Entrega
    	{
			$fechas = explode('/', $txtFechaEntrega);
			if ($fechas[0] != "00")
			{
				$slcStatus = "Entregada";
			}
    	}
		if (strlen($txtFechaA) == 10 && $txtFechaA != "" ) //Significa que ingresaron una Fecha, por tanto sin importar el Estado a elegir, este será Concluida
    	{
			$fechas = explode('/', $txtFechaA);
			if ($fechas[0] != "00")
			{
				$slcStatus = "Concluida";
			}
    	}
	}

	//Conectarse a la BD
	
	
	$conexion = mysql_connect("localhost","webcom","webcom") or die (mysql_error());
	$db = mysql_select_db("cargayex",$conexion) or die (mysql_error());

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
				echo "El nombre de destinatario ya existe (la guía no fue modificada).";
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
					echo "El nombre de destinatario ya existe (la guía no fue modificada).";
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



	$res = mysql_query($sql, $conexion);
	$my_error = $my_error.mysql_error($conexion);
	
	//Ahora se preocede con la modificación de la guía
	$txaObservaciones=utf8_encode($txaObservaciones); //SI no se cambia el formato marca error en la Actualización
	
	
	//Si no hay no de guía hay que cerciorarse de que entes no tenía alguno regisrtrado
	if($txtGuiaAerea=='')
	{
		$sql  = "SELECT IFNULL(guiaArea,'') AS guiaArea FROM cguias WHERE cveGuia='".$cveGuia."';";
		$res1 = mysql_query($sql,$conexion);
		while($row=mysql_fetch_assoc($res1))
		{
			$guiaA=$row['guiaArea'];	
		}
	}
		
	$sql1 = "UPDATE cguias SET estatus='$estatus',cveCliente='$cliente',guiaArea =	 '$txtGuiaAerea',cveLineaArea =	 '$slcLineaA',noVuelo =	 '$txtNumeroVuelo',fechaVuelo =	   
	'$txtFechaVuelo',recepcionCYE =	 '$txtRecepcioncye',nombreRemitente =	 '$txtRemitente',calleRemitente =	 '$txtCalleR',coloniaRemitente =	 '$txtColR',
	municipioRemitente =	 '$txtMunR',estadoRemitente =	 '$txtNombredo',codigoPostalRemitente =	 '$txtCodigoPr',telefonoRemitente =	 '$txtTelefonoR',
	rfcRemitente =	 '$txtRfcR',piezas =	 '$txtPiezas',kg =	 '$txtKg',volumen =	 '$txtVol',validezDias =	 '$txtVigencia',status =	 '$slcStatus',
	recibio =	 '$txtRecibiov',llegadaacuse =	 '$txtFechaA',observaciones =	 '$txaObservaciones',indicadorRespaldos =	 '$chkRespaldo',sello =	 '$chkSello',firma =	 '$chkFirma',fechaEntrega =	 '$txtFechaEntrega',recoleccion =	 '$slcRecoleccion',tipoEnvio =	 '$slcTipoe',valorDeclarado='$txtValord',cveConsignatario='$cveConsignatario',cveDireccion='$cveDireccion',reexpedicion='$reexpedicion',usuarioModifico='$usuario',fechaModificacion=NOW(),costoGuia='$ctoGuia',contCarga='$txtDContener',obsRemitente='$observacionesC'  WHERE cveGuia =	'$cveGuia'";
	
	$sql1=utf8_decode($sql1);

	$res1 = mysql_query($sql1, $conexion);
	$my_error1 = $my_error1.mysql_error($conexion);
	
	if($facturas!="")
		$facturas=chkArr($facturas);
	if($recibos!="")
		$recibos=chkArr($recibos);

	$my_error1="";

		$my_error1 = $my_error1.facturasEnvios($cveGuia, "cfacturassoporte", "facturaSoporte", $facturas,$cveFacturas, "cveFacturaS");
		
		$my_error1 = $my_error1.facturasEnvios($cveGuia, "cvalessoporte", "valeSoporte", $vales,$cveVale, "cveValeS");

		$my_error1 = $my_error1.facturasEnvios($cveGuia, "centregassoporte", "entregasSoporte", $recibos,$cveRecibos, "cveEntregaS");
	

	if($ctoGuia=='')
		$ctoGuia=0;
			
	//Actualizaremos ahora el costo de todas las guías con el mismo número de guía aérea
	if($txtGuiaAerea!='') //Si se ingreso guía , afectará a todas
	{			
		$sqlUpdate  = "UPDATE cguias SET costoGuia='$ctoGuia',costoGuiaParcial=0 WHERE guiaArea='$txtGuiaAerea';";
		$my_error2  = mysql_query($sqlUpdate,$conexion);	

		if($ctoGuia!=0)
		{
			$sqlConsulta="SELECT SUM(IF(kg>volumen,kg,volumen)) AS pesoTotal FROM cguias WHERE guiaArea='$txtGuiaAerea';";
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
					$sqlUpdate  = "UPDATE cguias SET costoGuiaParcial='$costoParcial' WHERE cveGuia='".$dato['cveGuia']."';";
					$my_error2  = mysql_query($sqlUpdate,$conexion);
				}
			}
		}
	}
	else  
	{
		if($guiaA!='') //Si ya había algo registrado y ahora se elimino, hay que afectar los costos de las guías con ese num de guía
		{
			//Primero actualizar el costo de la guía específica
			$sqlUpdate  = "UPDATE cguias SET costoGuiaParcial=0 WHERE cveGuia='$cveGuia';";
			$my_error2  = mysql_query($sqlUpdate,$conexion);
				
			$ctoConsultado=0;
			
			$sql  = "SELECT costoGuia FROM cguias WHERE guiaArea='".$guiaA."' LIMIT 1;";
			$res1 = mysql_query($sql,$conexion);
			while($row=mysql_fetch_assoc($res1))
			{
				$ctoConsultado=$row['costoGuia'];	
			}
			
			if($ctoConsultado!=0)
			{
				$sqlUpdate  = "UPDATE cguias SET costoGuia='$ctoConsultado',costoGuiaParcial=0 WHERE guiaArea='$guiaA';";
				$my_error2  = mysql_query($sqlUpdate,$conexion);	

			
				$sqlConsulta="SELECT SUM(IF(kg>volumen,kg,volumen)) AS pesoTotal FROM cguias WHERE guiaArea='$guiaA';";
				//Esta variable será nuestro 100% de peso, por lo tanto al división del costo, se hará sobre este peso
				$res1 = mysql_query($sqlConsulta,$conexion);
				while($row=mysql_fetch_assoc($res1))
				{	
					$totalPeso=$row['pesoTotal'];
				}
				
				if($totalPeso!=0){
					//Primero seleccionaremos las guías que cuentan con el mismo número de Guía Aérea
					$sqlConsulta="SELECT cveGuia,IF(kg>volumen,kg,volumen) AS peso FROM cguias WHERE guiaArea='$guiaA' ORDER BY peso DESC;";
					$res1 		= mysql_query($sqlConsulta,$conexion);
		
					while($dato=mysql_fetch_assoc($res1))
					{
						$costoParcial=($dato['peso']*$ctoConsultado)/$totalPeso;
						$sqlUpdate  = "UPDATE cguias SET costoGuiaParcial='$costoParcial' WHERE cveGuia='".$dato['cveGuia']."';";
						$my_error2  = mysql_query($sqlUpdate,$conexion);
					}
				}
			}
		}
	}
	
	// Verifica si existe error en la sintaxis en MySql
	if (!empty($my_error1))
	{
		echo "Error: Sintaxis MySql, verifique";
	}
	else
	{ echo "La modificación se ha realizado exitosamente."; }

	function facturasEnvios($guia, $tabla, $campo, $datos, $claves, $campoLlave)
	{
		//Conexion a la bd
		$conexion = mysql_connect("localhost","webcom","webcom") or die (mysql_error());
		$db = mysql_select_db("cargayex",$conexion) or die (mysql_error());

		if($claves!='')
		{
			if($datos=='')
				$sql = "DELETE FROM $tabla WHERE $campoLlave ='" .$claves . "' and cveGuia='$guia'";
			else
				$sql = "UPDATE $tabla SET $campo = '" . $datos . "',usuarioModifico='$usuario',fechaModificacion=NOW() WHERE $campoLlave ='" .
				       $claves . "' and cveGuia='$guia'";
		}
		else
		{
			$sql = "INSERT INTO $tabla (cveEmpresa ,cveSucursal ,cveGuia,$campo,usuarioCreador,fechaCreacion)
					VALUES ('1', '1', '$guia', '" . $datos . "','$usuario',NOW())";
		}
		
		//Dividimos las cadenas y las contamos		
		$res1 = mysql_query($sql,$conexion);
		$my_error1 = mysql_error($conexion);
	$my_error1="";
		return $my_error1;
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
