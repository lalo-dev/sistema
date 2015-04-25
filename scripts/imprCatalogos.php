<?php

	require("bd.php");
	require("pdfTable.php");		
	include("libreriaGeneral.php");
	
	$opcion=$_GET['opc'];	
	list($posicionX,$campos,$encabezados,$ancho)=datosCatalogo($opcion); //Obtenemos los datos del Catálogo
	
	class PDF extends PDF_Table
	{
		//Cabecera de página
		function Header()
		{
			//Movernos a la derecha
			$opcion=$_GET['opc'];
			$this->SetLineWidth(.5);
			$this->SetY(5);
			$this->SetFont('Arial', 'B',10);
			$this->SetX(30);
			$this->Cell(237,5, 'CARGA Y EXPRESS, S. A. DE C. V.' ,0, 0, 'C', 0);
			$this->SetY(10);
			date_default_timezone_set("America/Mexico_City");
			$fechaHoy=fecha(date('d-m-Y'),1);
			$encabezado=encabezadoReporte($opcion);
			$this->SetX(30);
			$this->Cell(237, 5, 'LISTADO DE '.$encabezado.' AL '.strtoupper($fechaHoy),0, 0,'C', 0);

			//Encabezados del Reporte				
			list($posicionX,$campos,$encabezados,$ancho)=datosCatalogo($opcion); //Obtenemos los datos del Catálogo
			$this->SetFont('Arial','B',9);
			$this->Ln(15);
			$this->SetFillColor(200,205,255);
			$this->SetX($posicionX);
			$this->SetLineWidth(.5);

			//Realizamos los encabezados
			for($i=0;$i<(count($encabezados));$i++)
			{		
				$this->Cell($ancho[$i],7,utf8_decode($encabezados[$i]),0,0,'C',1);
			}
			$this->Ln(7);
		}
		
		function Footer()
		{
			//Posición: a 1 cm del final
			$this->SetXY(5,-10);
			$this->SetFont('Arial','',10);
			//Número de página
			$numero=$this->PageNo();
			$this->Cell('280',4,utf8_decode("Página: ".$numero),0,0,'R');
		}
	}
	
	$pdf=new PDF('L','mm','A4');
	$pdf->AddFont('lucida','','LCALLIG.php');	
	$pdf->AliasNbPages();   
	$pdf->AddPage();
	$pdf->SetMargins(1.5,0,1.5);
	$pdf->SetDisplayMode(65,'default'); //Para que el zoom este al 65%, normal real	
	
	//Cargamos los datos del catálogo
	$sql=query($opcion,$campos);
	$registros = $bd->Execute($sql);

	foreach($registros as $registro){
		$pdf->SetX($posicionX);
		for($i=0;$i<(count($encabezados));$i++)
		{		
			if($opcion==2 && $i==3){
				$registro[$i] = str_replace("[br][br]","[br]",$registro[$i]);
				$registro[$i] = str_replace("[br]"," ",$registro[$i]);
			}
			if($i==0)
				$pdf->CellFitScale($ancho[$i],7,$registro[$i],1,0,'C',0);
			else
				$pdf->CellFitScale($ancho[$i],7,$registro[$i],1,0,'L',0);
		}
		$pdf->Ln(7);
	}
	
	//Se imprime el Reporte
	$pdf->Output();
	

	function encabezadoReporte($opcion) //La función regresará el encabezado del Reporte
	{	
		switch($opcion)
		{
			case 1: //Aerolineas
				$encabezado      = "AEROLÍNEAS";
			break;
			case 2: //Empresas
				$encabezado      = "EMPRESAS";			
			break;
			case 3: //Sucursales
				$encabezado      = "SUCURSALES";			
			break;
			case 4: //Monedas
				$encabezado      = "MONEDAS";			
			break;
			case 5: //Tipos de Envío
				$encabezado      = "TIPOS DE ENVÍO";			
			break;
			case 6: //Tipos de Documentos
				$encabezado      = "TIPOS DE DOCUMENTOS";			
			break;
			case 7: //Usuario
				$encabezado      = "USUARIOS";			
			break;
			case 8: //Códigos Postales
				$encabezado      = "CÓDIGOS POSTALES";			
			break;
			case 9: //Bancos
				$encabezado      = "BANCOS";			
			break;
			case 10: //Estaciones
				$encabezado      = "ESTACIONES";
			break; 
			default:
			break;
		}		
		return utf8_decode($encabezado);
	}
	
	function datosCatalogo($opcion) //La función regresará un arreglo con los campos y encabezados para cada Catálogo
	{	
		switch($opcion)
		{
			case 1: //Aerolineas
			{
				$campos          = "cveLineaArea,descripcion,contacto,telefono,IF(estatus=1,'Activado','Desactivado') AS estatus";
				$encabezados     = array('Clave Línea Aérea','Línea','Contacto','Teléfono','Estatus');
				$longitudColumna = array(35,65,65,35,30);				
				$x=33.5;
			}
			break;
			case 2: //Empresas
			{
				$campos          = "cveEmpresa,razonSocial,rfc,direccion,IF(estatus=1,'Activado','Desactivado') AS estatus";
				$encabezados     = array('Clave Empresa','Razón Social','RFC','Dirección','Estatus');
				$longitudColumna = array(25,85,45,85,30);		
				$x=13.5;
			}
			break;
			case 3: //Sucursales
			{
				$campos          = "csucursales.cveSucursal,cempresas.razonSocial,csucursales.nombre,IF(csucursales.estatus=1,'Activado','Desactivado') AS estatus";
				$encabezados     = array('Clave Sucursal','Sucursal','Empresa','Estatus');							
				$longitudColumna = array(25,87,87,30);		
				$x=34;
			}
			break;
			case 4: //Monedas
			{
				$campos          = "cveMoneda,descripcion,IF(estatus=1,'Activado','Desactivado') AS estatus";
				$encabezados     = array('Clave Moneda','Descripción','Estatus');							
				$longitudColumna = array(25,85,30);		
				$x=78.5;
			}
			break;
			case 5: //Tipos de Envío
			{
				$campos          = "cveTipoEnvio,descripcion,IF(estatus=1,'Activado','Desactivado') AS estatus";
				$encabezados     = array('Clave Envío','Descripción','Estatus');							
				$longitudColumna = array(25,115,30);		
				$x=63.5;
			}
			break;
			case 6: //Tipos de Documentos
			{
				$campos          = "cveDocumento,tipoDocumento,descripcion,folio,IF(estatus=1,'Activado','Desactivado') AS estatus";
				$encabezados     = array('Clave Documento','Tipo Documento','Siglas','Folio','Estatus');							
				$longitudColumna = array(30,60,60,60,60,30);		
				$x=13.5;
			}
			break;
			case 7: //Usuario
			{
				$campos          = "cusuarios.cveUsuario,cusuarios.nombre,cusuarios.apeidoPaterno,cusuarios.apeidoMaterno,cusuarios.permiso,".
								   "IF(permiso='Cliente',(LEFT(nombreComercial,'20')),(IFNULL(cusuarios.estacion,' '))) AS estacion,".
								   "IF(cusuarios.estatus=1,'Activado','Desactivado') AS estatus";
				$encabezados     = array('Clave Usuario','Nombre','Apellido Paterno','Apellido Materno','Permiso','Estación/Cliente','Estatus');							
				$longitudColumna = array(23,52,56,56,25,45,30);		
				$x=5;
			}
			break;
			case 8: //Códigos Postales
			{
				$campos          = "ccodigospostales.cveCP,cestados.nombre AS edo,cmunicipios.nombre AS mun,ccodigospostales.codigoPostal,ccodigospostales.nombre";
				$encabezados     = array('Clave C.P.','Estado','Municipio','C.P.','Colonia');														
				$longitudColumna = array(25,67,67,30,100);	
				$x=4.5;				
			}
			break;
			case 9: //Bancos
			{
				$campos          = "cveBanco,descripcion,IF(estatus=1,'Activado','Desactivado') AS estatus";
				$encabezados     = array('Clave Banco','Banco','Estatus');	
				$longitudColumna = array(30,60,60);		
				$x=73.5;				
			}
			break;
			case 10: //Estaciones
			{
				$campos          = " cdestinos.cveDestino,cestados.nombre,cdestinos.descripcion,IF(cdestinos.estatus=1,'Activado','Desactivado') AS estatus";
				$encabezados     = array('Clave Destino','Estado','Estación','Estatus');
				$longitudColumna = array(25,87,87,30);
				$x=34;
			}
			break;
			default:
			break;
		}
		return array($x,$campos,$encabezados,$longitudColumna);
	}
	
	function query($opcion,$campos)
	{
		switch($opcion)
		{
			case 1: //Aerolineas
			{
				$query="SELECT ".$campos." FROM  clineasaereas ORDER BY estatus ASC,cveLineaArea ASC";
			}
			break;
			case 2: //Empresas
			{
				$query="SELECT ".$campos." FROM  cempresas ORDER BY estatus ASC,cveEmpresa ASC";
			}
			break;
			case 3: //Sucursales
			{
				$query="SELECT ".$campos." FROM   csucursales ".
					   "INNER JOIN cempresas ON csucursales.cveEmpresa=cempresas.cveEmpresa ".	
					   "ORDER BY estatus ASC,csucursales.nombre ASC";
			}
			break;
			case 4: //Monedas
			{
				$query="SELECT ".$campos." FROM  cmonedas ORDER BY estatus ASC,cveMoneda ASC";
			}
			break;
			case 5: //Tipos de Envío
			{
				$query="SELECT ".$campos." FROM  ctipoenvio ORDER BY estatus ASC,cveTipoEnvio ASC";
			}
			break;
			case 6: //Tipos de Documentos
			{ 
				$query="SELECT ".$campos." FROM  cfoliosdocumentos ORDER BY estatus ASC,tipoDocumento ASC";
			}
			break;
			case 7: //Usuario
			{
				$query="SELECT ".$campos." FROM  cusuarios ".
				"LEFT JOIN ccliente ON cusuarios.estacion=ccliente.cveCliente ".
				"ORDER BY estatus ASC,cusuarios.nombre ASC";
			}
			break;
			case 8: //Códigos Postales
			{
				$query="SELECT ".$campos." FROM  ccodigospostales ".
				"INNER JOIN cestados ON cestados.cveEstado = ccodigospostales.cveEstado ".
				"INNER JOIN cmunicipios ON cmunicipios.cveMunicipio = ccodigospostales.cveMunicipio ".
				"WHERE cmunicipios.cveEntidadFederativa= ccodigospostales.cveEstado ".
				"ORDER BY cestados.nombre ASC,cmunicipios.nombre ASC,ccodigospostales.nombre ASC";
			}
			break;
			case 9: //Bancos
			{
				$query="SELECT ".$campos." FROM  cbancos ORDER BY estatus ASC,descripcion ASC";		
			}
			break;
			case 10: //Destino
			{
				$query="SELECT ".$campos." FROM cdestinos ".
				"INNER JOIN cestados ON cdestinos.estado=cestados.cveEstado ".
				"ORDER BY estatus ASC,cestados.nombre ASC,cdestinos.cveDestino";
			}
			break;
			default:
			break;
		}
		return $query;
	}
?>
