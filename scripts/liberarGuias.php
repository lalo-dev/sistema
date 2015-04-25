<?php
	/**
	* @author Jose Miguel Pantaleon
	* @copyright 2010
	*/

	header("Content-Type: text/Text; charset=ISO-8859-1 Cache-Control: no-store, no-cache, must-revalidate");
	header ("Expires: Fri, 14 Mar 1980 20:53:00 GMT"); //la pagina expira en fecha pasada
	header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); //ultima actualizacion ahora cuando la cargamos
	header ("Cache-Control: no-cache, must-revalidate"); //no guardar en CACHE
	header ("Pragma: no-cache"); //PARANOIA, NO GUARDAR EN CACHE

	include("bd.php");
	$folio = $_GET["folio"];
	//Primero consultamos las facturas derivadas de la factura principal, sin considerar el seguro, pues en él se repiten las guías
	$sqlConsulta="SELECT cveCliente,cveFactura,seguro FROM cfacturas WHERE cveFactura='$folio' OR referencia='$folio'";
	$facturasD = $bd->Execute($sqlConsulta);
	//Por cada factura se consultarán las guías que tiene cada una, para poder liberarlas
	foreach($facturasD as $facturaD)
	{
		$factSeguro=$facturaD['seguro'];
		$cliente=$facturaD['cveCliente'];
		if($factSeguro==0)
			$clavesFacturas.="'".$facturaD['cveFactura']."',";
		$clavesGenerales.="'".$facturaD['cveFactura']."',";
	}

	$clavesFacturas = substr($clavesFacturas,0,strlen($clavesFacturas)-1);
	$clavesGenerales = substr($clavesGenerales,0,strlen($clavesGenerales)-1);

	//Restaurar valores de Factura principal y las secundarias
	    //Consultar las guías
	$guiasD="SELECT cveGuia FROM cfacturasdetalle WHERE cveFactura IN(".$clavesFacturas.")";
	$guias = $bd->Execute($guiasD);
	foreach($guias as $guia)
	{
		$clavesGuias.="'".$guia['cveGuia']."',";
	}
	$clavesGuias = substr($clavesGuias,0,strlen($clavesGuias)-1);
	
	    //LIBERAR LAS GUÍAS
	$sqlUpdate="UPDATE cguias SET facturada=0 WHERE cveGuia IN(".$clavesGuias.")";
	$my_error1 += $bd->ExecuteNonQuery($sqlUpdate);

	   //ACTUALIZAR ACUSE
	$sqlUpdate="UPDATE cacuse SET facturado=0 WHERE cveGuia IN(".$clavesGuias.") AND cveCliente='".$cliente."'";
	$my_error1 += $bd->ExecuteNonQuery($sqlUpdate);

  	   //ACTUALIZAR LA DEUDA DEL CLIENTE
	$sqlUpdate="UPDATE dedocta SET saldo=0 WHERE tipoEstadoCta='cliente' AND cveTipoDocumento='FAC' ".
	  "AND folioDocumento IN(".$clavesGenerales.") AND cveCliente='".$cliente."'";
	$my_error1 += $bd->ExecuteNonQuery($sqlUpdate);


	if ($my_error1>0)
	{ echo "Error: No se pudieron liberar las guías."; }
	else
	{ echo "Las guias se han liberado exitosamente.";  }

?>
