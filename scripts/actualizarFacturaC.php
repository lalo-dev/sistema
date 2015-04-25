<?php


	include ("bd.php");
	
	$opcion=$_GET['opcion'];
		//Datos Generales	
	$usuario         = trim($_POST['usuario']);
	$empresa         = trim($_POST['empresa']);
	$empresa         = str_replace("-","'",$empresa);
	list($empresa,$sucursal)=explode(',',$empresa);
	
		//Cambiamos el formato de la Fecha
	$txtfechaFactura=$_POST['txtfechaFactura'];
	list($dia,$mes,$anyo)=explode("/",$txtfechaFactura);
	$fechaFactura        = $anyo."-".$mes."-".$dia;
	
	if($opcion==0) //Cuando se eliminan guías de la factura
	{				  
		$cveGuia=$_GET["guia"];	
		
			//Datos Generales
		$cveCorresponsal = $_POST['txtCodigo'];	
		$fechaFactura    = $_POST['txtfechaFactura'];
		$anyoFactura	 = $_POST['anyoFactura'];
		$folioFactura    = $_POST['txtFolioFactura'];
		$porIva          = $_POST['txtPorIva'];
		$porRetencion    = $_POST['txtPorRetencion'];	
		$totalBruto      = $_POST['txtImporteF'];
		$iva             = $_POST['txtIvaF'];
		$retencion       = $_POST['txtRetencionF'];
		$totalNeto       = $_POST['txtTotalF'];
		
		$anyo=$anyoFactura;
		//Tomamos los datos de las guías
		$total         = $_POST['total'];		
		$detalle       = $_POST['detalle'];	
		
		$cveFactura=$folioFactura;
		
		//Consultar los datos de la deuda actual
		
		$sqlConsulta="SELECT montoBruto,montoIva,montoNeto,saldo,porImpuesto,porRetencion ".
			 "FROM dedocta ".
			 "INNER JOIN cfacturascorresponsal ".
			 "ON dedocta.folioDocumento=cfacturascorresponsal.cveFactura ".
			 "AND dedocta.anyoDocumento=cfacturascorresponsal.anyoFactura ".
			 "WHERE folioDocumento='".$cveFactura."' AND anyoDocumento='".$anyo."' AND dedocta.cveEmpresa=".$empresa." AND dedocta.cveSucursal=".$sucursal.";";
		$dedoctas = $bd->Execute($sqlConsulta);
		foreach ($dedoctas as $dedocta)
		{
			$montoBrutobd   = $dedocta["montoBruto"];
			$montoIvabd     = $dedocta["montoIva"];			
			$montoNetobd    = $dedocta["montoNeto"];
			$saldobd        = $dedocta["saldo"];		
			$porImpuestobd  = $dedocta["porImpuesto"]/100;
			$porRetencionbd = $dedocta["porRetencion"]/100;
		}
		
		//Calcular el iva y la retencion proporcionales a esa guía
		$totalGuia     = $total;
		$ivaGuia       = $totalGuia*$porImpuestobd;      
		$retencionGuia = $totalGuia*$porRetencionbd;
		$totalAportado = $totalGuia+$ivaGuia-$retencionGuia;		
		$importeFin    = $saldobd-$totalAportado;
		
		//Checar que lo aportado por esa guia NO sea superior a la deuda actual de la factura
		if($totalAportado>$saldobd)
		{
			echo "No se realizó la modificación; \nEl importe de la guía supera la deuda actual de la factura.";
			exit();
		}		
		else
		{
			//Primero se borrará del Detalle de la Factura
			$sql1="DELETE FROM cfacturasdetallecorresponsales WHERE cveDetalle='".$detalle."';";	
			$res1=$bd->ExecuteNonQuery($sql1);
			
			//Actualizar la guía, para ponerla como NO facturada
			$sql2="UPDATE cguias SET 
				   usuarioModifico='".$usuario."',
				   fechaModificacion=NOW(),
				   pagada=0 WHERE cveGuia='".$cveGuia."' AND cveEmpresa=".$empresa." AND cveSucursal=".$sucursal.";";
			$res2=$bd->ExecuteNonQuery($sql2);
			
			//Actualizar los totales de la Factura		
						   
			$sql3="UPDATE cfacturascorresponsal SET
				   usuarioModifico='".$usuario."',
				   fechaModificacion=NOW(),		   	
				   porImpuesto='".$porIva."',
				   porRetencion='".$porRetencion."',
				   importeBruto='".$totalBruto."',
				   iva='".$iva."',
				   retencion='".$retencion."',
				   importeNeto='".$totalNeto."'
				   WHERE cveFactura='".$cveFactura."' AND cveCorresponsal='".$cveCorresponsal.
				   "' AND cveEmpresa=".$empresa." AND cveSucursal=".$sucursal." AND anyoFactura='".$anyo."'";			   
			$res3=$bd->ExecuteNonQuery($sql3);
			
			//Actualizar los totales de la Factura
			$extra="";			
			
				//Si cambian saldo y monto neto NO cambiar solo restar (por que ya debió hacerse algún pago),si no actualizar
			if($montoNetobd==$saldobd)
				$extra=",saldo='".$totalNeto."' ";
			else
				$extra=",saldo='".$importeFin."' ";
			
			//Actualizar los totales de la 'dedocta'
			$sql4="UPDATE dedocta SET
				   usuarioModifico='".$usuario."',
				   fechaModificacion=NOW(),		   
				   montoBruto='".$totalBruto."',
				   montoIva='".$iva."',
				   montoNeto='".$totalNeto."'".$extra."
				   WHERE cveCliente='".$cveCorresponsal."' AND tipoEstadoCta='corresponsal' AND folioDocumento='".$cveFactura.
				   "' AND anyoDocumento='".$anyo."' AND cveEmpresa=".$empresa." AND cveSucursal=".$sucursal.";";
			$res4=$bd->ExecuteNonQuery($sql4);		   
			
			$error=$res1+$res2+$res3+$res4;
			
			if($error>0)
			{ $msj="Ocurrió un problema, inténtelo más tarde.-0";}
			else
			{ $msj="La guía ha sido eliminada exitosamente.-1";}
			
			echo $msj;
			exit();
		}	
	}
	else if($opcion==1) //Cuando se modifican los porcentajes de Iva, Retención, fecha y/o folio
	{

			//Datos Generales						
		$cveCorresponsal = $_POST['txtCodigo'];	
		$anyoFactura	 = $_POST['anyoFactura'];
		$folioFactura    = $_POST['txtFolioFactura'];
		$porIva          = $_POST['txtPorIva'];
		$porRetencion    = $_POST['txtPorRetencion'];	
		$totalBruto      = $_POST['txtImporteF'];
		$iva             = $_POST['txtIvaF'];
		$retencion       = $_POST['txtRetencionF'];
		$totalNeto       = $_POST['txtTotalF'];
		
		$facAntigua=$_POST['facAntigua'];
		$anyoAntiguo=$_POST['anyoAntiguo'];
		$anyo=$anyoFactura;
			
		//Si modifico el folio y/o año,checar que no exista otra guía así
		if($facAntigua!=$folioFactura || $anyoAntiguo!=$anyo)
		{
			$sqlConsulta="SELECT 1 FROM cfacturascorresponsal 
						  WHERE cveFactura='$folioFactura'
						  AND anyoFactura='$anyo'
						  AND cveEmpresa=".$empresa." AND cveSucursal=".$sucursal.";";
			$resultado=$bd->Execute($sqlConsulta);
			$total=count($resultado);
			if($total>0)
			{
				echo "No puede cambiar los datos de la Factura, pues existe una con ese folio para ese año.";
				exit();
			}
		}
		
		//Consultar los datos de la deuda actual
		$sqlConsulta="SELECT montoBruto,montoIva,montoNeto,saldo ".
					 "FROM dedocta ".
					 "WHERE folioDocumento='".$facAntigua."' AND anyoDocumento='".$anyo."' AND dedocta.cveEmpresa=".$empresa." AND dedocta.cveSucursal=".$sucursal.";";
		$dedoctas = $bd->Execute($sqlConsulta);
		foreach ($dedoctas as $dedocta)
		{
			$montoNetobd    = $dedocta["montoNeto"];
			$saldobd        = $dedocta["saldo"];		
		}
		
		$diferencia=$montoNetobd-$saldobd;
		
		//Checar que el importe de la deuda no sea mayor al saldo de la deduda de la guía
		if($diferencia>$totalNeto)
		{
			echo "No se realizó la modificación; \nEl importe de factura es menor al importe cubierto de la misma.";
			exit();
		}
		else
		{
			//Realizar el cambio de la factura y de la deuda
			$sqlFactura = "UPDATE cfacturascorresponsal SET 
						  cveFactura='".$folioFactura."',
						  anyoFactura='".$anyo."',
						  fechaFactura='".$fechaFactura."',
						  porImpuesto='".$porIva."',
						  porRetencion='".$porRetencion."',
						  importeBruto='".$totalBruto."',
						  iva='".$iva."',
						  retencion='".$retencion."',
						  importeNeto='".$totalNeto."',
						  usuarioModifico='".$usuario."',
						  fechaModificacion=NOW()
						  WHERE cveCorresponsal='".$cveCorresponsal."' AND cveFactura='".$facAntigua."' AND anyoFactura='".$anyo."'
						  AND cveEmpresa=".$empresa." AND cveSucursal=".$sucursal.";";
			$res1=$bd->ExecuteNonQuery($sqlFactura);			
		
			//Actualizar los totales de la Factura		
			$extra="";
				//Si cambian saldo y monto neto NO cambiar solo restar (por que ya debió hacerse algún pago),si no actualizar
			if($montobd==$saldobd)
			{
				$extra=",saldo='".$totalNeto."',";
			}else
			{
				$diferencia=$montoNetobd-$saldobd;
				$saldoFinal=$totalNeto-$diferencia;	
				$extra=",saldo='".$saldoFinal."',";
			}
			
			
			$sqlDeuda = "UPDATE dedocta SET 
						folioDocumento='".$folioFactura."',
						anyoDocumento='".$anyo."',
						montoBruto='".$totalBruto."',
						montoIva='".$iva."',
						montoNeto='".$totalNeto."'".
						$extra."
						cveIva='".$porIva."',
						usuarioCreador='".$usuario."',
						fechaCreacion=NOW()
						WHERE cveCliente='".$cveCorresponsal."' AND tipoEstadoCta='corresponsal' AND cveTipoDocumento='FAC' AND 
						folioDocumento='".$facAntigua."' AND anyoDocumento='".$anyo."' AND dedocta.cveEmpresa=".$empresa." AND dedocta.cveSucursal=".$sucursal.";";
			$res2=$bd->ExecuteNonQuery($sqlDeuda);		
						
			$error=$res1+$res2;

			if($error>0)
			{ $msj="Ocurrió un problema, inténtelo más tarde.";}
			else
			{ $msj="La factura ha sido actualizada exitosamente.";}
			
			echo $msj;
			exit();
		}
	}

?> 




