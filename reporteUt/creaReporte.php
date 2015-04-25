<?php

extract($_REQUEST);

require_once('../scripts/bd.php');
require_once('../scripts/pdfTable2.php');
require_once('../scripts/libreriaGeneral.php');

$fechaDesde=$_GET['desde'];
$fechaHasta=$_GET['hasta'];

class PDF extends PDF_Table
{
	function Header()
	{
		extract($_REQUEST);		
		
		$fechaDesde=$_GET['desde'];
		$fechaHasta=$_GET['hasta'];
		$cliente=$_GET['cliente'];
		
		//Logo
		$this->Image('../scripts/imagenes/logo.jpeg',4,4,60,20);
		$this->SetFont('Arial','',10);
		$this->SetXY(6,26);
		$fecha = date("d/m/Y");
		$fecha=strtoupper(nombre_mes($fecha,2));	
		$this->Cell(117,4,'MÉXICO, D.F. , '.$fecha,0,0,'L',0);
					
		$bd = new BD;
		if($cliente!=0)
		{
			$sqlConsulta = "SELECT ccliente.rfc,ccliente.razonSocial,cdireccionescliente.cveSucursal,cdireccionescliente.cveDireccion,".
			"cdireccionescliente.calle, cdireccionescliente.codigoPostal,cdireccionescliente.colonia,".
			"IF(cdireccionescliente.numeroInterior='',cdireccionescliente.numeroexterior,cdireccionescliente.numeroInterior) AS numero,".
			"cdireccionescliente.cveEstado,cdireccionescliente.cveMunicipio ".
			"FROM cdireccionescliente ".
			"INNER JOIN ccliente ON cdireccionescliente.cveCliente=ccliente.cveCliente ".
			"WHERE cdireccionescliente.cveCliente='".$cliente."' ORDER BY cdireccionescliente.cveDireccion LIMIT 1";
			$datos = $bd->Execute($sqlConsulta);
			
			foreach ($datos as $dato)
			{
				$razonSocial     = $dato["razonSocial"];
				$rfc             = $dato["rfc"];
				$sucursalCliente = $dato["cveSucursal"];
				$calle           = $dato["calle"];
				$numero          = $dato["numero"];
				$colonia         = $dato["colonia"];
				$cveMunicipio    = $dato["cveMunicipio"];
				$cveEstado       = $dato["cveEstado"];
				$codigoPostal    = $dato["codigoPostal"];
			}
			
			$direccion="Calle ".$calle.' No.'.$numero." Col. ".$colonia." C.P. ".$codigoPostal;
		}
		else
		{
			//La consulta es fija, por que la primer empresa siempre será Carga y Express		
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
		}
		
		$this->SetFillColor(200,205,255);
		$this->SetTextColor(0);
		$this->SetDrawColor(0);
		$this->SetLineWidth(.2);
		$this->SetFont('Arial','B',10);
		$this->SetXY(90,2);

		//LTR: Con márgen L:izquierdo, T:arriba y R:derecho
		$this->Cell(94,8,$razonSocial,'',0,'L',0);
		$this->SetFont('Arial','B',8);
		$this->Cell(25,8,'R.F.C.','',0,'L',0);
		$this->CellFitScale(21,8,$rfc,'',0,'L',0);
		$x=$this->GetX()-46;
		$y=$this->GetY()+8;
		$this->Ln();
		$this->SetX(90);		
		$this->MultiCell(94,7,"Dirección: ".$direccion,'','L');
		$this->SetXY($x,$y);
		$fechaDesde=nombre_mes($fechaDesde,2);
		$this->Cell(10,7,'DEL:','',0,'1',0);
		$this->Cell(36,7,$fechaDesde,'',0,'L',0);
		$this->SetXY($x,$y+7);
		$fechaHasta=nombre_mes($fechaHasta,2);
		$this->Cell(10,7,'AL:','',0,'R',0);
		$this->Cell(36,7,$fechaHasta,'',0,'L',0);
		$this->Cell(36,7,$fechaHasta,'',0,'L',0);
		
		$this->Ln();
		$this->SetX(90);
		$this->Cell(36,3,'Días del Periodo','',0,'L',0);
		$this->Cell(36,3,$vdiasMes,'',0,'L',0);
		$this->Ln();
		$this->SetX(90);
		$this->Cell(36,3,'Días Efectivos','',0,'L',0);
		$this->Cell(36,3,$vdiasEfectivos,'',0,'L',0);
		$this->Ln();
		$this->SetX(90);
		$this->Cell(36,3,'Días por Transcurrir','',0,'L',0);
		$this->Cell(36,3,$vdiasporTranscurrir,'',0,'L',0);
		
		
	}
	//Cabecera de página	
	function Footer()
	{
	}
}

//Creación del objeto de la clase heredada
$pdf=new PDF('L','mm','A4');
$pdf->AliasNbPages();   
$pdf->AddPage();
$pdf->SetMargins(1.5,0,1.5);
$pdf->SetDisplayMode(80,'default'); //Para que el zoom este al 100%, normal real

$pdf->SetFillColor(200,205,255);

//Mostraremos los datos Reales
$pdf->Ln(3);
$pdf->SetX(16);	
$pdf->Cell(260,5,'REAL',0, 0, 'C', 1); 

$pdf->SetFont('Arial','',7);


$pdf->Ln(6);
$pdf->SetX(16);	
$pdf->Cell(39,4.5,'','', 0, 'C', 0); 
$pdf->Cell(31,4.5,'Prom. del Periodo','TLR', 0, 'C', 0); 
$pdf->Cell(25,6,'',0, 0, 'C', 0); 
$pdf->Cell(39,4.5,'','', 0, 'C', 0); 
$pdf->Cell(31,4.5,'Prom. x Día','TLR', 0, 'C', 0); 
$pdf->Cell(25,6,'',0, 0, 'C', 0); 
$pdf->Cell(39,4.5,'','', 0, 'C', 0); 
$pdf->Cell(31,4.5,'Prom. peso/vol. x Día','TLR', 0, 'C', 0); 


$pdf->Ln();
$pdf->SetX(16);	
$pdf->Cell(39,4.5,'Días del Periodo','TLR', 0,'R', 0); 
$pdf->Cell(31,4.5,$vdiasEfectivos,'TLR', 0,'L', 0); 
$pdf->Cell(25,4.5,'',0, 0, 'C', 0); 
$pdf->Cell(39,4.5,'Piezas por Día','TLR', 0,'R', 0); 
$pdf->Cell(31,4.5,$vpiezasDiaPPDR,'TLR', 0, 'L', 0); 
$pdf->Cell(25,4.5,'',0, 0, 'C', 0); 
$pdf->Cell(39,4.5,'Peso/Volúmen','TLR', 0,'R', 0); 
$pdf->Cell(31,4.5,$vvolumenDiaPPVR,'TLR', 0, 'L', 0); 

$pdf->Ln();
$pdf->SetX(16);	
$pdf->Cell(39,4.5,'Piezas por Día','TLR', 0,'R', 0); 
$pdf->Cell(31,4.5,$vpiezasDiaPPR,'TLR', 0, 'L', 0); 
$pdf->Cell(25,6,'',0, 0, 'C', 0); 
$pdf->Cell(39,4.5,'Total Cobrado','TLR', 0,'R', 0); 
$pdf->Cell(31,4.5,$vtotalCobradoDiaPPDR,'TLR', 0, 'L', 0); 
$pdf->Cell(25,6,'',0, 0, 'C', 0); 
$pdf->Cell(39,4.5,'Total Cobrado','TLR', 0,'R', 0); 
$pdf->Cell(31,4.5,$vtotalCobradoDiaPPVR,'TLR', 0, 'L', 0); 


$pdf->Ln();
$pdf->SetX(16);	
$pdf->Cell(39,4.5,'Peso/Volúmen','TLR', 0, 'R', 0); 
$pdf->Cell(31,4.5,$vvolumenDiaPPR,'TLR', 0, 'L', 0); 
$pdf->Cell(25,6,'',0, 0, 'C', 0); 
$pdf->Cell(39,4.5,'Total Pagado','TLR', 0, 'R', 0); 
$pdf->Cell(31,4.5,$vtotalPagadoDiaPPDR,'TLR', 0, 'L', 0); 
$pdf->Cell(25,6,'',0, 0, 'C', 0); 
$pdf->Cell(39,4.5,'Total Pagado','TLR', 0, 'R', 0); 
$pdf->Cell(31,4.5,$vtotalPagadoDiaPPVR,'TLR', 0, 'L', 0); 

$pdf->Ln();
$pdf->SetX(16);	
$pdf->Cell(39,4.5,'IVA','TLR', 0, 'R', 0); 
$pdf->Cell(31,4.5,$vivaDiaPPR,'TLR', 0, 'L', 0); 
$pdf->Cell(25,6,'',0, 0, 'C', 0); 
$pdf->Cell(39,4.5,'Utilidad','1', 0, 'R', 0); 
$pdf->Cell(31,4.5,$vtotalUtilidadDiaPPDR,'1', 0, 'L', 0); 
$pdf->Cell(25,6,'',0, 0, 'C', 0); 
$pdf->Cell(39,4.5,'Utilidad','1', 0, 'R', 0); 
$pdf->Cell(31,4.5,$vtotalUtilidadDiaPPVR,'1', 0, 'L', 0); 

$pdf->Ln();
$pdf->SetX(16);	
$pdf->Cell(39,4.5,'Subtotal','TLR', 0, 'R', 0); 
$pdf->Cell(31,4.5,$vsubtotalDiaPPR,'TLR', 0, 'L', 0); 

$pdf->Ln();
$pdf->SetX(16);	
$pdf->Cell(39,4.5,'Retención','TLR', 0, 'R', 0); 
$pdf->Cell(31,4.5,$vretencionDiaPPR,'TLR', 0, 'L', 0); 


$pdf->Ln();
$pdf->SetX(16);	
$pdf->Cell(39,4.5,'Total Cobrado','TLR', 0, 'R', 0); 
$pdf->Cell(31,4.5,$vtotalCobradoDiaPPR,'TLR', 0, 'L', 0); 


$pdf->Ln();
$pdf->SetX(16);	
$pdf->Cell(39,4.5,'Total Pagado','TLR', 0, 'R', 0); 
$pdf->Cell(31,4.5,$vtotalPagadoDiaPPR,'TLR', 0, 'L', 0); 

$pdf->Ln();
$pdf->SetX(16);	
$pdf->Cell(39,4.5,'Utilidad','1', 0, 'R', 0); 
$pdf->Cell(31,4.5,$vtotalUtilidadDiaPPR,'1', 0, 'L', 0); 

//Mostraremos los datos Proyectados
if($vdiasporTranscurrir!=0)
{ 
	$pdf->Ln(6);
	$pdf->SetX(16);	
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(260,5,'PROYECTADO',0, 0, 'C', 1); 
	$pdf->SetFont('Arial','',7);
	
	$pdf->Ln(6);
	$pdf->SetX(16);	
	$pdf->Cell(39,4.5,'Días por Transcurrir','1', 0, 'R', 0); 
	$pdf->Cell(31,4.5,$vdiasporTranscurrir,'1', 0, 'C', 0); 
	
	$pdf->Ln(7);
	$pdf->SetX(16);	
	$pdf->Cell(39,4.5,'Piezas a Vender','TLR', 0,'R', 0); 
	$pdf->Cell(31,4.5,$vpiezasDiaPPP,'TLR', 0,'L', 0); 
	
	$pdf->Ln();
	$pdf->SetX(16);	
	$pdf->Cell(39,4.5,'Peso/Volúmen a Vender','TLR', 0,'R', 0); 
	$pdf->Cell(31,4.5,$vvolumenDiaPPP,'TLR', 0,'L', 0); 

	$pdf->Ln();
	$pdf->SetX(16);	
	$pdf->Cell(39,4.5,'IVA','TLR', 0,'R', 0); 
	$pdf->Cell(31,4.5,$vivaDiaPPP,'TLR', 0, 'L', 0); 
	
	$pdf->Ln();
	$pdf->SetX(16);	
	$pdf->Cell(39,4.5,'Subtotal','TLR', 0,'R', 0); 
	$pdf->Cell(31,4.5,$vsubtotalDiaPPP,'TLR', 0, 'L', 0); 
	
	$pdf->Ln();
	$pdf->SetX(16);	
	$pdf->Cell(39,4.5,'Retención','TLR', 0, 'R', 0); 
	$pdf->Cell(31,4.5,$vretencionDiaPPP,'TLR', 0, 'L', 0); 
	
	$pdf->Ln();
	$pdf->SetX(16);	
	$pdf->Cell(39,4.5,'Total a Cobrar','TLR', 0, 'R', 0); 
	$pdf->Cell(31,4.5,$vtotalCobradoDiaPPP,'TLR', 0, 'L', 0); 
	
	
	$pdf->Ln();
	$pdf->SetX(16);	
	$pdf->Cell(39,4.5,'Total a Pagar','TLR', 0, 'R', 0); 
	$pdf->Cell(31,4.5,$vtotalPagadoDiaPPP,'TLR', 0, 'L', 0); 
	
	$pdf->Ln();
	$pdf->SetX(16);	
	$pdf->Cell(39,4.5,'Utilidad','1', 0, 'R', 0); 
	$pdf->Cell(31,4.5,$vtotalUtilidadDiaPPP,'1', 0, 'L', 0); 
}

$pdf->Ln(6);
$pdf->SetX(16);	
$pdf->SetFont('Arial','B',8);
$pdf->Cell(260,5,'RESÚMEN DEL MES',0, 0, 'C', 1); 
$pdf->SetFont('Arial','',7);


$pdf->Ln(7);
$pdf->SetX(16);	
$pdf->Cell(39,4.5,'','', 0, 'C', 0); 
$pdf->Cell(31,4.5,'','', 0, 'C', 0); 
$pdf->Cell(25,6,'',0, 0, 'C', 0); 
$pdf->Cell(39,4.5,'','', 0, 'C', 0); 
$pdf->Cell(31,4.5,'Utilidad x Piezas','TLR', 0, 'C', 0); 
$pdf->Cell(25,6,'',0, 0, 'C', 0); 
$pdf->Cell(39,4.5,'','', 0, 'C', 0); 
$pdf->Cell(31,4.5,'Utilidad x peso/vol.','TLR', 0, 'C', 0); 


$pdf->Ln();
$pdf->SetX(16);	
$pdf->Cell(39,4.5,'Días del Periodo','TLR', 0,'R', 0); 
$pdf->Cell(31,4.5,$vdiasMes,'TLR', 0,'L', 0); 
$pdf->Cell(25,4.5,'',0, 0, 'C', 0); 
$pdf->Cell(39,4.5,'Piezas por Día','TLR', 0,'R', 0); 
$pdf->Cell(31,4.5,$vpiezasDiaPPDR,'TLR', 0, 'L', 0); 
$pdf->Cell(25,4.5,'',0, 0, 'C', 0); 
$pdf->Cell(39,4.5,'Peso/Volúmen','TLR', 0,'R', 0); 
$pdf->Cell(31,4.5,$vvolumenDiaPPVR,'TLR', 0, 'L', 0); 

$pdf->Ln();
$pdf->SetX(16);	
$pdf->Cell(39,4.5,'Piezas','TLR', 0,'R', 0); 
$pdf->Cell(31,4.5,$vpiezasDiaPPT,'TLR', 0, 'L', 0); 
$pdf->Cell(25,6,'',0, 0, 'C', 0); 
$pdf->Cell(39,4.5,'Utilidad','1', 0,'R', 0); 
$pdf->Cell(31,4.5,$vtotalUtilidadDiaPPDT,'1', 0, 'L', 0); 
$pdf->Cell(25,6,'',0, 0, 'C', 0); 
$pdf->Cell(39,4.5,'Utilidad','1', 0,'R', 0); 
$pdf->Cell(31,4.5,$vtotalUtilidadDiaPPVT,'1', 0, 'L', 0); 


$pdf->Ln();
$pdf->SetX(16);	
$pdf->Cell(39,4.5,'Peso/Volúmen','TLR', 0, 'R', 0); 
$pdf->Cell(31,4.5,$vvolumenDiaPPT,'TLR', 0, 'L', 0); 

$pdf->Ln();
$pdf->SetX(16);	
$pdf->Cell(39,4.5,'IVA','TLR', 0, 'R', 0); 
$pdf->Cell(31,4.5,$vivaDiaPPT,'TLR', 0, 'L', 0); 

$pdf->Ln();
$pdf->SetX(16);	
$pdf->Cell(39,4.5,'Subtotal','TLR', 0, 'R', 0); 
$pdf->Cell(31,4.5,$vsubtotalDiaPPT,'TLR', 0, 'L', 0); 

$pdf->Ln();
$pdf->SetX(16);	
$pdf->Cell(39,4.5,'Retención','TLR', 0, 'R', 0); 
$pdf->Cell(31,4.5,$vretencionDiaPPT,'TLR', 0, 'L', 0); 


$pdf->Ln();
$pdf->SetX(16);	
$pdf->Cell(39,4.5,'Total Cobrado','TLR', 0, 'R', 0); 
$pdf->Cell(31,4.5,$vtotalCobradoDiaPPT,'TLR', 0, 'L', 0); 


$pdf->Ln();
$pdf->SetX(16);	
$pdf->Cell(39,4.5,'Total Pagado','TLR', 0, 'R', 0); 
$pdf->Cell(31,4.5,$vtotalPagadoDiaPPT,'TLR', 0, 'L', 0); 

$pdf->Ln();
$pdf->SetX(16);	
$pdf->Cell(39,4.5,'Utilidad','1', 0, 'R', 0); 
$pdf->Cell(31,4.5,$vtotalUtilidadDiaPPT,'1', 0, 'L', 0); 

//$pdf->Output();

//Al traer datos no podemos mosotrarlo directamente sobre el pdf, por lo que es necesario
//guardar el PDF y posteriormente mostrarlo
$file = basename(tempnam('.','tmp'));
rename($file,'../reporteUt/temporales/'.$file.'.pdf');
$file .= '.pdf';

//Guardar el PDF en un fichero
$pdf->Output($file,'F');

//Limpiará los archivos temporales que esten en el directorio, con una antiguedad superior a 3 minutos
CleanFiles('../temporales');

//Regresará el nombre del archivo para poder abrielo posteriormente
echo $file;


function CleanFiles($dir)
{
    //Borrar los ficheros temporales
    $t = time();
    $h = opendir($dir);
    while($file=readdir($h))
    {
        if(substr($file,0,3)=='tmp' && substr($file,-4)=='.pdf')
        {
            $path = $dir.'/'.$file;
            if($t-filemtime($path)>180)
                @unlink($path);
        }
    }
    closedir($h);
}
?>

