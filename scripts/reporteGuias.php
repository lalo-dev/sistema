<?php

	session_start();
	$sqlFinal=$_SESSION['sqlFinal2'];
	$_SESSION['sqlFinal2']='';
	
	$tipoReporte=$_GET['tipo'];
	
	require_once("bd.php");
	require_once("libreriaGeneral.php");
	require_once('pdfTable.php');



	class PDF extends PDF_Table
	{
		//Cabecera de página
		function Header()
		{
			//Movernos a la derecha
			$this->SetY(5);
			$this->SetFont('lucida','','LCALLIG.php');
			$this->SetFont('lucida', '',8);
			$this->SetX(34);
			$this->Cell(286.6,4, 'CARGA Y EXPRESS, S. A. DE C. V.' ,0, 0, 'C', 0);
			$this->SetY(9);
			$this->SetFont('lucida', '',9);
			$this->SetX(34);
			$this->Cell(134,4, 'Reporte de Confirmaciones Pendientes',0, 0, 'R', 0);
			$this->Cell(65,4,'(clave C/6 - 016   Fecha:03/Nov/2003',0, 0, 'L', 0);
			$this->Cell(87.5,4, ' Revision 00)',0, 0,'L',0);		
			$this->SetXY(0,0);
			$this->SetLineWidth(.1);
			$this->Line(248.5,9,248,9.5);	//Acento para revisión
			$this->SetFont('Arial','B',7);
			$this->SetY(17);
			$this->SetFont('lucida','','7');
			$this->SetFillColor(200,205,255);
			$tipoReporte=$_GET['tipo'];

			if($tipoReporte==0)
			{
				$this->SetXY(5,17);
				$this->MultiCell(18,3.5,'FECHA DE ENVIO',1,'C', 1);		
				$this->SetXY(23,17);
				$this->MultiCell(18,3.5,'LINEA AEREA',1,'C',1);
				$this->SetXY(41,17);
				$this->MultiCell(21,3.5,'No. DE GUIA AEREA',1,'C', 1);
				$this->SetXY(62,17);
				$this->Cell(16,7,'GUIA CYE',1,2,'C',1);
				$this->SetXY(78,17);
				$this->Cell(40,7,'CLIENTE',1,0,'C', 1);
				$this->SetXY(118,17);
				$this->Cell(19,7,'DESTINO',1,0,'C', 1);
				$this->SetXY(137,17);
				$this->Cell(12,7,'PIEZAS',1,0,'C', 1);
				$this->SetXY(149,17);
				$this->Cell(12,7,'KILOS',1,0,'C', 1);
				$this->SetXY(161,17);
				$this->Cell(40,7,'DESTINATARIO',1,0,'C', 1);
				$this->SetXY(201,17);
				$this->MultiCell(18,3.5,'FECHA DE ENTREGA',1,'C', 1);
				$this->SetXY(219,17);
				$this->MultiCell(25,3.5,'NOMBRE DE QUIEN RECIBE',1,'C', 1);	
				$this->SetXY(244,17);
				$this->MultiCell(18,3.5,'FECHA DE RELACION',1,'C', 1);	
				$this->SetXY(262,17);
				$this->Cell(30,7,'OBSERVACIONES',1,0,'C', 1);
				$this->SetXY(292,17);
				$this->MultiCell(20,3.5,'F. LLEGADA DEL ACUSE',1,'C', 1);
				$this->SetXY(312,17);
				$this->MultiCell(18,3.5,'FECHA DE VUELO',1,'C', 1);
				$this->SetXY(330,17);
				$this->MultiCell(22,3.5,'No. DE FACTURA',1,'C', 1);
				$this->Ln(0);
				$this->SetX(5);
				$this->SetFont('Arial', '',8);
			}else
			{
				$this->SetFont('lucida', '',5);
				$this->SetXY(2,17);
				$this->MultiCell(14,3.5,'FECHA DE ENVIO',1,'C', 1);		
				$this->SetXY(16,17);
				$this->MultiCell(12,3.5,'LINEA AEREA',1,'C',1);
				$this->SetXY(28,17);
				$this->MultiCell(16,3.5,'No. DE GUIA AEREA',1,'C', 1);
				$this->SetXY(44,17);
				$this->Cell(13,7,'GUIA CYE',1,2,'C',1);
				$this->SetXY(57,17);				
				$this->Cell(19,7,'CLIENTE',1,0,'C', 1);
				$this->SetXY(76,17);				
				$this->Cell(11,7,'DESTINO',1,0,'C', 1);		
				$this->SetXY(87,17);				
				$this->Cell(15,3.5,'FACTURA',0,0,'C', 1);				
				$this->Line(87,17,102,17);				
				$this->SetXY(87,20.5);
				$this->Cell(15,3.5,'REMISION',0,0,'C', 1);	
				$this->Line(87,24,102,24);
				$this->Line(87,17,87,24);
				$this->SetXY(102,17);			
				$this->Cell(18,3.5,'PLANIFICACION',0,0,'C', 1);				
				$this->Line(102,17,120,17);				
				$this->SetXY(102,20.5);
				$this->Cell(18,3.5,'FOLIO',0,0,'C', 1);	
				$this->Line(102,24,120,24);
				$this->Line(102,17,102,24);				
				$this->SetXY(120,17);	                
				$this->Cell(19,7,'DESTINATARIO',1,0,'C', 1);
				$this->SetXY(139,17);				
				$this->MultiCell(13,3.5,'FECHA DE ENTREGA',1,'C', 1);
				$this->SetXY(152,17);				
				$this->MultiCell(18,3.5,'NOMBRE DE QUIEN RECIBE',1,'C', 1);	
				$this->SetXY(170,17);								
				$this->MultiCell(20,3.5,'F. LLEGADA   DEL ACUSE',1,'C', 1);
				$this->SetXY(188,17);				
				$this->MultiCell(15,3.5,'FECHA DE RELACION',1,'C', 1);	
				$this->SetXY(203,17);				
				$this->MultiCell(11,3.5,'TIPO DE ENVIO',1,'C', 1);
				$this->SetXY(214,17);				
				$this->MultiCell(13,3.5,'No. DE FACTURA',1,'C', 1);
				$this->SetXY(227,17);				
				$this->MultiCell(16,3.5,'VALOR DECLARADO',1,'C',1);
				$this->SetXY(243,17);				
				$this->Cell(8,7,'PIEZAS',1,0,'C', 1);
				$this->SetXY(251,17);				
				$this->Cell(8,7,'PESO',1,0,'C', 1);
				$this->SetXY(259,17);				
				$this->Cell(9,7,'TARIFA',1,0,'C', 1);
				$this->SetXY(268,17);				
				$this->Cell(8,7,'FLETE',1,0,'C', 1);
				$this->SetXY(276,17);				
				$this->Cell(13,7,'SEGURO (2%)',1,0,'C', 1);
				$this->SetXY(289,17);				
				$this->Cell(9,7,'ACUSE',1,0,'C', 1);
				$this->SetXY(298,17);				
				$this->Cell(11,7,'IMPORTE',1,0,'C', 1);
				$this->SetXY(309,17);				
				$this->Cell(8,7,'IVA',1,0,'C', 1);
				$this->SetXY(317,17);				
				$this->Cell(11,7,'SUBTOTAL',1,0,'C', 1);
				$this->SetXY(328,17);				
				$this->MultiCell(15,3.5,'RETENCION (4%)',1,'C', 1);
				$this->SetXY(343,17);				
				$this->Cell(10,7,'TOTAL',1,0,'C', 1);
				$this->Ln(7);
				$this->SetX('2');
				$this->SetFont('Arial', '',7);	
			}
		}
			
		function Footer()
		{
			//Posición: a 1 cm del final
			$this->SetXY(335,-20);
			$this->SetFont('Arial','',10);
			//Número de página
			$numero=$this->PageNo();
			$this->Cell('10',4,$numero,o,0,'R');
		}
	}

	$pdf=new PDF('L','mm','Legal');
	$pdf->AddFont('lucida','','LCALLIG.php');	
	$pdf->AliasNbPages();   
	$pdf->AddPage();
	$pdf->SetDisplayMode(70,'default'); //Para que el zoom este al 100%, normal real	
	$pdf->SetMargins(0,0,0);
	$pdf->SetLineWidth(.1);
	
	if($tipoReporte==0)
	{

		$i=0;
		$con=0;
		$guiaBase="";
		$guias = $bd->Execute($sqlFinal);
		foreach($guias as $datGuias)
		{
			$sqlFact="SELECT cfacturasdetalle.cveFactura FROM cfacturasdetalle ".
					"INNER JOIN cfacturas ON cfacturas.cveFactura=cfacturasdetalle.cveFactura ".
					"AND cfacturas.seguro=0 AND cfacturasdetalle.cveGuia='".$datGuias['cveGuia']."'";
					
			$noFact = $bd->soloUno($sqlFact);

			$a=$i%2;				
			if($guiaBase!=$datGuias['cveLineaArea'] && $con!=0)
			{
				$guiaBase=$datGuias['cveLineaArea'];
				$i++;						
				$pdf->Cell(18,4,'',1,0,'C',0);	
				$pdf->Cell(18,4,'',1,0,'C',0);
				$pdf->Cell(21,4,'',1,0,'C',0);
				$pdf->Cell(16,4,'',1,0,'C',0);
				$pdf->Cell(40,4,'',1,0,'C',0);
				$pdf->Cell(19,4,'',1,0,'C',0);
				$pdf->Cell(12,4,'',1,0,'C',0);
				$pdf->Cell(12,4,'',1,0,'C',0);
				$pdf->Cell(40,4,'',1,0,'C',0);
				$pdf->Cell(18,4,'',1,0,'C',0);
				$pdf->Cell(25,4,'',1,0,'C',0);	
				$pdf->Cell(18,4,'',1,0,'C',0);	
				$pdf->Cell(30,4,'',1,0,'C',0);
				$pdf->Cell(20,4,'',1,0,'C',0);
				$pdf->Cell(18,4,'',1,0,'C',0);
				$pdf->Cell(22,4,'',1,0,'C',0);
				$pdf->Ln(4);
				$pdf->SetX(5);
			}
				
			$guiaBase=$datGuias['cveLineaArea'];	
			$a=$i%2;
			$con++;
			$i++;
			
			
			$fecha=fechaCon($datGuias['recepcionCYE']);					
			$pdf->Cell(18,4,$fecha,1,0,'C',0);	
			
			$pdf->Cell(18,4,$datGuias['cveLineaArea'],1,0,'C',0);
			$pdf->Cell(21,4,$datGuias['guiaArea'],1,0,'C',0);
			$pdf->Cell(16,4,$datGuias['cveGuia'],1,0,'C',0);					
			
			$cliente=subCadena($datGuias['nombreCli'],21);							
			$pdf->Cell(40,4,$cliente,1,0,'C',0);
			
			$pdf->Cell(19,4,$datGuias['estacion'],1,0,'C',0);
			$pdf->Cell(12,4,redondeaNum($datGuias['piezas']),1,0,'C',0);
			$pdf->Cell(12,4,redondeaNum($datGuias['kg']),1,0,'C',0);
			
			$consignatario=subCadena($datGuias['nombreCon'],21);							
			$pdf->Cell(40,4,$consignatario,1,0,'C',0);
			
			$fecha=fechaCon($datGuias['fechaEntrega']);
			$pdf->Cell(18,4,'88-88-8888',1,0,'C',0);
			
			$datEntrega=cambioCadena($datGuias['datEntrega']);
			$datEntrega=subCadena($datEntrega,12);
			$pdf->Cell(25,4,$datEntrega,1,0,'C',0);	
			
			$fecha=fechaCon($datGuias['fechaRelacion']);
			$pdf->Cell(18,4,$datGuias['fechaRelacion'],1,0,'C',0);					
		
			$observaciones=subCadena($datGuias['observacion'],14);
			$pdf->Cell(30,4,$observaciones,1,0,'C',0);
			
			$fecha=fechaCon($datGuias['llegadaAcuse']);
			$pdf->Cell(20,4,$fecha,1,0,'C',0);
			
			$fecha=fechaCon($datGuias['fechaVuelo']);
			$pdf->Cell(18,4,$fecha,1,0,'C',0);
			
			$pdf->Cell(22,4,$noFact,1,0,'C',0);
			$pdf->Ln(4);
			$pdf->SetX(5);
					
		}          
	}else
	{		
			
		
		//Obtenemos los datos de la Guía		
		$datos = $bd->Execute($sqlFinal);
		
		$sqlr="SELECT primerRango,segundoRango,tercerRango FROM ctarifascorresponsales WHERE cveCorresponsal=0";	
		$rangos = $bd->Execute($sqlr);
		
		foreach($rangos as $rango)
		{
			 $rango1= valorRango($rango['primerRango']);
			 $rango2= valorRango($rango['segundoRango']);
			 $rango3= valorRango($rango['tercerRango']);
		}	
		
		$h=0;
		$i=0;
		$con=0;
		$guiaBase="";
		foreach ($datos as $datoGuia)
		{
			
			$cveGuia=$datoGuia['cveGuia'];
			
			$sqlFact="SELECT cfacturasdetalle.cveFactura FROM cfacturasdetalle ".
					 "INNER JOIN cfacturas ON cfacturas.cveFactura=cfacturasdetalle.cveFactura ".
					 "AND cfacturas.seguro=0 AND cfacturasdetalle.cveGuia='".$cveGuia."' ".
					 "ORDER BY cfacturasdetalle.cveDetalle DESC LIMIT 1";
						
			$noFact = $bd->soloUno($sqlFact);
			
			if($noFact!="") //Consultar los datos de la Factura real
			{
				$sqlDatosFactura="SELECT acuse,peso,piezas,tarifa,flete,importe,cveIva,retencionIVA,subtotal,total ".
								 "FROM cfacturasdetalle WHERE cveFactura='".$noFact."' AND cveGuia='".$cveGuia."'";
								 
				$datosFact = $bd->Execute($sqlDatosFactura);
				
				foreach($datosFact as $datoFact)
				{
					//Se les da formato a los números
					$acuse=redondeaNum($datoFact['acuse']);
					$peso=redondeaNum($datoFact['peso']);
					$piezas=redondeaNum($datoFact['piezas']);
					$cargo=redondeaNum($datoFact['tarifa']);
					$flete=redondeaNum($datoFact['flete']);
					$valorDec=redondeaNum($datoGuia['seguro']);			
					$seguro=redondeaNum($datoGuia['seguro']*.02);			
					$importe=redondeaNum($datoFact['importe']);
					$iva=redondeaNum($datoFact['cveIva']);
					$retIva=redondeaNum($datoFact['retencionIVA']);
					$subtotal=redondeaNum($datoFact['subtotal']);
					$total=redondeaNum($datoFact['total']);
				}
			}
			else
			{         //Calcularlos
				$sqlC="SELECT DISTINCT(ctarifas.cvetipoc) FROM ctarifas ".
					   "INNER JOIN ccliente ON ctarifas.cveTipoc=ccliente.cveTipoCliente ".
					   "INNER JOIN cguias ON cguias.cveCliente=ccliente.cveCliente ".
					   "WHERE cguias.cveGuia='".$cveGuia."'";
				
				$tipoc=$bd->soloUno($sqlC);
				$tipoE=$datoGuia["tipoEnvio"];
				
				$tarifaa= 0;
				$tarifab= 0;
				$tarifac=0;
				$tarifad=0;
				$cargoMinimo=0;
				
				if($tipoc!="" && $tipoE!="")
				{
					$sqlt="SELECT cargo99,cargo299,cargo300,cuartoRango,cargoMinimo FROM ctarifas ".
					  "WHERE estadoOrigen='9' AND estadoDestino='".$datoGuia["estadoDestinatario"]."' ".
					  "AND origen='17' AND destino='".$datoGuia["municipioDestinatario"] ."' AND tipoEnvio='$tipoE' ".
					  "AND cvetipoc='$tipoc' AND estatus=1";
					//echo $cveGuia."   ".$sqlt."<br>";
					$tarifas = $bd->Execute($sqlt);
				
					$i=count($tarifas);
					if($i>0)                       //Sólo si se tienen los datos necesarios(cliente,consiganatario y q existe la tarifa), se tendrán datos de TARIFA
					{
						foreach($tarifas as $tarifa)
						{
							$tarifaa= $tarifa["cargo99"];
							$tarifab= $tarifa["cargo299"];
							$tarifac= $tarifa["cargo300"];
							$tarifad= $tarifa["cuartoRango"];
							$cargoMinimo=$tarifa["cargoMinimo"];
						} 
					} 
				}
				$peso=$datoGuia['peso'];
				 
				if($peso<= $rango1)
				{ $cargo=$tarifaa; }
				
				if($peso > $rango1 AND $peso<= $rango2)
				{ $cargo=$tarifab; }
				
				if($peso> $rango2 AND $peso<= $rango3)
				{ $cargo=$tarifac; }
				
				if($peso>= $rango3)
				{ $cargo=$tarifad; }
				
				$porcentajeIva=$datoGuia["cveImpuestoCli"]/100;
				$flete= $cargo*$peso;
				
				if($flete>$cargoMinimo){$flete=$flete;}else{$flete=$cargoMinimo;}
				
				$acuse=150;
				$importe=$flete+$acuse;
				$iva=$importe*$porcentajeIva;
				$retIva=($importe*.04);
				$subtotal=$iva+$importe;
				$total=$subtotal-$retIva;
				
				//Se les da formato a los números
				$peso=redondeaNum($peso);
				$piezas=redondeaNum($datoGuia['piezas']);
				$cargo=redondeaNum($cargo);
				$flete=redondeaNum($flete);
				$valorDec=redondeaNum($datoGuia['seguro']);			
				$seguro=redondeaNum($datoGuia['seguro']*.02);			
				$importe=redondeaNum($importe);
				$iva=redondeaNum($iva);
				$retIva=redondeaNum($retIva);
				$subtotal=redondeaNum($subtotal);
				$total=redondeaNum($total);
			}
			
			//Aquí se tendrán que imprimir los datos de las Guías			

			$a=$h%2;
			if($guiaBase!=$datoGuia['cveLineaArea'] && $con!=0)
			{
				$h++;
				$pdf->Cell(14,4,'',1,0,'C',0);
				$pdf->Cell(12,4,'',1,0,'C',0);
				$pdf->Cell(16,4,'',1,0,'C',0);
				$pdf->Cell(13,4,'',1,0,'C',0);
				$pdf->Cell(19,4,'',1,0,'C',0);
				$pdf->Cell(11,4,'',1,0,'C',0);				
				$pdf->Cell(15,4,'',1,0,'C',0);				
				$pdf->Cell(18,4,'',1,0,'C',0);				
				$pdf->Cell(19,4,'',1,0,'C',0);				
				$pdf->Cell(13,4,'',1,0,'C',0);
				$pdf->Cell(18,4,'',1,0,'C',0);			
				$pdf->Cell(18,4,'',1,0,'C',0);				
				$pdf->Cell(15,4,'',1,0,'C',0);	
				$pdf->Cell(11,4,'',1,0,'C',0);			
				$pdf->Cell(13,4,'',1,0,'C',0);				
				$pdf->Cell(16,4,'',1,0,'C',0);				
				$pdf->Cell(8,4,'',1,0,'C',0);
				$pdf->Cell(8,4,'',1,0,'C',0);
				$pdf->Cell(9,4,'',1,0,'C',0);
				$pdf->Cell(8,4,'',1,0,'C',0);
				$pdf->Cell(13,4,'',1,0,'C',0);
				$pdf->Cell(9,4,'',1,0,'C',0);
				$pdf->Cell(11,4,'',1,0,'C',0);
				$pdf->Cell(8,4,'',1,0,'C',0);
				$pdf->Cell(11,4,'',1,0,'C',0);	
				$pdf->Cell(15,4,'',1,'C',0);
				$pdf->Cell(10,4,'',1,0,'C',0);	
				
				$pdf->Ln(4);
				$pdf->SetX(2);
			} 
			$guiaBase=$datoGuia['cveLineaArea'];	
	   		$a=$h%2;
		  	$con++;
			$h++;

			$fecha=fechaCon($datoGuia['recepcionCYE']);
			$pdf->Cell(14,4,$fecha,1,0,'C',0);
					
			$cveLinea=subCadena($datoGuia['cveLineaArea'],6);
			$pdf->Cell(12,4,$cveLinea,1,0,'C',0);
			
			$guiaLinea=subCadena($datoGuia['guiaArea'],11);
			$pdf->Cell(16,4,$guiaLinea,1,0,'C', 0);
			
			$pdf->Cell(13,4,$datoGuia['cveGuia'],1,0,'C',0);
			
			$nomCli=subCadena($datoGuia['nombreCli'],10);
			$pdf->Cell(19,4,$nomCli,1,0,'C', 0);
					
			$estacion=subCadena($datoGuia['estacion'],6);
			$pdf->Cell(11,4,$estacion,1,0,'C', 0);		
			
			 if($datoGuia['facturaSoporte']=="")
				$facRem=$datoGuia['entregasSoporte'];
            else
                $facRem=$datoGuia['facturaSoporte'];
			
			$facRem=subCadena($facRem,6);
			$pdf->Cell(15,4,$facRem,1,0,'C', 0);				
			
			$vale=subCadena($datoGuia['valeSoporte'],6);
			$pdf->Cell(18,4,$vale,1,0,'C', 0);				
			
			$nombreCons=subCadena($datoGuia['nombreCon'],8);
			$pdf->Cell(19,4,$nombreCons,1,0,'C', 0);
			
			$fecha=fechaCon($datoGuia['fechaEntrega']);
			$pdf->Cell(13,4,$fecha,1,0,'C', 0);
			
			$datEntrega=cambioCadena($datoGuia['datEntrega']);
			$datEntrega=subCadena($datEntrega,10);			
			$pdf->Cell(18,4,$datEntrega,1,0,'C',0);			
			
			$fecha=fechaCon($datoGuia['llegadaAcuse']);
			$pdf->Cell(18,4,$fecha,1,0,'C',0);
			
			$fecha=fechaCon($datoGuia['fechaRelacion']);
			$pdf->Cell(15,4,$fecha,1,0,'C',0);	
			
			$tipoEn=subCadena($datoGuia['tipoEnvio'],5);		
			$pdf->Cell(11,4,$tipoEn,1,0,'C',0);
			
			if($noFact!="")
				$pdf->CellFitScale(13,4,$noFact,1,0,'C',0);
			else
				$pdf->Cell(13,4,$noFact,1,0,'C',0);
				
			if($valorDec!="")
				$pdf->CellFitScale(16,4,$valorDec,1,0,'C',0);				
			else
				$pdf->CellFitScale(16,4,$valorDec,1,0,'C',0);				
				
			if($piezas!="")
				$pdf->CellFitScale(8,4,$piezas,1,0,'C',0);				
			else
				$pdf->Cell(8,4,$piezas,1,0,'C',0);				
				
			if($peso!="")
				$pdf->CellFitScale(8,4,$peso,1,0,'C',0);				
			else
				$pdf->Cell(8,4,$peso,1,0,'C',0);
			
			if($cargo!="")
				$pdf->CellFitScale(9,4,$cargo,1,0,'C',0);				
			else
				$pdf->Cell(9,4,$cargo,1,0,'C',0);
				
			if($flete!="")
				$pdf->CellFitScale(8,4,$flete,1,0,'C',0);				
			else
				$pdf->Cell(8,4,$flete,1,0,'C',0);			

			if($seguro!="")
				$pdf->CellFitScale(13,4,$seguro,1,0,'C',0);				
			else
				$pdf->Cell(13,4,$seguro,1,0,'C',0);
				
			if($acuse!="")
				$pdf->CellFitScale(9,4,$acuse,1,0,'C',0);				
			else
				$pdf->Cell(9,4,$acuse,1,0,'C',0);

			if($importe!="")
				$pdf->CellFitScale(11,4,$importe,1,0,'C',0);				
			else
				$pdf->Cell(11,4,$importe,1,0,'C',0);
				
			if($iva!="")
				$pdf->CellFitScale(8,4,$iva,1,0,'C',0);				
			else
				$pdf->Cell(8,4,$iva,1,0,'C',0);			
				
			if($subtotal!="")
				$pdf->CellFitScale(11,4,$subtotal,1,0,'C',0);				
			else
				$pdf->Cell(11,4,$subtotal,1,0,'C',0);
	
			if($retIva!="")
				$pdf->CellFitScale(15,4,$retIva,1,0,'C',0);				
			else
				$pdf->Cell(15,4,$retIva,1,0,'C',0);
	
			if($total!="")
				$pdf->CellFitScale(10,4,$total,1,0,'C',0);				
			else
				$pdf->Cell(10,4,$total,1,0,'C',0);

			
			$pdf->Ln(4);
			$pdf->SetX(2);

		}
   }  

$pdf->Output();


?>
