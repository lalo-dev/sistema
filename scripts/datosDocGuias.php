<?php
	header("Content-Type: text/Text; charset=ISO-8859-1 Cache-Control: no-store, no-cache, must-revalidate");
	header ("Expires: Fri, 14 Mar 1980 20:53:00 GMT"); //la pagina expira en fecha pasada
	header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); //ultima actualizacion ahora cuando la cargamos
	header ("Cache-Control: no-cache, must-revalidate"); //no guardar en CACHE
	header ("Pragma: no-cache"); //PARANOIA, NO GUARDAR EN CACHE
	
	include("bd.php");
	
	$cveGuia = $_GET["guia"];

	$sqlConsulta="SELECT cveFactura FROM cfacturasdetalle WHERE cveGuia='".$cveGuia."'";
	$facturas = $bd->Execute($sqlConsulta);

	if(count($facturas)==0)
		$foliosFac='Sin Folio';
	else
	{	
		foreach($facturas as $factura)
		{
			$foliosFac.=$factura['cveFactura'].",";
		}
		$foliosFac = substr($foliosFac, 0, strlen($foliosFac)-1);
	}
	
	
	$sqlConsulta="SELECT folio FROM cacuse WHERE cveGuia='".$cveGuia."'";	
	$folios = $bd->Execute($sqlConsulta);
	
	if(count($folios)==0)
		$folioAcu='Sin Folio';
	else
	{	
		foreach($folios as $folio)
		{
			$folioAcu.=$folio['folio'].",";
		}
		$folioAcu = substr($folioAcu,0,strlen($folioAcu)-1);
	}
	
	$respuesta = "[{acuses: '".$folioAcu."', facturas: '".$foliosFac."'}]";

	echo $respuesta;

?>
