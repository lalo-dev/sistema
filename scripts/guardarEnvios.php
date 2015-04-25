<?php

	include ("bd.php");
	include("libreriaGeneral.php");
	
	$contador = trim($_GET['contador']);
	$nuevoFolio = $txtFolio - 1;
	$valesAbierta = trim($_POST['valesAbierta']);	
	$cveCliente = trim($_POST['txtcveCliente']);
	$cveFactura = trim($_POST['txtFolioFactura']);
	$tmpFactura=trim($_POST['txtFolioFactura']);	
	$usuario=trim($_POST['usuario']);
	$txttotalTotal = trim($_POST['txttotalTotal']);
	$empresa=trim($_POST['empresa']);
	
	//Generales
	$cveRetencion=trim($_POST['cveRetencion']);	
	$cveImpuesto=trim($_POST['impuesto']);	  

	$empresa=str_replace ( "-", "'", $empresa);
	$empresaS=explode(',',$empresa);
	$fecha = date("Y-m-d");
	$valores = "";
	$campos[2]="fecha";
	$campos[3]="noGuia";
	$campos[4]="remicion";
	$campos[5]="planificacion";
	$campos[6]="destino";
	$campos[7]="destinatario";
	$campos[8]="observacion";
	$campos[9]="valorDeclarado";
	$campos[10]="piezas";
	$campos[11]="peso";
	$campos[12]="tarifa";
	$campos[13]="flete";
	$campos[14]="seguro";
	$campos[15]="acuse";
	$campos[16]="importe";
	$campos[17]="iva";
	$campos[18]="subtotal";
	$campos[19]="retencion";
	$campos[20]="total";
	$campos[21]="observaciones";
	
	if($valesAbierta!=""){
		$datos=split(",",$valesAbierta);
		$a=0;
		for($i=0;$i<(count($datos));$i++)
		{
			if($datos[$i]!=""){
				$datos2[$a]=$datos[$i];
				$a++;
			}
		}
		$valesAbierta = implode(",", $datos2);
	}
	
	$update='';
	
	
	for ($i = 2; $i < 21; $i++)
	{
		if ($_POST['condicion_' . $i] == "on")
		{ $valor = 1; }
		else
		{ $valor = 0; }
		$valores = $valores . "'" . $valor . "', ";
		$update=$update.$campos[$i]."='".$valor."',";
	}

	if ($_POST['condicion_21'] == "on")
	{ $valor = 1; }
	else
	{ $valor = 0; }
	
	$valores = $valores . "'" . $valor . "'";
	$update=$update.$campos[$i]."='".$valor."',";
	
	$txtTope= $_POST['txtTope'];
	$txtTopeGral= $_POST['txtTope'];
	$principal=$cveFactura;

	$cuenta=0;
	$tmp = 1;
	$factura=0;
	$totalseguro=0;

	//Toma los datos de los clientes, de la primera dirección que esté registrada
	$myqury = "SELECT ccliente.rfc,ccliente.razonSocial,cdireccionescliente.cveSucursal,cdireccionescliente.cveDireccion,cdireccionescliente.calle, cdireccionescliente.codigoPostal,cdireccionescliente.colonia,cdireccionescliente.numeroexterior, cdireccionescliente.numeroInterior,cdireccionescliente.cveEstado,cdireccionescliente.cveMunicipio FROM cdireccionescliente INNER JOIN ccliente ON cdireccionescliente.cveCliente=ccliente.cveCliente WHERE cdireccionescliente.cveCliente='$cveCliente' AND cdireccionescliente.cveEmpresa=".$empresaS[0]." AND cdireccionescliente.cveSucursal=".$empresaS[1]." ORDER BY cdireccionescliente.cveDireccion LIMIT 1;";
	
	$nombres = $bd->Execute($myqury);
	foreach ($nombres as $nombre)
	{
		$razonSocial = $nombre["razonSocial"];
		$rfc = $nombre["rfc"];
		$sucursalCliente = $nombre["cveSucursal"];
		$calle = $nombre["calle"];
		$numeroInterior = $nombre["numeroInterior"];
		$numeroexterior = $nombre["numeroexterior"];

		$colonia = $nombre["colonia"];
		$cveMunicipio = $nombre["cveMunicipio"];
		$cveEstado = $nombre["cveEstado"];
		$codigoPostal = $nombre["codigoPostal"];
	}
	
	$totalGeneral=0;
	$totalfac=1;
	$sumaImporte=0;
	$sumaRetencion=0;
	$sumaIva=0;
	$sumaSubtotal=0;
	$sumaTotal=0;
	$primeravez=0;
	
	for($j=1;$j<($contador+1);$j++)
	{
		$a=$j+1;
		$generar=false;
		//Tomamos los datos de las guías
		$cveGuia = $_POST['txtGuia_' . $j];
		$piezas = $_POST['txtpiezas_' . $j];
		$fechaEntrega = $_POST['txtFecha_' . $j];
		$facturaRemicion = $_POST['txtFactura_' . $j];
		$PlanificacionFolio = $_POST['txtFolio_' . $j];
		$destino = $_POST['txtDestino_' . $j];
		$destinatario = $_POST['txtDestinatario_' . $j];
		$observacion = $_POST['txtObservacion_' . $j];
		$valorDeclarado = $_POST['txtValorD_' . $j];
		$peso = $_POST['txtPeso_' . $j];
		$tarifa = $_POST['txtTarifa_' . $j];
		$flete = $_POST['txtFlete_' . $j];
		$seguro = $_POST['txtSeguro_' . $j];
		$acuse = $_POST['txtAcuse_' . $j];
		$importe = $_POST['txtImporte_' . $j];
		$cveIva = $_POST['txtIva_' . $j];
		$subtotal = $_POST['txtSubtotal_' . $j];
		$retencionIva = $_POST['txtRetencionIva_' . $j];
		$total = $_POST['txtTotal_' . $j];
		$totalseguro+=$seguro;
		$observacionB = $_POST['txtObservacionB_' . $j];
		$totalGeneral=$totalGeneral+$total;
		
		
		//Los totales serán calculados sobre el total del importe, no sobre la suma de los parciales de cada rubro
		$iva=0;
		$subtotal=0;
		$retencion=0;
		$total=0;

		$importe=round($importe*100)/100;		

		//Calculamos porcentajes
		$porcentajeRet=$cveRetencion/100;  
		$porcentajeIva=$cveImpuesto/100;  
			//Cantidades de iva y retenciones
		$retencion=$importe*$porcentajeRet;
		$iva=$importe*$porcentajeIva;
			//Redondeo
		$retencionFinal=round($retencion*100)/100 ;
		$ivaFinal=round($iva*100)/100 ;
		
			//Calculos finales
		$iva=$ivaFinal;
		$subtotal=$importe+$iva;
		$retencion=$retencionFinal;
		$total=$subtotal-$retencion;

		$sumaImporte+=$importe;
		$sumaRetencion+=$retencion;
		$sumaIva+=$iva;
		$sumaSubtotal+=$subtotal;		
		$sumaTotal+=$total;
		
		$folioParcial="";
		$sqlConsulta="SELECT folio FROM `cacuse` WHERE cveGuia='".$cveGuia."'";
		$folioParcial=$bd->soloUno($sqlConsulta);
		if($folioParcial!="")
			$folioFactura.=$folioParcial.",";
		
		//Se ingresa el detalle de la facura
		$qry = "INSERT INTO cenviosdetalle (cveEmpresa ,cveSucursal ,cveCliente ,cveFactura ,cveGuia ,piezas ,fechaEntrega ,facturaRemicion ,PlanificacionFolio ,destino ,destinatario ,observacion ,valorDeclarado ,peso ,tarifa ,flete ,seguro ,acuse ,importe ,cveIva ,subtotal ,retencionIva ,total ,observacionB,usuarioCreador,fechaCreacion )
		VALUES (".$empresa.",'$cveCliente','$cveFactura','$cveGuia','$piezas','$fechaEntrega' ,'$facturaRemicion','$PlanificacionFolio','$destino','$destinatario','$observacion','$valorDeclarado','$peso','$tarifa','$flete','$seguro','$acuse','$importe','$cveIva','$subtotal','$retencionIva','$total','$observacionB','$usuario',NOW());";
		$qry=utf8_decode($qry);			
		$error2=$bd->ExecuteNonQuery($qry);
		//echo $qry;
		
		//Se verificará en caso de que exista, el valor siguiente de la guía para ver si se tiene que facturar en este bucle en caso de que ya no haya una siguiente guía, no se superará el tope
		if(!isset($_POST['txtTotal_' . $a]))
			$generar=true;
		else{
			$total_parcial=$_POST['txtTotal_' . $a];
			$total_parcial=$totalGeneral+$total_parcial;
			if(redondeaNum($total_parcial)>redondeaNum($txtTope))
			{
				$diferencia=redondeaNum($total_parcial)-redondeaNum($txtTope);
				if($diferencia>1)
					$generar=true;
			}
		}
				
		
		if($generar){			
			if($primeravez==0){ $primeravez++; $referencia=0;}
			else $referencia=$principal;
				
			$folioFactura = substr($folioFactura, 0, strlen($folioFactura)-1);		
			$folioFactura=quitarRep($folioFactura);
			$folioFactura=implode(",",$folioFactura);
			
			//Checar si ya existe la Factura
			$sqlExs="SELECT COUNT(cveFactura) FROM ccamposenvio WHERE cveFactura='".$cveFactura."' AND cveEmpresa=".$empresaS[0]." AND cveSucursal=".$empresaS[1].";";	

			$existe=$bd->soloUno($sqlExs);
			
			if($existe>0)
			{
				$sql = "UPDATE ccamposenvio set ".$update." usuarioModifico='".$usuario."', fechaModificacion=NOW() ". 
				"WHERE cveFactura='".$cveFactura."' AND cveEmpresa=".$empresaS[0]." AND cveSucursal=".$empresaS[1].";";
			}
			else
			{
				$sql = "INSERT INTO ccamposenvio (cveEmpresa ,cveSucursal ,cveFactura ,fecha ,noGuia ,remicion ,planificacion ,destino,".
				"destinatario ,observacion ,valorDeclarado ,piezas,peso ,tarifa ,flete ,seguro ,acuse ,importe ,iva ,subtotal ,retencion,".
				"total ,observaciones,usuarioCreador,fechaCreacion) VALUES (".$empresa.",'".$cveFactura."',".$valores . ",'$usuario',NOW());";
			}
			
			$error1=$bd->ExecuteNonQuery($sql);
			
					
			//Los totales serán calculados sobre el total del importe, no sobre la suma de los parciales de cada rubro
			$iva=0;
			$subtotal=0;
			$retencion=0;
			$total=0;
			
			$sumaImporte=round($sumaImporte*100)/100;		
			
			//Calculamos porcentajes
			$porcentajeRet=$cveRetencion/100;  
			$porcentajeIva=$cveImpuesto/100;  
			//Cantidades de iva y retenciones
			$retencion=$sumaImporte*$porcentajeRet;
			$iva=$sumaImporte*$porcentajeIva;
			//Redondeo
			$retencionFinal=round($retencion*100)/100 ;
			$ivaFinal=round($iva*100)/100 ;
			
			//Calculos finales
			$sumaIva=$ivaFinal;
			$sumaSubtotal=$sumaImporte+$sumaIva;
			$sumaRetencion=$retencionFinal;
			$sumaTotal=$sumaSubtotal-$sumaRetencion;		

			$sql = "INSERT INTO cenvios (cveEmpresa ,cveSucursal ,cveCliente ,cveFactura,seguro ,fechaFactura ,razonSocial ,rfc,sucursalCliente ,calle ,numeroInterior ,numeroexterior ,colonia ,referencia,cveMunicipio ,cveEstado ,codigoPostal ,folios ,importe ,iva ,subtotal ,retencion ,total,tope,cveRetencion,usuarioCreador,fechaCreacion )VALUES (".$empresa.", '$cveCliente', '$cveFactura',0,NOW(), '$razonSocial','$rfc', '$sucursalCliente', '$calle', '$numeroInterior', '$numeroexterior', '$colonia','$referencia', '$cveMunicipio', '$cveEstado', '$codigoPostal','$folioFactura', '$sumaImporte', '$sumaIva', '$sumaSubtotal', '$sumaRetencion', '$sumaTotal','$txtTopeGral','$cveRetencion','$usuario',NOW());";

			$error2=$bd->ExecuteNonQuery($sql);
									
			$totalfac++;
			$sql="SELECT IFNULL(MAX(cveFactura),0)+1 AS id FROM cenvios";
			$cveFactura=$bd->soloUno($sql);

			$total_parcial=0;
			$totalGeneral=0;
			$sumaImporte=0;
			$sumaRetencion=0;
			$sumaIva=0;
			$sumaSubtotal=0;
			$sumaTotal=0;
			$folioFactura="";
		}
		
	}

	$error=$error1+$error2;


	$txtTope= $_POST['txtTope'];
	$txtTopeGral= $_POST['txtTope'];

	//Ya se emitieron las facturas correspondientes,tomando en cuenta el Tope, ahora se realizará la factura del Seguro Social (se cobrá por separado)
	$primeravez=0;
	$folioFactura="";	
	
	if($error==0) 								//Significa que no ocurrió un error en la generación de la(s) facturas
	{
		if($totalseguro > 0 )				    //Primero checar que si haya alguna guía con seguro
		{
			$sql="SELECT IFNULL(MAX(cveFactura),0)+1 AS id FROM cenvios";
			$cveFactura=$bd->soloUno($sql);			
			$totalGeneral=0;
			$totalfac=1;
			$sumaImporte=0;
			$sumaRetencion=0;
			$sumaIva=0;
			$sumaSubtotal=0;
			$sumaTotal=0;
			$total=0;
			
			//Toma los datos de los clientes, de la primera dirección que esté registrada
			$myqury = "SELECT ccliente.rfc,ccliente.razonSocial,cdireccionescliente.cveSucursal,cdireccionescliente.cveDireccion,cdireccionescliente.calle, cdireccionescliente.codigoPostal,cdireccionescliente.colonia,cdireccionescliente.numeroexterior, cdireccionescliente.numeroInterior,cdireccionescliente.cveEstado,cdireccionescliente.cveMunicipio FROM cdireccionescliente INNER JOIN ccliente ON cdireccionescliente.cveCliente=ccliente.cveCliente WHERE cdireccionescliente.cveCliente='$cveCliente' AND cdireccionescliente.cveEmpresa=".$empresaS[0]." AND cdireccionescliente.cveSucursal=".$empresaS[1]." ORDER BY cdireccionescliente.cveDireccion LIMIT 1;";
			
			$nombres = $bd->Execute($myqury);
			foreach ($nombres as $nombre)
			{
				$razonSocial = $nombre["razonSocial"];
				$rfc = $nombre["rfc"];
				$sucursalCliente = $nombre["cveSucursal"];
				$calle = $nombre["calle"];
				$numeroInterior = $nombre["numeroInterior"];
				$numeroexterior = $nombre["numeroexterior"];
				$colonia = $nombre["colonia"];
				$cveMunicipio = $nombre["cveMunicipio"];
				$cveEstado = $nombre["cveEstado"];
				$codigoPostal = $nombre["codigoPostal"];
			}
	
			for($j=1;$j<($contador+1);$j++)
			{
				$a=$j+1;
				$generar=false;
				
				//Tomamos los datos de las guías
				$cveGuia = $_POST['txtGuia_' . $j];
				$piezas = $_POST['txtpiezas_' . $j];
				$fechaEntrega = $_POST['txtFecha_' . $j];
				$facturaRemicion = $_POST['txtFactura_' . $j];
				$PlanificacionFolio = $_POST['txtFolio_' . $j];
				$destino = $_POST['txtDestino_' . $j];
				$destinatario = $_POST['txtDestinatario_' . $j];
				$observacion = $_POST['txtObservacion_' . $j];
				$valorDeclarado = $_POST['txtValorD_' . $j];
				$ivap = $_POST['txtIva_' . $j];
				$peso = $_POST['txtPeso_' . $j];
				$tarifa = $_POST['txtTarifa_' . $j];
				$flete = $_POST['txtFlete_' . $j];
				$seguro = $_POST['txtSeguro_' . $j];
				$acuse = $_POST['txtAcuse_' . $j];
				$importe = $_POST['txtImporte_' . $j];
				$cveIva = $_POST['cveIva_' . $j];
				$observacionB = $_POST['txtObservacionB_' . $j];
				
				//Los totales serán calculados sobre el total del importe, no sobre la suma de los parciales de cada rubro
				$iva=0;
				$subtotal=0;
				$retencion=0;
				$total=0;
				
				$importe=round($seguro*100)/100;		
		
				//Calculamos porcentajes  
				$porcentajeIva=$cveIva/100;  
					//Cantidades de iva y retenciones
				$iva=$importe*$porcentajeIva;
					//Redondeo
				$ivaFinal=round($iva*100)/100 ;
					//Calculos finales
				$iva=$ivaFinal;
				$subtotal=$importe+$iva;
				$retencion=0;
				$total=$subtotal;
		
				$totalGeneral+=$importe;
				
				$sumaImporte+=$importe;
				$sumaIva+=$iva;
				$sumaSubtotal+=$subtotal;		
				$sumaTotal+=$subtotal;				

				if($seguro > 0){	
				
					$folioParcial="";
					//Iremos consultando los folios que tendrá la factura, de acuerdo si están en los acuses, independientemente de si se ingresaron o no en la factura
					$sqlConsulta="SELECT folio FROM `cacuse` WHERE cveGuia='".$cveGuia."' AND cveCliente=".$cveCliente.";";
					$folioParcial=$bd->soloUno($sqlConsulta);
					
					if($folioParcial!="")
						$folioFactura.=$folioParcial.",";
														
				 
						//Se ingresa el detalle de la facura
						$qry = "INSERT INTO cenviosdetalle (cveEmpresa ,cveSucursal ,cveCliente ,cveFactura ,cveGuia ,piezas ,fechaEntrega ,facturaRemicion ,PlanificacionFolio ,destino ,destinatario ,observacion ,valorDeclarado ,peso ,tarifa ,flete ,seguro ,acuse ,importe ,cveIva ,subtotal ,retencionIva ,total ,observacionB,usuarioCreador,fechaCreacion )
						VALUES (".$empresa.",'$cveCliente','$cveFactura','$cveGuia','$piezas','$fechaEntrega' ,'$facturaRemicion','$PlanificacionFolio','$destino','$destinatario','$observacion','$valorDeclarado','$peso','$tarifa','$flete','$seguro','$acuse','$importe','$iva','$subtotal','$retencionIva','$subtotal','$observacionB','$usuario',NOW());";
						$qry=utf8_decode($qry);
						$error2=$bd->ExecuteNonQuery($qry);
						//echo $qry."<BR>";
				}
				
				//Se verificará en caso de que exista, el valor siguiente de la guía para ver si se tiene que facturar en 
				//este bucle en caso de que ya no haya una siguiente guía, no se superará el tope
				if(!isset($_POST['txtSeguro_' . $a])){
					$generar=true;
				}

				if($generar){
										
						if($primeravez==0){ $primeravez++; $referencia=0;}
						else $referencia=$principal;
						
						//Checar si ya existe la Factura
						$sqlExs="SELECT COUNT(cveFactura) FROM ccamposenvio WHERE cveFactura='".$cveFactura."' AND cveEmpresa=".$empresaS[0]." AND cveSucursal=".$empresaS[1].";";	
						$existe=$bd->soloUno($sqlExs);
						
						if($existe>0)
						{
							$sql = "UPDATE ccamposenvio set ".$update." usuarioModifico='".$usuario."', fechaModificacion=NOW() ". 
							"WHERE cveFactura='".$cveFactura."' AND cveEmpresa=".$empresaS[0]." AND cveSucursal=".$empresaS[1].";";
						}
						else
						{
							$sql = "INSERT INTO ccamposenvio (cveEmpresa ,cveSucursal ,cveFactura ,fecha ,noGuia ,remicion ,planificacion ,destino,".
							"destinatario ,observacion ,valorDeclarado ,piezas,peso ,tarifa ,flete ,seguro ,acuse ,importe ,iva ,subtotal ,retencion,".
							"total ,observaciones,usuarioCreador,fechaCreacion) VALUES (".$empresa.",'".$cveFactura."',".$valores . ",'$usuario',NOW());";
						}
						
						
						
						//Los totales serán calculados sobre el total del importe, no sobre la suma de los parciales de cada rubro
						$iva=0;
						$subtotal=0;
						$retencion=0;
						$total=0;
						
						$sumaImporte=round($sumaImporte*100)/100;		
						
						//Calculamos porcentajes 
						$porcentajeIva=$cveImpuesto/100;  
						//Cantidades de iva y retenciones
						$iva=$sumaImporte*$porcentajeIva;
						//Redondeo
						$ivaFinal=round($iva*100)/100 ;
						
						//Calculos finales
						$sumaIva=$ivaFinal;
						$sumaSubtotal=$sumaImporte+$sumaIva;
						$sumaTotal=$sumaSubtotal;
						
						$folioFactura = substr($folioFactura, 0, strlen($folioFactura)-1);
						$folioFactura=quitarRep($folioFactura);
						$folioFactura=implode(",",$folioFactura);
						$error1=$bd->ExecuteNonQuery($sql);
			
						$sql = "INSERT INTO cenvios (cveEmpresa ,cveSucursal ,cveCliente ,cveFactura,seguro ,fechaFactura ,razonSocial ,rfc,sucursalCliente ,calle ,numeroInterior ,numeroexterior ,colonia ,referencia,cveMunicipio ,cveEstado ,codigoPostal ,folios ,importe ,iva ,subtotal ,retencion ,total,tope,cveRetencion,usuarioCreador,fechaCreacion )VALUES (".$empresa.", '$cveCliente', '$cveFactura', 1,NOW(), '$razonSocial','$rfc', '$sucursalCliente', '$calle', '$numeroInterior', '$numeroexterior', '$colonia','$principal', '$cveMunicipio', '$cveEstado', '$codigoPostal','$folioFactura', '$sumaImporte', '$sumaIva', '$sumaSubtotal', '0', '$sumaSubtotal','$txtTopeGral','$cveRetencion','$usuario',NOW());";
						$error2=$bd->ExecuteNonQuery($sql);
						//echo $sql."<br>";					
						$totalfac++;
						$sql="SELECT IFNULL(MAX(cveFactura),0)+1 AS id FROM cenvios";
						$cveFactura=$bd->soloUno($sql);
						$total_parcial=0;
						$totalGeneral=0;
						$sumaImporte=0;
						$sumaRetencion=0;
						$sumaIva=0;
						$sumaSubtotal=0;
						$sumaTotal=0;
						$folioFactura="";
				}
					
			}		
		}
	
		$error=$error1.$error2;
	}

	if ($error!=0)
	{
		echo "Error: No se generó la factura-0";
	}
	else
	{
		echo "Generando la factura...-1";
	}

	function quitarRep($cadena)
	{
		$datos=explode(",",$cadena);
		$arreglo=array();
		for($i=0;$i<count($datos);$i++)
		{
			if(!(in_array($datos[$i],$arreglo)))
				$arreglo[$i]=$datos[$i];
		}
		return $arreglo;
	
	}
?>

