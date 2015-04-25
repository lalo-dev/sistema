<?php

header("Content-Type: text/Text; charset=ISO-8859-1 Cache-Control: no-store, no-cache, must-revalidate");
header ("Expires: Fri, 14 Mar 1980 20:53:00 GMT"); //la pagina expira en fecha pasada
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); //ultima actualizacion ahora cuando la cargamos
header ("Cache-Control: no-cache, must-revalidate"); //no guardar en CACHE
header ("Pragma: no-cache"); //PARANOIA, NO GUARDAR EN CACHE

include("bd.php");
$caracteres =  $_POST["caracteres"];
$cveCorresponsal=$_GET["corresponsal"];
$operacion=$_GET["operacion"];

	if ($caracteres != ""){
		switch($operacion){

			case 1:
			{
				$anyo=$_GET["anyo"];
				$sql="SELECT DISTINCT(cveFactura) FROM cfacturascorresponsal WHERE cveCorresponsal='$cveCorresponsal' ".
					 "AND anyoFactura='$anyo' AND cveFactura LIKE '$caracteres%' LIMIT 0,5";
				$dests = $bd->ExecuteFieldf($sql);
			}
			break;
			case 2:
			{
					//$dests = $bd->ExecuteField("cfoliosdocumentos", "folio","cveCliente=".$cliente. " AND cveGuia LIKE '$caracteres%%' LIMIT 0,10");
					$dests = $bd->ExecuteField("dedocta", "folioDocumento", "cveCliente='$cveCliente' AND cveTipoDocumento='FAC' AND saldo > 0 AND folioDocumento LIKE '$caracteres%%' LIMIT 0,5");
				}
			break;
			case 3:
			{
					$dests = $bd->ExecuteFieldf("SELECT DISTINCT(cveFactura) FROM cfoliosdocumentos WHERE folio LIKE '$caracteres%%' LIMIT 0,10");
				}
			break;
		
		default:break;
		}
			
		$respuesta = "<ul>";
		foreach ($dests as $dest){
			$respuesta .= "<li>" . $dest . "</li>";
		}
		$respuesta .= "</ul>";
		echo $respuesta;
		
	}
	

?>
