<?php

/**
 * @author Jose Miguel Pantaleon
 * @copyright 2010
 */

require_once('bd.php');
require_once('pdfTable.php');
require_once('libreriaGeneral.php');


$codigo=$_GET['codigo'];
$inicio=$_GET['inicio'];
$termino=$_GET['termino'];
$opcion=$_GET['opc'];
$tabla=($opcion==1)?"cliente":"corresponsal";

$empresa=$_GET['empresa'];
$empresa=str_replace ( "-", "'", $empresa);
$empresaS=explode(',',$empresa);

class PDF extends PDF_Table
{
	function Header()
	{
		$codigo=$_GET['codigo'];
		$empresa=$_GET['empresa'];
		$empresa=str_replace ( "-", "'", $empresa);
		$empresaS=explode(',',$empresa);
		$inicio=$_GET['inicio'];
		$termino=$_GET['termino'];
		$opcion=$_GET['opc'];
		$tabla=($opcion==1)?"cliente":"corresponsal";
		
		//Logo
		$this->Image('imagenes/logo.jpeg',4,4,60,20);
		$this->SetFont('Arial','',10);
		
		$this->SetXY(63,6);
		$this->Cell(60,4,'DETALLE DE MOVIMIENTOS',0,0,'L',0);
		$this->SetXY(6,26);
		$fecha = date("d/m/Y");
		$fecha=strtoupper(nombre_mes($fecha,2));	
		$this->Cell(117,4,'MÉXICO, D.F., '.$fecha,0,0,'L',0);
		$this->Ln();
		
		//Datos del Cliente		
		$fechaF=$termino;
		$finF=$termino;
		$fechaI=$inicio;
		$inicio = cambiaf_a_mysql($inicio);
		$termino=cambiaf_a_mysql($termino);
		
		//Verificamos si se trata de un corresponsal o de un cliente
		if($opcion==1) //Cliente
		{ 
			$myqury = "SELECT ccliente.rfc,ccliente.razonSocial,cdireccionescliente.cveSucursal,cdireccionescliente.cveDireccion,".
			"cdireccionescliente.calle, cdireccionescliente.codigoPostal,cdireccionescliente.colonia,".
			"IF(cdireccionescliente.numeroInterior='',cdireccionescliente.numeroexterior,cdireccionescliente.numeroInterior) AS numero,".
			"cdireccionescliente.cveEstado,cdireccionescliente.cveMunicipio ".
			"FROM cdireccionescliente ".
			"INNER JOIN ccliente ON cdireccionescliente.cveCliente=ccliente.cveCliente ".
			"WHERE cdireccionescliente.cveCliente='$codigo' AND cdireccionescliente.cveEmpresa=".$empresaS[0]." ".
			"AND cdireccionescliente.cveSucursal=".$empresaS[1]." ORDER BY cdireccionescliente.cveDireccion LIMIT 1";
		}
		else
		{		 //Corresponsal	
			$myqury = "SELECT ccorresponsales.rfc,ccorresponsales.razonSocial,cdireccionesprovedores.cveSucursal,cdireccionesprovedores.cveDireccion,".
			"cdireccionesprovedores.calle, cdireccionesprovedores.codigoPostal,cdireccionesprovedores.colonia,".
			"IF(cdireccionesprovedores.numeroInterior='',cdireccionesprovedores.numeroexterior,cdireccionesprovedores.numeroInterior) AS numero,".
			"cdireccionesprovedores.cveEstado,cdireccionesprovedores.cveMunicipio ".
			"FROM cdireccionesprovedores ".
			"INNER JOIN ccorresponsales ON cdireccionesprovedores.cveCorresponsal=ccorresponsales.cveCorresponsal ".
			"WHERE cdireccionesprovedores.cveCorresponsal='$codigo' AND cdireccionesprovedores.cveEmpresa=".$empresaS[0]." ".
			"AND cdireccionesprovedores.cveSucursal=".$empresaS[1]." ORDER BY cdireccionesprovedores.cveDireccion LIMIT 1";
		}
		
		$bd = new BD;
		$nombres = $bd->Execute($myqury);
		foreach ($nombres as $nombre)
		{
			$razonSocial     = $nombre["razonSocial"];
			$rfc             = $nombre["rfc"];
			$sucursalCliente = $nombre["cveSucursal"];
			$calle           = $nombre["calle"];
			$numero          = $nombre["numero"];
			$colonia         = $nombre["colonia"];
			$cveMunicipio    = $nombre["cveMunicipio"];
			$cveEstado       = $nombre["cveEstado"];
			$codigoPostal    = $nombre["codigoPostal"];
		}
	
		$sqlsaldoI="SELECT IFNULL(SUM(saldo),0)AS saldo FROM dedocta WHERE dedocta.cveCliente='$codigo' AND dedocta.cveEmpresa ='1' AND dedocta.fecha<'$inicio' ORDER BY fecha";
		$saldosI = $bd->soloUno($sqlsaldoI);
		
		$sqlsaldoF="SELECT IFNULL(SUM(saldo),0)AS saldo FROM dedocta WHERE dedocta.cveCliente='$codigo' AND dedocta.cveEmpresa ='1' ".
				   "AND dedocta.fecha<DATE_ADD('$termino', INTERVAL 1 DAY)";
		$saldosF = $bd->soloUno($sqlsaldoF);
		
		//Se considerará deposito como todo aquello que el cliente haya pagado de cuqluier forma
		$sqldepositos="SELECT IFNULL(SUM(montoNeto),0) AS pagos FROM dedocta WHERE dedocta.cveCliente='$codigo' AND dedocta.cveEmpresa ='1' ".
				   "AND (cveTipoDocumento = 'PAG' OR cveTipoDocumento = 'NCE ') AND dedocta.fecha<DATE_ADD('$termino', INTERVAL 1 DAY)";
		$depositos = $bd->soloUno($sqldepositos);
		

		$this->SetFillColor(200,205,255);
		$this->SetTextColor(0);
		$this->SetDrawColor(0);
		$this->SetLineWidth(.2);
		$this->SetFont('Arial','B',10);
		$this->Ln(8);
		//LTR: Con márgen L:izquierdo, T:arriba y R:derecho
		$this->SetX(0);
		$this->SetX(6);
		$this->Cell(94,6,$razonSocial,'LTR',0,'L',0);
		$this->SetFont('Arial','B',8);
		$tipoPersona=($opcion==1)?'CLIENTE':'CORRESPONSAL';
		$this->Cell(25,6,$tipoPersona,'TR',0,'L',0);
		$this->Cell(21,6,$codigo,'LTR',0,'L',0);
		$this->Ln();
		$this->SetX(6);
		$this->Cell(94,6,"Calle ".$calle .' No.'.$numero,'LR',0,'L',0);
		$this->Cell(25,6,'R.F.C.','BR',0,'L',0);
		$this->CellFitScale(21,6,$rfc,'LBR',0,'L',0);
		$this->Ln();
		$this->SetX(6);
		$this->Cell(94,6,"Col. ".$colonia,'LR',0,'L',0);
		$this->Cell(46,6,'PERIODO','R',0,'L',0);
		$this->SetFont('Arial','B',8);
		$this->Ln();
		$this->SetX(6);
		$this->Cell(94,6,"C.P. ".$codigoPostal,'LR',0,'L',0);
		
		$fechaI=nombre_mes($fechaI,2);
		$this->Cell(10,6,'DEL:','',0,'R',0);
		$this->Cell(36,6,$fechaI,'R',0,'L',0);
		$this->SetFont('Arial','B',8);
		$this->Ln(); 
		$this->SetX(6);		
		$this->Cell(94,6,'' ,'LBR',0,'L',0);
		$fechaF=nombre_mes($fechaF,2);
		$this->Cell(10,6,'AL:','B',0,'R',0);
		$this->Cell(36,6,$fechaF,'BR',0,'L',0);
		$this->Ln();      
		$this->SetX(6);		
		$this->Cell(140,6,'RESUMEN DEL PERIODO' ,0,0,'C',0);
		$this->Ln();
		$this->SetX(6);
		$this->SetFont('Arial','B',8);          
		$this->Cell(30,6,'SALDO AL INCIO: ','TBL',0,'L',0);
		$this->Cell(40,6,"$".number_format($saldosI,2),'TB',0,'L',0);
		$this->Cell(30,6,'MONTO DEPÓSITOS:','TBL',0,'R',0);
		$this->Cell(40,6,"$".number_format($depositos,2),'TBR',0,'L',0);
		$this->Ln(6);
		$this->SetX(6);		
		$this->Cell(30,6,'SALDO AL FINAL: ','TBL',0,'L',0);		
		$this->Cell(40,6,"$".number_format($saldosF,2),'TB',0,'L',0);
		$this->Cell(30,6,'MONTO RETIROS:','TBL',0,'R',0);
		$this->Cell(40,6,"$".number_format($saldoIt,2),'TBR',0,'L',0);		
		$this->Ln();     
		$this->SetX(6);
		$this->Cell(140,6,'Detalle de Movimientos' ,'RL',0,'C',0);
		$this->Ln();		
		$this->SetX(6);
		
		//Encabezados
		$this->SetFont('Arial','',8);
		$this->SetAligns(array('C','C','C','C','C','C','C'));
		$this->SetWidths(array(18,19,19,20,20,20,24));	
		$this->SetX(6);
		
		$cabezeras[0]='Fecha';
		$cabezeras[1]='Documento';
		$cabezeras[2]='Tipo de Documento';
		$cabezeras[3]='Referencia';
		$cabezeras[4]='Cargos';
		$cabezeras[5]='Abonos';
		$cabezeras[6]='Saldo';
		$this->Row($cabezeras,10,0);		
		$this->SetX(6);
		
	}
	//Cabecera de página
	
	function Footer()
	{
	}
}

//Creación del objeto de la clase heredada
$pdf=new PDF('L','mm','A4');
$pdf->AddFont('lucida','','LCALLIG.php');	
$pdf->AliasNbPages();   
$pdf->AddPage();
$pdf->SetMargins(1.5,0,1.5);
$pdf->SetDisplayMode(80,'default'); //Para que el zoom este al 100%, normal real

//Datos del Estado

$inicio = cambiaf_a_mysql($inicio);
$termino=cambiaf_a_mysql($termino);

$sql="SELECT referencia AS ref,fecha, folioDocumento, cveTipoDocumento, montoNeto AS Cargo, 0.00 AS Abono, saldo FROM dedocta WHERE tipoEstadoCta='$tabla' ".
	"AND cveTipoDocumento = 'FAC' AND dedocta.cveCliente='$codigo' AND dedocta.fecha BETWEEN '$inicio' AND '$termino' ". 
	"UNION ".
	"SELECT documentoReferencia AS ref,fecha,folioDocumento, cveTipoDocumento, 0.00 AS Cargo, montoNeto AS Abono, saldo FROM dedocta WHERE tipoEstadoCta='$tabla' ".
	"AND cveTipoDocumento = 'PAG' AND dedocta.cveCliente='$codigo' AND dedocta.fecha BETWEEN '$inicio' AND '$termino' ".
	"UNION ".
	"SELECT documentoReferencia AS ref,fecha,folioDocumento,cveTipoDocumento, 0.00 AS Cargo,montoNeto AS Abono, 0.00 AS saldo FROM dedocta ".
	"WHERE tipoEstadoCta='$tabla' ".
	"AND cveTipoDocumento = 'NCE' AND dedocta.cveCliente='$codigo' AND dedocta.fecha BETWEEN '$inicio' AND '$termino' ".
	"ORDER BY fecha";	

$campos = $bd->Execute($sql);

$pdf->SetAligns(array('C','C','C','C','R','R','R'));
$pdf->SetFont('Arial','',8);
$x=6;
$pdf->SetX($x);
$total=0;
$totalPer=13;
$r=0;

if(count($campos)==0)
{
	$pdf->Cell(140,6,'No hay cuentas asociadas.' ,'BLR',0,'C',0);
}
else{

	foreach($campos as $campo){
		
		if($total>$totalPer) //Mostrar en la otra parte de la página
		{
			if($totalPer==28) //Se agrega una hoja y se reetablecen algunos valores
			{
				$pdf->AddPage();
				$totalPer=13;
				$total=0;
				$x=6;
			}
			else{
				$pdf->SetXY(154,6);
				$pdf->Cell(140,6,'Detalle de Movimientos' ,'TLR',0,'C',0);
				$pdf->Ln(6);
				$pdf->SetX(154);
				$x=154;
				$totalPer=28;
				$total=0;
			}
		}
		$valores[0]=$campo["fecha"];
		$valores[1]=$campo["folioDocumento"];
		$valores[2]=$campo["cveTipoDocumento"];
		$valores[3]=$campo["ref"];
		$valores[4]="$".number_format($campo["Cargo"],2);
		$valores[5]="$".number_format($campo["Abono"],2);
		$valores[6]="$".number_format($campo["saldo"],2);
		$pdf->Row($valores,6,0);  
		$pdf->SetX($x);
		$total++;
		$r++;
	}
}

$pdf->Output();
    
   

?>

