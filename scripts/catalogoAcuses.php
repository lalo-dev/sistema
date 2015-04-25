<?php

	/**
	 * @author miguel
	 * @copyright 2009
	 */
	
	header("Content-Type: text/Text; charset=ISO-8859-1 Cache-Control: no-store, no-cache, must-revalidate");
	header("Expires: Fri, 14 Mar 1980 20:53:00 GMT"); //la pagina expira en fecha pasada
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); //ultima actualizacion ahora cuando la cargamos
	header("Cache-Control: no-cache, must-revalidate"); //no guardar en CACHE
	header("Pragma: no-cache"); //PARANOIA, NO GUARDAR EN CACHE
	include ("bd.php");
	$caracteres = $_POST["caracteres"];
	$cliente = $_GET["cliente"];
	$operacion = $_GET["operacion"];
	
	
	if ($caracteres != "")
	{
					switch ($operacion)
					{
	
									case 1:
										{
											$dests = $bd->ExecuteField("cacuse", "folio", "cveCliente=$cliente AND folio LIKE '$caracteres%' LIMIT 0,10");
										}
										break;
									case 2:
										{
											$dests = $bd->ExecuteField("cacuse", "folio", "facturado='0' AND cveCliente=$cliente AND folio LIKE '$caracteres%' LIMIT 0,10");
										}
										break;
									case 3:
										{
											$dests = $bd->ExecuteField("cacuse", "folio", " (SELECT COUNT(*) FROM cacuse INNER JOIN cguias ON cacuse.cveGuia=cguias.cveGuia WHERE cguias.facturada=0)>0 AND cveCliente=$cliente AND folio LIKE '$caracteres%' LIMIT 0,10");
										}
										break;
									default:
													break;
					}
					$respuesta = "<ul>";
					foreach ($dests as $dest)
					{
						$respuesta .= "<li>" . $dest . "</li>";
					}
					$respuesta .= "</ul>";
					echo $respuesta;
	
	}

?>
