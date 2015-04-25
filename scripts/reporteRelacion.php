<?php

 	//Solo para poder visualizar la Factura, lo que se haya guardado se borrará
	include('bd.php');
	include('libreriaGeneral.php');
	
	//A partir de la opción elegida, será de donde se generará el reporte
	$opcion=$_GET['opc'];
	if($opcion==0)
	{
		$tablaGral    = "crelacion";
		$tablaDetalle = "crelaciondetalle";		
	}
	else
	{
		$tablaGral    = "cfacturascorresponsal";
		$tablaDetalle = "cfacturasdetallecorresponsales";			
	}

		//Datos para consultar los datos de la Relación
	$cveCorresponsal=$_GET['corresponsal'];
	$folioFactura=$_GET['factura'];
							
	require('pdfTable.php');
	
	class PDF extends PDF_Table
	{
		//Cabecera de página
		function Header()
		{
		}
		//Pie de Página	
		function Footer()
		{
		}
	}
	
	//Creación del objeto de la clase heredada
	$pdf=new PDF('L','mm','A4');
	$pdf->SetDisplayMode(100,'default'); //Para que el zoom este al 100%, normal real	
	$pdf->AliasNbPages();	
	$pdf->AddPage();
	$pdf->SetMargins(1.5,3,1.5);
	
	//Ecabezados
	$bd = new BD;

	$sqlGenerales="SELECT cveFactura,fechaFactura,porImpuesto,porRetencion ".
				  "FROM ".$tablaGral." WHERE cveCorresponsal=".$cveCorresponsal." AND cveFactura=".$folioFactura.";";
	$generales=$bd->Execute($sqlGenerales);
	
	foreach($generales as $general)
	{
		$cveFactura   = $general["cveFactura"];
		$fechaFactura = $general["fechaFactura"];
		$porImpuesto  = $general["porImpuesto"];
		$porRetencion = $general["porRetencion"];
	}
	
	//Razón Social
	$sqlConsulta="SELECT razonSocial FROM ccorresponsales WHERE cveCorresponsal=".$cveCorresponsal.";";
	$razonSocial=$bd->soloUno($sqlConsulta);
	
	 //Arial bold 4
	$pdf->SetFont('Arial','B',8);
	//Movernos a la derecha
	$pdf->SetXY(10,10);
	$pdf->Cell(35,3,'CORRESPONSAL: ',0,0,'R',0);
	$pdf->Cell(80,3,$razonSocial,0,0,'L',0);
	$pdf->SetXY(10,13);
	$pdf->Cell(35,3,'FACTURA : ',0,0,'R',0);
	$pdf->Cell(80,3,$folioFactura,0,0,'L',0);
	$pdf->SetXY(10,16);
	$pdf->Cell(35,3,'FECHA : ',0,0,'R',0);
	if($fechaFactura!='0000-00-00')
	{
		list($anyo,$mes,$dia)=explode("-",$fechaFactura);
		$fechaFinal=strtoupper(fecha(($dia."-".$mes."-".$anyo),1));
	}
	else
		$fechaFinal='';

	$pdf->Cell(80,3,$fechaFinal,0,0,'L',0);
	$pdf->SetXY(10,19);	
	$pdf->Cell(115,3,' RELACIÓN DE ENVÍOS ',0,0,'L',0);
	
	//Encabezados de la tabla
	$pdf->SetFillColor(200,205,255);
	$pdf->SetTextColor(0);
	$pdf->SetDrawColor(0);
	$pdf->SetLineWidth(.2);
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(2,25);	
	
	$pdf->Cell(16,6,'Guía',1,0,'C',1);
	$pdf->Cell(17,6,'Tipo Entrega',1,0,'C',1);
	$pdf->Cell(14,6,'Piezas',1,0,'C',1);	
	$pdf->Cell(14,6,'Kilos',1,0,'C',1);
	$pdf->Cell(15,6,'Tarifa',1,0,'C',1);
	$pdf->Cell(20,6,'Cto. Entrega',1,0,'C',1);	
	$pdf->Cell(17,6,'Sobrepeso',1,0,'C',1);
	$pdf->Cell(20,6,'Cto. Sobrepeso',1,0,'C',1);
	$pdf->Cell(17,6,'Distancia',1,0,'C',1);	
	$pdf->Cell(20,6,'Cto. Distancia',1,0,'C',1);
	$pdf->Cell(20,6,'Cto. Especial',1,0,'C',1);
	$pdf->Cell(17,6,'Viáticos',1,0,'C',1);	
	$pdf->Cell(17,6,'Guía Aérea',1,0,'C',1);
	$pdf->Cell(17,6,'Exrta 1',1,0,'C',1);
	$pdf->Cell(17,6,'Extra 2',1,0,'C',1);	
	$pdf->Cell(19,6,'Observaciones',1,0,'C',1);
	$pdf->Cell(17,6,'Total',1,0,'C',1);

	//Despliegue de los Datos
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(2,31);	
	
	$sqlDetalles="SELECT cveGuia,tipoEnvio,piezas,kg,tarifa,costoEntrega,sobrepeso,costoSobrepeso,viaticos,".
			   	  "distancia,costoDistancia,costoEspecial,guiaAerea,extra1,extra2,observaciones,total ".
				  "FROM ".$tablaDetalle." WHERE cveCorresponsal=".$cveCorresponsal." AND cveFactura=".$folioFactura.";";

	$detalles=$bd->Execute($sqlDetalles);
	
	foreach($detalles as $detalle)
	{
		$cveGuia       = $detalle["cveGuia"];
		$tipoEnvio     = $detalle["tipoEnvio"];
		$piezas        = $detalle["piezas"];
		$peso          = $detalle["kg"];
		$tarifa        = $detalle["tarifa"];
		$ctoEntrega    = $detalle["costoEntrega"];
		$sobrepeso     = $detalle["sobrepeso"];
		$ctoSobrepeso  = $detalle["costoSobrepeso"];
		$distancia     = $detalle["distancia"];
		$ctoDistancia  = $detalle["costoDistancia"];
		$ctoEspecial   = $detalle["costoEspecial"];
		$viaticos      = $detalle["viaticos"];		
		$guiaAerea     = $detalle["guiaAerea"];
		$extra1        = $detalle["extra1"];
		$extra2        = $detalle["extra2"];
		$observaciones = $detalle["observaciones"];
		$total         = $detalle["total"];		
		
		$bordes='BR';
		$pdf->Cell(16,6,$cveGuia,$bordes.'L',0,'C',0);
		$pdf->Cell(17,6,$tipoEnvio,$bordes,0,'C',0);
		$pdf->Cell(14,6,$piezas,$bordes,0,'C',0);	
		$pdf->Cell(14,6,$peso,$bordes,0,'C',0);
		$pdf->Cell(15,6,$tarifa,$bordes,0,'C',0);
		$pdf->Cell(20,6,$ctoEntrega,$bordes,0,'C',0);	
		$pdf->Cell(17,6,$sobrepeso,$bordes,0,'C',0);
		$pdf->Cell(20,6,$ctoSobrepeso,$bordes,0,'C',0);
		$pdf->Cell(17,6,$distancia,$bordes,0,'C',0);	
		$pdf->Cell(20,6,$ctoDistancia,$bordes,0,'C',0);
		$pdf->Cell(20,6,$ctoEspecial,$bordes,0,'C',0);
		$pdf->Cell(17,6,$viaticos,$bordes,0,'C',0);	
		$pdf->Cell(17,6,$guiaAerea,$bordes,0,'C',0);
		$pdf->Cell(17,6,$extra1,$bordes,0,'C',0);
		$pdf->Cell(17,6,$extra2,$bordes,0,'C',0);	
		$pdf->Cell(19,6,substr($observaciones,0,12),$bordes,0,'C',0);
		$pdf->Cell(17,6,$total,$bordes,0,'R',0);
		$pdf->Ln(6);
		$pdf->SetX(2);	
	}

	//Obtenemos los Totales de la Factura
	$sqlTotales="SELECT importeBruto,iva,retencion,importeNeto ".
				"FROM ".$tablaGral." WHERE cveCorresponsal=".$cveCorresponsal." AND cveFactura=".$folioFactura.";";
	
	$totales=$bd->Execute($sqlTotales);
	
	foreach($totales as $total)
	{
		$subtotal  = $total["importeBruto"];
		$iva       = $total["iva"];
		$retencion = $total["retencion"];
		$total     = $total["importeNeto"];
		
		$pdf->SetFont('Arial','B',7);
		$pdf->SetX(260);
		$pdf->CellFitScale(19,6,'SUBTOTAL','TBL', 0,'C',1);
		$pdf->Cell(3,6,'$ ','TBL', 0,'L',0);
		$pdf->CellFitScale(14,6,$subtotal,'TBR', 0,'R',0);
		$pdf->Ln(6);
		$pdf->SetX(260);
		$pdf->CellFitScale(19,6,'IVA','TBL', 0,'C',1);
		$pdf->Cell(3,6,'$ ','TBL', 0,'L',0);	
		$pdf->CellFitScale(14,6,$iva,'TBR', 0,'R',0);
		$pdf->Ln(6);
		$pdf->SetX(260);
		$pdf->CellFitScale(19,6,'RETENCIÓN','TBL', 0,'C',1);
		$pdf->Cell(3,6,'$ ','TBL', 0,'L',0);
		$pdf->CellFitScale(14,6,$retencion,'TBR',0,'R',0);
		$pdf->Ln(6);
		$pdf->SetX(260);
		$pdf->CellFitScale(19,6,'TOTAL','TBL', 0,'C',1);
		$pdf->Cell(3,6,'$ ','TBL', 0,'L',0);	
		$pdf->CellFitScale(14,6,$total,'TBR', 0,'R',0);
	}

	if($opcion==0)
	{
		//BORRAR DATOS en la tabla, es como DELETE
		$sql1 = "TRUNCATE TABLE crelaciondetalle;";
		$error1=$bd->ExecuteNonQuery($sql1);
		
		$sql2 = "TRUNCATE TABLE crelacion;";
		$error1=$bd->ExecuteNonQuery($sql2);
	}
	
	//IMPRIMIR DOCUMENTO*/	 
	$pdf->Output();
	
?>




