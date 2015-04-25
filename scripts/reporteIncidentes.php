<?php

require_once('bd.php');
require_once('pdfTable.php');
require_once('libreriaGeneral.php');

class PDF extends PDF_Table
{
	function Header()
	{
	}
	//Cabecera de página
	
	function Footer()
	{
	}
}

//Creación del objeto de la clase heredada
$pdf=new PDF('P','mm','A4');
$pdf->AliasNbPages();	
$pdf->AddPage();
$pdf->SetMargins(1.5,3,1.5);
$pdf->SetDisplayMode(60,'default'); //Para que el zoom este al 100%, normal real

$pdf->SetFont('Arial','',12);
$pdf->SetXY(10,10);
$pdf->Cell(113,5,'',0,0,'C',0);
$pdf->Cell(40,5,'FOLIO ',0,0,'R',0);
$reporte=$_GET['reporte'];
$pdf->Cell(37,5,$reporte,0,0,'L',0);		
$pdf->Ln(5);
$pdf->SetX(10);		
$pdf->Cell(190,5,'Carga y Express, S. A. de C. V.',0,0,'C',0);	
		
$pdf->Ln(6);
$pdf->SetX(10);
$pdf->Cell(190,5,'Reporte de incidentes en la entrega',0,0,'C',0);	
$pdf->Ln(6);
$pdf->SetX(0);
$pdf->Cell(30,5,'',0,0,'C',0);
$pdf->Cell(150,5,'(CLAVE: C/E-013      FECHA: 18/abr/2011      Revisión: 01)',0,0,'C',0);
$pdf->Cell(30,5,'',0,0,'C',0);

//Traemos los datos
$sqlConsulta = "SELECT cveReporte,cveGuia,IF(tipoIncidente=0,'Entrega extemporánea','Daños y Faltantes') AS tipoInc,fechaReporte,estacion,municipio,".
			   "remitente,consignado,lineaAerea,guiaAerea,noVuelo,pzasEnviadas,kgEnviados,pzasEntregadas,elaboro,corroboro,".
			   "kgEntregados,incidentes,descripcionProblema,descripcionProblemaSol,fechaDeteccion,personaDetecta,".
			   "tecnicaSolucion,fechaSolucion,personaSoluciona,descripcionSolucion ".
			   "FROM creporteincidencias ".
			   "WHERE creporteincidencias.cveReporte='".$reporte."'";

$campos = $bd->Execute($sqlConsulta);

foreach($campos as $campo)
{

	//Desarrollo de Reporte
	$pdf->Ln(35);
	$pdf->SetX(10);
	$pdf->SetFont('Arial','',10);
	$fechaReporte=fechaCon($campo["fechaReporte"]);
	$pdf->Cell(30,5,'Fecha de reporte:',0,0,'l',0);
	$pdf->Cell(30,5,$fechaReporte,0,0,'L',0);
	
	$pdf->SetFont('Arial','',10);
	
	$pdf->Ln(10);
	$pdf->SetX(10);
	$pdf->Cell(30,5,'Guía House:',0,0,'L',0);
	$pdf->Cell(30,5,$campo["cveGuia"],0,0,'L',0);
	$pdf->Cell(20,5,'Destino:',0,0,'C',0);
	$pdf->Cell(35,5,$campo["estacion"],0,0,'L',0);
	$pdf->Cell(35,5,'Municipio/Delegación:',0,0,'C',0);
	$pdf->Cell(45,5,$campo["municipio"],0,0,'L',0);
	
	$pdf->Ln(10);
	$pdf->SetX(10);
	$pdf->Cell(30,5,'Remitente:',0,0,'L',0);
	$pdf->Cell(165,5,$campo["remitente"],0,0,'L',0);
	
	$pdf->Ln(10);
	$pdf->SetX(10);
	$pdf->Cell(30,5,'Consignado a:',0,0,'L',0);
	$pdf->Cell(165,5,$campo["consignado"],0,0,'L',0);
	
	$pdf->Ln(10);
	$pdf->SetX(10);
	$pdf->Cell(30,5,'Línea Aérea:',0,0,'L',0);
	$pdf->Cell(30,5,$campo["lineaAerea"],0,0,'L',0);
	$pdf->Cell(20,5,'Guía Master:',0,0,'C',0);
	$pdf->Cell(35,5,$campo["guiaAerea"],0,0,'L',0);
	$pdf->Cell(35,5,'No De Vuelo:',0,0,'C',0);
	$pdf->Cell(45,5,$campo["noVuelo"],0,0,'L',0);
	
	$pdf->Ln(25);
	$pdf->SetX(10);
	$pdf->Cell(42,5,'No De piezas enviadas:',0,0,'L',0);
	$pdf->SetLineWidth(1);
	$pdf->Cell(45,5,$campo["pzasEnviadas"],1,0,'C',0);
	$pdf->SetLineWidth(.1);
	$pdf->Cell(20,5,'',0,0,'C',0);
	$pdf->Cell(42,5,'No De piezas Entregadas:',0,0,'L',0);
	$pdf->SetLineWidth(1);
	$pdf->Cell(45,5,$campo["pzasEntregadas"],1,0,'C',0);
	
	$pdf->Ln(6);
	$pdf->SetX(10);
	$pdf->SetLineWidth(.1);
	$pdf->Cell(42,5,'Kilos enviados:',0,0,'L',0);
	$pdf->SetLineWidth(1);
	$pdf->Cell(45,5,$campo["kgEnviados"],1,0,'C',0);
	$pdf->SetLineWidth(.1);
	$pdf->Cell(20,5,'',0,0,'C',0);
	$pdf->Cell(42,5,'Kilos Entregados:',0,0,'L',0);
	$pdf->SetLineWidth(1);
	$pdf->Cell(45,5,$campo["kgEntregados"],1,0,'C',0);
	
	$pdf->SetLineWidth(.1);
	$pdf->SetFont('Arial','B',14);
	$pdf->Ln(20);
	$pdf->SetX(10);
	$pdf->Cell(195,5,'Incidente:',0,0,'C',0);
	
	$pdf->SetFont('Arial','',8);
	$pdf->Ln(6);
	$pdf->SetX(10);
	$pdf->Cell(15.5,3,'',0,0,'C',0);
	$pdf->Cell(20,3,'Extemporánea',0,0,'C',0);
	$pdf->Cell(20,3,'Mal estado',0,0,'C',0);
	$pdf->Cell(20,3,'Roto',0,0,'C',0);
	$pdf->Cell(20,3,'Mojado',0,0,'C',0);
	$pdf->Cell(20,3,'Robado',0,0,'C',0);
	$pdf->Cell(20,3,'Abierto',0,0,'C',0);
	$pdf->Cell(20,3,'Sucio',0,0,'C',0);
	$pdf->Cell(20,3,'Otro',0,0,'C',0);
	$pdf->Cell(15.5,3,'',0,0,'C',0);
	
	$pdf->SetLineWidth(1);
	$pdf->Ln(4);
	$pdf->SetX(10);
	$pdf->Cell(15.5,5,'',0,0,'C',0);
	$x=$pdf->GetX();
	$y=$pdf->GetY();
	
	$datosIncidentes=explode(",",$campo["incidentes"]);
	$avance=7;
	for($i=0;$i<count($datosIncidentes);$i++)
	{
		if($datosIncidentes[$i]==1)
			$pdf->Image('../imagenes/tache3.jpg',$x+$avance,$y+2.5);
		$avance+=20;
	}
	
	$pdf->Cell(20,10,'',1,0,'C',0);
	$pdf->Cell(20,10,'',1,0,'C',0);
	$pdf->Cell(20,10,'',1,0,'C',0);
	$pdf->Cell(20,10,'',1,0,'C',0);
	$pdf->Cell(20,10,'',1,0,'C',0);
	$pdf->Cell(20,10,'',1,0,'C',0);
	$pdf->Cell(20,10,'',1,0,'C',0);
	$pdf->Cell(20,10,'',1,0,'C',0);
	$pdf->Cell(15.5,10,'',0,0,'C',0);
	
	$pdf->SetFont('Arial','',10);
	$pdf->Ln(25);
	$pdf->SetX(10);
	$pdf->SetLineWidth(.1);
	$pdf->Cell(32,5,'Descripción:',0,0,'L',0);
	$pdf->MultiCell(163,7,$campo["descripcionProblema"],1,'L',0);
	
	$pdf->SetLineWidth(.1);
	
	$pdf->Ln(55);
	$pdf->SetX(10);
	$pdf->CellFitScale(50,5,$campo["elaboro"],0,0,'C',0);
	$pdf->Cell(95,5,'',0,0,'l',0);
	$pdf->CellFitScale(50,5,$campo["corroboro"],0,0,'C',0);
	
	$pdf->Ln();
	$pdf->SetX(10);
	$pdf->Cell(50,5,'Elaboró','T',0,'C',0);
	$pdf->Cell(95,5,'',0,0,'l',0);
	$pdf->Cell(50,5,'Corroboró','T',0,'C',0);
}


//Relizamos el segundo reporte
$pdf->AddPage();

$pdf->SetXY(10,10);
$pdf->SetFont('Arial','',10);
$pdf->Cell(100,5,'Clave: C/E-021','LRB',0,'C',0);
$pdf->Cell(35,5,'Revisión:00','LRB',0,'C',0);
$pdf->Cell(55,5,'Fecha: 03/Nov/2003','RLB',0,'C',0);

$pdf->Ln(20);
$pdf->SetX(10);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(30,5,'',0,0,'C',0);
$pdf->Cell(130,7,'REPORTE DE SOLUCIÓN DEL PROBLEMA',0,0,'C',0);	
$pdf->Cell(30,5,'',0,0,'C',0);

$pdf->SetFont('Arial','',12);
$pdf->SetXY(10,50);
$pdf->Cell(70,7,'Descripción del problema detectado:',0,0,'L',0);	
$pdf->MultiCell(120,7,$campo["descripcionProblemaSol"],0,'L',0);	

   
$y=$pdf->GetY()+20;
$pdf->SetXY(10,$y+7);
$fechaDet=fechaCon($campo["fechaDeteccion"]);
$pdf->Cell(70,7,'Fecha de detección del problema:',0,0,'L',0);	
$pdf->MultiCell(120,7,$fechaDet,0,'L',0);	

$y=$pdf->GetY();
$pdf->SetXY(10,$y+20);
$pdf->Cell(70,7,'Quién detecta el problema:',0,0,'L',0);	
$pdf->MultiCell(120,7,$campo["personaDetecta"],0,'L',0);	

$y=$pdf->GetY();
$pdf->SetXY(10,$y+20);
$pdf->Cell(70,7,'Técnica utilizada en la solución:',0,0,'L',0);	
$pdf->MultiCell(120,7,$campo["tecnicaSolucion"],0,'L',0);	

$y=$pdf->GetY();
$pdf->SetXY(10,$y+20);
$fechaSolucion=fechaCon($campo["fechaSolucion"]);
$pdf->Cell(70,7,'Fecha de solución:',0,0,'L',0);
$pdf->MultiCell(120,7,$fechaSolucion,0,'L',0);

$y=$pdf->GetY();
$pdf->SetXY(10,$y+20);
$pdf->Cell(70,7,'Quién soluciona el problema:',0,0,'L',0);	
$pdf->MultiCell(120,7,$campo["personaSoluciona"],0,'L',0);	

$y=$pdf->GetY();
$pdf->SetXY(10,$y+20);
$pdf->Cell(70,7,'Descripción de la solución:',0,0,'L',0);	
$pdf->MultiCell(120,7,$campo["descripcionSolucion"],0,'L',0);	

$pdf->Output();

?>

