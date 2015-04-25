<?php

/**
 * @author Jose Miguel Pantaleon
 * @copyright 2010
 */

include_once("bd.php");
include_once("libreriaGeneral.php");

$j=0;
$fechai=$_GET['fechaI'];
$fechaF=$_GET['fechaF'];
$fechaDia=cambiaf_a_mysql($fechai);
$fechaFin=cambiaf_a_mysql($fechaF);

require('pdfTable.php');


class PDF extends PDF_Table
{
	//Cabecera de página
	function Header()
	{
		//Movernos a la derecha
		$this->SetY(5);
		$this->SetFont('lucida','','LCALLIG.php');
		$this->SetFont('lucida', '',8);
		$this->SetX(5);
		$this->Cell(286.6, 3, 'CARGA Y EXPRESS, S. A. DE C. V.' ,0, 0, 'C', 0);
		$this->SetY(8);
		$this->SetFont('lucida', '',9);
		$this->SetX(5);
		$this->Cell(115,4, 'Reporte de Operaciones',0, 0, 'R', 0);
		$this->Cell(65,4,'(clave C/E - 005   Fecha:03/Nov/2003',0, 0, 'L', 0);
		$this->Cell(25,4, ' Revision 00)',0, 0,'L',0);		
		$this->SetFont('lucida','',9);
		$fechaHoy=fecha(date('d-m-Y'),0);
		$this->Cell(81.6,4,'FECHA: '.$fechaHoy,0, 0, 'R', 0);
		$this->Cell(81.6,4,'FECHA: '.$fechaHoy,0, 0, 'R', 0);
		$this->SetXY(0,0);
		$this->SetLineWidth(.1);
		$this->Line(199.5,8,199,8.5);	
		$this->SetFont('Arial','B',7);
		$this->SetY(15);
		$this->SetFont('lucida','','7');
		$this->SetFillColor(200,205,255);
		$this->SetX(5);
		$this->Cell(286.6,3.5,'' ,0, 0, 'C', 1);
		$this->SetXY(5,15);
		$this->MultiCell(14,3.5,'Linea Aerea',0,'C', 1);
		$this->SetXY(19,15);
		$this->MultiCell(16,3.5,'No. Guia Aerea',0,'C', 1);
		$this->SetXY(35,18.5);
		$this->Cell(18,3.5,'Fecha',0, 0, 'C', 1);
		$this->SetXY(53,15);
		$this->MultiCell(16,3.5,'Guia House',0,'C', 1);
		$this->SetXY(69,18.5);
		$this->Cell(40,3.5,'Cliente',0, 0, 'C', 1);
		$this->SetXY(109,18.5);
		$this->Cell(19,3.5,'Destino',0, 0, 'C', 1);
		$this->SetXY(128,18.5);
		$this->Cell(10,3.5,'Pzas',0,0, 'C', 1);
		$this->SetXY(138,18.5);
		$this->Cell(10,3.5,'Kilos',0,0, 'C', 1);
		$this->SetXY(148,18.5);
		$this->Cell(19,3.5,'Volumen',0, 0, 'C', 1);
		$this->SetXY(167,18.5);
		$this->Cell(18,3.5,'Vigencia',0, 0, 'C', 1);
		$this->SetXY(185,18.5);
		$this->Cell(37,3.5,'Observacion',0, 0, 'C', 1);	
		$this->SetXY(222,18.5);
		$this->SetLineWidth(.1);
		$this->Line(210,18.9,209.5,19.4);
		$this->Cell(37.6,3.5,'Destinatario',0, 0, 'C', 1);	
		$this->SetXY(259.6,15);
		$this->MultiCell(14,3.5,'No. Vuelo',0,'C', 1);
		$this->SetXY(273.6,18.5);
		$this->Cell(18,3.5,'Fecha',0, 0, 'C', 1);	
		$this->Ln(3.5);
		$this->SetX(5);
		$this->SetFont('Arial','',9);
	}
		
	function Footer()
	{
		//Posición: a 1 cm del final
		$this->SetXY(5,-10);
		$this->SetFont('Arial','',10);
		//Número de página
		$numero=$this->PageNo();
		$this->Cell('280',4,$numero,0,0,'R');
	}
}

$pdf=new PDF('L','mm','A4');
$pdf->AddFont('lucida','','LCALLIG.php');	
$pdf->AliasNbPages();   
$pdf->AddPage();
$pdf->SetMargins(1.5,0,1.5);
$pdf->SetDisplayMode(80,'default'); //Para que el zoom este al 100%, normal real	

$sql="SELECT cveLineaArea ,guiaArea,recepcionCYE,cveGuia,IFNULL(LEFT(ccliente.razonSocial,18),'Sin Asignar'),
cconsignatarios.estacion,piezas,kg,volumen,validezDias,
LEFT(tipoEnvio,14),IFNULL(LEFT(cconsignatarios .nombre,14),'Sin Asignar'),
noVuelo,fechaVuelo
FROM cguias LEFT JOIN ccliente ON cguias.cveCliente=ccliente.cveCliente 
LEFT JOIN cconsignatarios ON cguias.cveConsignatario = cconsignatarios.cveConsignatario
WHERE cguias.estatus=1 AND recepcionCYE
BETWEEN  '".$fechaDia."'
AND  '".$fechaFin."'
ORDER BY  cconsignatarios.estacion ASC, cguias.cveCliente  ASC,cveLineaArea  ASC, recepcionCYE  ASC";


$valores = $bd->Execute($sql);
$pdf->SetFont('Arial','',9);
$tmpDestino="";
$tmpLinea="";
$i=0;

$totalPzs=0;
$totalGuias=0;
$totalKilos=0;
$totalVol=0;


foreach($valores as $valor)
{
	//Asignar valores a variables
	$linea=$valor[0];
	$noGuia=$valor[1];
	$fechaGuia=fechaCon($valor[2]);
	$guiaHouse=$valor[3];
	$cliente=$valor[4];
	$destino=$valor[5];
	$piezas=$valor[6];
	$peso=$valor[7];	
	$volumen=$valor[8];
	if($valor[9]!='0000-00-00')
		$fechaVig=fechaCon($valor[9]);
	else
		$fechaVig="";

	$tipoEnvio=$valor[10];
	$destinatario=$valor[11];
	$noVuelo=$valor[12];
	if($valor[13]!='0000-00-00')
		$fechaVuelo=fechaCon($valor[13]);
	else
		$fechaVuelo="";
		
	if(($tmpDestino!=$destino || $tmpLinea!=$noGuia) && $i!=0)//Para línea en blanco al cambiar Destino o la Guía Aerea
	{
		$pdf->SetX(5);
		$pdf->Cell(14,8,'',1,0,'L',0);	
		$pdf->Cell(16,8,'',1,0,'R',0);	
		$pdf->Cell(18,8,'',1,0,'R',0);	
		$pdf->Cell(16,8,'',1,0,'R',0);	
		$pdf->Cell(40,8,'',1,0,'C',0);	
		
		$pdf->Cell(19,8,'',1,0,'C',0);	
		$pdf->Cell(10,8,'',1,0,'C',0);	
		$pdf->Cell(10,8,'',1,0,'C',0);	
		$pdf->Cell(19,8,'',1,0,'C',0);	
		
		$pdf->Cell(18,8,'',1,0,'R',0);	
		$pdf->Cell(37,8,'',1,0,'L',0);	
		$pdf->Cell(37.6,8,'',1,0,'L',0);	
		$pdf->Cell(14,8,'',1,0,'L',0);	
		$pdf->Cell(18,8,'',1,0,'L',0);	
		$pdf->Ln(8);
	}	

	//Imprimir
	$pdf->SetX(5);
	if($linea=="")	
		$pdf->Cell(14,8,'',1, 0,'C',0);	   		
	else
		$pdf->CellFitScale(14,8,$linea,1, 0,'L',0);		    //En caso de que los caracteres traídos abarcaran más espacio
	$pdf->Cell(16,8,$noGuia,1, 0,'R',0);	
	$pdf->Cell(18,8,$fechaGuia,1,0,'R',0);				
	$pdf->Cell(16,8,$guiaHouse,1, 0,'R',0);	
	if($cliente=="")	
		$pdf->Cell(40,8,'',1, 0,'C',0);	   		
	else
		$pdf->CellFitScale(40,8,$cliente,1, 0,'C',0);	   //En caso de que los caracteres traídos abarcaran más espacio
	
	if($destino=="")	
		$pdf->Cell(19,8,'',1, 0,'C',0);	   		
	else
		$pdf->CellFitScale(19,8,$destino,1, 0, 'C',0);	   //En caso de que los caracteres traídos abarcaran más espacio
	$pdf->Cell(10,8,$piezas,1, 0, 'C',0);	
	$pdf->Cell(10,8,$peso,1, 0, 'C',0);	
	$pdf->Cell(19,8,$volumen,1, 0, 'C',0);	
	
	$pdf->Cell(18,8,$fechaVig,1, 0, 'R',0);	
	$pdf->Cell(37,8,$tipoEnvio,1, 0, 'L',0);	
	$pdf->Cell(37.6,8,$destinatario,1, 0, 'L',0);	
	$pdf->CellFitScale(14,8,$noVuelo,1, 0, 'L',0);	
	$pdf->Cell(18,8,$fechaVuelo,1, 0, 'L',0);	
	$pdf->Ln(8);
	$i++;
	$tmpDestino=$destino;
	$tmpLinea=$noGuia;

	//Totales
	$totalPzs+=$piezas;
	$totalGuias++;
	$totalKilos+=$peso;
	$totalVol+=$volumen;
}

//Imprimir Totales
$pdf->SetX(5);
$pdf->Cell(14,8,'',0, 0,'L',0);		   
$pdf->Cell(16,8,'',0, 0,'R',0);	
$pdf->Cell(18,8,'No Guías',0,0,'R',0);		
$pdf->Cell(16,8,$totalGuias,0, 0,'R',0);	
$pdf->Cell(40,8,'',0, 0,'C',0);	   
$pdf->Cell(19,8,'',0, 0, 'C',0);	   
$pdf->Cell(10,8,$totalPzs,0, 0,'C',0);	   
$pdf->Cell(10,8,$totalKilos,0, 0, 'C',0);	   
$pdf->Cell(19,8,$totalVol,0,0, 'C',0);	
$pdf->Cell(153.6,8,'',0, 0, 'C',0);

         
$pdf->Output();

?>
