<?php

	include ("bd.php");
	
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
	$totalGuias      = $_POST['totalGuias'];
	
	$usuario         = trim($_POST['usuario']);
	$empresa         = trim($_POST['empresa']);
	
	$empresa         = str_replace("-", "'", $empresa);
	list($empresa,$sucursal)=explode(',',$empresa);
	
		//Cambiamos el formato de la Fecha
	if($fechaFactura!='')
	{
		list($dia,$mes,$anyo)=explode("/",$fechaFactura);
		$fechaFactura        = $anyo."-".$mes."-".$dia;
	}
	$anyo=$anyoFactura;

	//Antes de guardar, verificaremos que NO haya otra factura con ese folio y el mismo año
	$sqlConsulta= "SELECT 1 FROM cfacturascorresponsal WHERE ".
				  "cveCorresponsal='".$cveCorresponsal."' AND ".
				  "cveFactura='".$folioFactura."' AND ".
				  "anyoFactura='".$anyo."';";
	$total=$bd->soloUno($sqlConsulta);
	if($total==1)
	{
		echo "Error: La factura ".$folioFactura." ya está registrada, para el año ".$anyo.".-0";
		exit();
	}

	$guias='';
	$res1=0;
	
	
	for($i=1;$i<=$totalGuias;$i++)
	{

		//Tomamos los datos de las guías
		$cveGuia       = $_POST['txtGuia_'.$i];
		$tipoEnvio     = $_POST['txtTipoE_'.$i];
		$piezas        = $_POST['txtPiezas_'.$i];
		$kilos         = $_POST['txtKilos_'.$i];
		$tarifa        = $_POST['txtTarifa_'.$i];
		$ctoEnvio      = $_POST['txtCostoE_'.$i];
		$sobrepeso     = $_POST['txtSobrepeso_'.$i];
		$ctoSobrepeso  = $_POST['txtCostoS_'.$i];
		$viaticos      = $_POST['txtViaticos_'.$i];
		$distancia     = $_POST['txtDistancia_'.$i];
		$ctoDistancia  = $_POST['txtCostoD_'.$i];
		$ctoEspecial   = $_POST['txtCostoEs_'.$i];	
		$guiaAerea     = $_POST['txtGuiaAerea_'.$i];
		$extra1        = $_POST['txtExtra1_'.$i];
		$extra2        = $_POST['txtExtra2_'.$i];
		$observaciones = $_POST['txtObservaciones_'.$i];
		$total         = $_POST['txtTotal_'.$i];		
		$cargoMin      = $_POST['hdnCargoM_'.$i];		
		
		//Se ingresa el detalle de la facura
		$cvedetalle=$bd->get_Id("cfacturasdetallecorresponsales","cveDetalle");
		
		$sqlDetalle = "INSERT INTO cfacturasdetallecorresponsales".
					  "(cveEmpresa,cveSucursal,cveDetalle,cveCorresponsal,cveFactura,anyoFactura,cveGuia,tipoEnvio,piezas,kg,tarifa,costoEntrega,".
					  "sobrepeso,costoSobrepeso,distancia,costoDistancia,costoEspecial,viaticos,guiaAerea,extra1,extra2,".
					  "observaciones,total,cargoMinimo,usuarioCreador,fechaCreacion) ".
					  "VALUES (".
					  $empresa.",".$sucursal.",'".$cvedetalle."','".$cveCorresponsal."','".$folioFactura."','".$anyo."','".$cveGuia."','".$tipoEnvio."','".$piezas.
					  "','".$kilos."','".$tarifa."','".$ctoEnvio."','".$sobrepeso."','".$ctoSobrepeso."','".$distancia."','".$ctoDistancia."','".$ctoEspecial.
					  "','".$viaticos."','".$guiaAerea."','".$extra1."','".$extra2."','".$observaciones."','".$total."','".$cargoMin."','".$usuario."',NOW());";
		
		$sqlDetalle=utf8_decode($sqlDetalle);			
		$res1+=$bd->ExecuteNonQuery($sqlDetalle);
		$guias.="'".$cveGuia."',";
	}

	$guias = substr($guias,0,strlen($guias)-1);

	//Consultamos los folios y los vales a los que pertenecen las guías	
		//Vales
	$sqlVales = "SELECT DISTINCT(valeSoporte) AS vale FROM cvalessoporte WHERE cveGuia IN(".$guias.");";
	$regVales = $bd->Execute($sqlVales);
	$vales='';
	foreach($regVales as $regVale)
	{
		$vales.=$regVale['vale'].",";
	}
	$vales = substr($vales,0,strlen($vales)-1);
	
		//Folios
	$sqlFolios = "SELECT DISTINCT(folio) AS folio FROM cacuse WHERE cveGuia IN(".$guias.");";
	$regFolios = $bd->Execute($sqlFolios);
	$folios='';
	foreach($regFolios as $regFolio)
	{
		$folios.=$regFolio['folio'].",";
	}
	$folios = substr($folios,0,strlen($folios)-1);
	
	
	//Se ingresa la factura
	$sqlGeneral = "INSERT INTO cfacturascorresponsal ".
		   		  "(cveEmpresa,cveSucursal,cveCorresponsal,cveFactura,anyoFactura,fechaFactura,porImpuesto,porRetencion,importeBruto,iva,retencion,".
		          "importeNeto,folios,vales,usuarioCreador,fechaCreacion) ".
		          "VALUES (".
		          $empresa.",".$sucursal.",'".$cveCorresponsal."','".$folioFactura."','".$anyo."','".$fechaFactura."','".$porIva."','".$porRetencion.
		          "','".$totalBruto."','".$iva."','".$retencion."','".$totalNeto."','".$folios."','".$vales."','".$usuario."',NOW());";			
	$sqlGeneral=utf8_decode($sqlGeneral);			
	$res2=$bd->ExecuteNonQuery($sqlGeneral);

	//Modificamos el estado de las guías
	$sqlPagadas = "UPDATE cguias SET pagada = '1' WHERE cveGuia IN (".$guias.");";
	$res3=$bd->ExecuteNonQuery($sqlPagadas);

	//Ingresa en dedocta los datos del pago hará
	$sql="SELECT IFNULL(MAX(numeroMovimiento),0)+1 AS id FROM dedocta";
	$idNm=$bd->soloUno($sql);
						
	$sqlDeuda = "INSERT INTO dedocta ".
		 	    "(cveEmpresa,cveSucursal,numeroMovimiento,cveTipoDocumento,tipoEstadoCta,folioDocumento,anyoDocumento,montoBruto,montoIva,montoNeto,tipoDocumentoRef,".
		 	    "saldo,estatus,cveCliente,cveMoneda,cveBanco,referencia,cveIva,documentoReferencia,sentido,fecha,transaccionBancaria,".
		        "usuarioCreador,fechaCreacion) ".
		        "VALUES (".				  
		        $empresa.",".$sucursal.",'".$idNm."','FAC','corresponsal','".$folioFactura."','".$anyo."','".$totalBruto."','".$iva."','".$totalNeto."',".
		        "'FAC','".$totalNeto."','1','".$cveCorresponsal."','','','".$folioFactura."','".$porIva."','',1,NOW(),'','".$usuario."',NOW())";
	$res4=$bd->ExecuteNonQuery($sqlDeuda);	  

	$error=$res1+$res2+$res4+$res4;

	if ($error!=0)
		echo "Error: Ocurrió un problema.-0";
	else
		echo "La factura se ha registrado exitosamente.-1";

?>
