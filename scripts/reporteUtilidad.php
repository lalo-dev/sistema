<?php

require_once('bd.php');
require_once('pdfTable.php');
require_once('libreriaGeneral.php');

session_start();
$sqlFinal=$_SESSION['sqlFinal2'];
$_SESSION['sqlFinal2']='';

$fechaDesde=$_GET['desde'];
$fechaHasta=$_GET['hasta'];


class PDF extends PDF_Table
{
	function Header()
	{
		$fechaDesde=$_GET['desde'];
		$fechaHasta=$_GET['hasta'];

		//Logo
		$this->Image('imagenes/logo.jpeg',4,4,60,20);
		$this->SetFont('Arial','',10);
		
		$this->SetXY(63,6);
		$this->Cell(60,4,'DETALLE DE MOVIMIENTOS',0,0,'L',0);
		$this->SetXY(6,26);
		date_default_timezone_set("America/Mexico_City");
		$fecha = date("d/m/Y");
		$fecha=strtoupper(nombre_mes($fecha,2));	
		$this->Cell(117,4,'M�XICO, D.F., '.$fecha,0,0,'L',0);
		$this->Ln();
		
		$bd = new BD;
		//La consulta es fija, por que la primer empresa siempre ser� Carga y Express		
		$sqlConsulta="SELECT razonSocial,rfc,direccion FROM cempresas WHERE cveEmpresa='1';";		
		$datos = $bd->Execute($sqlConsulta);
		
		foreach($datos as $dato)
		{
			$razonSocial=$dato['razonSocial'];
			$rfc=$dato['rfc'];
			$direccion=$dato['direccion'];			
		}
		$patron = "/\[br\]/";
		$direccion=preg_replace($patron,' ',$direccion);
	
		$this->SetFillColor(200,205,255);
		$this->SetTextColor(0);
		$this->SetDrawColor(0);
		$this->SetLineWidth(.2);
		$this->SetFont('Arial','B',10);
		$this->Ln(8);
		//LTR: Con m�rgen L:izquierdo, T:arriba y R:derecho
		$this->SetX(0);
		$this->SetX(6);
		$this->Cell(94,8,$razonSocial,'LTR',0,'L',0);
		$this->SetFont('Arial','B',8);
		$this->Cell(25,8,'R.F.C.','BRT',0,'L',0);
		$this->CellFitScale(21,8,$rfc,'1',0,'L',0);
		$x=$this->GetX()-46;
		$y=$this->GetY()+8;
		$this->Ln();
		$this->SetX(6);		
		$this->MultiCell(94,7,"Direcci�n: ".$direccion,'BLR','L');
		$this->SetXY($x,$y);
		$fechaDesde=nombre_mes($fechaDesde,2);
		$this->Cell(10,7,'DEL:','B',0,'1',0);
		$this->Cell(36,7,$fechaDesde,'BR',0,'L',0);
		$this->SetXY($x,$y+7);
		$fechaHasta=nombre_mes($fechaHasta,2);
		$this->Cell(10,7,'AL:','B',0,'R',0);
		$this->Cell(36,7,$fechaHasta,'BR',0,'L',0);
		
		$this->Ln(7);      
		$this->SetX(6);		
		$this->Cell(140,6,'RESUMEN DEL PERIODO' ,0,0,'C',0);
		$this->Ln(6);
		$this->SetX(6);		
		$this->Cell(140,6,'Detalle de Movimientos' ,'TRL',0,'C',0);
		$this->Ln();		
		$this->SetX(6);
		
		//Encabezados
		$this->SetFont('Arial','',8);
		$this->SetAligns(array('C','C','C','C','C','C','C','C'));
		$this->SetWidths(array(12,19,16,22,18,18,17,18));
		$this->SetX(6);
		
		$cabeceras[0]='Fecha';
		$cabeceras[1]='Gu�a';
		$cabeceras[2]='Destino';
		$cabeceras[3]='Tipo de Env�o';
		$cabeceras[4]='Cargos';
		$cabeceras[5]='Abono Corr.';
		$cabeceras[6]='Abono G.A';
		$cabeceras[7]='Utilidad';
		$this->Row($cabeceras,6,0);		
		$this->SetX(6);	
	}
	//Cabecera de p�gina
	
	function Footer()
	{
	}
}

//Creaci�n del objeto de la clase heredada
$pdf=new PDF('L','mm','A4');
$pdf->AddFont('lucida','','LCALLIG.php');	
$pdf->AliasNbPages();   
$pdf->AddPage();
$pdf->SetMargins(1.5,0,1.5);
$pdf->SetDisplayMode(80,'default'); //Para que el zoom este al 100%, normal real

//Datos Generales a todas las gu�as
$sqlr="SELECT primerRango,segundoRango,tercerRango FROM ctarifascorresponsales WHERE cveCorresponsal=0";	
$rangos = $bd->Execute($sqlr);

foreach($rangos as $rango)
{
	$rango1= valorRango($rango['primerRango']);
	$rango2= valorRango($rango['segundoRango']);
	$rango3= valorRango($rango['tercerRango']);
}	

$pdf->SetAligns(array('C','C','C','C','R','R','R','R'));
$pdf->SetFont('Arial','',7);
$x=6;
$pdf->SetX($x);
$totalR=0;
$totalPer=13;
$r=0;


//Datos de las gu�as
$campos = $bd->Execute($sqlFinal);

if(count($campos)==0)
{
	$pdf->Cell(140,6,'No hay cuentas asociadas.' ,'BLR',0,'C',0);
}
else{

	foreach($campos as $campo)
	{
		$cveGuia = $campo['cveGuia'];
		$peso    = $campo['peso'];
		$piezas  = $campo['piezas'];
		$seguro  = $campo['seguro'];
		$ctoGuiaA = $campo['ctoGuiaA'];
				
		if($totalR>$totalPer) //Mostrar en la otra parte de la p�gina
		{
			if($totalPer==28) //Se agrega una hoja y se reetablecen algunos valores
			{
				$pdf->AddPage();
				$totalPer=13;
				$totalR=0;
				$x=6;
			}
			else{
				$pdf->SetXY(154,6);
				$pdf->Cell(140,6,'Detalle de Movimientos' ,'TLR',0,'C',0);
				$pdf->Ln(6);
				$pdf->SetX(154);
				$x=154;
				$totalPer=28;
				$totalR=0;
			}
		}
		
		//Obtener los datos de tarifa del cliente
												
		//Buscamos primero si la gu�a ya fue facturada
		$sqlFact="SELECT cfacturasdetalle.cveFactura FROM cfacturasdetalle ".
				 "INNER JOIN cfacturas ON cfacturas.cveFactura=cfacturasdetalle.cveFactura ".
				 "WHERE cfacturas.seguro=0 AND cfacturasdetalle.cveGuia='".$cveGuia."' ".
				 "ORDER BY cfacturasdetalle.cveDetalle DESC LIMIT 1";
					
		$noFact = $bd->soloUno($sqlFact);
		
		if($noFact!="")  //Consultar los datos de la Factura real
		{
			$sqlDatosFactura="SELECT peso,piezas,tarifa,flete,importe,cveIva,retencionIVA,subtotal,total ".
							 "FROM cfacturasdetalle WHERE cveFactura='".$noFact."' AND cveGuia='".$cveGuia."'";
							 
			$datosFact = $bd->Execute($sqlDatosFactura);
			
			foreach($datosFact as $datoFact)
			{
				//Se les da formato a los n�meros
				$peso=redondeaNum($datoFact['peso']);
				$piezas=redondeaNum($datoFact['piezas']);
				$valorDec=redondeaNum($seguro);			
				//$importe=redondeaNum($datoFact['importe']);
				$iva=redondeaNum($datoFact['cveIva']);
				$retIva=redondeaNum($datoFact['retencionIVA']);
				$subtotal=redondeaNum($datoFact['subtotal']);
				$total=redondeaNum($datoFact['total']);
			}
		}
		else		    //Realizar el c�lculo
		{					
			$sqlConsulta="SELECT DISTINCT(ctarifas.cvetipoc) FROM ctarifas ".
						 "INNER JOIN ccliente ON ctarifas.cveTipoc=ccliente.cveTipoCliente ".
						 "INNER JOIN cguias ON cguias.cveCliente=ccliente.cveCliente ".
						 "WHERE cguias.cveGuia='".$cveGuia."'";
			$tipoc=$bd->soloUno($sqlConsulta);
			$tipoE= $campo['tipoEnvio'];

			$tarifaa     = 0;
			$tarifab     = 0;
			$tarifac     = 0;
			$tarifad     = 0;
			$cargoMinimo = 0;
			
			if($tipoc!="" && $tipoE!="")
			{
				$sqlt="SELECT cargo99,cargo299,cargo300,cuartoRango,cargoMinimo FROM ctarifas ".
					  "WHERE estadoOrigen='9' AND estadoDestino='".$campo["estadoDestinatario"]."' ".
					  "AND origen='17' AND destino='".$campo["municipioDestinatario"] ."' AND tipoEnvio='$tipoE' ".
					  "AND cvetipoc='$tipoc' AND estatus=1";
				$tarifas = $bd->Execute($sqlt);

				$i=count($tarifas);
				if($i>0)   //S�lo si se tienen los datos necesarios(cliente,consiganatario y q existe la tarifa), se tendr�n datos de TARIFA
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

			
			if($peso<= $rango1)
			{ $cargo=$tarifaa; }
			
			if($peso > $rango1 AND $peso<= $rango2)
			{ $cargo=$tarifab; }
			
			if($peso> $rango2 AND $peso<= $rango3)
			{ $cargo=$tarifac; }
			
			if($peso>= $rango3)
			{ $cargo=$tarifad; }
		
			$sqlConsulta="SELECT IFNULL(ccliente.cveImpuesto,0) AS cveImpuestoCli ".
						 "FROM ccliente ".
						 "INNER JOIN cguias ON ccliente.cveCliente=cguias.cveCliente ".
						 "WHERE cguias.cveGuia='".$cveGuia."'";
			$porcentajeIva = $bd->soloUno($sqlConsulta);
			
			if($porcentajeIva=='')
				$porcentajeIva=0;
			else
				$porcentajeIva=$porcentajeIva/100;
			
			$flete= $cargo*$peso;
			
			if($flete>$cargoMinimo){$flete=$flete;}else{$flete=$cargoMinimo;}
			
			$acuse=150;
			$importe=$flete+$acuse;
			$iva=$importe*$porcentajeIva;
			$retIva=($importe*.04);
			$subtotal=$iva+$importe;
			$total=$subtotal-$retIva;

			
			//Se les da formato a los n�meros
			$valorDec=redondeaNum($seguro);			
			$importe=redondeaNum($importe);
			$iva=redondeaNum($iva);
			$retIva=redondeaNum($retIva);
			$subtotal=redondeaNum($subtotal);
			$total=redondeaNum($total);
		}
		
		
		//Obtener los datos de tarifa del corresponsal
		
		//Buscamos primero si la gu�a ya fue facturada
		$sqlFact="SELECT cfacturasdetallecorresponsales.cveFactura FROM cfacturasdetallecorresponsales ".
				 "INNER JOIN cfacturascorresponsal ON cfacturascorresponsal.cveFactura=cfacturasdetallecorresponsales.cveFactura ".
				 "WHERE cfacturasdetallecorresponsales.cveGuia='".$cveGuia."' ".
				 "ORDER BY cfacturasdetallecorresponsales.cveDetalle DESC LIMIT 1;";
					
		$noFact = $bd->soloUno($sqlFact);
		
		if($noFact!="") //Consultar los datos de la Factura real
		{
			$sqlDatosFactura="SELECT costoEntrega ".
							 "FROM cfacturasdetallecorresponsales ".
							 "WHERE cveFactura='".$noFact."' AND cveGuia='".$cveGuia."' ".
							 "ORDER BY cveDetalle DESC LIMIT 1;";
							 
			$costoEntrega = $bd->soloUno($sqlDatosFactura);					
		}
		else		    //Obtenemos el costo de entrega	  
		{
				//Consultar si hay tarifa para la gu�a
			$sqlConsulta="SELECT cguias.piezas,".
					 "rango1,rango2,rango3,rango4,".					 
					 "datostarifas.primerRango,datostarifas.segundoRango,datostarifas.Tercerrango,datostarifas.cuartoRango,".
					 "datostarifas.tipoEnvio,datostarifas.cargoMinimo,".
					 "datostarifas.municipioDestino,cconsignatarios.municipio,".
					 "datostarifas.estadoDestino,cconsignatarios.estado ".
					 "FROM cconsignatarios,cguias ".
					 "INNER JOIN (".
								 "SELECT cdetalletarifa.tipoEnvio,cdetalletarifa.cargoMinimo,".
								 "ctarifascorresponsales.estadoDestino,ctarifascorresponsales.municipioDestino,".								 
								 "cdetalletarifa.primerRango,cdetalletarifa.segundoRango,".								 
								 "cdetalletarifa.Tercerrango,cdetalletarifa.cuartoRango,".								 								 
								 "ctarifascorresponsales.primerRango AS rango1,ctarifascorresponsales.segundoRango AS rango2,".								 
								 "ctarifascorresponsales.tercerRango AS rango3,ctarifascorresponsales.cuartoRango AS rango4 ".
								 "FROM cdetalletarifa ".
								 "INNER JOIN ctarifascorresponsales ".
								 "ON ctarifascorresponsales.cveCorresponsal=cdetalletarifa.cveCorresponsal ".
								 "AND ctarifascorresponsales.estadoOrigen=9 ".
								 "AND ctarifascorresponsales.municipioOrigen=17 ".
								 ") AS datostarifas ".
					"ON cguias.tipoEnvio=datostarifas.tipoEnvio ".
					"WHERE cguias.cveGuia='".$cveGuia."' ".
					"AND datostarifas.municipioDestino=cconsignatarios.municipio ".
					"AND datostarifas.estadoDestino=cconsignatarios.estado ".
					"AND cguias.cveConsignatario=cconsignatarios.cveConsignatario ".
					"LIMIT 1;"; 

			$datosCorresponsal = $bd->Execute($sqlConsulta);

			if(count($datosCorresponsal)==0)  //Si no hay tarifa registrada,el costo ser� de 0
			{
				$costoEntrega=0;
			}
			else  							 //Realizar el c�lculo
			{
				foreach($datosCorresponsal as $datosC)
				{	
					$rango1= valorRango($datosC['rango1']);
					$rango2= valorRango($datosC['rango2']);
					$rango3= valorRango($datosC['rango3']);
						
					if($peso<= $rango1)
					{
						$cargo=$datosC['primerRango'];
					}
					if($peso > $rango1 AND $peso<= $rango2){
						$cargo=$datosC['segundoRango'];
					}
					if($peso> $rango2 AND $peso<= $rango3){
						$cargo=$datosC['Tercerrango'];
					}
					if($peso>= $rango3 ){
						$cargo=$datosC['cuartoRango'];
					}
					$costoEntrega=$peso*$cargo;
					$cargoMinimo=$datosC['cargoMinimo'];
					if($costoEntrega<$cargoMinimo)
						$costoEntrega=$cargoMinimo;
				}
			}
		}

		$totalCobrar=$total-$costoEntrega-$ctoGuiaA;
		
		list($anyo,$mes,$dia)=explode("-",$campo["recepcionCYE"]);
		
		$fechaFinal=$dia."/".$mes."/".$anyo[2].$anyo[3];
		
		$valores[0]=$fechaFinal;
		$valores[1]=$cveGuia;
		$valores[2]=$campo["estacion"];
		$valores[3]=$campo["tipoEnvio"];
		$valores[4]="$".number_format($total,2);
		$valores[5]="$".number_format($costoEntrega,2);
		$valores[6]="$".number_format($ctoGuiaA,2);
		$valores[7]="$".number_format($totalCobrar,2);
		$pdf->Row($valores,6,0);  
		$pdf->SetX($x);
		$totalR++;
		$r++;
	}
}

$pdf->Output();

?>

