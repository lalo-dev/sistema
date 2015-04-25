<?php

/**
 * @author miguel
 * @copyright 2010
 */

include ("bd.php");
require ('clasepdf/fpdf.php');
 
$txtRazon=$_GET['razon'];
$txtFolio=$_GET['folio'];
$cveCliente=$_GET['cveCliente'];
$hdnContador=$_GET['hdnContador'];
session_start();
$usuarioLog=$_SESSION["usuario_valido"];
date_default_timezone_set("America/Mexico_City");
$fechaReporte=date('d/m/Y H:i:s');

$maximo=$bd->soloUno("SELECT MAX(cacuse.cveGuia) FROM cacuse WHERE cacuse.folio='$txtFolio' AND cacuse.cveCliente='$cveCliente'");

$sqlConsulta="SELECT nombre,apellidoPaterno,apellidoMaterno FROM ccontactoscliente INNER JOIN cguias ON cguias.cveDireccion=ccontactoscliente.sucursalCliente WHERE cguias.cveGuia IN (".$maximo.") AND contactoFacturacion=1 AND cguias.cveCliente='$cveCliente' AND ccontactoscliente.cveCliente='$cveCliente'";

$nombres=$bd->Execute($sqlConsulta);

foreach ($nombres as $nombre)
{
	$nombrec = $nombre["nombre"] . " " . $nombre["apellidoPaterno"] . " " . $nombre["apellidoMaterno"] ." ";
}
session_start();
$_SESSION['nombreCon']=$nombrec;

$nombreUsuario=$bd->soloUno("SELECT LEFT(CONCAT(nombre,' ',apeidoPaterno),15) FROM cusuarios WHERE cveUsuario='".$usuarioLog."'");


					class PDF extends FPDF
					{
							function Header()
							{
								$nombrec=$_SESSION['nombreCon'];
								
								$this->SetFont('Arial', '', 6);	
								//Cabecera de página
								$txtFolio=$_GET['folio'];
								$this->Ln(8);
								$this->Cell(263, 3, 'FOLIO ' . $txtFolio,0, 0, 'R', 0);
								$this->Ln();
								$this->Cell(263, 3, '( ' . $this->PageNo() . '/{nb} )', 0, 0, 'R', 0);


								$this->Image('imagenes/logo2.jpg', 20, 16, 60, 20);
								//Arial bold 15
								$this->SetFont('Arial', '', 6);
								//Movernos a la derecha
								$this->Ln(8);

								$txtRazon=$_GET['razon'];
								$txtFolio=$_GET['folio'];
								$cveCliente=$_GET['cveCliente'];


								$this->Ln();
								$this->SetFont('Arial', '', 9);
								$this->Cell(127);
								$this->Cell(150, 3, 'ATN: ' . $nombrec, 0, 0, 'L', 0);
								$this->Ln(4);
								$this->Cell(127);
								$this->Cell(150, 3, 'P R E S E N T E', 0, 0, 'L', 0);
								$this->Ln(4);
								$this->Cell(62);
								$this->Cell(65, 3, 'Cliente :', 0, 0, 'R', 0);
								$this->SetFont('Arial', 'B', 9);
								$this->Cell(150, 3, $txtRazon, 0, 0, 'L', 0);
								$this->Ln();
								$this->Ln(8);
								$this->SetFont('Arial', '', 9);
								$this->Cell(13, 4, '', 0, 0, 'C', 0);
								$this->Cell(35, 4, 'Reporte de Acuses por Cliente', 0, 0, 'C', 0);
								$this->Cell(13, 4, '', 0, 0, 'C', 0);
								$this->SetFont('Arial', '', 7);
								//Este es un valor fijo para el Reporte: SIEMPRE DIRÁ LO MISMO: ( Clave	C/E-014    Fecha 03 / Nov / 2003	Revision  00 )
								$this->Cell(220, 3, '( Clave	C/E-014    Fecha 03 / Nov / 2003	Revisión  00 )', 0,
												0, 'L', 0);
								$this->Ln(5);
							}
							function Footer()
							{

							}
					}
					

					
					
					//Creación del objeto de la clase heredada
					$pdf = new PDF('L', 'mm', 'A4');
					$pdf->SetDisplayMode(75,'default'); //Para que el zoom este al 75%, normal real
					$pdf->AliasNbPages();
					$pdf->SetAutoPageBreak(true,5);
					$pdf->AddPage();

					$pdf->Ln(8);
					$pdf->SetFillColor(41,22,111);
					$pdf->SetTextColor(0);
					$pdf->SetDrawColor(0);
					$pdf->SetLineWidth(.2);
					$pdf->SetFont('Arial', 'B', 7);
					$pdf->SetTextColor(247,247,247);
					$pdf->Cell(13, 4, '', 0, 0, 'C', 0);
					$pdf->Cell(33, 4, 'FECHA', 1, 0, 'C', 1);
					$pdf->Cell(18, 4, 'GUÍA', 1, 0, 'C', 1);
					$pdf->Cell(63, 4, 'DESTINO', 1, 0, 'C', 1);
					$pdf->Cell(74, 4, 'RECIBIÓ', 1, 0, 'C', 1);
					$pdf->Cell(65, 4, 'FACTURA Y/O ENTREGA', 1, 0, 'C', 1);								
					$pdf->Ln();
					$pdf->SetTextColor(0,0,0);
					$contador=0;
					$guias = $bd->Execute("SELECT cveGuia FROM cacuse WHERE folio='$txtFolio' AND cveCliente='$cveCliente'");

								
				foreach ($guias as $guia)
				{
								$cveguia = $guia["cveGuia"];

								$facturas = $bd->Execute("SELECT facturasoporte FROM cfacturassoporte WHERE cveGuia= '$cveguia'");

								$facturaEnviar='';
								foreach ($facturas as $factura)
								{
												$facturaEnviar = $facturaEnviar . $factura["facturasoporte"] . ",";

								}
								$facturaEnviar = substr($facturaEnviar, 0, strlen($facturaEnviar) - 1);
								$entregas = $bd->Execute("SELECT entregasSoporte FROM centregassoporte WHERE cveGuia= '$cveguia'");
								$entregaEnviar ='';
								foreach ($entregas as $entrega)
								{
												$entregaEnviar = $entregaEnviar . $entrega["entregasSoporte"] . ",";

								}

								$entregaEnviar = substr($entregaEnviar, 0, strlen($entregaEnviar) - 1);

								if ($facturaEnviar == "")
								{
												$facturaEnviar = $entregaEnviar;
								}
								
								$campos = $bd->Execute("SELECT cguias.recepcionCYE, cguias.sello, cguias.firma, cguias.indicadorRespaldos, cguias.recibio,cmunicipios.nombre AS municipioDestinatario,cveDireccion FROM cguias 
								LEFT JOIN cconsignatarios ON cguias.cveConsignatario = cconsignatarios.cveConsignatario	
								INNER JOIN cestados ON cconsignatarios.estado = cestados.cveEstado
								INNER JOIN cmunicipios ON cconsignatarios.municipio = cmunicipios.cveMunicipio
								WHERE 
								cestados.cveEstado=cmunicipios.cveEntidadFederativa	AND cguias.cveGuia= '$cveguia'");

								foreach ($campos as $campo)
								{
												$sello = $campo["sello"];
												$firma = $campo["firma"];
												$recibio = $campo["recibio"];
												$respaldo = $campo["indicadorRespaldos"];
												if ($sello == 1)
												{
																$recibido = "SELLO ";
																if ($firma == 1)
																{
																				$recibido = $recibido . "Y FIRMA ";
																}
																else
																{
																				$recibido = $recibido;
																}
																if ($recibio != '')
																{
																				$recibido = $recibido . $recibio;
																}
																else
																{
																				$recibido = $recibido;
																}
												}
												else
												{
																$recibido = "";
																if ($firma == 1)
																{
																				$recibido = $recibido . "FIRMA ";
																}
																else
																{
																				$recibido = $recibido;
																}
																if ($recibio != '')
																{
																				$recibido = $recibido . $recibio;
																}
																else
																{
																				$recibido = $recibido;
																}
												}
												$guia = "GUIA ";
												if ($facturaEnviar != '')
												{
																$guia .= "Y FACT. " . $facturaEnviar;
												} elseif ($respaldo == 1)
												{
																$guia .= "Y RESPALDO";
												}
												
																
																$fecha = cambiaf_a_normal($campo["recepcionCYE"]);
																$destino = $campo["municipioDestinatario"];
																$recibio = $recibido;
																$factura = $guia;

																
if(strlen($factura)>40)
																{
																	$totalRenglones=$pdf->MultiCellWO(65,4,$factura, 1, 0, 'C', 0);
																	$alto=$totalRenglones*4;
																}
																else
																	$alto=4;

																$pdf->Cell(13,$alto, '', 0, 0, 'C', 0);
																$pdf->Cell(33,$alto, $fecha, 1, 0, 'C', 0);
																$pdf->Cell(18,$alto, $cveguia, 1, 0, 'C', 0);
																$pdf->Cell(63,$alto, $destino, 1, 0, 'C', 0);
																$pdf->Cell(74,$alto, $recibio, 1, 0, 'C', 0);
																$pdf->MultiCell(65,4,$factura,1,'C',0);
								}


				}


				$pdf->Ln();							
				$pdf->Cell(52, 4, "Elaboró:".$nombreUsuario,0, 0, 'C', 0);
				$pdf->Cell(25, 4, "",0, 0, 'C', 0);
				$pdf->Cell(30, 4, "Firma",0, 0, 'L', 0);
				$pdf->Cell(65, 4, "", 'B', 0, 'C', 0);
				$pdf->Cell(30, 4, "", 0, 0, 'L', 0);
				$pdf->Cell(40, 4,$fechaReporte, 0, 0, 'L', 0);
				$pdf->Ln(8);
				$pdf->Cell(76, 4, "",0, 0, 'C', 0);
				$pdf->Cell(30, 4, "Nombre", 0, 0, 'L', 0);
				$pdf->Cell(65, 4, "", 'B', 0, 'C', 0);
				$pdf->Ln(8);
				$pdf->Cell(76, 4, "", 0, 0, 'C', 0);
				$pdf->Cell(30, 4, "Fecha",0, 0, 'L', 0);
				$pdf->Cell(65, 4, "", 'B', 0, 'C', 0);
				$pdf->Ln(8);
				$pdf->Output();


function cambiaf_a_normal($fecha){ 
   	preg_match( "#([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2})#", $fecha, $mifecha); 
   	$lafecha=$mifecha[3]."/".$mifecha[2]."/".$mifecha[1]; 
   	return $lafecha; 
}
?>
