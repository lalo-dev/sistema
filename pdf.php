<?php
 //conexion
  

require('clasepdf/fpdf.php');
class PDF extends FPDF
{
//Cabecera de p�gina
function Header()
{
	//Logo
	
	//Arial bold 15
	$this->SetFont('Arial','B',9);
	//Movernos a la derecha
	$this->Cell(55);
	//T�tulo
	$folio = 1000;
	$uno = " Folio: " . $folio ;
	$this->Cell(130,10,"$uno",0,0,0);
	//Salto de l�nea  
	$this->Ln(20);
}
//Pie de p�gina
function Footer()
{
	//Posici�n: a 1,5 cm del final
	$this->SetY(-15);
	//Arial italic 8
	$this->SetFont('Arial','I',8);
	//N�mero de p�gina
	$this->Cell(0,10,'P�gina '.$this->PageNo().'/{nb}',0,0,'C');
}
}
$reg="esto es una prueva";
$pdf=new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFillColor(50,20,220);
	$pdf->SetTextColor(255);
	$pdf->SetDrawColor(128,0,0);
	$pdf->SetLineWidth(.3);
	$pdf->SetFont('','B');
	//Cabecera
	$pdf->Cell(192,7,'Control MAD',1,0,'C',1);
	$pdf->Ln();
	//Restauraci�n de colores y fuentes
	$pdf->SetFillColor(224,235,255);
	$pdf->SetTextColor(0);
	$pdf->SetFont('','',7);
	//Datos
		$pdf->SetFont('','B',7);
		$pdf->Cell(33,6,'Hora','LR',0,'R',1);
		$pdf->SetFont('','',7);
		$pdf->Cell(27,6,$rgc['horareg'] ,'LR',0,'L',1);
		$pdf->SetFont('','B',7);
		$pdf->Cell(30,6,'Fecha','LR',0,'R',1);
		$pdf->SetFont('','',7);
		$pdf->Cell(25,6,$rgc['fechaalta'] ,'LR',0,'L',1);
		$pdf->SetFont('','B',7);
		$pdf->Cell(25,6,'Suscriptor','LR',0,'R',1);
		$pdf->SetFont('','',7);
		$pdf->Cell(52,6,$rgc['razonsocial'],'LR','L',1);
		//$pdf->Ln();
		$pdf->SetFont('','B',7);
		$pdf->Cell(33,6,'Fecha/Alta','LR',0,'R',0);
		$pdf->SetFont('','',7);
		$pdf->Cell(27,6,$rgc['fechaestudio'] ,'LR',0,'L',0);
		$pdf->SetFont('','B',7);
		$pdf->Cell(30,6,'Fecha/Baja','LR',0,'R',0);
		$pdf->SetFont('','',7);
		$pdf->Cell(25,6,$rgc['fechabaja'] ,'LR',0,'L',0);
		$pdf->SetFont('','B',7);
		$pdf->Cell(25,6,'D�as Ocupados','LR',0,'R',0);
		$pdf->SetFont('','',7);
		$pdf->Cell(52,6,$rgc['diasocupados'],'LR',0,'L',0);
		$pdf->Ln();
		$pdf->SetFont('','B',7);
		$pdf->Cell(33,6,'Fecha Envio/Complemento','LR',0,'R',1);
		$pdf->SetFont('','',7);
		$pdf->Cell(27,6,$rgc['fechaenvio'] ,'LR',0,'L',1);
		$pdf->SetFont('','B',7);
		$pdf->Cell(30,6,'D�as Totales Ocupados','LR',0,'R',1);
		$pdf->SetFont('','',7);
		$pdf->Cell(25,6,$rgc['dtocupados'] ,'LR',0,'L',1);
		$pdf->SetFont('','B',7);
		$pdf->Cell(25,6,'Servicio','LR',0,'R',1);
		$pdf->SetFont('','',7);
		$pdf->Cell(52,6,$rgc['servicio'],'LR',0,'L',1);
		$pdf->Ln();
		$pdf->SetFont('','B',7);
		$pdf->Cell(33,6,'Costo','LR',0,'R',0);
		$pdf->SetFont('','',7);
		$pdf->Cell(27,6,$rgc['costo'] ,'LR',0,'L',0);
		$pdf->SetFont('','B',7);
		$pdf->Cell(30,6,'Anexo','LR',0,'R',0);
		$pdf->SetFont('','',7);
		$pdf->Cell(25,6,$rgc['anexo'] ,'LR',0,'L',0);
		$pdf->SetFont('','B',7);
		$pdf->Cell(25,6,'Folio','LR',0,'R',0);
		$pdf->SetFont('','',7);
		$pdf->Cell(52,6,$rgc['folio'],'LR',0,'L',0);
		$pdf->Ln();
		$pdf->SetFont('','B',7);
		$pdf->Cell(33,6,'Reporte de Incidencias','LR',0,'R',1);
		$pdf->SetFont('','',7);
		$pdf->MultiCell(159,6,$rgc['reporteinc'],'LR','L',1);
		$pdf->Cell(152,0,'','T');
		$pdf->Ln();
// ++++++++++++++++++++++ Termina Control MAD
//Validaci�n de Pesta�as

// ******************* Operativo ++++++++++++++++++++++++++++++++++++++++++++++++++++++
	//cambiar de p�gina
//	$pdf->AddPage();
	//Tabla coloreada
	//Colores, ancho de l�nea y fuente en negrita
	$pdf->SetFillColor(50,20,220);
	$pdf->SetTextColor(255);
	$pdf->SetDrawColor(128,0,0);
	$pdf->SetLineWidth(.3);
	$pdf->SetFont('','B',10);
	//Cabecera
	$pdf->Cell(192,5,'REPORTE DE INVESTIGACION PERSONA FISICA TITULAR',1,0,'C',1);
	$pdf->Ln();
	//Restauraci�n de colores y fuentes
	$pdf->SetFillColor(224,235,255);
	$pdf->SetTextColor(0);
	$pdf->SetFont('','',7);
	
	//Datos
   //consulta
     //detalle 
   		$pdf->SetFont('','B',7);
  		$pdf->Cell(32,5,'Contrato No:','LR',0,'L',0);
		$pdf->SetFont('','',7);
		$pdf->Cell(62,5,"alfa 10",'LR',0,'L',0);
		$pdf->SetFont('','B',6);
		$pdf->Cell(32,5,'Fecha de Investigaci�n:','LR',0,'L',0);
		$pdf->SetFont('','',6);
		$pdf->Cell(66,5,"alfa 50",'LR',0,'L',0);
	$pdf->Ln();
		//$pdf->Ln();
	//Colores, ancho de l�nea y fuente en negrita
	$pdf->SetFillColor(50,20,220);
	$pdf->SetTextColor(255);
	$pdf->SetDrawColor(128,0,0);
	$pdf->SetLineWidth(.3);
	$pdf->SetFont('','B',9);
	//Cabecera
	$pdf->Cell(192,5,'DATOS GENERALES DEL ACREDITADO:',1,0,'C',1);
	$pdf->Ln();
	//Restauraci�n de colores y fuentes
	$pdf->SetFillColor(224,235,255);
	$pdf->SetTextColor(0);
	$pdf->SetFont('','',7);
		$pdf->SetFont('','B',7);
		$pdf->Cell(32,5,'Nombre:','LR',0,'L',0);
		$pdf->SetFont('','',7);
		$pdf->Cell(130,5,"alfa 50",'LR',0,'L',0);
		$pdf->SetFont('','B',7);
		$pdf->Cell(8,5,'RFC:','LR',0,'L',0);
		$pdf->SetFont('','',7);
		$pdf->Cell(22,5,"alfa 20",'LR',0,'L',0);
		$pdf->Ln();
		$pdf->SetFont('','B',7);
		$pdf->Cell(32,5,'Edad:','LR',0,'L',1);
		$pdf->SetFont('','',7);
		$pdf->Cell(35,5,"alfa 10",'LR',0,'L',1);
		$pdf->SetFont('','B',7);
		$pdf->Cell(20,5,'Edo. Civil:','LR',0,'L',1);
		$pdf->SetFont('','',7);
		$pdf->Cell(35,5,"alfa 30",'LR',0,'L',1);
		$pdf->SetFont('','B',7);
		$pdf->Cell(20,5,'Conyugue:','LR',0,'L',1);
		$pdf->SetFont('','',7);
		$pdf->Cell(50,5,"alfa 50",'LR',0,'L',1);
		$pdf->Ln();
		$pdf->SetFont('','B',7);
		$pdf->Cell(32,5,'Domicilio:','LR',0,'L',0);
		$pdf->SetFont('','',7);
		$pdf->Cell(160,5,"alfa 100",'LR',0,'L',0);
		$pdf->Ln();
		$pdf->SetFont('','B',7);
		$pdf->Cell(32,5,'Telefono:','LR',0,'L',1);
		$pdf->SetFont('','',7);
		$pdf->Cell(160,5,"numerico 20",'LR',0,'L',1);
	$pdf->Ln();
	//Colores, ancho de l�nea y fuente en negrita
	$pdf->SetFillColor(50,20,220);
	$pdf->SetTextColor(255);
	$pdf->SetDrawColor(128,0,0);
	$pdf->SetLineWidth(.3);
	$pdf->SetFont('','B',9);
	//Cabecera
	$pdf->Cell(192,5,'OBSERVACIONES ADICIONALES:',1,0,'C',1);
	$pdf->Ln();
	$pdf->SetFillColor(224,235,255);
	$pdf->SetTextColor(0);
	//Restauraci�n de colores y fuentes
		$pdf->SetFont('','',7);
		$pdf->Cell(192,5,'alfa 500 a 100','LR',0,'L',0);
		$pdf->Ln();
		$pdf->SetFont('','',7);
		$pdf->Cell(192,5,'alfa 500 a 100','LR',0,'L',0);
		$pdf->Ln();
		$pdf->SetFont('','',7);
		$pdf->Cell(192,5,'alfa 500 a 100','LR',0,'L',0);
		$pdf->Ln();
		$pdf->SetFont('','',7);
		$pdf->Cell(192,5,'alfa 500 a 100','LR',0,'L',0);
		$pdf->Ln();
		$pdf->SetFont('','',7);
		$pdf->Cell(192,5,'alfa 500 a 100','LR',0,'L',0);
		$pdf->Ln();	
			//Colores, ancho de l�nea y fuente en negrita
	$pdf->SetFillColor(50,20,220);
	$pdf->SetTextColor(255);
	$pdf->SetDrawColor(128,0,0);
	$pdf->SetLineWidth(.3);
	$pdf->SetFont('','B',9);
	//Cabecera
	$pdf->Cell(192,5,'DATOS LABORALES:',1,0,'C',1);
	$pdf->Ln();
	//Restauraci�n de colores y fuentes
	$pdf->SetFillColor(224,235,255);
	$pdf->SetTextColor(0);
	$pdf->SetFont('','',7);
		$pdf->SetFont('','B',7);
		$pdf->Cell(44,5,'Razon Social o Nombre Comercial:','LR',0,'L',0);
		$pdf->SetFont('','',7);
		$pdf->Cell(148,5,"alfa 50",'LR',0,'L',0);
		$pdf->Ln();
		$pdf->SetFont('','B',7);
		$pdf->Cell(32,5,'Domicilio:','LR',0,'L',1);
		$pdf->SetFont('','',7);
		$pdf->Cell(160,5,"alfa 100",'LR',0,'L',1);
		$pdf->Ln();
		$pdf->SetFont('','B',7);
		$pdf->Cell(32,5,'Giro:','LR',0,'L',0);
		$pdf->SetFont('','',7);
		$pdf->Cell(64,5,"alfa 50",'LR',0,'L',0);
		$pdf->SetFont('','B',7);
		$pdf->Cell(32,5,'Telefono:','LR',0,'L',0);
		$pdf->SetFont('','',7);
		$pdf->Cell(64,5,"numerico 20",'LR',0,'L',0);
		$pdf->Ln();
		$pdf->SetFont('','B',7);
		$pdf->Cell(32,5,'Cargo o Puesto:','LR',0,'L',1);
		$pdf->SetFont('','',7);
		$pdf->Cell(64,5,"alfa 20",'LR',0,'L',1);
		$pdf->SetFont('','B',7);
		$pdf->Cell(32,5,'Antiguedad:','LR',0,'L',1);
		$pdf->SetFont('','',7);
		$pdf->Cell(64,5,"alfa 20",'LR',0,'L',1);
		$pdf->Ln();
		$pdf->SetFont('','B',7);
		$pdf->Cell(32,5,'Ingresos Mensuales:','LR',0,'L',0);
		$pdf->SetFont('','',7);
		$pdf->Cell(64,5,"numerico 15",'LR',0,'L',0);
		$pdf->SetFont('','B',7);
		$pdf->Cell(32,5,'Contrato:','LR',0,'L',0);
		$pdf->SetFont('','',7);
		$pdf->Cell(64,5,"Planta:    SI( x )    NO( x )",'LR',0,'L',0);	
		$pdf->SetFont('','B',7);
		$pdf->Ln();
		$pdf->Cell(44,5,'Descripcion general de actividades:','LR',0,'L',1);
		$pdf->SetFont('','',7);
		$pdf->Cell(148,5,"alfa 146",'LR',0,'L',1);
		$pdf->Ln();
		$pdf->Cell(192,0,'','T');
		$pdf->Ln();
		$pdf->SetFont('','',5);
		$pdf->Cell(192,4,'NOTA: DECLARO QUE SE VERIFICO EL LUGAR DE TRABAJO DEL CLIENTE Y SE CONSTATO QUE PERCIBE LOS INGRESOS AQUI MENCIONADOS, DE LOS CUALES MANIFESTO EN ESTA ENTREVISTA','LR',0,'L',0);
		$pdf->Ln();
		$pdf->SetFont('','',5);
		$pdf->Cell(192,4,'MEDIANTE EL OFICIO O CARGO QUE OCUPA.POR LO QUE SE ANEXA TODA INFORMACION Y DOCUMENTACION POSIBLE QUE SOPORTA LO DECLARADO','LR',0,'L',0);
		$pdf->Ln();
		
	$pdf->SetFillColor(50,20,220);
	$pdf->SetTextColor(255);
	$pdf->SetDrawColor(128,0,0);
	$pdf->SetLineWidth(.3);
	$pdf->SetFont('','B',9);
		$pdf->Cell(192,5,'OBSERVACIONES ADICIONALES:',1,0,'C',1);
		$pdf->Ln();
		$pdf->SetFillColor(224,235,255);
		$pdf->SetTextColor(0);
	//Restauraci�n de colores y fuentes
		$pdf->SetFont('','',7);
		$pdf->Cell(192,5,'alfa 500 a 100','LR',0,'L',0);
		$pdf->Ln();
		$pdf->SetFont('','',7);
		$pdf->Cell(192,5,'alfa 500 a 100','LR',0,'L',0);
		$pdf->Ln();
		$pdf->SetFont('','',7);
		$pdf->Cell(192,5,'alfa 500 a 100','LR',0,'L',0);
		$pdf->Ln();
		$pdf->SetFont('','',7);
		$pdf->Cell(192,5,'alfa 500 a 100','LR',0,'L',0);
		$pdf->Ln();
		$pdf->SetFont('','',7);
		$pdf->Cell(192,5,'alfa 500 a 100','LR',0,'L',0);
		$pdf->Ln();
	$pdf->SetFillColor(50,20,220);
	$pdf->SetTextColor(255);
	$pdf->SetDrawColor(128,0,0);
	$pdf->SetLineWidth(.3);
	$pdf->SetFont('','B',9);
		$pdf->Cell(192,5,'OTROS INGRESOS:',1,0,'C',1);
		$pdf->Ln();
	$pdf->SetFillColor(224,235,255);
	$pdf->SetTextColor(0);
	$pdf->SetFont('','',7);
		$pdf->SetFont('','B',7);
		$pdf->Cell(32,5,'Actividad:','LR',0,'L',0);
		$pdf->SetFont('','',7);
		$pdf->Cell(104,5,"alfa 50",'LR',0,'L',0);
		$pdf->SetFont('','B',7);
		$pdf->Cell(32,5,'Antiguedad:','LR',0,'L',0);
		$pdf->SetFont('','',7);
		$pdf->Cell(24,5,"alfa 10 20",'LR',0,'L',0);
		$pdf->Ln();
		$pdf->Cell(192,0,'','T');
		$pdf->Ln();
	

	$pdf->SetFillColor(50,20,220);
	$pdf->SetTextColor(255);
	$pdf->SetDrawColor(128,0,0);
	$pdf->SetLineWidth(.3);
	$pdf->SetFont('','B',9);
		$pdf->Cell(192,5,'EGRESOS:',1,0,'C',1);
		$pdf->Ln();
	$pdf->SetFillColor(224,235,255);
	$pdf->SetTextColor(0);
	$pdf->SetFont('','',7);
		$pdf->SetFont('','B',7);
		$pdf->Cell(47,5,'Gastos operativos mensuales: ','LR',0,'L',0);
		$pdf->SetFont('','',7);
		$pdf->Cell(145,5,"numerico 10",'LR',0,'L',0);
		$pdf->Ln();
		$pdf->SetFont('','B',7);
		$pdf->Cell(47,5,'Gastos familiares fijos mensuales:','LR',0,'L',1);
		$pdf->SetFont('','',7);
		$pdf->Cell(145,5,"numerico 10",'LR',0,'L',1);
		$pdf->Ln();
		$pdf->SetFont('','B',7);
		$pdf->Cell(47,5,'Creditos pagandose:','LR',0,'L',0);
		$pdf->SetFont('','',7);
		$pdf->Cell(145,5,"numerico 10",'LR',0,'L',0);
		$pdf->Ln();
	$pdf->SetFillColor(50,20,220);
	$pdf->SetTextColor(255);
	$pdf->SetDrawColor(128,0,0);
	$pdf->SetLineWidth(.3);
	$pdf->SetFont('','B',9);	
		$pdf->Cell(47,5,'TOTAL:','LR',0,'L',1);
	$pdf->SetFillColor(224,235,255);
	$pdf->SetTextColor(0);
	$pdf->SetFont('','',7);
		$pdf->Cell(145,5,"numerico 10",'LR',0,'L',0);
		$pdf->Ln();
		$pdf->SetFont('','B',7);
		$pdf->Cell(37,5,'Comentarios:','LR',0,'L',1);
		$pdf->SetFont('','',7);
		$pdf->Cell(155,5,"alfa 150",'LR',0,'L',1);
		$pdf->Ln();
	$pdf->SetFillColor(50,20,220);
	$pdf->SetTextColor(255);
	$pdf->SetDrawColor(128,0,0);
	$pdf->SetLineWidth(.3);
	$pdf->SetFont('','B',9);
		$pdf->Cell(192,5,'DESGLOSE EGRESOS FIJOS:',1,0,'C',1);
		$pdf->Ln();	
	$pdf->SetFillColor(224,235,255);
	$pdf->SetTextColor(0);
		$pdf->SetFont('','',7);		
		$pdf->Cell(30,5,"",'LR',0,'L',0);
		$pdf->Cell(30,5,'Alimentos: ','LR',0,'L',1);
		$pdf->SetFont('','',7);
		$pdf->Cell(30,5,"numerico 10",'LR',0,'L',0);
		$pdf->SetFont('','',7);		
		$pdf->Cell(32,5,"",'LR',0,'L',0);
		$pdf->Cell(40,5,'Ropa y Calzado: ','LR',0,'L',1);
		$pdf->SetFont('','',7);
		$pdf->Cell(30,5,"numerico 10",'LR',0,'L',0);
		$pdf->Ln();	
			$pdf->SetFont('','',7);		
		$pdf->Cell(30,5,"",'LR',0,'L',0);
		$pdf->Cell(30,5,'Renta/Hip:','LR',0,'L',1);
		$pdf->SetFont('','',7);
		$pdf->Cell(30,5,"numerico 10",'LR',0,'L',0);
		$pdf->SetFont('','',7);		
		$pdf->Cell(32,5,"",'LR',0,'L',0);
		$pdf->Cell(40,5,'sporte y Combustible:','LR',0,'L',1);
		$pdf->SetFont('','',7);
		$pdf->Cell(30,5,"numerico 10",'LR',0,'L',0);
		$pdf->Ln();
		$pdf->SetFont('','',7);		
		$pdf->Cell(30,5,"",'LR',0,'L',0);
		$pdf->Cell(30,5,'Servicios:','LR',0,'L',1);
		$pdf->SetFont('','',7);
		$pdf->Cell(30,5,"numerico 10",'LR',0,'L',0);
		$pdf->SetFont('','',7);		
		$pdf->Cell(32,5,"",'LR',0,'L',0);
		$pdf->Cell(40,5,'Pago por Creditos, Targetas etc:','LR',0,'L',1);
		$pdf->SetFont('','',7);
		$pdf->Cell(30,5,"numerico 10",'LR',0,'L',0);
		$pdf->Ln();
		$pdf->SetFont('','',7);		
		$pdf->Cell(30,5,"",'LR',0,'L',0);
		$pdf->Cell(30,5,'otros:','LR',0,'L',1);
		$pdf->SetFont('','',7);
		$pdf->Cell(30,5,"numerico 10",'LR',0,'L',0);
		$pdf->SetFont('','',7);		
		$pdf->Cell(32,5,"",'LR',0,'L',0);
		$pdf->Cell(40,5,'Colegiaturas:','LR',0,'L',1);
		$pdf->SetFont('','',7);
		$pdf->Cell(30,5,"numerico 10",'LR',0,'L',0);
		$pdf->Ln();
		$pdf->Cell(192,0,'','T');
		$pdf->Ln();
		$pdf->Cell(60,5,"",'LR',0,'L',0);
	$pdf->SetFillColor(50,20,220);
	$pdf->SetTextColor(255);
	$pdf->SetDrawColor(128,0,0);
	$pdf->SetLineWidth(.3);
	$pdf->SetFont('','B',9);	
		$pdf->Cell(30,5,'TOTAL:','LR',0,'L',1);
	$pdf->SetFillColor(224,235,255);
	$pdf->SetTextColor(0);
	$pdf->SetFont('','',7);
		$pdf->Cell(32,5,"numerico 10",'LR',0,'L',0);
		$pdf->Cell(70,5,"",'LR',0,'L',0);
		$pdf->Ln();
		$pdf->Cell(192,0,'','T');
		$pdf->Ln();
		$pdf->SetFont('','',5);
		$pdf->Cell(192,4,"Nota: se declara bajo protesta que los datos registrados en la investigacion anexa son reales y validados por nuestra parte",'LR',0,'L',0);
		$pdf->Ln();
		$pdf->Cell(192,0,'','T');
		$pdf->Ln();
		$pdf->Cell(192,0,'','T');
		$pdf->Ln();
		$pdf->SetFillColor(50,20,220);
	$pdf->SetTextColor(255);
	$pdf->SetDrawColor(128,0,0);
	$pdf->SetLineWidth(.3);
	$pdf->SetFont('','B',9);
		$pdf->Cell(192,5,'PATRIMONIO:',1,0,'C',1);
		$pdf->Ln();	
	$pdf->SetFillColor(224,235,255);
	$pdf->SetTextColor(0);
		$pdf->SetFont('','',7);		
		$pdf->Cell(40,5,'Casa Habitacion:','LR',0,'L',0);
		$pdf->SetFont('','',7);
		$pdf->Cell(152,5,"Propia      SI ( x )   NO( x )            Rentada      SI ( x )   NO( x )            Familiar      SI ( x )   NO( x )",'LR',0,'L',0);
		$pdf->Ln();	
		$pdf->SetFont('','',7);		
		$pdf->Cell(40,5,'Nombre del Propietario:','LR',0,'L',1);
		$pdf->Cell(72,5,'alfa 50:','LR',0,'L',1);
		$pdf->Cell(40,5,'parentesco:','LR',0,'L',1);
		$pdf->Cell(40,5,'alfa 15','LR',0,'L',1);
		$pdf->Ln();
		$pdf->SetFont('','',7);		
		$pdf->Cell(40,5,'Automovil:','LR',0,'L',0);
		$pdf->Cell(15,5,'Propio:','LR',0,'L',0);
		$pdf->Cell(30,5,'SI ( x )   NO( x )','LR',0,'L',0);
		$pdf->Cell(15,5,'Pagando:','LR',0,'L',0);
		$pdf->Cell(92,5,'SI ( x )   NO( x )','LR',0,'L',0);
		$pdf->Ln();
		$pdf->SetFont('','',7);		
		$pdf->Cell(40,5,'','LR',0,'L',1);
		$pdf->Cell(15,5,'Marca:','LR',0,'L',1);
		$pdf->Cell(30,5,'alfa 15','LR',0,'L',1);
		$pdf->Cell(15,5,'Modelo:','LR',0,'L',1);
		$pdf->Cell(20,5,'numerico 5','LR',0,'L',1);
		$pdf->Cell(15,5,'Valor:','LR',0,'L',1);
		$pdf->Cell(57,5,'numerico 10','LR',0,'L',1);
		$pdf->Ln();
		$pdf->SetFont('','',7);		
		$pdf->Cell(40,5,'','LR',0,'L',0);
		$pdf->Cell(15,5,'Placas:','LR',0,'L',0);
		$pdf->Cell(30,5,'alfa 15','LR',0,'L',0);
		$pdf->Cell(15,5,'Color:','LR',0,'L',0);
		$pdf->Cell(92,5,'alfa 15','LR',0,'L',0);
		$pdf->Ln();
		$pdf->Cell(192,0,'','T');
		$pdf->Ln();
		$pdf->Cell(192,0,'','T');
		$pdf->Ln();
		$pdf->SetFillColor(50,20,220);
	$pdf->SetTextColor(255);
	$pdf->SetDrawColor(128,0,0);
	$pdf->SetLineWidth(.3);
	$pdf->SetFont('','B',9);
		$pdf->Cell(192,5,'REFERENCIAS PERSONALES:(Familiares y amistades que no vivan con el).',1,0,'C',1);
		$pdf->Ln();
$pdf->SetFillColor(224,235,255);
$pdf->SetTextColor(0);
		$pdf->SetFont('','',7);		
		$pdf->Cell(17,5,'Nombre:','LR',0,'L',1);
		$pdf->Cell(56,5,'alfa 50','LR',0,'L',0);
		$pdf->Cell(15,5,'Localidad','LR',0,'L',1);
		$pdf->Cell(104,5,'alfa 100','LR',0,'L',0);
		$pdf->Ln();
		$pdf->SetFont('','',7);		
		$pdf->Cell(17,5,'Telefono:','LR',0,'L',1);
		$pdf->Cell(56,5,'alfa 20','LR',0,'L',0);
		$pdf->Cell(119,5,'','LR',0,'L',0);
		$pdf->Ln();
		$pdf->SetFont('','',7);	
		$pdf->Cell(17,5,'Comentarios:','LR',0,'L',1);
		$pdf->Cell(56,5,'alfa 20','LR',0,'L',0);
		$pdf->Cell(15,5,'Parentesco:','LR',0,'L',1);
		$pdf->Cell(37,5,'alfa 10','LR',0,'L',0);
		$pdf->Cell(30,5,'Tiempo de Conocerlo:','LR',0,'L',1);
		$pdf->Cell(37,5,'alfa 15','LR',0,'L',0);
		$pdf->Ln();
		$pdf->Cell(192,3,'','LR',0,'L',0);
		$pdf->Ln();
	$pdf->SetFillColor(224,235,255);
$pdf->SetTextColor(0);
		$pdf->SetFont('','',7);		
		$pdf->Cell(17,5,'Nombre:','LR',0,'L',1);
		$pdf->Cell(56,5,'alfa 50','LR',0,'L',0);
		$pdf->Cell(15,5,'Localidad','LR',0,'L',1);
		$pdf->Cell(104,5,'alfa 100','LR',0,'L',0);
		$pdf->Ln();
		$pdf->SetFont('','',7);		
		$pdf->Cell(17,5,'Telefono:','LR',0,'L',1);
		$pdf->Cell(56,5,'alfa 20','LR',0,'L',0);
		$pdf->Cell(119,5,'','LR',0,'L',0);
		$pdf->Ln();
		$pdf->SetFont('','',7);	
		$pdf->Cell(17,5,'Comentarios:','LR',0,'L',1);
		$pdf->Cell(56,5,'alfa 20','LR',0,'L',0);
		$pdf->Cell(15,5,'Parentesco:','LR',0,'L',1);
		$pdf->Cell(37,5,'alfa 10','LR',0,'L',0);
		$pdf->Cell(30,5,'Tiempo de Conocerlo:','LR',0,'L',1);
		$pdf->Cell(37,5,'alfa 15','LR',0,'L',0);
		$pdf->Ln();
		$pdf->Cell(192,3,'','LR',0,'L',0);
		$pdf->Ln();
$pdf->SetFillColor(224,235,255);
$pdf->SetTextColor(0);
		$pdf->SetFont('','',7);		
		$pdf->Cell(17,5,'Nombre:','LR',0,'L',1);
		$pdf->Cell(56,5,'alfa 50','LR',0,'L',0);
		$pdf->Cell(15,5,'Localidad','LR',0,'L',1);
		$pdf->Cell(104,5,'alfa 100','LR',0,'L',0);
		$pdf->Ln();
		$pdf->SetFont('','',7);		
		$pdf->Cell(17,5,'Telefono:','LR',0,'L',1);
		$pdf->Cell(56,5,'alfa 20','LR',0,'L',0);
		$pdf->Cell(119,5,'','LR',0,'L',0);
		$pdf->Ln();
		$pdf->SetFont('','',7);	
		$pdf->Cell(17,5,'Comentarios:','LR',0,'L',1);
		$pdf->Cell(56,5,'alfa 20','LR',0,'L',0);
		$pdf->Cell(15,5,'Parentesco:','LR',0,'L',1);
		$pdf->Cell(37,5,'alfa 10','LR',0,'L',0);
		$pdf->Cell(30,5,'Tiempo de Conocerlo:','LR',0,'L',1);
		$pdf->Cell(37,5,'alfa 15','LR',0,'L',0);
		$pdf->Ln();
		$pdf->Cell(192,3,'','LR',0,'L',0);
		$pdf->Ln();
$pdf->SetFillColor(224,235,255);
$pdf->SetTextColor(0);
		$pdf->SetFont('','',7);		
		$pdf->Cell(17,5,'Nombre:','LR',0,'L',1);
		$pdf->Cell(56,5,'alfa 50','LR',0,'L',0);
		$pdf->Cell(15,5,'Localidad','LR',0,'L',1);
		$pdf->Cell(104,5,'alfa 100','LR',0,'L',0);
		$pdf->Ln();
		$pdf->SetFont('','',7);		
		$pdf->Cell(17,5,'Telefono:','LR',0,'L',1);
		$pdf->Cell(56,5,'alfa 20','LR',0,'L',0);
		$pdf->Cell(119,5,'','LR',0,'L',0);
		$pdf->Ln();
		$pdf->SetFont('','',7);	
		$pdf->Cell(17,5,'Comentarios:','LR',0,'L',1);
		$pdf->Cell(56,5,'alfa 20','LR',0,'L',0);
		$pdf->Cell(15,5,'Parentesco:','LR',0,'L',1);
		$pdf->Cell(37,5,'alfa 10','LR',0,'L',0);
		$pdf->Cell(30,5,'Tiempo de Conocerlo:','LR',0,'L',1);
		$pdf->Cell(37,5,'alfa 15','LR',0,'L',0);
		$pdf->Ln();
		$pdf->Cell(192,3,'','LR',0,'L',0);
		$pdf->Ln();
$pdf->SetFillColor(50,20,220);
	$pdf->SetTextColor(255);
	$pdf->SetDrawColor(128,0,0);
	$pdf->SetLineWidth(.3);
	$pdf->SetFont('','B',9);
		$pdf->Cell(192,5,'INFORMACION CREDITICIA:',1,0,'C',1);
		$pdf->Ln();
$pdf->SetFillColor(224,235,255);
$pdf->SetTextColor(0);
		$pdf->SetFont('','',7);			
		$pdf->Cell(40,5,'TARJETAS DE CREDITO:','LR',0,'L',1);
		$pdf->Cell(152,5,'','LR',0,'L',0);
		$pdf->Ln();
		$pdf->Cell(192,3,'','LR',0,'L',0);
		$pdf->Ln();
		$pdf->SetFont('','',7);	
		$pdf->Cell(40,5,'Institucion:','LR',0,'L',1);
		$pdf->Cell(56,5,'alfa 15','LR',0,'L',0);
		$pdf->Cell(96,5,'','LR',0,'L',0);
		$pdf->Ln();
		$pdf->SetFont('','',7);
		$pdf->Cell(40,5,'Titular:','LR',0,'L',1);
		$pdf->Cell(56,5,'alfa 50','LR',0,'L',0);
		$pdf->Cell(40,5,'No. De Cuenta:','LR',0,'L',1);
		$pdf->Cell(56,5,'numerico 20','LR',0,'L',0);
		$pdf->Ln();
		$pdf->SetFont('','',7);	
		$pdf->Cell(40,5,'Fecha Apertura:','LR',0,'L',1);
		$pdf->Cell(56,5,'numerico 5','LR',0,'L',0);
		$pdf->Cell(96,5,'','LR',0,'L',0);
			$pdf->Ln();
		$pdf->SetFont('','',7);	
		$pdf->Cell(40,5,'Saldo Deudor (vigente):','LR',0,'L',1);
		$pdf->Cell(56,5,'numerico 5','LR',0,'L',0);
		$pdf->Cell(96,5,'','LR',0,'L',0);
		$pdf->Ln();
		$pdf->SetFont('','',7);
		$pdf->Cell(40,5,'Experiencia:','LR',0,'L',1);
		$pdf->Cell(56,5,'Buena ( X )  Mala  ( X )','LR',0,'L',0);
		$pdf->Cell(40,5,'Limite de Credito:','LR',0,'L',1);
		$pdf->Cell(56,5,'numerico 20','LR',0,'L',0);
		$pdf->Ln();
		$pdf->Cell(192,3,'','LR',0,'L',0);
		$pdf->Ln();
		$pdf->SetFont('','',7);	
		$pdf->Cell(40,5,'Institucion:','LR',0,'L',1);
		$pdf->Cell(56,5,'alfa 15','LR',0,'L',0);
		$pdf->Cell(96,5,'','LR',0,'L',0);
		$pdf->Ln();
		$pdf->SetFont('','',7);
		$pdf->Cell(40,5,'Titular:','LR',0,'L',1);
		$pdf->Cell(56,5,'alfa 50','LR',0,'L',0);
		$pdf->Cell(40,5,'No. De Cuenta:','LR',0,'L',1);
		$pdf->Cell(56,5,'numerico 20','LR',0,'L',0);
		$pdf->Ln();
		$pdf->SetFont('','',7);	
		$pdf->Cell(40,5,'Fecha Apertura:','LR',0,'L',1);
		$pdf->Cell(56,5,'numerico 5','LR',0,'L',0);
		$pdf->Cell(96,5,'','LR',0,'L',0);
			$pdf->Ln();
		$pdf->SetFont('','',7);	
		$pdf->Cell(40,5,'Saldo Deudor (vigente):','LR',0,'L',1);
		$pdf->Cell(56,5,'numerico 5','LR',0,'L',0);
		$pdf->Cell(96,5,'','LR',0,'L',0);
		$pdf->Ln();
		$pdf->SetFont('','',7);
		$pdf->Cell(40,5,'Experiencia:','LR',0,'L',1);
		$pdf->Cell(56,5,'Buena ( X )  Mala  ( X )','LR',0,'L',0);
		$pdf->Cell(40,5,'Limite de Credito:','LR',0,'L',1);
		$pdf->Cell(56,5,'numerico 20','LR',0,'L',0);
		$pdf->Ln();
		$pdf->Cell(192,3,'','LR',0,'L',0);
		$pdf->Ln();
		
	$pdf->SetFillColor(50,20,220);
	$pdf->SetTextColor(255);
	$pdf->SetDrawColor(128,0,0);
	$pdf->SetLineWidth(.3);
	$pdf->SetFont('','B',9);
		$pdf->Cell(192,5,'OBSERVACIONES ADICIONALES:',1,0,'C',1);
		$pdf->Ln();
		$pdf->SetFillColor(224,235,255);
		$pdf->SetTextColor(0);
	//Restauraci�n de colores y fuentes
		$pdf->SetFont('','',7);
		$pdf->Cell(192,5,'alfa 500 a 100','LR',0,'L',0);
		$pdf->Ln();
		$pdf->SetFont('','',7);
		$pdf->Cell(192,5,'alfa 500 a 100','LR',0,'L',0);
		$pdf->Ln();
		$pdf->SetFont('','',7);
		$pdf->Cell(192,5,'alfa 500 a 100','LR',0,'L',0);
		$pdf->Ln();
		$pdf->SetFont('','',7);
		$pdf->Cell(192,5,'alfa 500 a 100','LR',0,'L',0);
		$pdf->Ln();
		$pdf->SetFont('','',7);
		$pdf->Cell(192,5,'alfa 500 a 100','LR',0,'L',0);
		$pdf->Ln();
		$pdf->SetFont('','',7);
		$pdf->Cell(192,5,'alfa 500 a 100','LR',0,'L',0);
		$pdf->Ln();
		$pdf->Cell(192,0,'','T');
		$pdf->Ln();
		$pdf->SetFont('','',7);
		$pdf->Cell(20,5,'','LR',0,'L',0);
		$pdf->Cell(20,5,'INVESTIGADOR','LR',0,'L',1);
		$pdf->Cell(56,5,'ALFA 10','LR',0,'L',0);
		$pdf->Cell(20,5,'','LR',0,'L',0);
		$pdf->Cell(20,5,'SUPERVISOR','LR',0,'L',1);
		$pdf->Cell(56,5,'ALFA 5','LR',0,'L',0);
		$pdf->Ln();
		$pdf->Cell(192,0,'','T');
		$pdf->Ln();
		$pdf->SetFont('','',5);
		$pdf->Cell(192,4,"Nota: se declara bajo protesta que los datos registrados en la investigacion anexa son reales y validados por nuestra parte",'LR',0,'L',0);
		$pdf->Ln();
		$pdf->Cell(192,0,'','T');
		$pdf->Ln();
$pdf->Output();
?>