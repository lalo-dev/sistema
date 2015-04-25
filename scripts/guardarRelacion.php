<?php

	include ("bd.php");
	

	
	//Datos Generales
	$cveCorresponsal = $_POST['txtCodigo'];	
	$fechaFactura    = $_POST['txtfechaFactura'];
	$folioFactura    = $_POST['txtFolioFactura'];
	$porIva          = $_POST['txtPorIva'];
	$porRetencion    = $_POST['txtPorRetencion'];	
	$totalBruto      = $_POST['txtImporteF'];
	$iva             = $_POST['txtIvaF'];
	$retencion       = $_POST['txtRetencionF'];
	$totalNeto       = $_POST['txtTotalF'];
	$iva             = $_POST['txtIvaF'];
	$retencion       = $_POST['txtRetencionF'];
	$totalGuias      = $_POST['totalGuias'];
	
	
		//Cambiamos el formato de la Fecha
	list($dia,$mes,$anyo)=explode("/",$fechaFactura);
	$fechaFactura        = $anyo."-".$mes."-".$dia;
	
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
		
		$qry = "INSERT INTO crelaciondetalle".
			   "(cveCorresponsal,cveFactura,cveGuia,tipoEnvio,piezas,kg,tarifa,costoEntrega,sobrepeso,costoSobrepeso,viaticos,".
			   "distancia,costoDistancia,costoEspecial,guiaAerea,extra1,extra2,observaciones,total) ".
			   "VALUES (".
			   "'".$cveCorresponsal."','".$folioFactura."','".$cveGuia."','".$tipoEnvio."','".$piezas."','".$kilos."','".$tarifa."','".$ctoEnvio.
			   "','".$sobrepeso."','".$ctoSobrepeso."','".$viaticos."','".$distancia."','".$ctoDistancia."','".$ctoEspecial."','".$guiaAerea.
			   "','".$extra1."','".$extra2."','".$observaciones."','".$total."');";
			   
		$qry=utf8_decode($qry);			
		$error2+=$bd->ExecuteNonQuery($qry);
	}
	
	//Se ingresa el total de la Relación generada	
	$qry = "INSERT INTO crelacion ".
		   "(cveCorresponsal,cveFactura,fechaFactura,porImpuesto,porRetencion,importeBruto,iva,retencion,importeNeto) ".
		   "VALUES (".
		   "'".$cveCorresponsal."','".$folioFactura."','".$fechaFactura."','".$porIva."','".$porRetencion."','".$totalBruto."','".$iva."','".$retencion.
		   "','".$totalNeto."');";
	
	$qry=utf8_decode($qry);			
	$error2+=$bd->ExecuteNonQuery($qry);

	if ($error!=0)
		echo "Error: Ocurrió un problema-0";
	else
		echo "Generando relación...-1";

?>

