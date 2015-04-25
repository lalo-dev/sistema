<?php

	/**
	 * @author Jose Miguel Pantaleon
	 * @copyright 2010
	 */
	
	session_start();
	if ((!isset($_SESSION["usuario_valido"]))||($_SESSION["permiso"]!="Administrador"))
	{
    	header("Location: ../login.php");
	}
	
	
		
	
	include("bd.php");
	include("libreriaGeneral.php");
	require('pdfTable.php');
	
	//Consultamos la clave de la Factura
	$sql="SELECT descripcion FROM cfoliosdocumentos WHERE tipoDocumento='FAC' ";
	$siglasFac = $bd->soloUno($sql);
	
	//$empresa=$_POST["empresa"];
	//$empresaS=explode(',',$empresa);
	
	$cveFactura = $_GET['cveFactura'];
	$formato=$_GET['formato']; 
	$porcenteje=$_GET['iva'];
	
	$sql="SELECT cveCliente, cveFactura, fechaFactura, razonSocial, rfc, sucursalCliente, calle, numeroInterior, numeroexterior, colonia, cveMunicipio, cveEstado, codigoPostal, folios, importe, iva, subtotal, retencion, total FROM cfacturas WHERE cveFactura= '$cveFactura'";
	$facturas = $bd->Execute($sql);

	
	foreach($facturas as $factura){
		$cveCliente = $factura['cveCliente'];
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
	

	$condicionP=$bd-> soloUno("SELECT condicionesPago FROM ccliente WHERE cveCliente='$cveCliente'");
	
	class PDF extends PDF_Table
	{
		//Cabecera de página
		function Header()
		{
			$bd = new BD;
			$cveFactura = $_GET['cveFactura'];
			$formato=$_GET['formato']; 
			$porcenteje=$_GET['iva'];


			$sql="SELECT cveCliente, cveFactura, fechaFactura, razonSocial, rfc, sucursalCliente, calle, numeroInterior, numeroexterior, colonia, cveMunicipio, cveEstado, codigoPostal, folios, importe, iva, subtotal, retencion, total FROM cfacturas WHERE cveFactura= '$cveFactura'";
			//echo $sql;
			$facturas = $bd->Execute($sql);
			
			foreach($facturas as $factura)
			{	
				$cveCliente = $factura['cveCliente'];
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
				$estado=$factura["cveEstado"];
			}

			$nombreM=$bd-> soloUno("SELECT nombre FROM cmunicipios WHERE cveEntidadFederativa='$estado' AND cveMunicipio='$cveMunicipio'");
			$fechaFactura=nombre_mes($fechaFactura,1);
			
			$this->SetXY(156,51);			
			//Se pone la Fecha
			$this->SetFont('Arial','',8);
			$this->Cell(6,3,"",0,0,'C',0);
			$this->Cell(40,3,"México D.F. a ".$fechaFactura,0,0,'C',0);

			//Razón Social	
			$this->SetXY(15,33);	
			$this->SetFont('Arial','B',9);
			$this->Cell(90,6,$razonSocial,0,0,'L',0);
			$this->Ln();
			$this->SetX(15);
			$this->SetFont('Arial','',9);
			if($numeroInterior=="") $numeroInterior=$numeroexterior;			
			if($numeroInterior=="") $numeroInterior=" S/N";
			else $numeroInterior=" No. ".$numeroInterior;

			$this->Cell(90,3,$calle.$numeroInterior,0,0,'L',0);
			$this->Ln();
			$this->SetXY(15,43);
			$this->SetFont('Arial','',9);
			$this->Cell(90,3,"Col. ".$colonia." C.P.".$codigoPostal,0,0,'L',0);
			$this->Ln();
			$this->SetXY(15,47);
			$this->SetFont('Arial','B',9);
			$this->Cell(90,3,"Del. ".$nombreM.' RFC: '. $rfc,0,0,'L',0);	
    	}
		
		function Footer()
		{			
		}
	}
	
	//Creación del objeto de la clase heredada
	$pdf=new PDF('P','mm','A4');
	$pdf->SetDisplayMode(100,'default'); //Para que el zoom este al 100%, normal real
	$pdf->AliasNbPages();
	$pdf->SetMargins(3,2,4);

	//NORMAL*********************************************************************************************************************************

	if($formato==1)        //Formato General
	{
		$pdf->AddPage();
		
		//fin de cabecera
		$sql="SELECT cveCliente, cveFactura, fechaFactura, razonSocial, rfc, sucursalCliente, calle, numeroInterior, numeroexterior, colonia, cveMunicipio, cveEstado, codigoPostal, folios, importe, iva, subtotal, retencion, total FROM cfacturas WHERE cveFactura= '$cveFactura'";
		
		$facturas = $bd->Execute($sql);

		foreach($facturas as $factura){			
			$importet=$factura["importe"];
			$iva=$factura["iva"];
			$subtotal=$factura["subtotal"];
			$retencion=$factura["retencion"];
			$totalFac=$factura["total"];
			$retencion=$factura["retencion"];
		}

		$facturas = $bd->Execute($sql);
		
		$pdf->SetFillColor(200,205,255);
		$pdf->SetTextColor(0);
		$pdf->SetDrawColor(0);
		$pdf->SetLineWidth(.2);
		$pdf->SetFont('Arial','B',9);
		//espacio en blanco para los margenes
		$pdf->Ln(24);
		$pdf->SetX(30);
		$pdf->Cell(90,4,'Servicios realizados a los diferentes destinos  ',0,0,'L',0);
		$pdf->Ln();
		$pdf->SetX(30);
		$pdf->Cell(90,4,'según relación anexa  ',0,0,'L',0);
		$pdf->Ln(16);
 
		$pdf->SetFont('Arial','',8);
		$pdf->SetY(115);
		$pdf->Cell(22,6,'',0,0,'C',0);
		$pdf->Cell(112,4,"Pago hecho en una sola exhibición ",0,0,'L',0);

		$importe=formatoEntero($importe);
		$iva=formatoEntero($iva);
		$subtotal=formatoEntero($subtotal);
		$retencion=formatoEntero($retencion);
		$totalFacN=$totalFac;
		$totalFac=formatoEntero($totalFac);
		
		$pdf->SetFont('Arial','B',8);
		$pdf->SetXY(140,109);
		$pdf->Cell(37,4,'',0,0,'R',0);
		$pdf->Cell(21,4,$importe,0,0,'R',0);
		
		$pdf->SetFont('Arial','',8);
		$pdf->SetXY(140,113);		
		$pdf->Cell(37,4,'',0,0,'R',0);
		$pdf->Cell(21,4,$iva,0,0,'R',0);
		
		$pdf->SetXY(140,117);
		$pdf->Cell(37,4,'',0,0,'R',0);
		$pdf->Cell(21,4,$subtotal,0,0,'R',0);
		
		$pdf->SetXY(140,121);	
		$pdf->Cell(37,4,'',0,0,'R',0);
		$pdf->Cell(21,4,$retencion,0,0,'R',0);
		
		$pdf->SetFont('Arial','B',8);
		$pdf->SetXY(140,125);	
		$pdf->Cell(37,4,'',0,0,'R',0);
		$pdf->Cell(21,4,$totalFac,0,0,'R',0);
		
		$porcenteje=40;
		$pdf->Cell(15,6,'',0,0,'C',0);
		$pdf->Cell(112,7,"",0,0,'',0);
		$pdf->SetXY(28,149);				
		$pdf->SetFont('Arial','',5);
		$pdf->Cell(20,2,"",0,0,'',0);
				
		$pdf->SetXY(28,123);				
		$pdf->SetFont('Arial','B',8);
		$numero="$totalFacN";
									         
		//Para que lo pueda tomar como una cadena
		$longitud=strlen($numero);
		$posicion=strpos($numero,".");
		if($posicion==""){
			$letras=num2letras($num);
			$letras="( ".$letras.$totalFac." totalFac PESOS 00/100 m.n. )";		
		}else{
			$enteros=substr($numero,0,$posicion);
			$centavos=substr($numero,($posicion+1),$longitud);
			$letras=num2letras($enteros);
			$letras="( ".$letras." PESOS ".$centavos."/100 m.n. )";		
		}
		
		$loncadena=$longitud=strlen($letras);
		$pdf->CellFitScale(109,4,$letras,0,0,'C',0);
    }
	else if($formato==2)   //Formato con detalle de movimientos
	{
		//Primero verificaremos cuáles son los datos que pidió se mostraran
		$sql="SELECT noGuia,destino,fecha,piezas,peso,tarifa,acuse,flete FROM ccamposenvio WHERE cveFactura='$cveFactura';";
		$camposDis = $bd->Execute($sql);
		$i=0;
		foreach($camposDis as $datos)
		{
			$camposMostrar[0]=$datos[0];
			$camposMostrar[1]=$datos[1];			
			$camposMostrar[2]=$datos[2];			
			$camposMostrar[3]=$datos[3];			
			$camposMostrar[4]=$datos[4];
			$camposMostrar[5]=$datos[5];			
			$camposMostrar[6]=$datos[6];			
			$camposMostrar[7]=$datos[7];			
		}

		$pdf->AddPage('P');
		
		$pdf->SetXY(110,53);			
		$pdf->Ln(13);
		$agregados=0;
		$sql="SELECT cveCliente, cveFactura, fechaFactura, razonSocial, rfc, sucursalCliente, calle, numeroInterior, numeroexterior, colonia, cveMunicipio, cveEstado, codigoPostal, folios, importe, iva, subtotal, retencion, total FROM cfacturas WHERE cveFactura= '$cveFactura'";

		$facturas = $bd->Execute($sql);

		foreach($facturas as $factura){			
			$importet=$factura["importe"];
			$iva=$factura["iva"];
			$subtotal=$factura["subtotal"];
			$retencion=$factura["retencion"];
			$totalFac=$factura["total"];
			$retencion=$factura["retencion"];
		}
		///Aqui comienza la factura
  
		//Movernos a la derecha
		$pdf->SetFillColor(200,205,255);
		$pdf->SetTextColor(0);
		$pdf->SetDrawColor(0);
		$pdf->SetLineWidth(.2);
		$pdf->SetFont('Arial','',9);
		
		//Espacio en blanco para los margenes
			
		if($camposMostrar[0]==1) $pdf->Cell(23,6,'No. GUIA',0,0,'C',1);
		else					 $pdf->Cell(23,6,'',0,0,'C',1);
		
		if($camposMostrar[1]==1) $pdf->Cell(16,6,'DESTINO',0,0,'C',1);
		else					 $agregados++;
		
		if($camposMostrar[2]==1) $pdf->Cell(16,6,'FECHA',0,0,'C',1);
		else					 $agregados++;
		
		if($camposMostrar[3]==1) $pdf->Cell(16,6,'PIEZAS',0,0,'C',1);
		else					 $agregados++;
		
		if($camposMostrar[4]==1) $pdf->Cell(16,6,'KILOS',0,0,'C',1);
		else					 $agregados++;
		
		if($camposMostrar[5]==1) $pdf->Cell(16,6,'TARIFA',0,0,'C',1);
		else					 $agregados++;
		
		if($camposMostrar[6]==1) $pdf->Cell(16,6,'ACUSE',0,0,'C',1);
		else					 $agregados++;
		
		if($camposMostrar[7]==1) $pdf->Cell(16,6,'FLETE',0,0,'C',1);
		else					 $agregados++;
	
		//Depediendo de los que no se hayan pedido se agregarán las columnas para no afectar diseño
		for($i=0;$i<$agregados;$i++)
		{
			$pdf->Cell(16,6,'',0,0,'C',1);
		}
		$agregados=0;
		
		$pdf->Cell(37,6,'',0,0,'C',1);
		$pdf->Cell(27,6,'',0,0,'C',1);

		
		$i=1;
		$hdnContador=$_POST['hdnContador'];
		//Datos de detalle de Factura
		$sql2="SELECT fechaEntrega, cveGuia, facturaRemicion, PlanificacionFolio, destino, destinatario, observacion, valorDeclarado,piezas, peso, tarifa, TRUNCATE(flete,2) AS flete, seguro,TRUNCATE(acuse,2) AS acuse , TRUNCATE(importe,2) AS importe,TRUNCATE(cveIva,2) AS cveIva,TRUNCATE(subtotal,2) AS subtotal , TRUNCATE(retencionIva,2) AS retencionIva,TRUNCATE(total,2) as total, observacionB FROM cfacturasdetalle WHERE cveFactura= '$cveFactura' ";

        $variables = $bd->Execute($sql2);
		$total=count($variables);

		
		$inicio=1;
		$ocurridas=1;

		$pdf->Ln(6);
		$y=71;
		$parametro=0;
		//Como máximo 28 guías
		foreach($variables as $variable){
			$txtFecha_ =$variable['fechaEntrega'];
			$txtGuia_=$variable['cveGuia'];
			$txtDestino_=$variable['destino'];  
			$txtpiezas_=$variable['piezas'];
			$txtPeso_=$variable['peso'];  
			$txtTarifa_ =$variable['tarifa'];
			$txtFlete_=$variable['flete'];
			$txtTotal=$variable['total'];
			$imported=$variable['importe'];
			$txttarifa=$variable['tarifa'];
			$txtacuse=$variable['acuse'];
			
			$pdf->SetTextColor(0);
			$pdf->SetDrawColor(0);
			$pdf->SetLineWidth(.2);
			$pdf->SetFont('Arial','',8);
			//Espacio en blanco para los margenes
			$y=$y+$parametro;
			$pdf->SetY($y);
			
			
			if($camposMostrar[0]==1) $pdf->Cell(23,6,$txtGuia_,0,0,'C',0);
			else					 $pdf->Cell(23,6,'',0,0,'C',0);
			
			if($camposMostrar[1]==1) $pdf->Cell(16,6,$txtDestino_,0,0,'C',0);
			else					 $agregados++;
			
			if($camposMostrar[2]==1) $pdf->Cell(16,6,$txtFecha_,0,0,'C',0);
			else					 $agregados++;
			
			if($camposMostrar[3]==1) $pdf->Cell(16,6,$txtpiezas_,0,0,'C',0);
			else					 $agregados++;
			
			if($camposMostrar[4]==1) $pdf->Cell(16,6,$txtPeso_,0,0,'C',0);
			else					 $agregados++;
			
			if($camposMostrar[5]==1) $pdf->Cell(16,6,$txttarifa,0,0,'C',0);
			else					 $agregados++;
			
			if($camposMostrar[6]==1) $pdf->Cell(16,6,$txtacuse,0,0,'C',0);
			else					 $agregados++;
			
			if($camposMostrar[7]==1) $pdf->Cell(16,6,$txtFlete_,0,0,'C',0);
			else					 $agregados++;
			
			//Depediendo de los que no se hayan pedido se agregarán las columans para no afectar diseño
			for($i=0;$i<$agregados;$i++)
			{
				$pdf->Cell(16,6,'',0,0,'C',0);
			}
			$pdf->Cell(37,6,'',0,0,'R',0);
			$pdf->Cell(23,6,$imported,0,0,'R',0);
			
			$parametro=3;
			
			if($ocurridas==10)
			{
				if(!(($inicio+1)>$total)){
					$pdf->AddPage();	
					$pdf->SetXY(110,53);			
					$pdf->Ln(13);				
				
					//Movernos a la derecha
					$pdf->SetFillColor(200,205,255);
					$pdf->SetTextColor(0);
					$pdf->SetDrawColor(0);
					$pdf->SetLineWidth(.2);
					$pdf->SetFont('Arial','',9);
					$agregados=0;
					//Espacio en blanco para los margenes
					if($camposMostrar[0]==1) $pdf->Cell(23,6,'No. GUIA',0,0,'C',1);
					else					 $pdf->Cell(23,6,'',0,0,'C',1);
					
					if($camposMostrar[1]==1) $pdf->Cell(16,6,'DESTINO',0,0,'C',1);
					else					 $agregados++;
					
					if($camposMostrar[2]==1) $pdf->Cell(16,6,'FECHA',0,0,'C',1);
					else					 $agregados++;
					
					if($camposMostrar[3]==1) $pdf->Cell(16,6,'PIEZAS',0,0,'C',1);
					else					 $agregados++;
					
					if($camposMostrar[4]==1) $pdf->Cell(16,6,'KILOS',0,0,'C',1);
					else					 $agregados++;
					
					if($camposMostrar[5]==1) $pdf->Cell(16,6,'TARIFA',0,0,'C',1);
					else					 $agregados++;
					
					if($camposMostrar[6]==1) $pdf->Cell(16,6,'ACUSE',0,0,'C',1);
					else					 $agregados++;
					
					if($camposMostrar[7]==1) $pdf->Cell(16,6,'FLETE',0,0,'C',1);
					else					 $agregados++;
				
					//Depediendo de los que no se hayan pedido se agregarán las columans para no afectar diseño
					for($i=0;$i<$agregados;$i++)
					{
						$pdf->Cell(16,6,'',0,0,'C',1);
					}
				
					$pdf->Cell(37,6,'',0,0,'C',1);
					$pdf->Cell(27,6,'',0,0,'C',1);

					
					$pdf->Ln(10);
					
					$ocurridas=0;
					$y=71;
					$parametro=0;
				}
			}
						
			$ocurridas++;
				
		}

		
		//A partir de la posición de "y" se imprimirán los datos del total
		$pdf->SetY(112);
		$pdf->Cell(22,6,'',0,0,'C',0);
		$pdf->Cell(112,4,"NOTA: CONDICIONES DE PAGO A ".$condicionP,0,0,'L',0);
		
		$pdf->SetY(115);
		$pdf->SetFont('Arial','',8);
		$porcenteje=40;
		$pdf->Cell(22,6,'',0,0,'C',0);
		$pdf->Cell(112,4,"Pago hecho en una sola exhibición ",0,0,'L',0);
		
	    $importe=formatoEntero($importe);
		$iva=formatoEntero($iva);
		$subtotal=formatoEntero($subtotal);
		$retencion=formatoEntero($retencion);
		$totalFacN=$totalFac;
		$totalFac=formatoEntero($totalFac);
		
		$pdf->SetFont('Arial','B',8);
		$pdf->SetXY(140,109);
		$pdf->Cell(37,4,'',0,0,'R',0);
		$pdf->Cell(21,4,$importe,0,0,'R',0);
		
		$pdf->SetFont('Arial','',8);
		$pdf->SetXY(140,113);		
		$pdf->Cell(37,4,'',0,0,'R',0);
		$pdf->Cell(21,4,$iva,0,0,'R',0);
		
		$pdf->SetXY(140,117);
		$pdf->Cell(37,4,'',0,0,'R',0);
		$pdf->Cell(21,4,$subtotal,0,0,'R',0);
		
		$pdf->SetXY(140,121);	
		$pdf->Cell(37,4,'',0,0,'R',0);
		$pdf->Cell(21,4,$retencion,0,0,'R',0);
		
		$pdf->SetFont('Arial','B',8);
		$pdf->SetXY(140,125);	
		$pdf->Cell(37,4,'',0,0,'R',0);
		$pdf->Cell(21,4,$totalFac,0,0,'R',0);
		
		$porcenteje=40;
		$pdf->Cell(15,6,'',0,0,'C',0);
		$pdf->Cell(112,7,"",0,0,'',0);
		$pdf->SetXY(28,149);				
		$pdf->SetFont('Arial','',5);
		$pdf->Cell(20,2,"",0,0,'',0);
				
		$pdf->SetXY(28,123);				
		$pdf->SetFont('Arial','B',8);
		$numero="$totalFacN";											         
		//Para que lo pueda tomar como una cadena
		$longitud=strlen($numero);
		$posicion=strpos($numero,".");
		if($posicion==""){
			$letras=num2letras($num);
			$letras="( ".$letras." PESOS 00/100 m.n. )";		
		}else{
			$enteros=substr($numero,0,$posicion);
			$centavos=substr($numero,($posicion+1),$longitud);
			$letras=num2letras($enteros);
			$letras="( ".$letras." PESOS ".$centavos."/100 m.n. )";		
		}

		$loncadena=$longitud=strlen($letras);
		$pdf->CellFitScale(109,4,$letras,0,0,'C',0);	
	
	}
	else if($formato==3)  //Formato Abierto
	{
		$pdf->AddPage();
		$pdf->SetXY(110,53);			
		$pdf->Ln(15);

		
		//fin de cabecera
		$sql="SELECT cveCliente, cveFactura, fechaFactura, razonSocial, rfc, sucursalCliente, calle, numeroInterior, numeroexterior, colonia, cveMunicipio, cveEstado, codigoPostal, folios, importe, iva, subtotal, retencion, total,vales FROM cfacturas WHERE cveFactura= '$cveFactura';";
		
		$facturas = $bd->Execute($sql);

		foreach($facturas as $factura){			
			$importet=$factura["importe"];
			$iva=$factura["iva"];
			$subtotal=$factura["subtotal"];
			$retencion=$factura["retencion"];
			$totalFac=$factura["total"];
			$retencion=$factura["retencion"];
			$vale=$factura["vales"];
		}
		$facturas = $bd->Execute($sql);

		$pdf->SetFont('Arial','',7);
		$pdf->SetX(25);
		$pdf->Cell(95,3,'PLANIFICACIONES DE TRANSPORTE CON SUS CORRESPONDENTES GUÍAS:',0,0,'L',0);
		$pdf->Ln(1);
		$y=$pdf->GetY();
		$varios=explode(",",$vale);
		$pdf->SetFont('Arial','B',9);
		$totalvales=count($varios);
		if(($varios[0]!="")||($totalvales>1)){
			for($i=0;$i<(count($varios));$i++)
			{
				$y=$y+3;
				$pdf->SetXY(30,$y);
				$pdf->Cell(90,3,$varios[$i],0,0,'L',0);
			}
			
		}
		
		$pdf->SetFillColor(200,205,255);
		$pdf->SetTextColor(0);
		$pdf->SetDrawColor(0);
		$pdf->SetLineWidth(.2);
		$pdf->SetFont('Arial','B',9);
		//Espacio en blanco para los margenes
		$pdf->SetXY(25,106);
		$pdf->Cell(90,4,'Servicios realizados a los diferentes destinos',0,0,'L',0);
		$pdf->SetXY(25,110);
		$pdf->Cell(90,4,'según relación anexa  ',0,0,'L',0);

		$pdf->SetY(115);
		$pdf->SetFont('Arial','',8);
		$porcenteje=40;
		$pdf->Cell(22,6,'',0,0,'C',0);
		$pdf->Cell(112,4,"Pago hecho en una sola exhibición",0,0,'L',0);


		$importe=formatoEntero($importe);
		$iva=formatoEntero($iva);
		$subtotal=formatoEntero($subtotal);
		$retencion=formatoEntero($retencion);
		$totalFacN=$totalFac;
		$totalFac=formatoEntero($totalFac);
		
		$pdf->SetFont('Arial','B',8);
		$pdf->SetXY(140,109);
		$pdf->Cell(37,4,'',0,0,'R',0);
		$pdf->Cell(21,4,$importe,0,0,'R',0);
		
		$pdf->SetFont('Arial','',8);
		$pdf->SetXY(140,113);		
		$pdf->Cell(37,4,'',0,0,'R',0);
		$pdf->Cell(21,4,$iva,0,0,'R',0);
		
		$pdf->SetXY(140,117);
		$pdf->Cell(37,4,'',0,0,'R',0);
		$pdf->Cell(21,4,$subtotal,0,0,'R',0);
		
		$pdf->SetXY(140,121);	
		$pdf->Cell(37,4,'',0,0,'R',0);
		$pdf->Cell(21,4,$retencion,0,0,'R',0);
		
		$pdf->SetFont('Arial','B',8);
		$pdf->SetXY(140,125);	
		$pdf->Cell(37,4,'',0,0,'R',0);
		$pdf->Cell(21,4,$totalFac,0,0,'R',0);
		
		$porcenteje=40;
		$pdf->Cell(15,6,'',0,0,'C',0);
		$pdf->Cell(112,7,"",0,0,'',0);
		$pdf->SetXY(28,149);				
		$pdf->SetFont('Arial','',5);
		$pdf->Cell(20,2,"",0,0,'',0);
				
		$pdf->SetXY(28,123);				
		$pdf->SetFont('Arial','B',8);
		$numero="$totalFacN";
									         
		//Para que lo pueda tomar como una cadena
		$longitud=strlen($numero);
		$posicion=strpos($numero,".");
		if($posicion==""){
			$letras=num2letras($num);
			$letras="( ".$letras.$totalFac." totalFac PESOS 00/100 m.n. )";		
		}else{
			$enteros=substr($numero,0,$posicion);
			$centavos=substr($numero,($posicion+1),$longitud);
			$letras=num2letras($enteros);
			$letras="( ".$letras." PESOS ".$centavos."/100 m.n. )";		
		}
				
		$loncadena=$longitud=strlen($letras);
		$pdf->CellFitScale(109,4,$letras,0,0,'C',0);
    }

	//CORTE*********************************************************************************************************************************
	
	//Creación del objeto de la clase heredada
	//Verificamos si la factura tiene mas facturas (Cuando se ha superado el tope de facturas; y se crearon más)
	$sqlC="SELECT COUNT(cvefactura) FROM cfacturas WHERE referencia='$cveFactura' AND seguro=0";

	$cuantasf=$bd->soloUno($sqlC);
	
	if($cuantasf>0)
	{
		$sql3="SELECT cvefactura FROM cfacturas WHERE referencia='$cveFactura'  AND seguro=0";
		$foliosref = $bd->Execute($sql3);
	
		foreach($foliosref as $folioref){    //Haremos una factura, por cada referencia que se haya generado, por haber sobrepasado el tope
	
			$folioRef=$folioref["cvefactura"];
			$agregados=0;
			if($formato==1)
			{
				$pdf->AddPage();
				//Fin de cabecera
				$sql="SELECT cveCliente, cveFactura, fechaFactura, razonSocial, rfc, sucursalCliente, calle, numeroInterior, numeroexterior, colonia, cveMunicipio, cveEstado, codigoPostal, folios, importe, iva, subtotal, retencion, total FROM cfacturas WHERE cveFactura= '$folioRef'";
				$facturas = $bd->Execute($sql);

				foreach($facturas as $factura){
					$importe=$factura["importe"];
					$iva=$factura["iva"];
					$subtotal=$factura["subtotal"];
					$retencion=$factura["retencion"];
					$totalFac=$factura["total"];
				}

				$facturas = $bd->Execute($sql);
		
				$pdf->SetFillColor(200,205,255);
				$pdf->SetTextColor(0);
				$pdf->SetDrawColor(0);
				$pdf->SetLineWidth(.2);
				$pdf->SetFont('Arial','B',9);
				//espacio en blanco para los margenes
				$pdf->Ln(24);
				$pdf->SetX(30);
				$pdf->Cell(90,4,'Servicios realizados a los diferentes destinos  ',0,0,'L',0);
				$pdf->Ln();
				$pdf->SetX(30);
				$pdf->Cell(90,4,'según relación anexa  ',0,0,'L',0);
								
				$pdf->SetXY(7,107);
				$pdf->SetFont('Arial','',6);
				$pdf->Cell(18,6,'',0,0,'C',0);
				$pdf->Cell(101,7,'COMPLEMENTO DE LA FACTURA '.$siglasFac.$cveFactura,0,0,'L',0);

				$pdf->SetFont('Arial','',7);
				$pdf->SetY(115);
				$pdf->Cell(22,6,'',0,0,'C',0);
				$pdf->Cell(112,4,"Pago hecho en una sola exhibición ",0,0,'L',0);

				$importe=formatoEntero($importe);
				$iva=formatoEntero($iva);
				$subtotal=formatoEntero($subtotal);
				$retencion=formatoEntero($retencion);
				$totalFacN=$totalFac;
				$totalFac=formatoEntero($totalFac);
				
				$pdf->SetFont('Arial','B',8);
				$pdf->SetXY(140,109);
				$pdf->Cell(37,4,'',0,0,'R',0);
				$pdf->Cell(21,4,$importe,0,0,'R',0);
				
				$pdf->SetFont('Arial','',8);
				$pdf->SetXY(140,113);		
				$pdf->Cell(37,4,'',0,0,'R',0);
				$pdf->Cell(21,4,$iva,0,0,'R',0);
				
				$pdf->SetXY(140,117);
				$pdf->Cell(37,4,'',0,0,'R',0);
				$pdf->Cell(21,4,$subtotal,0,0,'R',0);
				
				$pdf->SetXY(140,121);	
				$pdf->Cell(37,4,'',0,0,'R',0);
				$pdf->Cell(21,4,$retencion,0,0,'R',0);
				
				$pdf->SetFont('Arial','B',8);
				$pdf->SetXY(140,125);	
				$pdf->Cell(37,4,'',0,0,'R',0);
				$pdf->Cell(21,4,$totalFac,0,0,'R',0);
				
				$porcenteje=40;
				$pdf->Cell(15,6,'',0,0,'C',0);
				$pdf->Cell(112,7,"",0,0,'',0);
				$pdf->SetXY(28,149);				
				$pdf->SetFont('Arial','',5);
				$pdf->Cell(20,2,"",0,0,'',0);
						
				$pdf->SetXY(28,123);				
				$pdf->SetFont('Arial','B',8);
				$numero="$totalFacN";
													 
				//Para que lo pueda tomar como una cadena
				$longitud=strlen($numero);
				$posicion=strpos($numero,".");
				if($posicion==""){
					$letras=num2letras($num);
					$letras="( ".$letras.$totalFac." totalFac PESOS 00/100 m.n. )";		
				}else{
					$enteros=substr($numero,0,$posicion);
					$centavos=substr($numero,($posicion+1),$longitud);
					$letras=num2letras($enteros);
					$letras="( ".$letras." PESOS ".$centavos."/100 m.n. )";		
				}
				
				
				$loncadena=$longitud=strlen($letras);
				$pdf->CellFitScale(109,4,$letras,0,0,'C',0);
			}
			else if($formato==2)
			{				
				//Primero verificaremos cuáles son los datos que pidió se mostraran
				$sql="SELECT noGuia,destino,fecha,piezas,peso,tarifa,acuse,flete FROM ccamposenvio WHERE cveFactura='$cveFactura';";
				$camposDis = $bd->Execute($sql);
				$i=0;
				foreach($camposDis as $datos)
				{
					$camposMostrar[0]=$datos[0];
					$camposMostrar[1]=$datos[1];			
					$camposMostrar[2]=$datos[2];			
					$camposMostrar[3]=$datos[3];			
					$camposMostrar[4]=$datos[4];
					$camposMostrar[5]=$datos[5];			
					$camposMostrar[6]=$datos[6];			
					$camposMostrar[7]=$datos[7];			
				}
		
				$pdf->AddPage('P');			
				$pdf->SetXY(110,53);			
				$pdf->Ln(13);
				
				$sql="SELECT cveCliente, cveFactura, fechaFactura, razonSocial, rfc, sucursalCliente, calle, numeroInterior, numeroexterior, colonia, cveMunicipio, cveEstado, codigoPostal, folios, importe, iva, subtotal, retencion, total FROM cfacturas WHERE cveFactura= '$folioRef'";

				$facturas = $bd->Execute($sql);
			
				foreach($facturas as $factura){			
					$importet=$factura["importe"];
					$iva=$factura["iva"];
					$subtotal=$factura["subtotal"];
					$retencion=$factura["retencion"];
					$totalFac=$factura["total"];
					//$retencion=$factura["retencion"]
				}
				
				
				///Aqui comienza la factura
		  
				//Movernos a la derecha
				$pdf->SetFillColor(200,205,255);
				$pdf->SetTextColor(0);
				$pdf->SetDrawColor(0);
				$pdf->SetLineWidth(.2);
				$pdf->SetFont('Arial','',9);
				
				//Espacio en blanco para los margenes
		
				if($camposMostrar[0]==1) $pdf->Cell(23,6,'No. GUIA',0,0,'C',1);
				else					 $pdf->Cell(23,6,'',0,0,'C',1);
				
				if($camposMostrar[1]==1) $pdf->Cell(16,6,'DESTINO',0,0,'C',1);
				else					 $agregados++;
				
				if($camposMostrar[2]==1) $pdf->Cell(16,6,'FECHA',0,0,'C',1);
				else					 $agregados++;
				
				if($camposMostrar[3]==1) $pdf->Cell(16,6,'PIEZAS',0,0,'C',1);
				else					 $agregados++;
				
				if($camposMostrar[4]==1) $pdf->Cell(16,6,'KILOS',0,0,'C',1);
				else					 $agregados++;
				
				if($camposMostrar[5]==1) $pdf->Cell(16,6,'TARIFA',0,0,'C',1);
				else					 $agregados++;
				
				if($camposMostrar[6]==1) $pdf->Cell(16,6,'ACUSE',0,0,'C',1);
				else					 $agregados++;
				
				if($camposMostrar[7]==1) $pdf->Cell(16,6,'FLETE',0,0,'C',1);
				else					 $agregados++;
			
				//Depediendo de los que no se hayan pedido se agregarán las columans para no afectar diseño
				for($i=0;$i<$agregados;$i++)
				{
					$pdf->Cell(16,6,'',0,0,'C',1);
				}
				$agregados=0;
				
				$pdf->Cell(37,6,'',0,0,'C',1);
				$pdf->Cell(27,6,'',0,0,'C',1);
		
		
				
				$i=1;
				$hdnContador=$_POST['hdnContador'];
				//Datos de detalle de Factura
				$sql2="SELECT fechaEntrega, cveGuia, facturaRemicion, PlanificacionFolio, destino, destinatario, observacion, valorDeclarado,piezas, peso, tarifa, TRUNCATE(flete,2) AS flete, seguro,TRUNCATE(acuse,2) AS acuse , TRUNCATE(importe,2) AS importe,TRUNCATE(cveIva,2) AS cveIva,TRUNCATE(subtotal,2) AS subtotal , TRUNCATE(retencionIva,2) AS retencionIva,TRUNCATE(total,2) as total, observacionB FROM cfacturasdetalle WHERE cveFactura= '$folioRef' ";

				$variables = $bd->Execute($sql2);
				$total=count($variables);
		
				
				$inicio=1;
				$ocurridas=1;
		
				$pdf->Ln(6);
				$y=71;
				$parametro=0;
				//Como máximo 28 guías
				foreach($variables as $variable){
					$txtFecha_ =$variable['fechaEntrega'];
					$txtGuia_=$variable['cveGuia'];
					$txtDestino_=$variable['destino'];  
					$txtpiezas_=$variable['piezas'];
					$txtPeso_=$variable['peso'];  
					$txtTarifa_ =$variable['tarifa'];
					$txtFlete_=$variable['flete'];
					$txtTotal=$variable['total'];
					$imported=$variable['importe'];
					$txtacuse=$variable['acuse'];
					$txttarifa=$variable['tarifa'];
					
					$pdf->SetTextColor(0);
					$pdf->SetDrawColor(0);
					$pdf->SetLineWidth(.2);
					$pdf->SetFont('Arial','',8);
					//Espacio en blanco para los margenes
					$y=$y+$parametro;
					$pdf->SetY($y);
					
					if($camposMostrar[0]==1) $pdf->Cell(23,6,$txtGuia_,0,0,'C',0);
					else					 $pdf->Cell(23,6,'',0,0,'C',0);
					
					if($camposMostrar[1]==1) $pdf->Cell(16,6,$txtDestino_,0,0,'C',0);
					else					 $agregados++;
					
					if($camposMostrar[2]==1) $pdf->Cell(16,6,$txtFecha_,0,0,'C',0);
					else					 $agregados++;
					
					if($camposMostrar[3]==1) $pdf->Cell(16,6,$txtpiezas_,0,0,'C',0);
					else					 $agregados++;
					
					if($camposMostrar[4]==1) $pdf->Cell(16,6,$txtPeso_,0,0,'C',0);
					else					 $agregados++;
					
					if($camposMostrar[5]==1) $pdf->Cell(16,6,$txttarifa,0,0,'C',0);
					else					 $agregados++;
					
					if($camposMostrar[6]==1) $pdf->Cell(16,6,$txtacuse,0,0,'C',0);
					else					 $agregados++;
					
					if($camposMostrar[7]==1) $pdf->Cell(16,6,$txtFlete_,0,0,'C',0);
					else					 $agregados++;
					
					//Depediendo de los que no se hayan pedido se agregarán las columans para no afectar diseño
					for($i=0;$i<$agregados;$i++)
					{
						$pdf->Cell(16,6,'',0,0,'C',0);
					}
					$pdf->Cell(37,6,'',0,0,'R',0);
					$pdf->Cell(23,6,$imported,0,0,'R',0);
					
					
					$parametro=3;
					
					if($ocurridas==10)
					{
						if(!(($inicio+1)>$total)){
							$pdf->AddPage();					
						
							//Movernos a la derecha
							$pdf->SetFillColor(200,205,255);
							$pdf->SetTextColor(0);
							$pdf->SetDrawColor(0);
							$pdf->SetLineWidth(.2);
							$pdf->SetFont('Arial','',9);
							
							//Espacio en blanco para los margenes
					
						if($camposMostrar[0]==1) $pdf->Cell(23,6,'No. GUIA',0,0,'C',1);
						else					 $pdf->Cell(23,6,'',0,0,'C',1);
						
						if($camposMostrar[1]==1) $pdf->Cell(16,6,'DESTINO',0,0,'C',1);
						else					 $agregados++;
						
						if($camposMostrar[2]==1) $pdf->Cell(16,6,'FECHA',0,0,'C',1);
						else					 $agregados++;
						
						if($camposMostrar[3]==1) $pdf->Cell(16,6,'PIEZAS',0,0,'C',1);
						else					 $agregados++;
						
						if($camposMostrar[4]==1) $pdf->Cell(16,6,'KILOS',0,0,'C',1);
						else					 $agregados++;
						
						if($camposMostrar[5]==1) $pdf->Cell(16,6,'TARIFA',0,0,'C',1);
						else					 $agregados++;
						
						if($camposMostrar[6]==1) $pdf->Cell(16,6,'ACUSE',0,0,'C',1);
						else					 $agregados++;
						
						if($camposMostrar[7]==1) $pdf->Cell(16,6,'FLETE',0,0,'C',1);
						else					 $agregados++;
					
						//Depediendo de los que no se hayan pedido se agregarán las columans para no afectar diseño
						for($i=0;$i<$agregados;$i++)
						{
							$pdf->Cell(16,6,'',0,0,'C',1);
						}
						$agregados=0;
						
						$pdf->Cell(37,6,'',0,0,'C',1);
						$pdf->Cell(27,6,'',0,0,'C',1);
	
							
							$pdf->Ln(10);
							
							$ocurridas=0;
							$y=71;
							$parametro=0;
						}
					}
								
					$ocurridas++;
						
				}
		
				
				//A partir de la posición de "y" se imprimirán los datos del total
				
				$pdf->SetXY(7,107);
				$pdf->SetFont('Arial','',6);
				$pdf->Cell(18,6,'',0,0,'C',0);
				$pdf->Cell(101,7,'COMPLEMENTO DE LA FACTURA '.$siglasFac.$cveFactura,0,0,'L',0);
				
				$pdf->SetY(112);
				$pdf->SetFont('Arial','',7);
				$pdf->Cell(22,6,'',0,0,'C',0);
				$pdf->Cell(112,4,"NOTA: CONDICIONES DE PAGO A ".$condicionP,0,0,'L',0);
				
				$pdf->SetY(115);
				$porcenteje=40;
				$pdf->Cell(22,6,'',0,0,'C',0);
				$pdf->Cell(112,4,"Pago hecho en una sola exhibición ",0,0,'L',0);
				
				$importe=formatoEntero($importe);
				$iva=formatoEntero($iva);
				$subtotal=formatoEntero($subtotal);
				$retencion=formatoEntero($retencion);
				$totalFacN=$totalFac;
				$totalFac=formatoEntero($totalFac);
				
				$pdf->SetFont('Arial','B',8);
				$pdf->SetXY(140,109);
				$pdf->Cell(37,4,'',0,0,'R',0);
				$pdf->Cell(21,4,$importe,0,0,'R',0);
				
				$pdf->SetFont('Arial','',8);
				$pdf->SetXY(140,113);		
				$pdf->Cell(37,4,'',0,0,'R',0);
				$pdf->Cell(21,4,$iva,0,0,'R',0);
				
				$pdf->SetXY(140,117);
				$pdf->Cell(37,4,'',0,0,'R',0);
				$pdf->Cell(21,4,$subtotal,0,0,'R',0);
				
				$pdf->SetXY(140,121);	
				$pdf->Cell(37,4,'',0,0,'R',0);
				$pdf->Cell(21,4,$retencion,0,0,'R',0);
				
				$pdf->SetFont('Arial','B',8);
				$pdf->SetXY(140,125);	
				$pdf->Cell(37,4,'',0,0,'R',0);
				$pdf->Cell(21,4,$totalFac,0,0,'R',0);
				
				$porcenteje=40;
				$pdf->Cell(15,6,'',0,0,'C',0);
				$pdf->Cell(112,7,"",0,0,'',0);
				$pdf->SetXY(28,149);				
				$pdf->SetFont('Arial','',5);
				$pdf->Cell(20,2,"",0,0,'',0);
						
				$pdf->SetXY(28,123);				
				$pdf->SetFont('Arial','B',8);
				$numero="$totalFacN";													         
				//Para que lo pueda tomar como una cadena
				$longitud=strlen($numero);
				$posicion=strpos($numero,".");
				if($posicion==""){
					$letras=num2letras($num);
					$letras="( ".$letras." PESOS 00/100 m.n. )";		
				}else{
					$enteros=substr($numero,0,$posicion);
					$centavos=substr($numero,($posicion+1),$longitud);
					$letras=num2letras($enteros);
					$letras="( ".$letras." PESOS ".$centavos."/100 m.n. )";		
				}
		
				$loncadena=$longitud=strlen($letras);
				$pdf->CellFitScale(109,4,$letras,0,0,'C',0);

			}
			else if($formato==3)
			{
				$pdf->AddPage();
				$pdf->SetXY(110,53);			
				$pdf->Ln(15);

				
				//fin de cabecera
				$sql="SELECT cveCliente, cveFactura, fechaFactura, razonSocial, rfc, sucursalCliente, calle, numeroInterior, numeroexterior, colonia, cveMunicipio, cveEstado, codigoPostal, folios, importe, iva, subtotal, retencion, total,vales FROM cfacturas WHERE cveFactura= '$folioRef';";
				
				$facturas = $bd->Execute($sql);
		
				foreach($facturas as $factura){			
					$importet=$factura["importe"];
					$iva=$factura["iva"];
					$subtotal=$factura["subtotal"];
					$retencion=$factura["retencion"];
					$totalFac=$factura["total"];
					$retencion=$factura["retencion"];
					$vale=$factura["vales"];
				}
				$facturas = $bd->Execute($sql);
				
				$pdf->SetFont('Arial','',7);
				$pdf->SetX(25);
				$pdf->Cell(95,3,'PLANIFICACIONES DE TRANSPORTE CON SUS CORRESPONDENTES GUÍAS:',0,0,'L',0);
				
				$pdf->Ln(1);
				$y=$pdf->GetY();
				$varios=explode(",",$vale);
				$pdf->SetFont('Arial','B',9);
				$totalvales=count($varios);

				if(($varios[0]!="")||($totalvales>1)){
					for($i=0;$i<(count($varios));$i++)
					{
						$y=$y+3;
						$pdf->SetXY(30,$y);
						$pdf->Cell(90,3,$varios[$i],0,0,'L',0);
					}
				}
				
				$pdf->SetFillColor(200,205,255);
				$pdf->SetTextColor(0);
				$pdf->SetDrawColor(0);
				$pdf->SetLineWidth(.2);
				$pdf->SetFont('Arial','B',9);
				//Espacio en blanco para los margenes
				$pdf->SetXY(25,106);
				$pdf->Cell(90,4,'Servicios realizados a los diferentes destinos',0,0,'L',0);
				$pdf->SetXY(25,110);
				$pdf->Cell(90,4,'según relación anexa  ',0,0,'L',0);
		
				
				$pdf->SetXY(7,101);
				$pdf->SetFont('Arial','',6);
				$pdf->Cell(18,6,'',0,0,'C',0);
				$pdf->Cell(101,7,'COMPLEMENTO DE LA FACTURA '.$siglasFac.$cveFactura,0,0,'L',0);
			
				$pdf->SetY(115);
				$pdf->SetFont('Arial','',8);
				$porcenteje=40;
				$pdf->Cell(22,6,'',0,0,'C',0);
				$pdf->Cell(112,4,"Pago hecho en una sola exhibición",0,0,'L',0);
		
				$importe=formatoEntero($importe);
				$iva=formatoEntero($iva);
				$subtotal=formatoEntero($subtotal);
				$retencion=formatoEntero($retencion);
				$totalFacN=$totalFac;
				$totalFac=formatoEntero($totalFac);
				
				$pdf->SetFont('Arial','B',8);
				$pdf->SetXY(140,109);
				$pdf->Cell(37,4,'',0,0,'R',0);
				$pdf->Cell(21,4,$importe,0,0,'R',0);
				
				$pdf->SetFont('Arial','',8);
				$pdf->SetXY(140,113);		
				$pdf->Cell(37,4,'',0,0,'R',0);
				$pdf->Cell(21,4,$iva,0,0,'R',0);
				
				$pdf->SetXY(140,117);
				$pdf->Cell(37,4,'',0,0,'R',0);
				$pdf->Cell(21,4,$subtotal,0,0,'R',0);
				
				$pdf->SetXY(140,121);	
				$pdf->Cell(37,4,'',0,0,'R',0);
				$pdf->Cell(21,4,$retencion,0,0,'R',0);
				
				$pdf->SetFont('Arial','B',8);
				$pdf->SetXY(140,125);	
				$pdf->Cell(37,4,'',0,0,'R',0);
				$pdf->Cell(21,4,$totalFac,0,0,'R',0);
				
				$porcenteje=40;
				$pdf->Cell(15,6,'',0,0,'C',0);
				$pdf->Cell(112,7,"",0,0,'',0);
				$pdf->SetXY(28,149);				
				$pdf->SetFont('Arial','',5);
				$pdf->Cell(20,2,"",0,0,'',0);
						
				$pdf->SetXY(28,123);				
				$pdf->SetFont('Arial','B',8);
				$numero="$totalFacN";

													 
				//Para que lo pueda tomar como una cadena
				$longitud=strlen($numero);
				$posicion=strpos($numero,".");
				if($posicion==""){
					$letras=num2letras($num);
					$letras="( ".$letras.$totalFac." totalFac PESOS 00/100 m.n. )";		
				}else{
					$enteros=substr($numero,0,$posicion);
					$centavos=substr($numero,($posicion+1),$longitud);
					$letras=num2letras($enteros);
					$letras="( ".$letras." PESOS ".$centavos."/100 m.n. )";		
				}
						
				$loncadena=$longitud=strlen($letras);
				$pdf->CellFitScale(109,4,$letras,0,0,'C',0);
			}			
			
		}
	}

	//FACTURA*********************************************************************************************************************************
	
	//Factura del seguro, el formato del seguro no cambia, siempre será detallado (formato2)
	
	$sqlC="SELECT COUNT(cvefactura) FROM cfacturas WHERE referencia='$cveFactura' AND seguro=1";

	$cuantasf=$bd->soloUno($sqlC);
	
	if($cuantasf>0)
	{
		
		//Primero verificaremos cuáles son los datos que pidió se mostraran
		$sql="SELECT noGuia,destino,fecha,piezas,peso FROM ccamposenvio WHERE cveFactura='$cveFactura';";
		$camposDis = $bd->Execute($sql);
		$i=0;
		foreach($camposDis as $datos)
		{
			$camposMostrar[0]=$datos[0];
			$camposMostrar[1]=$datos[1];			
			$camposMostrar[2]=$datos[2];			
			$camposMostrar[3]=$datos[3];			
			$camposMostrar[4]=$datos[4];			
		}

		$sql3="SELECT cvefactura FROM cfacturas WHERE referencia='$cveFactura'  AND seguro=1";

		$foliosref = $bd->Execute($sql3);
	
		foreach($foliosref as $folioref){ 		
			$pdf->AddPage();
			$pdf->SetXY(110,53);		
			$pdf->Ln(13);
			$folioRef=$folioref["cvefactura"];
			$agregados=0;
			$sql="SELECT cveCliente, cveFactura, fechaFactura, razonSocial, rfc, sucursalCliente, calle, numeroInterior, numeroexterior, colonia, cveMunicipio, cveEstado, codigoPostal, folios, importe, iva, subtotal, retencion, total FROM cfacturas WHERE  cveFactura= '$folioRef' AND seguro=1";

			$facturas = $bd->Execute($sql);
			
			
			foreach($facturas as $factura){
				$facturaSeguro=  $factura["cveFactura"]; 
				$importet=$factura["importe"];
				$iva=$factura["iva"];
				$subtotal=$factura["subtotal"];
				$retencion=$factura["retencion"];
				$total=$factura["total"];
			}
			///Aqui comienza la factura
			//cabecera
			  
			//Arial bold 4
			//Movernos a la derecha
			//fin de cabecera
			$pdf->SetFillColor(200,205,255);
			$pdf->SetTextColor(0);
			$pdf->SetDrawColor(0);
			$pdf->SetLineWidth(.2);
			$pdf->SetFont('Arial','',9);
			//espacio en blanco para los margenes
			//$pdf->Cell(13,4,'',0,0,'C',0);
			
			if($camposMostrar[0]==1) $pdf->Cell(23,6,'No. GUIA',0,0,'C',1);
			else					 $pdf->Cell(23,6,'',0,0,'C',1);
			
			if($camposMostrar[1]==1) $pdf->Cell(16,6,'DESTINO',0,0,'C',1);
			else					 $agregados++;
			
			if($camposMostrar[2]==1) $pdf->Cell(16,6,'FECHA',0,0,'C',1);
			else					 $agregados++;
			
			if($camposMostrar[3]==1) $pdf->Cell(16,6,'PIEZAS',0,0,'C',1);
			else					 $agregados++;
			
			if($camposMostrar[4]==1) $pdf->Cell(16,6,'KILOS',0,0,'C',1);
			else					 $agregados++;
			
			
			//Depediendo de los que no se hayan pedido se agregarán las columans para no afectar diseño
			for($i=0;$i<$agregados;$i++)
			{
				$pdf->Cell(16,6,'',0,0,'C',1);
			}
			$pdf->Cell(16,6,'',0,0,'C',1);
			$pdf->Cell(16,6,'',0,0,'C',1);
			$pdf->Cell(16,6,'SEGURO',0,0,'C',1);
			$pdf->Cell(37,6,'',0,0,'C',1);
			$pdf->Cell(27,6,'',0,0,'C',1);
		
			
			$i=1;
			$hdnContador=$_POST['hdnContador'];
			$sql2="SELECT fechaEntrega, cveGuia, facturaRemicion, PlanificacionFolio, destino, destinatario, observacion, valorDeclarado,piezas, peso, tarifa, TRUNCATE(flete,2) AS flete, seguro,TRUNCATE(acuse,2) AS acuse , TRUNCATE(importe,2) AS importe,TRUNCATE(cveIva,2) AS cveIva,TRUNCATE(subtotal,2) AS subtotal , TRUNCATE(retencionIva,2) AS retencionIva,TRUNCATE(total,2) as total, observacionB FROM cfacturasdetalle WHERE cveFactura= '$folioRef' ";
				
			
			$variables = $bd->Execute($sql2);
			$total=count($variables);
			$inicio=1;
			$ocurridas=1;	
			$pdf->Ln(6);
			$y=71;
			$parametro=0;
		
		
			foreach($variables as $variable)
			{
				$txtFecha_ =$variable['fechaEntrega'];
				$txtGuia_=$variable['cveGuia'];
				$txtDestino_=$variable['destino'];  
				$txtpiezas_=$variable['piezas'];
				$txtPeso_=$variable['peso'];  
				$txtTarifa_ =$variable['tarifa'];
				$txtFlete_=$variable['flete'];
				$totalFac=$variable['total'];
				$imported=$variable['importe'];
				
				$pdf->SetTextColor(0);
				$pdf->SetDrawColor(0);
				$pdf->SetLineWidth(.2);
				$pdf->SetFont('Arial','',8);
				
				$y=$y+$parametro;
				$pdf->SetY($y);
		
				//espacio en blanco para los margenes
				
				$agregados=0;
				
				if($camposMostrar[0]==1) $pdf->Cell(23,6,$txtGuia_,0,0,'C',0);
				else					 $pdf->Cell(23,6,'',0,0,'C',0);
				
				if($camposMostrar[1]==1) $pdf->Cell(16,6,$txtDestino_,0,0,'C',0);
				else					 $agregados++;
				
				if($camposMostrar[2]==1) $pdf->Cell(16,6,$txtFecha_,0,0,'C',0);
				else					 $agregados++;
				
				if($camposMostrar[3]==1) $pdf->Cell(16,6,$txtpiezas_,0,0,'C',0);
				else					 $agregados++;
				
				if($camposMostrar[4]==1) $pdf->Cell(16,6,$txtPeso_,0,0,'C',0);
				else					 $agregados++;
				
				//Depediendo de los que no se hayn pedido se agregarán las columans para no afectar diseño
				for($i=0;$i<$agregados;$i++)
				{
					$pdf->Cell(16,6,'',0,0,'C',0);
				}
				
				$pdf->Cell(16,6,'',0,0,'C',0);
				$pdf->Cell(16,6,'',0,0,'C',0);
				$pdf->Cell(16,6,$imported,0,0,'C',0);
				$pdf->Cell(37,6,'',0,0,'C',0);
				$pdf->Cell(23,6,$imported,0,0,'R',0);
		
				
				$parametro=3;
		
				if($ocurridas==10)
				{
					if(!(($inicio+1)>$total)){
							$pdf->AddPage();
							$agregados=0;
				
							$pdf->SetFillColor(200,205,255);
							$pdf->SetTextColor(0);
							$pdf->SetDrawColor(0);
							$pdf->SetLineWidth(.2);
							$pdf->SetFont('Arial','',9);
							//espacio en blanco para los margenes
		
							if($camposMostrar[0]==1) $pdf->Cell(23,6,'No. GUIA',0,0,'C',1);
							else					 $pdf->Cell(23,6,'',0,0,'C',1);
							
							if($camposMostrar[1]==1) $pdf->Cell(16,6,'DESTINO',0,0,'C',1);
							else					 $agregados++;
							
							if($camposMostrar[2]==1) $pdf->Cell(16,6,'FECHA',0,0,'C',1);
							else					 $agregados++;
							
							if($camposMostrar[3]==1) $pdf->Cell(16,6,'PIEZAS',0,0,'C',1);
							else					 $agregados++;
							
							if($camposMostrar[4]==1) $pdf->Cell(16,6,'KILOS',0,0,'C',1);
							else					 $agregados++;
							
							
							//Depediendo de los que no se hayan pedido se agregarán las columans para no afectar diseño
							for($i=0;$i<$agregados;$i++)
							{
								$pdf->Cell(16,6,'',0,0,'C',1);
							}
							$pdf->Cell(16,6,'',0,0,'C',1);
							$pdf->Cell(16,6,'',0,0,'C',1);
							$pdf->Cell(16,6,'SEGURO',0,0,'C',1);
							$pdf->Cell(37,6,'',0,0,'C',1);
							$pdf->Cell(27,6,'',0,0,'C',1);
		
							$pdf->Ln();
						
						$pdf->Ln(10);
						
						$ocurridas=0;
						$y=74;
						$parametro=0;
					}
				}
				
				$ocurridas++;
			}
		
					
			$pdf->SetXY(7,107);
			$pdf->SetFont('Arial','',6);
			$pdf->Cell(18,6,'',0,0,'C',0);
			$pdf->Cell(101,7,'COMPLEMENTO DE LA FACTURA '.$cveFactura,0,0,'L',0);
			
			$pdf->SetY(112);
			$pdf->SetFont('Arial','',7);
			$pdf->Cell(22,6,'',0,0,'C',0);
			$pdf->Cell(112,4,"NOTA:CONDICIONES DE PAGO A $condicionP",0,0,'L',0);
			
			$pdf->SetY(115);
			$pdf->Cell(22,6,'',0,0,'C',0);
			$pdf->Cell(112,4,"Pago hecho en una sola exhibición.",0,0,'L',0);
		
			
			$importe=formatoEntero($importet);
			$iva=formatoEntero($iva);
			$subtotal=formatoEntero($subtotal);
			$totalFacN=$totalFac;
			$totalFac=formatoEntero($totalFac);
			$retencion=formatoEntero($retencion);
			
			
			
			$pdf->SetFont('Arial','B',8);
			$pdf->SetXY(140,109);
			$pdf->Cell(37,4,'',0,0,'R',0);
			$pdf->Cell(21,4,$importe,0,0,'R',0);
			
			$pdf->SetFont('Arial','',8);
			$pdf->SetXY(140,113);		
			$pdf->Cell(37,4,'',0,0,'R',0);
			$pdf->Cell(21,4,$iva,0,0,'R',0);
			
			$pdf->SetXY(140,117);
			$pdf->Cell(37,4,'',0,0,'R',0);
			$pdf->Cell(21,4,$subtotal,0,0,'R',0);
			
			$pdf->SetXY(140,121);	
			$pdf->Cell(37,4,'',0,0,'R',0);
			$pdf->Cell(21,4,$retencion,0,0,'R',0);
			
			$pdf->SetFont('Arial','B',8);
			$pdf->SetXY(140,125);	
			$pdf->Cell(37,4,'',0,0,'R',0);
			$pdf->Cell(21,4,$subtotal,0,0,'R',0);
			
			$porcenteje=40;
			$pdf->Cell(15,6,'',0,0,'C',0);
			$pdf->Cell(112,7,"",0,0,'',0);
			$pdf->SetXY(28,149);				
			$pdf->SetFont('Arial','',5);
			$pdf->Cell(20,2,"",0,0,'',0);
					
			$pdf->SetXY(28,123);				
			$pdf->SetFont('Arial','B',8);
			$numero="$totalFacN";													         
			//Para que lo pueda tomar como una cadena
			$longitud=strlen($numero);
			$posicion=strpos($numero,".");
			if($posicion==""){
				$letras=num2letras($num);
				$letras="( ".$letras." PESOS 00/100 m.n. )";		
			}else{
				$enteros=substr($numero,0,$posicion);
				$centavos=substr($numero,($posicion+1),$longitud);
				$letras=num2letras($enteros);
				$letras="( ".$letras." PESOS ".$centavos."/100 m.n. )";		
			}
		
			$loncadena=$longitud=strlen($letras);
			$pdf->CellFitScale(109,4,$letras,0,0,'C',0);
		}
	}


	//Imprimir todas
	$pdf->Output();
 
?>

