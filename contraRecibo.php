<?php

/**
 * @author Jose Miguel Pantaleon
 * @copyright 2010
 */

include_once("bd.php");
include_once("libreriaGeneral.php");
require('pdfTable.php');


class PDF extends PDF_Table
{
	//Cabecera de página
	function Header()
	{
		
	}
	
	
	function Footer()
	{

	}
}

$pdf=new PDF('P','mm','A4');
$pdf->AddFont('lucida','','LCALLIG.php');	
$pdf->AliasNbPages();   
$pdf->AddPage();
$pdf->SetMargins(3,2,3);
$pdf->SetDisplayMode(80,'default'); //Para que el zoom este al 80%, normal real	

//Primer encabezado
$pdf->SetFillColor(204,204,204);
$pdf->SetXY(10,9);
$pdf->Cell(189.6,22,'',0, 0, 'C',1);
$pdf->SetXY(11,18);
$pdf->SetFont('Arial','I',20);
$pdf->Cell(62,8,'CONTRA RECIBO',0,0, 'L',1);


$pdf->SetLineWidth(.4);
$pdf->SetXY(79,12);
$pdf->SetFont('Arial','I',12);
$pdf->SetFillColor(255);
$pdf->Cell(60,5,'FECHA',1, 0, 'C',1);
$pdf->SetXY(79,17);
$pdf->SetFont('Arial','B',12);
$fechaHoy=fecha(date('d-m-Y'),1);
$pdf->Cell(60,8,$fechaHoy,1, 0, 'C',1);

$pdf->SetXY(155,12);
$pdf->SetFont('Arial','I',12);
$pdf->SetFillColor(204,204,204);
$pdf->Cell(40,5,'NÚMERO',0, 0, 'C',1);
$pdf->SetFillColor(255);
$pdf->SetXY(155,17);
$pdf->SetFont('Arial','B',20);
$pdf->Cell(40,9,'',1, 0, 'C',1);
$pdf->SetXY(156,18);

//Obtenemos el número de contrarecibo
$numero=$_GET["noContra"];
$pdf->Cell(38,7,$numero,1, 0, 'C',1);

//Segundo encabezado
$pdf->Ln(24);
$pdf->SetX(10);
$pdf->SetFont('Arial','IB',11);
$pdf->Cell(189.6,7,'RECIBIMOS DE CARGA Y EXPRESS, S.A. DE C.V. LOS SIGUIENTES DOCUMENTOS A REVISIÓN:',0,0,'L',1);

//Encabezado de Facturas
$pdf->Ln(15);
$pdf->SetX(10);
$pdf->SetFont('Arial','I',10);
$pdf->Cell(30,5,'FACTURA',0, 0, 'C',1);
$pdf->Cell(30,5,'FECHA',0, 0, 'C',1);
$pdf->Cell(30,5,'IMPORTE',0, 0, 'C',1);
$pdf->Cell(90,5,'OBSERVACIÓN',0, 0, 'C',1);
$pdf->Ln(5);
$pdf->SetX(10);
$pdf->SetFont('Arial','',10);

//Consultamos los datos del contrarecibo
$sql="SELECT cveFactura,importe,fechaFactura FROM ccontrarecibo WHERE noContrarecibo='".$numero."';";
$facturas = $bd->Execute($sql);

$totalFactura=0;

foreach($facturas as $factura){
	//Desgloce de factura(s)
	$facturaFolio=$factura['cveFactura'];
	$facturaFecha=$factura['fechaFactura'];
	$facturaFecha=convertirFecha($facturaFecha);
	$facturaImporte=$factura['importe'];

	$totalFactura+=$facturaImporte;
	$pdf->Cell(30,5,$facturaFolio,0, 0, 'C',1);
	$pdf->Cell(30,5,$facturaFecha,0, 0, 'C',1);
	$pdf->Cell(30,5,$facturaImporte,0, 0, 'C',1);
	$pdf->Ln(5);
	$pdf->SetX(10);
}

$y=$pdf->GetY();

//"Encabezados del Contra Recibo"
$pdf->Ln(35);
$pdf->SetX(10);
$pdf->SetFont('Arial','BI',10);
$pdf->Cell(40,5,'TOTAL:',0, 0,'R',1);
$cantidad=number_format($totalFactura,2);
$pdf->SetFont('Arial','',10);
$pdf->CellFitScale(30,5,'$'.$cantidad,1, 0,'C',1);

$pdf->SetX(120);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(40,5,'FECHA DE PAGO:',0, 0,'R',1);
$pdf->Cell(40,5,'',1, 0,'R',1);

$pdf->SetXY(103,$y+20);
$pdf->SetFont('Arial','B',8);
$pdf->Cell(57,5,'DÍA DE REVISIÓN Y HORARIO:',1, 0,'R',1);
$pdf->Cell(40,5,'',1, 0,'',1);
$pdf->SetXY(103,$y+25);
$pdf->Cell(57,5,'DÍA DE PAGO Y HORARIO:',1, 0,'R',1);
$pdf->Cell(40,5,'',1, 0,'',1);



//Sello
$pdf->Ln(16);
$pdf->SetX(10);
$pdf->Cell(92.8,28,'',1, 0,'',1);
$pdf->Cell(4,28);
$pdf->Cell(92.8,28,'',1, 0,'',1);

$pdf->Ln(19);
$pdf->SetX(11.6);
$pdf->SetFont('Arial','',8);
$cliente=$_GET["cliente"];
$pdf->CellFitScale(90,4,$cliente,0, 0,'C',1);
$pdf->Ln(4);
$pdf->SetX(11.6);
$pdf->SetFont('Arial','',11);
$pdf->Cell(90,4,'FIRMA',0, 0,'C',1);
$pdf->Cell(6.5,28);
$pdf->Cell(90,4,'SELLO',0, 0,'C',1);

$pdf->Output();

//Recibe la fecha en formato 2011-10-01, regresa dd/M/Y pe: 2010-10/01 --- > 01/oct/2010
function convertirFecha($fecha) 
{
	
	$meses=array('ENE','FEB','MAR','ABR','MAY','JUN','JUL','AGO','SEP','OCT','NOV','DIC');
	
	list($anyo,$mes,$dia)=explode("-",$fecha);
	$mes=$mes-1;
	$mesFinal = $meses[$mes];
	
	$fechaFinal=strtolower($dia."-".$mesFinal."-".$anyo);
	
	return $fechaFinal;
}

?>
