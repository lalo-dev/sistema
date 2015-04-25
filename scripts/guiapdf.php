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

/*************************************************************************************************************/
// valoresphp = '&oculto='+oculto+'&empresa='+cveEmp+'&sucursal='+cveSuc+'&cveGuiaInt='+cveGuiaInt+'&cveCliente='+cveCli+'&nombre='+nombre+'&calle='+calle+'&colonia='+colonia+'&municipio='+municipioNum+'&estado='+estadoNum+'&cp='+cp+'&telefono='+telefono+'&rfc='+rfc+'&usuario='+usuario+'&totalFolios='+totalFolios+'&numGuiaInicio='+numGuiaInicio+'&totalGuias='+totalGuias;
	
	$canImp = $_REQUEST['canImp'];
	$oculto = $_REQUEST['oculto'];
	
	$nombre = $_REQUEST['nombreR'];
	$calle = $_REQUEST['calleR'];
	$colonia = $_REQUEST['coloniaR'];
	$municipio = $_REQUEST['municipioR'];
	$estado = $_REQUEST['estadoR'];
	$cp = $_REQUEST['cpR'];
	$telefono = $_REQUEST['telefonoR'];
	
	if(isset($_REQUEST['nombreC']))
	{
		$numPiezas = $_REQUEST['numPiezas'];
		$numKilos = $_REQUEST['numKilos'];
		$volumen = $_REQUEST['volumen'];
		
		$nombreC = $_REQUEST['nombreC'];
		$calleC = $_REQUEST['calleC'];
		$coloniaC = $_REQUEST['coloniaC'];
		$municipioC = $_REQUEST['municipioC'];
		$estadoC = $_REQUEST['estadoC'];
		$cpC = $_REQUEST['cpC'];
		$telefonoC = $_REQUEST['telefonoC'];
	}
/*************************************************************************************************************/

//Creación del objeto de la clase heredada
$pdf=new PDF('P','mm','mcarta');
$pdf->AliasNbPages();
$pdf->SetDisplayMode(60,'default'); //Para que el zoom este al 100%, normal real

function partirTexto($texto)
{
	$divisor=strlen($texto)/2;
	$texto1=substr($texto,0,$divisor);
	$texto2=substr($texto,$divisor,strlen($texto));
	$siguiente=substr($texto,$divisor,1);
	if($siguiente!=" ")	//Significa que la palabra se corto
	{
		$palabra=encontrarEspacio($texto2);
		$texto2=$palabra[1];
		$texto1.=$palabra[0];
	}
	$resultados=array($texto1,$texto2);
	return $resultados;
}
function encontrarEspacio($texto)
{
	$longitud=strlen($texto);
	$palabra="";
	for($i=0;$i<$longitud;$i++)
	{
		if($texto[$i]!=" ")
		{
			$palabra.=$texto[$i];
		}
		else
		{
			break;
		}
	}
	$i++;
	$textoFinal=substr($texto,$i,$longitud);
	$resultados=array($palabra,$textoFinal);
	return $resultados;
}
$nombreP=partirTexto($nombre);
$nombre1=$nombreP[0];
$nombre2=$nombreP[1];

if(isset($_REQUEST['nombreC']))
{
	$nombreP2=partirTexto($nombreC);
	$nombre3=$nombreP2[0];
	$nombre4=$nombreP2[1];
}
if(!is_numeric($canImp))
{
	//echo "entro isnum<br />";
	$canImp = trim($canImp);
	$rangos = explode(",", $canImp);
	$a = (sizeof($rangos))-1;
	$inicio = $rangos[0];
	$fin = $inicio + $a;
}
else
{
	$inicio = $_REQUEST["inicio"];
	$fin = ($_REQUEST["fin"] + 1);
}
$conn = 0;
for($i=$inicio;$i<$fin;$i++)
{
	$pdf->AddPage();
	$pdf->SetAutoPageBreak(true,1);
	$pdf->SetMargins(1,1,1);
	
	//Línea 1 --> FOLIO
	$pdf->SetFont('Arial','B',24);
	$pdf->SetXY(168,11);
	$pdf->SetTextColor(0,0,0);
	if($oculto == "si")
	{
		$pdf->Cell(35,8,$i,0,0,'C',0);
	}
	$conn++;
	
	//Línea 1 --> No. PIEZAS
	if(isset($_REQUEST['nombreC']))
	{
		$pdf->SetFont('Arial','B',14);
		$pdf->SetTextColor(0,0,0);
		$pdf->Ln();
		$pdf->SetXY(59,30);
		$pdf->Cell(72,8,'LO QUE DICE CONTENER ES . . ',0,0,'C',0);
		$pdf->SetFont('Arial','B',24);
		$pdf->Cell(24,8,$numPiezas,0,0,'C',0);
		$pdf->Cell(25,8,$numKilos,0,0,'C',0);
		$pdf->Cell(25,8,$volumen,0,0,'C',0);
	}
	
	//Línea 2 --> NOMBRE 1 y 2
	$pdf->SetFont('Arial','B',14);//14
	$pdf->SetTextColor(0,0,0);
	$pdf->Ln();
	$pdf->SetXY(27,39);
	$pdf->CellFitScale(76.5,4,$nombre1,0,0,'L',0);
	$y=$pdf->GetY();
	$pdf->SetXY(10,$y+4);
	$pdf->CellFitScale(93.5,4,$nombre2,0,0,'L',0);
	
	//Línea 2 --> NOMBRE 1 y 2 Consignatario
	if(isset($_REQUEST['nombreC']))
	{			
		$pdf->SetFont('Arial','B',14);
		$pdf->SetTextColor(0,0,0);
		$pdf->Ln();
		$pdf->SetXY(132,39);
		$pdf->CellFitScale(76.5,4,$nombre3,0,0,'L',0);
		$z=$pdf->GetY();
		$pdf->SetXY(109,$z+4);
		$pdf->CellFitScale(93.5,4,$nombre4,0,0,'L',0);
	}
	
	//Línea 3 --> CALLE
	$pdf->SetFont('Arial','B',14);//12
	$pdf->Ln(5);
	$pdf->SetX(20);
	$pdf->CellFitScale(83.5,4,$calle,0,0,'L',0);
	if(isset($_REQUEST['nombreC']))
	{
		$pdf->SetX(119);
		$pdf->CellFitScale(83.5,4,$calleC,0,0,'L',0);
	}
	$pdf->Ln(4.5);
	$pdf->SetX(20);
	$pdf->Cell(83.5,4,$colonia,0,0,'L',0);
	if(isset($_REQUEST['nombreC']))
	{
		$pdf->SetX(119);
		$pdf->Cell(83.5,4,$coloniaC,0,0,'L',0);
	}
		
	//Línea 4
	$pdf->SetFont('Arial','B',14);
	$pdf->Ln(4.5);
	$pdf->SetX(20);
	$pdf->Cell(83.3,4,$municipio.' '.$estado,0,0,'L',0);
	if(isset($_REQUEST['nombreC']))
	{
		$pdf->SetX(119);
		$pdf->Cell(83.5,4,$municipioC.' '.$estadoC,0,0,'L',0);
	}
	$pdf->Ln(4.5);
	$pdf->SetX(20);
	$pdf->Cell(33,4,$cp,0,0,'L',0);
	$pdf->Cell(33.5,4,$telefono,0,0,'L',0);
	if(isset($_REQUEST['nombreC']))
	{
		$pdf->SetFont('Arial','B',14);
		$pdf->SetX(119);
		$pdf->Cell(33,4,$cpC,0,0,'L',0);
		$pdf->Cell(33.5,4,$telefonoC,0,0,'L',0);
	}
}

//Poner el nombre para descargar el archivo
//$pdf->Output('MiNuevoPDF.pdf',D);
$pdf->Output();
?>