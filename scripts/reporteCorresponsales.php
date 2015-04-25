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
		$this->SetFont('Arial', 'B',8);
		$this->SetX(5);
		$this->Cell(56.6,5,'',0, 0, 'C', 0);
		$this->Cell(200,5, 'CARGA Y EXPRESS, S. A. DE C. V.' ,0, 0, 'l', 0);
		$this->SetXY(5,10);
		date_default_timezone_set("America/Mexico_City");
		$fechaHoy=strtoupper(fecha(date('d-m-Y'),1));
		$this->Cell(56.6,5, '' ,0, 0, 'C', 0);
		$this->Cell(200, 5, 'LISTA DE CONSIGNATARIOS AL '.$fechaHoy ,0, 0, 'L', 0);
		
		$this->SetFont('Arial','B',9);
		$this->SetY(20);
		$this->SetFillColor(200,205,255);
		$this->SetXY(53,18);
		$this->Cell(34,7,'Clave Consignatario',0,0,'C', 1);
		$this->Cell(100,7,'Nombre Consignatario',0, 0, 'l', 1);
		$this->Cell(22,7,'Estación',0, 0, 'l', 1);
		$this->Cell(59,7,'Estado',0,0, 'l', 1);
		$this->Ln(7);
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
		$this->Cell('280',4,"Página: ".$numero,0,0,'R');
	}
}

$pdf=new PDF('L','mm','A4');
$pdf->AddFont('lucida','','LCALLIG.php');	
$pdf->AliasNbPages();   
$pdf->AddPage();
$pdf->SetMargins(1.5,0,1.5);
$pdf->SetDisplayMode(80,'default'); //Para que el zoom este al 100%, normal real	

$sql="SELECT cveConsignatario,cconsignatarios.nombre,estacion,cestados.nombre
FROM cconsignatarios 
INNER JOIN cestados ON cconsignatarios.estado=cestados.cveEstado 
ORDER BY cconsignatarios.nombre ASC;";


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
	$claveCon=$valor[0];
	$nombreCon=$valor[1];
	$destinoCon=$valor[2];
	$estadoCon=$valor[3];

	//Imprimir
	$pdf->SetX(53);
	$pdf->Cell(34,7,$claveCon,0,0,'C',0);
	if($nombreCon!="")
		$pdf->CellFitScale(100,7,$nombreCon,0, 0,'L',0);
	else
		$pdf->Cell(100,7,'',0, 0,'L',0);
	$pdf->Cell(22,7,$destinoCon,0, 0, 'L',0);
	$pdf->Cell(50,7,$estadoCon,0, 0, 'L',0);
	
	$pdf->Ln(8);
	$i++;

}
      
$pdf->Output();

?>
