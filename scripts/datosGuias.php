<?php
	header("Content-Type: text/Text; charset=ISO-8859-1 Cache-Control: no-store, no-cache, must-revalidate");
	header ("Expires: Fri, 14 Mar 1980 20:53:00 GMT"); //la pagina expira en fecha pasada
	header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); //ultima actualizacion ahora cuando la cargamos
	header ("Cache-Control: no-cache, must-revalidate"); //no guardar en CACHE
	header ("Pragma: no-cache"); //PARANOIA, NO GUARDAR EN CACHE
	
	include("bd.php");
	$cveguia = $_GET["cveguia"];

	$facturas = $bd->Execute("SELECT facturaSoporte,cveFacturaS FROM cfacturassoporte WHERE cveGuia= '$cveguia'");
	$facturaEnviar="";
	$cveFactura="";

	foreach($facturas as $factura){
		$facturaEnviar=	$facturaEnviar.$factura["facturaSoporte"].",";
		$cveFactura=	$cveFactura.$factura["cveFacturaS"].",";
	}
	$facturaEnviar = substr($facturaEnviar, 0, strlen($facturaEnviar)-1);
	$cveFactura = substr($cveFactura, 0, strlen($cveFactura)-1);
	
	$entregas = $bd->Execute("SELECT cveEntregaS,entregasSoporte FROM centregassoporte WHERE cveGuia= '$cveguia'");
	$entregaEnviar="";
	$cveEntrega="";

	foreach($entregas as $entrega){
		$entregaEnviar=	$entregaEnviar.$entrega["entregasSoporte"].",";
		$cveEntrega=	$cveEntrega.$entrega["cveEntregaS"].",";
	}
	$entregaEnviar = substr($entregaEnviar, 0, strlen($entregaEnviar)-1);
	$cveEntrega = substr($cveEntrega, 0, strlen($cveEntrega)-1);
	
	$vales = $bd->Execute("SELECT valeSoporte,cveValeS FROM cvalessoporte WHERE cveGuia= '$cveguia'");
	$valeEnviar="";

	foreach($vales as $vale){
		$valeEnviar=	$valeEnviar.$vale["valeSoporte"].",";
		$cveVale=$vale["cveValeS"];
	}
	
	$valeEnviar = substr($valeEnviar, 0, strlen($valeEnviar)-1);
	
	$sql="SELECT *,cguias.estatus AS edo,IFNULL(clineasaereas.descripcion,'') AS lAerea,cconsignatarios.cveConsignatario, cconsignatarios.estacion, cconsignatarios.nombre, 
	cconsignatarios.estado, cconsignatarios.municipio, cconsignatarios.colonia, cconsignatarios.calle, cconsignatarios.codigoPostal, cconsignatarios.telefono
	FROM cguias
	LEFT JOIN cconsignatarios ON cguias.cveConsignatario = cconsignatarios.cveConsignatario
	LEFT JOIN clineasaereas ON cguias.cveLineaArea = clineasaereas.cveLineaArea
	WHERE cveGuia =  '$cveguia'";
	
	$campos = $bd->Execute($sql);


	$respuesta = "[";
	foreach($campos as $campo){
		 
		$respuesta .= "{obsCliente: '" . $campo["obsRemitente"] . "',obsDContener: '" . $campo["contCarga"]  . "',estatus: '" . $campo["edo"] . "', guiaArea: '" . $campo["guiaArea"] . "', cveLineaArea: '" . $campo["cveLineaArea"] ."', noVuelo: '" . $campo["noVuelo"] ."', fechaVuelo: '" . $campo["fechaVuelo"] ."', recepcionCYE: '" . $campo["recepcionCYE"] ."', nombreRemitente: '" . $campo["nombreRemitente"] ."', calleRemitente: '" . $campo["calleRemitente"] ."', coloniaRemitente: '" . $campo["coloniaRemitente"] ."', municipioRemitente: '" . $campo["municipioRemitente"] ."', estadoRemitente: '" . $campo["estadoRemitente"] ."', telefonoRemitente: '" . $campo["telefonoRemitente"] ."', rfcRemitente: '" . $campo["rfcRemitente"] ."', codigoPR: '" . $campo["codigoPostalRemitente"]  ."', piezas: '" . $campo["piezas"] ."', kg: '" . $campo["kg"] ."', volumen: '" . $campo["volumen"] ."', validezDias: '" . $campo["validezDias"] . "', estadoDestinatario: '" . $campo["estado"] ."', status: '" . $campo["status"] ."', respaldo: '" . $campo["indicadorRespaldos"] ."', sello: '" . $campo["sello"] ."', firma: '" . $campo["firma"] ."', llegadaacuse: '" . $campo["llegadaacuse"] ."', recibio: '" . $campo["recibio"] ."', fechaEntrega: '" . $campo["fechaEntrega"] ."', recoleccion: '" . $campo["recoleccion"] ."', tipoEnvio: '" . $campo["tipoEnvio"] ."', Valor: '" . $campo["valorDeclarado"] ."',vales: '" . $valeEnviar ."', cliente: '" . $campo["cveCliente"] ."', cveVale: '" . $cveVale ."', cveDireccion: '" . $campo["cveDireccion"]."', reexpedicion: '" . $campo["reexpedicion"] ."', consignatario: '" . $campo["cveConsignatario"]."', nombreD: '" . $campo["nombre"]."', sucursalDestino: '" . $campo["estacion"] ."', codigoPostaldestinatario: '" . $campo["codigoPostal"] ."', calleDestinatario: '" . $campo["calle"] ."', coloniaDestinatario: '" . $campo["colonia"] ."', municipioDestinatario: '" . $campo["municipio"] ."', telefonoD: '" . $campo["telefono"] ."', ctoGuia: '" . $campo["costoGuia"]."', lineaAerea: '" . $campo["lAerea"] ."'},";

	}

	$respuesta = substr($respuesta, 0, strlen($respuesta)-1);
	$respuesta .= "]";
	//echo utf8_decode($respuesta);
	echo $respuesta;

?>
