<?php

	/**reporte envios
	 * @author miguel
	 * @copyright 2010
	 */

 	//Solo para poder visualizar la Factura, lo que se haya guardado se borrará
	include("bd.php");
	include("libreriaGeneral.php");
	$empresa=$_POST["empresa"];
	$empresaS=explode(',',$empresa);
	$cveFactura = $_GET['cveFactura'];
	$cabezeras[0]="Fecha";
	$cabezeras[1]="No. Guia"; 
	$cabezeras[2]="Factura / Remision";
	$cabezeras[3]="Planificacion / Folio";
	$cabezeras[4]="Destino";
	$cabezeras[5]="Destinatario";
	$cabezeras[6]="Observacion";
	$cabezeras[7]="Observaciones";
	$cabezeras[8]="V. Declarado"; 
	$cabezeras[9]="Pzas";
	$cabezeras[10]="Peso";
	$cabezeras[11]="Tarifa";
	$cabezeras[12]="Flete";
	$cabezeras[13]="Seguro";
	$cabezeras[14]="Acuse";
	$cabezeras[15]="Importe";
	$cabezeras[16]="IVA";
	$cabezeras[17]="Subtotal";
	$cabezeras[18]="Retencion";
	$cabezeras[19]="Total";
	

	

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
	
	//Consultamos la clave de la Factura
	$sql="SELECT descripcion FROM cfoliosdocumentos WHERE tipoDocumento='FAC' ";
	$siglasFac = $bd->soloUno($sql);
	
	//Creación del objeto de la clase heredada
	$pdf=new PDF('L','mm','A4');
	$pdf->SetDisplayMode(100,'default'); //Para que el zoom este al 100%, normal real	
	$pdf->AliasNbPages();	
	$pdf->AddPage();
	$pdf->SetMargins(1.5,3,1.5);
	
	//Ecabezados
	$bd = new BD;
	$cveFactura = $_GET['cveFactura'];
	$formato=$_GET['formato']; 
	$sql="SELECT cveCliente, cveFactura, fechaFactura, razonSocial, rfc, sucursalCliente, calle, numeroInterior, numeroexterior, colonia, cveMunicipio, cveEstado, codigoPostal, folios, importe, iva, subtotal, retencion, total FROM cenvios WHERE cveFactura= '$cveFactura'";

	$facturas = $bd->Execute($sql);
	foreach($facturas as $factura){
		$razonSocial=$factura["razonSocial"];
		$folios=$factura["folios"];
		$rfc=$factura["rfc"];
		$fechaFactura=$factura["fechaFactura"];
		$sucursalCliente=$factura["sucursalCliente"];
		$calle=$factura["calle"];
		$numeroexterior=$factura["numeroexterior"];
		$numeroInterior=$factura["numeroInterior"];
		$colonia=$factura["colonia"];
		$cveMunicipio=$factura["cveMunicipio"];
		$codigoPostal=$factura["codigoPostal"];
		$importe=$factura["importe"];
		$iva=$factura["iva"];
		$subtotal=$factura["subtotal"];
		$retencion=$factura["retencion"];
		$total=$factura["total"];
	}

	 //Arial bold 4
	$pdf->SetFont('Arial','B',6);
	//Movernos a la derecha
	$pdf->SetX(1.5);
	$pdf->Cell(20,3,'CLIENTE: ',0,0,'R',0);
	$pdf->SetFont('Arial','',6);
	$pdf->Cell(80,3,$razonSocial,0,0,'L',0);
	$pdf->SetFont('Arial','B',6);
	$pdf->Ln();
	$pdf->Cell(20,3,'FACTURA CYE:',0,0,'R',0);
	$pdf->SetFont('Arial','',6);
	$pdf->Cell(80,3,$siglasFac.$cveFactura,0,0,'L',0);
	$pdf->Ln();
	$pdf->SetFont('Arial','B',6);
	$pdf->Cell(20,3,'FOLIOS: ',0,0,'R',0);
	$pdf->SetFont('Arial','',6);
	$pdf->Cell(80,3,$folios,0,0,'L',0);
	$pdf->Ln(8);
	$pdf->SetFont('Arial','',9);
	$pdf->Cell(263,3,'Relación de Envíos: ',0,0,'L',0);
	$pdf->Ln(4);
	
	//Fin de cabecera
	$pdf->SetFillColor(200,205,255);
	$pdf->SetTextColor(0);
	$pdf->SetDrawColor(0);
	$pdf->SetLineWidth(.2);
	$pdf->SetFont('Arial','B',6);

	//Inicio de la Tabla
	$agregados=0;
	$xf=0;
	$med=0;
	$x1=0;	
	
	//Se hará primero una consulta para que en caso de que la factura principal, no sea la que se está consultando, pueda aparecer el detalle
	$sql="SELECT fecha, noGuia, remicion, planificacion, destino, destinatario, observacion, observaciones, valorDeclarado, piezas, peso, tarifa, flete, seguro, acuse, importe, iva, subtotal, retencion, total FROM ccamposenvio WHERE cveFactura= '$cveFactura' ";

	$camposDis = $bd->Execute($sql);
	$i=0;
	foreach($camposDis as $datos)
	{
		$camposMostrar[0]=$datos[0];   //fecha
		$camposMostrar[1]=$datos[1];    //noGuia
		$camposMostrar[2]=$datos[2];    //remicion
		$camposMostrar[3]=$datos[3];    //planificacion
		$camposMostrar[4]=$datos[4];   //destino
		$camposMostrar[5]=$datos[5];    //destinatario
		$camposMostrar[6]=$datos[6];    //observacion
		$camposMostrar[7]=$datos[7];   //obs
		$camposMostrar[8]=$datos[8];    //valorDeclarado
		$camposMostrar[9]=$datos[9];    //piezas
		$camposMostrar[10]=$datos[10];   //peso
		$camposMostrar[11]=$datos[11];  //tarifa
		$camposMostrar[12]=$datos[12];  //flete
		$camposMostrar[13]=0;  		    //seguro
		$camposMostrar[14]=$datos[14];  //acuse
		$camposMostrar[15]=$datos[15];  //importe
		$camposMostrar[16]=$datos[16];  //iva
		$camposMostrar[17]=$datos[17];  //subtotal
		$camposMostrar[18]=$datos[18];  //retencion
		$camposMostrar[19]=$datos[19];  //total
		
	}


	
	$pdf->Ln(4);
	$m=0;
	$pdf->SetFont('Arial','B',7);
	
	if($camposMostrar[0]==1) {$pdf->Cell(14,6,'Fecha',1,0,'C',1); $m++; $xf=$pdf->GetX(); $med=15;}
	
	if($camposMostrar[1]==1) {$pdf->Cell(17,6,'No. Guía',1,0,'C',1); $m++; $xf=$pdf->GetX(); $med=19;}	
	
	if($camposMostrar[2]==1) {$pdf->Cell(22,6,'Factura/Remisión',1,0,'C',1); $m++; $xf=$pdf->GetX(); $med=19;}	
	
	if($camposMostrar[3]==1) {$pdf->Cell(23,6,'Planificación/Folio',1,0,'C',1); $m++; $xf=$pdf->GetX(); $med=23;}	
	
	if($camposMostrar[4]==1) {$pdf->Cell(12,6,'Destino',1,0,'C',1); $m++; $xf=$pdf->GetX(); $med=12;}	
	
	if($camposMostrar[5]==1) {$pdf->Cell(21,6,'Destinatario',1,0,'C',1); $m++; $xf=$pdf->GetX(); $med=23;}
	
	if($camposMostrar[6]==1) {$pdf->Cell(20,6,'Observación',1,0,'C',1); $m++; $xf=$pdf->GetX(); $med=16;}
	
	if($camposMostrar[7]==1) {$pdf->CellFitScale(17,6,'Observaciones',1,0,'C',1); $m++; }
	
	if($camposMostrar[8]==1) {$pdf->Cell(16,6,'V. Declarado',1,0,'C',1); $m++; $xf=$pdf->GetX(); $med=16;}
	
	if($camposMostrar[9]==1) {$pdf->Cell(11,6,'Piezas',1,0,'C',1); $m++; $xf=$pdf->GetX(); $med=11;}

	if($camposMostrar[10]==1) {$pdf->Cell(11,6,'Peso',1,0,'C',1); $m++; $xf=$pdf->GetX(); $med=11;}

	if($camposMostrar[11]==1) {$pdf->Cell(11,6,'Tarifa',1,0,'C',1); $m++; $xf=$pdf->GetX(); $med=11;}

	if($camposMostrar[12]==1) {$pdf->Cell(12,6,'Flete',1,0,'C',1); $m++; $xf=$pdf->GetX(); $med=12;}

//	if($camposMostrar[12]==1) {$pdf->Cell(14,6,'Seguro',1,0,'C',1); $m++; $xf=$pdf->GetX(); $med=14;}

	if($camposMostrar[14]==1) {$pdf->Cell(14,6,'Acuse',1,0,'C',1); $m++; $xf=$pdf->GetX(); $med=14;}

	if($camposMostrar[15]==1) {$m++;}
	if($camposMostrar[16]==1) {$m++;}
	if($camposMostrar[17]==1) {$m++;}
	if($camposMostrar[18]==1) {$m++;}
	if($camposMostrar[19]==1) {$m++;}
	
	$pdf->Cell(14,6,'Importe',1,0,'C',1); 
	$pdf->Cell(13,6,'IVA',1,0,'C',1); 
	$pdf->Cell(16,6,'Subtotal',1,0,'C',1); 
	$pdf->Cell(14,6,'Retención',1,0,'C',1);
	$pdf->CellFitScale(16,6,'Total',1,0,'C',1); 

	
	
	$pdf->Ln();      

	$posicion=34;//Nos indicará la posicion del cursor en el pdf respecto al eje de y
	$columnas=1;
	$hdnContador=$_POST['hdnContador'];
	 
	$variables = $bd->Execute("SELECT fechaEntrega, cveGuia, facturaRemicion, PlanificacionFolio, destino, LEFT(destinatario,12), observacion,observacionB, valorDeclarado,piezas, peso, tarifa, TRUNCATE(flete,2),TRUNCATE(seguro,2),TRUNCATE(acuse,2), TRUNCATE(importe,2),TRUNCATE(cveIva,2),TRUNCATE(subtotal,2) , TRUNCATE(retencionIva,2),TRUNCATE(total,2) FROM cenviosdetalle WHERE cveFactura= '$cveFactura' ");

	
	$pdf->SetFont('Arial','',6.5);
	$alineacionesDef=array('C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C');
	$anchosDef=array(14,17,22,23,12,21,20,17,16,11,11,11,12,14,14,14,13,16,14,16);
	

	foreach($variables as $variable)
	{	
		$a=0;
		$campoMayor=0;
		$posicion=0;
		//Configuración del Reporte
		for($i=0;$i<20;$i++)
		{
			if($camposMostrar[$i]==1 || ($i>=15 && $i<=19)) 
			{
				$anchos[$a]       = $anchosDef[$i];       //Crear los anchos
				$alineaciones[$a] = $alineacionesDef[$i]; //Crear la alineacion
				if($i==0){				
					$fecha=cambiaf_a_normal($variable[0]); 
					$valores[$a]      = $fecha; 	
				}
				else
					$valores[$a]      = $variable[$i];      //Meter los Datos

				if(strlen($variable[$i])>=$campoMayor){
					$campoMayor=strlen($variable[$i]);
					//if($anchosDef[$posicion]>$anchosDef[$a])
						$posicion=$a;
				}
				$a++;
			}
		
		}
		
		$pdf->SetAligns($alineaciones);
		$pdf->SetWidths($anchos);

		$factor=$anchosDef[$posicion]*(.63);
		$renglones=ceil($campoMayor/$factor);
		if($renglones<=1) $renglones=2;
		$altoFinal=$renglones*5;

		//echo $renglones."<br>";
		$pdf->Row($valores,$altoFinal,0);  
		 
		$valores="";
		$pdf->widths=""; 
		$pdf->aligns=""; 
		//echo $campoMayor."<br>";
	}

	$posy=$pdf->GetY();
	$final=$ubx-$ubxf;
	
	
	$importe=formatoEntero($importe);
	$iva=formatoEntero($iva);
	$subtotal=formatoEntero($subtotal);
	$retencion=formatoEntero($retencion);
	$total=formatoEntero($total);
	
		
	$pdf->SetFont('Arial','B',6.5);
	$pdf->SetX($xf-$med);
	$pdf->Cell($med,6," Total ",1,0,'C',0);	
	$pdf->Cell(14,6,$importe,1,0,'C',0);
	$pdf->Cell(13,6,$iva,1,0,'C',0);
	$pdf->Cell(16,6,$subtotal,1,0,'C',0);
	$pdf->Cell(14,6,$retencion,1,0,'C',0);
	$pdf->Cell(16,6,$total,1,0,'C',0);
	
	//Se mostrarán las facturas que se hayan podido generar a pratir de la primera por los TOPES******************************************************
	
	//Verificamos si la factura tiene mas facturas (Cuando se ha superado el tope de facturas; y se crearon más)
	$sqlC="SELECT COUNT(cvefactura) FROM cenvios WHERE referencia='$cveFactura' AND seguro=0";

	$cuantasf=$bd->soloUno($sqlC);
	
	if($cuantasf>0)
	{
		$sql3="SELECT cvefactura FROM cenvios WHERE referencia='$cveFactura'  AND seguro=0";

		$foliosref = $bd->Execute($sql3);
	
		foreach($foliosref as $folioref)
		{   
			
				$folioRef=$folioref["cvefactura"];

				//Creación del objeto de la clase heredada
				$pdf->AddPage();
				
				//Ecabezados
				$bd = new BD;
				$cveFactura = $_GET['cveFactura'];
				$formato=$_GET['formato']; 
				$sql="SELECT cveCliente, cveFactura, fechaFactura, razonSocial, rfc, sucursalCliente, calle, numeroInterior, numeroexterior, colonia, cveMunicipio, cveEstado, codigoPostal, folios, importe, iva, subtotal, retencion, total FROM cenvios WHERE cveFactura= '$folioRef';";
				
			
				$facturas = $bd->Execute($sql);
				foreach($facturas as $factura){
					$razonSocial=$factura["razonSocial"];
					$folios=$factura["folios"];
					$rfc=$factura["rfc"];
					$fechaFactura=$factura["fechaFactura"];
					$sucursalCliente=$factura["sucursalCliente"];
					$calle=$factura["calle"];
					$numeroexterior=$factura["numeroexterior"];
					$numeroInterior=$factura["numeroInterior"];
					$colonia=$factura["colonia"];
					$cveMunicipio=$factura["cveMunicipio"];
					$codigoPostal=$factura["codigoPostal"];
					$importe=$factura["importe"];
					$iva=$factura["iva"];
					$subtotal=$factura["subtotal"];
					$retencion=$factura["retencion"];
					$total=$factura["total"];
				}
			
				 //Arial bold 4
				$pdf->SetFont('Arial','B',6);
				//Movernos a la derecha
				$pdf->SetX(1.5);
				$pdf->Cell(20,3,'CLIENTE: ',0,0,'R',0);
				$pdf->SetFont('Arial','',6);
				$pdf->Cell(80,3,$razonSocial,0,0,'L',0);
				$pdf->SetFont('Arial','B',6);
				$pdf->Ln();
				$pdf->Cell(20,3,'FACTURA CYE:',0,0,'R',0);
				$pdf->SetFont('Arial','',6);
				$pdf->Cell(80,3,$siglasFac.$folioRef.' (Complemento de la factura:'.$siglasFac.$cveFactura.')',0,0,'L',0);
				$pdf->Ln();
				$pdf->SetFont('Arial','B',6);
				$pdf->Cell(20,3,'FOLIOS: ',0,0,'R',0);
				$pdf->SetFont('Arial','',6);
				$pdf->Cell(80,3,$folios,0,0,'L',0);
				$pdf->Ln(8);
				$pdf->SetFont('Arial','',9);
				$pdf->Cell(263,3,'Relación de Envíos: ',0,0,'L',0);
				$pdf->Ln(4);
				
				//Fin de cabecera
				$pdf->SetFillColor(200,205,255);
				$pdf->SetTextColor(0);
				$pdf->SetDrawColor(0);
				$pdf->SetLineWidth(.2);
				$pdf->SetFont('Arial','B',6);
			
				//Inicio de la Tabla
				$agregados=0;
				$xf=0;
				$med=0;
				$x1=0;	
				
				
				$pdf->Ln(4);
				$sql="SELECT fecha, noGuia, remicion, planificacion, destino, destinatario, observacion,observaciones, valorDeclarado, piezas, peso, tarifa, flete, seguro, acuse, importe, iva, subtotal, retencion, total FROM ccamposenvio WHERE cveFactura= '$folioRef';";
				$camposDis = $bd->Execute($sql);
				$i=0;
				foreach($camposDis as $datos)
				{
					$camposMostrar[0]=$datos[0];      //fecha		
					$camposMostrar[1]=$datos[1];	  //noGuia
					$camposMostrar[2]=$datos[2];	  //remicion
					$camposMostrar[3]=$datos[3];	  //planificacion	
					$camposMostrar[4]=$datos[4];      //destino
					$camposMostrar[5]=$datos[5];	  //destinatario		
					$camposMostrar[6]=$datos[6];	  //observacion
					$camposMostrar[7]=$datos[7];	  //observaciones
					$camposMostrar[8]=$datos[8];	  //valorDeclarado
					$camposMostrar[9]=$datos[9];	  //piezas
					$camposMostrar[10]=$datos[10];	  //peso
					$camposMostrar[11]=$datos[11];	  //tarifa
					$camposMostrar[12]=$datos[12];	  //flete
					//$camposMostrar[13]=$datos[13];  //seguro
					$camposMostrar[14]=$datos[14];	  //acuse
					$camposMostrar[15]=$datos[15];	  //importe
					$camposMostrar[16]=$datos[16];	  //iva
					$camposMostrar[17]=$datos[17];	  //subtotal		
					$camposMostrar[18]=$datos[18];	  //retencion
					$camposMostrar[19]=$datos[19];	  //total
					
				}
			
				$m=0;
				$pdf->SetFont('Arial','B',7);
				
				if($camposMostrar[0]==1) {$pdf->Cell(14,6,'Fecha',1,0,'C',1); $m++; $xf=$pdf->GetX(); $med=15;}
				
				if($camposMostrar[1]==1) {$pdf->Cell(17,6,'No. Guía',1,0,'C',1); $m++; $xf=$pdf->GetX(); $med=19;}	
				
				if($camposMostrar[2]==1) {$pdf->Cell(22,6,'Factura/Remisión',1,0,'C',1); $m++; $xf=$pdf->GetX(); $med=19;}	
				
				if($camposMostrar[3]==1) {$pdf->Cell(23,6,'Planificación/Folio',1,0,'C',1); $m++; $xf=$pdf->GetX(); $med=23;}	
				
				if($camposMostrar[4]==1) {$pdf->Cell(12,6,'Destino',1,0,'C',1); $m++; $xf=$pdf->GetX(); $med=12;}	
				
				if($camposMostrar[5]==1) {$pdf->Cell(21,6,'Destinatario',1,0,'C',1); $m++; $xf=$pdf->GetX(); $med=23;}
				
				if($camposMostrar[6]==1) {$pdf->Cell(20,6,'Observación',1,0,'C',1); $m++; $xf=$pdf->GetX(); $med=16;}
				
				if($camposMostrar[7]==1) {$pdf->CellFitScale(17,6,'Observaciones',1,0,'C',1); $m++; }
				
				if($camposMostrar[8]==1) {$pdf->Cell(16,6,'V. Declarado',1,0,'C',1); $m++; $xf=$pdf->GetX(); $med=16;}
				
				if($camposMostrar[9]==1) {$pdf->Cell(11,6,'Piezas',1,0,'C',1); $m++; $xf=$pdf->GetX(); $med=11;}
			
				if($camposMostrar[10]==1) {$pdf->Cell(11,6,'Peso',1,0,'C',1); $m++; $xf=$pdf->GetX(); $med=11;}
			
				if($camposMostrar[11]==1) {$pdf->Cell(11,6,'Tarifa',1,0,'C',1); $m++; $xf=$pdf->GetX(); $med=11;}
			
				if($camposMostrar[12]==1) {$pdf->Cell(12,6,'Flete',1,0,'C',1); $m++; $xf=$pdf->GetX(); $med=12;}
			
			//	if($camposMostrar[13]==1) {$pdf->Cell(14,6,'Seguro',1,0,'C',1); $m++; $xf=$pdf->GetX(); $med=14;}
			
				if($camposMostrar[14]==1) {$pdf->Cell(14,6,'Acuse',1,0,'C',1); $m++; $xf=$pdf->GetX(); $med=14;}
				
				if($camposMostrar[15]==1) {$m++;}
				if($camposMostrar[16]==1) {$m++;}
				if($camposMostrar[17]==1) {$m++;}
				if($camposMostrar[18]==1) {$m++;}
				if($camposMostrar[19]==1) {$m++;}
				
				$pdf->Cell(14,6,'Importe',1,0,'C',1); 
				$pdf->Cell(13,6,'IVA',1,0,'C',1); 
				$pdf->Cell(16,6,'Subtotal',1,0,'C',1); 
				$pdf->Cell(14,6,'Retención',1,0,'C',1);
				$pdf->CellFitScale(16,6,'Total',1,0,'C',1); 
				
				

				$pdf->Ln();      
				
				$posicion=34;//Nos indicará la posicion del cursor en el pdf respecto al eje de y
				$columnas=1;
				$hdnContador=$_POST['hdnContador'];
				 
				$variables = $bd->Execute("SELECT fechaEntrega, cveGuia, facturaRemicion, PlanificacionFolio, destino, LEFT(destinatario,12), observacion, observacionB,valorDeclarado,piezas, peso, tarifa, TRUNCATE(flete,2),TRUNCATE(seguro,2),TRUNCATE(acuse,2), TRUNCATE(importe,2),TRUNCATE(cveIva,2),TRUNCATE(subtotal,2) , TRUNCATE(retencionIva,2),TRUNCATE(total,2) FROM cenviosdetalle WHERE cveFactura= '$folioRef' ");
				
					
				$pdf->SetFont('Arial','',6.5);
				
				$alineacionesDef=array('C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C');
				$anchosDef=array(14,17,22,23,12,21,20,17,16,11,11,11,12,14,14,14,13,16,14,16);
	
				foreach($variables as $variable)
				{	
					$a=0;
					$campoMayor=0;
					$posicion=0;
					//Configuración del Reporte
					for($i=0;$i<20;$i++)
					{
						if($camposMostrar[$i]==1 || ($i>=15 && $i<=19)) 
						{
							$anchos[$a]       = $anchosDef[$i];       //Crear los anchos
							$alineaciones[$a] = $alineacionesDef[$i]; //Crear la alineacion
							if($i==0){				
								$fecha=cambiaf_a_normal($variable[0]); 
								$valores[$a]      = $fecha; 	
							}
							else
								$valores[$a]      = $variable[$i];      //Meter los Datos
			
							if(strlen($variable[$i])>=$campoMayor){
								$campoMayor=strlen($variable[$i]);
								//if($anchosDef[$posicion]>$anchosDef[$a])
									$posicion=$a;
							}
							$a++;
						}
					
					}
					
					$pdf->SetAligns($alineaciones);
					$pdf->SetWidths($anchos);
					
					$factor=$anchosDef[$posicion]*(.63);
					$renglones=ceil($campoMayor/$factor);
					if($renglones<=1) $renglones=2;
					$altoFinal=$renglones*5;

				//echo $renglones."<br>";
					$pdf->Row($valores,$altoFinal,0);  
					 
					$valores="";
					$pdf->widths=""; 
					$pdf->aligns=""; 
					//echo $campoMayor."<br>";
				}
				
				
				$posy=$pdf->GetY();
				$final=$ubx-$ubxf;
				
				
				$importe=formatoEntero($importe);
				$iva=formatoEntero($iva);
				$subtotal=formatoEntero($subtotal);
				$retencion=formatoEntero($retencion);
				$total=formatoEntero($total);
				
					
				$pdf->SetFont('Arial','B',6.5);
				$pdf->SetX($xf-$med);
				$pdf->Cell($med,6," Total ",1,0,'C',0);	
				$pdf->Cell(14,6,$importe,1,0,'C',0);
				$pdf->Cell(13,6,$iva,1,0,'C',0);
				$pdf->Cell(16,6,$subtotal,1,0,'C',0);
				$pdf->Cell(14,6,$retencion,1,0,'C',0);
				$pdf->Cell(16,6,$total,1,0,'C',0);
		
		}
	}

	//Se mostrarán las facturas que se hayan podido generar con SEGURO ******************************************************
	
	//Verificamos si la factura tiene mas facturas (Cuando se ha superado el tope de facturas; y se crearon más)
	$sqlC="SELECT COUNT(cvefactura) FROM cenvios WHERE referencia='$cveFactura' AND seguro=1";
	

	$cuantasf=$bd->soloUno($sqlC);
	
	if($cuantasf>0)
	{
		$sql3="SELECT cvefactura FROM cenvios WHERE referencia='$cveFactura'  AND seguro=1";

		$foliosref = $bd->Execute($sql3);
	
		foreach($foliosref as $folioref)
		{   
			
				$folioRef=$folioref["cvefactura"];

				//Creación del objeto de la clase heredada
				$pdf->AddPage();
				
				//Ecabezados
				$bd = new BD;
				$cveFactura = $_GET['cveFactura'];
				$formato=$_GET['formato']; 
				$sql="SELECT cveCliente, cveFactura, fechaFactura, razonSocial, rfc, sucursalCliente, calle, numeroInterior, numeroexterior, colonia, cveMunicipio, cveEstado, codigoPostal, folios, importe, iva, subtotal, retencion, total FROM cenvios WHERE cveFactura= '$folioRef';";
				
			
				$facturas = $bd->Execute($sql);
				foreach($facturas as $factura){
					$razonSocial=$factura["razonSocial"];
					$folios=$factura["folios"];
					$rfc=$factura["rfc"];
					$fechaFactura=$factura["fechaFactura"];
					$sucursalCliente=$factura["sucursalCliente"];
					$calle=$factura["calle"];
					$numeroexterior=$factura["numeroexterior"];
					$numeroInterior=$factura["numeroInterior"];
					$colonia=$factura["colonia"];
					$cveMunicipio=$factura["cveMunicipio"];
					$codigoPostal=$factura["codigoPostal"];
					$importe=$factura["importe"];
					$iva=$factura["iva"];
					$subtotal=$factura["subtotal"];
					$retencion=$factura["retencion"];
					$total=$factura["total"];
				}
			
				 //Arial bold 4
				$pdf->SetFont('Arial','B',6);
				//Movernos a la derecha
				$pdf->SetX(1.5);
				$pdf->Cell(20,3,'CLIENTE: ',0,0,'R',0);
				$pdf->SetFont('Arial','',6);
				$pdf->Cell(80,3,$razonSocial,0,0,'L',0);
				$pdf->SetFont('Arial','B',6);
				$pdf->Ln();
				$pdf->Cell(20,3,'FACTURA CYE:',0,0,'R',0);
				$pdf->SetFont('Arial','',6);
				$pdf->Cell(80,3,$siglasFac.$folioRef.' (Complemento de la factura:'.$siglasFac.$cveFactura.',SEGURO)',0,0,'L',0);
				$pdf->Ln();
				$pdf->SetFont('Arial','B',6);
				$pdf->Cell(20,3,'FOLIOS: ',0,0,'R',0);
				$pdf->SetFont('Arial','',6);
				$pdf->Cell(80,3,$folios,0,0,'L',0);
				$pdf->Ln(8);
				$pdf->SetFont('Arial','',9);
				$pdf->Cell(263,3,'Relación de Envíos: ',0,0,'L',0);
				$pdf->Ln(4);
				
				//Fin de cabecera
				$pdf->SetFillColor(200,205,255);
				$pdf->SetTextColor(0);
				$pdf->SetDrawColor(0);
				$pdf->SetLineWidth(.2);
				$pdf->SetFont('Arial','B',6);
			
				//Inicio de la Tabla
				$agregados=0;
				$xf=0;
				$med=0;
				$x1=0;	
				
				
				$pdf->Ln(4);
				$sql="SELECT fecha, noGuia, destino, valorDeclarado, piezas, peso, seguro, importe, iva, subtotal, retencion, total 
				FROM ccamposenvio WHERE cveFactura= '$folioRef' ";
				
				$camposDis = $bd->Execute($sql);
				$i=0;
				foreach($camposDis as $datos)
				{
					$camposMostrar[0]=$datos[0];      //fecha		
					$camposMostrar[1]=$datos[1];	  //noGuia
					$camposMostrar[2]=$datos[2];	  //destino
					$camposMostrar[3]=$datos[3];	  //valorDeclarado	
					$camposMostrar[4]=$datos[4];      //piezas
					$camposMostrar[5]=$datos[5];	  //peso		
					$camposMostrar[6]=$datos[6];	  //seguro
					$camposMostrar[7]=$datos[7];	  //importe
					$camposMostrar[8]=$datos[8];	  //iva
					$camposMostrar[9]=$datos[9];	  //subtotal
					$camposMostrar[10]=$datos[10];	  //retencion
					$camposMostrar[11]=$datos[11];	  //total
				}
			
				$m=0;
				$pdf->SetFont('Arial','B',7);
				
				if($camposMostrar[0]==1) {$pdf->Cell(15,6,'Fecha',1,0,'C',1); $m++; $xf=$pdf->GetX(); $med=15;}
				
				if($camposMostrar[1]==1) {$pdf->Cell(19,6,'No. Guía',1,0,'C',1); $m++; $xf=$pdf->GetX(); $med=19;}	
				
				if($camposMostrar[2]==1) {$pdf->Cell(12,6,'Destino',1,0,'C',1); $m++; $xf=$pdf->GetX(); $med=12;}	
				
				if($camposMostrar[3]==1) {$pdf->Cell(16,6,'V. Declarado',1,0,'C',1); $m++; $xf=$pdf->GetX(); $med=16;}
				
				if($camposMostrar[4]==1) {$pdf->Cell(11,6,'Piezas',1,0,'C',1); $m++; $xf=$pdf->GetX(); $med=11;}
				
				if($camposMostrar[5]==1) {$pdf->Cell(11,6,'Peso',1,0,'C',1); $m++; $xf=$pdf->GetX(); $med=11;}
				
				if($camposMostrar[6]==1) {$pdf->Cell(14,6,'Seguro',1,0,'C',1); $m++; $xf=$pdf->GetX(); $med=14;}
				
				
				if($camposMostrar[7]==1) {$m++;}
				if($camposMostrar[8]==1) {$m++;}
				if($camposMostrar[9]==1) {$m++;}
				if($camposMostrar[10]==1) {$m++;}
				if($camposMostrar[11]==1) {$m++;}
				
				$pdf->Cell(14,6,'Importe',1,0,'C',1); 
				$pdf->Cell(14,6,'IVA',1,0,'C',1); 
				$pdf->Cell(16,6,'Subtotal',1,0,'C',1); 
				$pdf->Cell(14,6,'Retención',1,0,'C',1);
				$pdf->CellFitScale(16,6,'Total',1,0,'C',1); 

				
				$pdf->Ln();      
				
				$posicion=34;//Nos indicará la posicion del cursor en el pdf respecto al eje de y
				$columnas=1;
				$hdnContador=$_POST['hdnContador'];
				 
				$variables = $bd->Execute("SELECT fechaEntrega, cveGuia, facturaRemicion, PlanificacionFolio, destino, destinatario, observacion,observacionB, valorDeclarado,piezas, peso, tarifa,TRUNCATE(flete,2),TRUNCATE(seguro,2) ,TRUNCATE(acuse,2), TRUNCATE(importe,2),TRUNCATE(cveIva,2),TRUNCATE(subtotal,2) , TRUNCATE(retencionIva,2),TRUNCATE(total,2) FROM cenviosdetalle WHERE cveFactura= '$folioRef' ");
				
				
				$pdf->SetFont('Arial','',7);
				
				foreach($variables as $variable)
				{
							if($camposMostrar[0]==1) { $fecha=cambiaf_a_normal($variable[0]);  $pdf->Cell(15,6,$fecha,1,0,'C',0);}				
							if($camposMostrar[1]==1) $pdf->Cell(19,6,$variable[1],1,0,'C',0);					
							if($camposMostrar[2]==1) $pdf->Cell(12,6,$variable[4],1,0,'C',0);
							if($camposMostrar[3]==1) $pdf->Cell(16,6,$variable[8],1,0,'C',0);			
							if($camposMostrar[4]==1) $pdf->Cell(11,6,$variable[9],1,0,'C',0);			
							if($camposMostrar[5]==1) $pdf->Cell(11,6,$variable[10],1,0,'C',0);	
							if($camposMostrar[6]==1) $pdf->Cell(14,6,$variable[13],1,0,'C',0);		
							
							if($camposMostrar[7]==1) $pdf->Cell(14,6,$variable[13],1,0,'C',0);	 else	$pdf->Cell(14,6,'',1,0,'C',0);	$x1=$pdf->GetX();
							if($camposMostrar[8]==1) $pdf->Cell(14,6,$variable[16],1,0,'C',0);  else	$pdf->Cell(14,6,'',1,0,'C',0); 			
							if($camposMostrar[9]==1) $pdf->Cell(16,6,$variable[17],1,0,'C',0);	 else	$pdf->Cell(14,6,'',1,0,'C',0);	
							if($camposMostrar[10]==1) $pdf->Cell(14,6,'0',1,0,'C',0);	 else	$pdf->Cell(14,6,'',1,0,'C',0);	
							if($camposMostrar[11]==1) $pdf->Cell(16,6,$variable[19],1,0,'C',0);	 else	$pdf->Cell(16,6,'',1,0,'C',0);	
							
							$pdf->Ln();
				}
				
				$final=$ubx-$ubxf;
				
				
				$importe=formatoEntero($importe);
				$iva=formatoEntero($iva);
				$subtotal=formatoEntero($subtotal);
				$retencion=formatoEntero(0);
				$total=formatoEntero($total);
				
				$pdf->SetFont('Arial','B',7);
				$pdf->SetX($xf-$med);
				$pdf->Cell($med,6," Total ",1,0,'C',0);	
				$pdf->SetX($x1-14);
				$pdf->Cell(14,6,$importe,1,0,'C',0);
				$pdf->Cell(14,6,$iva,1,0,'C',0);
				$pdf->Cell(16,6,$subtotal,1,0,'C',0);
				$pdf->Cell(14,6,$retencion,1,0,'C',0);
				$pdf->Cell(16,6,$total,1,0,'C',0);
			
		}
	}

	//BORRAR DATOS en la tabla, es como DELETE
	$sql1 = "TRUNCATE TABLE cenviosdetalle";
	$error1=$bd->ExecuteNonQuery($sql1);
	
	$sql2 = "TRUNCATE TABLE cenvios";
	$error1=$bd->ExecuteNonQuery($sql2);

	//IMPRIMIR DOCUMENTO*/
	 
	$pdf->Output();

?>




