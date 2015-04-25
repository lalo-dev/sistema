<?php
	/**
	 * @author Jose Miguel Pantaleon
	 * @copyright 2010
	 */
	 
	include_once("bd.php");
	include_once("libreriaGeneral.php");
	
	
	$fechaI=$_GET['fechaI'];
	$fechaF=$_GET['fechaF'];	
	$fechaInicio=cambiaf_a_mysql($fechaI);
	$fechaFin=cambiaf_a_mysql($fechaF);
	
	$destinos = $bd->Execute("SELECT DISTINCT(cveDestino) AS cveDestino2 FROM cdestinos ".
							 "WHERE cveEmpresa=1 AND cveSucursal=1 AND estatus=1 ".
							 "ORDER BY ".
							 "(SELECT IFNULL(SUM(IF(kg>volumen,kg,volumen)),0) AS peso FROM cguias ".
							 "INNER JOIN cconsignatarios ON cguias.cveConsignatario=cconsignatarios.cveConsignatario ".
							 "WHERE estatus=1 AND cconsignatarios.estacion=cveDestino2 AND recepcionCYE BETWEEN '".$fechaInicio."' AND '".$fechaFin."') DESC,cveDestino ASC;");
	$j=0;
	
	$contador= restaFechas($fechaFin,$fechaInicio);
	$contador++;
	
	$pesoCompleto=0;
	
	foreach ($destinos as $destino)
	{
						 
		$reporte[$j][0]=$destino['cveDestino2'];
		$destinos[$j]=$destino['cveDestino2'];
		$tmpFecha=$fechaInicio;
		
		for($i=1;$i<=$contador;$i++)		
		{
			$pesoTotal=0;
			$peso=0;
			
			$sql="SELECT IFNULL(SUM(IF(kg>volumen,kg,volumen)),0) AS peso FROM cguias 
				  INNER JOIN cconsignatarios ON cguias.cveConsignatario=cconsignatarios.cveConsignatario
				  WHERE  cconsignatarios.estacion='".$destino['cveDestino2']."' AND recepcionCYE='$tmpFecha' AND estatus=1";

			$pesoTotal=$bd->soloUno($sql);
			
			if($pesoTotal=="0.00")
				$pesoTotal=0;
			
			$reporte[$j][$i-1]=$pesoTotal; 
			$pesoCompleto=$pesoCompleto+$pesoTotal;
			
			$datosDias[$i-1]=$datosDias[$i-1]+$pesoTotal;
			$datosDestino[$j]=$datosDestino[$j]+$pesoTotal;
			$fechas[$i-1]=$tmpFecha; 
			
			$tmpFecha=suma_fechas($fechaInicio,$i);			
			$tmpFecha=cambiaf_a_mysql($tmpFecha); 
		}		
		$j++;                         
	}

	$contador--;
	//Calcular el porcentaje por día, sobre el total de ventas
	for($i=0;$i<$contador;$i++)
	{
		if($datosDias[$i]!="")
		{
			$porcentajeDia[$i]= $datosDias[$i]*100/$pesoCompleto;
			$porcentajeDia[$i]=redondear_dos_decimal($porcentajeDia[$i]);
		}else $porcentajeDia[$i]=0;
	}
		
	//Calcular el porcentaje de ventas por cliente	
	for($i=0;$i<$j;$i++)
	{
		if($datosDestino[$i]!="")
		{
			$porcentajeCliente[$i]= $datosDestino[$i]*100/$pesoCompleto;
			$porcentajeCliente[$i]=redondear_dos_decimal($porcentajeCliente[$i]);
		}else $porcentajeCliente[$i]=0;
	}
	
	$prefijo = substr(md5(uniqid(rand())),0,6);
	
	//Para la gráfica
	
	include("src/jpgraph.php");
	include("src/jpgraph_bar.php");
	 
	$ydata = $datosDestino;
	$graph = new Graph(1200,800, "auto");   

	 
	//Colores bordes
	$graph->frame_color='white';
	$graph->frame_weight=4;
	$graph->margin_color=array(255,255,255);   // Margin color of graph
   	$graph->plotarea_color=array(219,219,219); // Plot area color
	
	
	$graph->SetScale("textlin");
	 
	$graph->img->SetMargin(40,10,50,300);
	$graph->title->Set("");


	$graph->xaxis->SetTickLabels($destinos);
	$graph->xaxis->SetLabelAngle(90);

	//Letra de los Ejes
	$graph->yaxis->SetFont(FF_VERDANA,FS_BOLD, 8);
	$graph->xaxis->SetFont(FF_VERDANA,FS_BOLD, 8);
	 
	$barplot =new BarPlot($ydata);

	$barplot->SetColor("darkblue");
	 
	$graph->Add($barplot);
	$graph -> img -> SetImgFormat('jpeg');
	$graph->Stroke("graficas/$prefijo.jpeg");
	
	/**
	 * Aquí comienza a crearse el pdf
	 */
	require('pdfTable.php');
	
	
	class PDF extends PDF_Table
	{
		//Cabecera de página
		function Footer()
		{
			
		}
	}

	//Creación del objeto de la clase heredada
	$pdf=new PDF('L','mm','Legal');
	$pdf->AliasNbPages();
	
	$pdf->AddPage();
	$pdf->Image('imagenes/logo.jpeg', 20, 16, 60, 20);
	//Arial bold 15
	$pdf->SetFont('Arial', '', 6);

	//Encabezados para la primera página

	//Movernos a la derecha
	$pdf->Ln(8);
	$pdf->Cell(263);
	$pdf->Cell(70,3,'PÁGINA ' . $txtFolio,0, 0, 'R', 0);
	$pdf->Ln();
	$pdf->Cell(263);
	$pdf->Cell(70,3,'( ' . $pdf->PageNo() . '/{nb} )',0, 0, 'R', 0);
	$pdf->Ln();
	$pdf->SetFont('Arial', '', 15);
	$pdf->Cell(127);
	$pdf->Cell(90,8,'CARGA Y EXPRESS, S. A . DE C. V.' ,0, 0, 'L', 0);
	$pdf->Ln(4);
	$pdf->SetFont('Arial', '', 9);
	$pdf->Cell(127);
	$pdf->Cell(150, 3, '', 0, 0, 'L', 0);
	$pdf->Ln(4);
	$pdf->SetFont('Arial', '', 9);
	$pdf->Cell(97);
	$primeraFecha=nombre_mes($fechaI,1);
	$segundaFecha=nombre_mes($fechaF,1);
	$textoFinal=strtoupper('TOTAL DE CARGA ENVÍADA DEL '.$primeraFecha.' AL '.$segundaFecha);
	$pdf->Cell(150,5,$textoFinal,0,0,'C',0);
	$pdf->Ln(17);
	
	
	//Cargamos las Fechas
	$pdf->SetFont('Arial', '', 4);
	$pdf->SetTextColor(255);
	$pdf->SetX(4);
	$pdf->Cell(20, 4,'',0, 0, 'C', 0);
	$pdf->SetTextColor(0);
	for($i=0;$i<=$contador;$i++)
	{
		$fechaAvre=nombre_mes($fechas[$i],0);
		$pdf->Cell(9, 4,$fechaAvre, 1, 0, 'C', 0); 
	}
	
	//Cargamos los datos de Ventas
	$pdf->Cell(14, 4,'% CLIENTE' , 1, 0, 'C', 0);
	$pdf->Cell(15, 4,'TOTAL CLIENTE' , 1, 0, 'C', 0);
	$pdf->Ln(); 
	$pdf->SetFont('Arial', '', 7);  
	for($m=0;$m<$j;$m++)
	{
		$pdf->SetX(4);
		$pdf->Cell(20,4,$destinos[$m], 1, 0, 'R', 0);	
		for($i=0;$i<($contador+1);$i++)		
		{
			$pdf->Cell(9,4,$reporte[$m][$i], 1, 0, 'C', 0);	
		}
		$pdf->Cell(14,4,$porcentajeCliente[$m]." %", 1, 0, 'R', 0);
		$pdf->Cell(15,4,$datosDestino[$m], 1, 0, 'R', 0);
		$pdf->Ln();
	}
	
	//Datos Totales
	$pdf->SetFont('Arial', '', 6);
	$y=$pdf->GetY();
	$x=$pdf->GetX();
	$pdf->SetXY(4,$y);
	$pdf->SetTextColor(255);
	$pdf->Cell(20, 4,'PORCENTAJE', 1, 0, 'C', 1);
	$pdf->SetTextColor(0);
	//Datos de Porcentajes
	$final=0;
	for($i=0;$i<=$contador;$i++)
	{
		$pdf->CellFitScale(9,4,$porcentajeDia[$i]." % ",1,0,'C',0);
		$final+=$porcentajeDia[$i];
	}

	$pdf->Cell(14, 4,$final.' % ' , 1, 0, 'C', 0);
	$pdf->Cell(15, 4,$pesoCompleto , 1, 0, 'C', 0);
	$pdf->Ln();
	$pdf->SetX(4);
	$pdf->SetTextColor(255);
	$pdf->Cell(20, 4,'TOTAL', 1, 0, 'C', 1);
	$pdf->SetTextColor(0);
	//Datos de Totales
	for($i=0;$i<=$contador;$i++)
	{	
		if($datosDias[$i]==0)
			$pdf->CellFitScale(9,4,'0',1,0,'C',0);	
		else
			$pdf->CellFitScale(9,4,$datosDias[$i],1,0,'C',0);
	}

	//GRÁFICA
	$pdf->Ln();
	$pdf->AddPage();
	$pdf->Image('imagenes/logo.jpeg', 20, 16, 60, 20);
	$pdf->SetFont('Arial', '', 6);
	//Movernos a la derecha
	$pdf->Ln(8);
	$pdf->Cell(263);
	$pdf->Cell(70,3,'PÁGINA ' . $txtFolio,0, 0, 'R', 0);
	$pdf->Ln();
	$pdf->Cell(263);
	$pdf->Cell(70,3,'( ' . $pdf->PageNo() . '/{nb} )',0, 0, 'R', 0);
	$pdf->Ln();
	$pdf->SetFont('Arial', '', 15);
	$pdf->Cell(127);
	$pdf->Cell(90,8,'CARGA Y EXPRESS, S. A . DE C. V.' ,0, 0, 'L', 0);
	$pdf->Ln(4);
	$pdf->SetFont('Arial', '', 9);
	$pdf->Cell(127);
	$pdf->Cell(150, 3, '', 0, 0, 'L', 0);
	$pdf->Ln(4);
	$pdf->SetFont('Arial', '', 9);
	$pdf->Cell(97);
	$primeraFecha=nombre_mes($fechaI,1);
	$segundaFecha=nombre_mes($fechaF,1);
	$textoFinal=strtoupper('TOTAL DE CARGA ENVÍADA DEL '.$primeraFecha.' AL '.$segundaFecha);
	$pdf->Cell(150,5,$textoFinal,0,0,'C',0);
	$pdf->Ln(17);										
	$pdf->Image('graficas/'.$prefijo.'.jpeg', 20, 50, 310, 150);                       
	$pdf->Output();  
	//Borrará el archivo de la gráfica generado: para NO ocupar mucho espacio, la carpeta de graficas siempre estará vacía, solo es una referencia
	unlink('graficas/'.$prefijo.'.jpeg');


?>
