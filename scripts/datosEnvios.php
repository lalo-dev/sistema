<?php

	/**
	 * @author miguel
	 * @copyright 2009
	 */
	 
	header("Content-Type: text/Text; charset=ISO-8859-1 Cache-Control: no-store, no-cache, must-revalidate");
	header ("Expires: Fri, 14 Mar 1980 20:53:00 GMT"); //la pagina expira en fecha pasada
	header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); //ultima actualizacion ahora cuando la cargamos
	header ("Cache-Control: no-cache, must-revalidate"); //no guardar en CACHE
	header ("Pragma: no-cache"); //PARANOIA, NO GUARDAR EN CACHE
	include("bd.php");
	$cveguias = $_GET["cveguia"];
	$folio = $_GET["folio"];
	$operacion=$_GET["operacion"];

	switch($operacion){
		case 1: //Se cambio; se agregó lo de facturado
		{
			$cliente=$_GET["cliente"];
			//$aGpups = $bd->Execute("SELECT cveGuia FROM  cacuse WHERE folio= '$folio' AND facturado=0 AND cveCliente='$cliente'");
			$sql="SELECT cacuse.cveGuia FROM cacuse INNER JOIN cguias ON cguias.cveGuia = cacuse.cveGuia
			WHERE cguias.facturada=0 AND cacuse.folio= '$folio' AND cacuse.cveCliente='$cliente'";

			$aGpups = $bd->Execute($sql);
			
		}
		break;
		case 2:
		{
			$aGpups = $bd->Execute("SELECT cvalessoporte.cveGuia FROM cvalessoporte INNER JOIN cguias ON cguias.cveGuia=cvalessoporte.cveGuia WHERE valeSoporte= '$folio' AND cguias.facturada=0");
		}
		break;
		case 3:
		{
			$soloGuia ="si";
		}
		break;
		case 4:
		{
			$aGpups = $bd->Execute("SELECT cveGuia FROM cfacturasdetalle 
								   INNER JOIN (SELECT cvefactura,seguro FROM cfacturas WHERE cvefactura='$folio') AS factura
									ON cfacturasdetalle.cveFactura=factura.cvefactura");
	
		}
		break;
		case 5:
		{
			$cliente=$_GET["cliente"];
			$aGpups = $bd->Execute("SELECT porRetencion,porSeguro,tope FROM cfacturas WHERE cvefactura='$folio' OR referencia='$folio' 
								   AND cveCliente='$cliente' ORDER BY cveFactura ASC;");	
		}
		break;
		case 6:
		{
			$sql="SELECT fecha,noGuia,remicion,planificacion,destino,destinatario=1,observacion,valorDeclarado, piezas, peso, 
			tarifa, flete, seguro, acuse, importe, iva, subtotal, retencion, total, observaciones FROM ccamposenvio WHERE cveFactura= '$folio' ";			
			$aGpups = $bd->Execute($sql);	
		}
		break;		
		default:break;
	}
	$respuesta = "[";

	if($soloGuia=="si")
	{
		$cveGuia=$cveguias;
		$datos = $bd->Execute("SELECT recepcionCYE,cveGuia,tipoEnvio,valorDeclarado,piezas,kg,volumen,municipioRemitente,cguias.cveCliente,
							  ccliente.cveImpuesto,estadoRemitente,ccliente.razonSocial,cconsignatarios.estacion,cconsignatarios.nombre,
							  cconsignatarios.estado AS estadoDestinatario,cconsignatarios.municipio AS municipioDestinatario,
							  (IF(reexpedicion=1,
							  (SELECT CONCAT('Reexpedicion',' ',cmunicipios.nombre,' ',recoleccion) FROM cguias 
							  LEFT JOIN cconsignatarios ON cguias.cveConsignatario = cconsignatarios.cveConsignatario
							  INNER JOIN cmunicipios ON cconsignatarios.estado=cmunicipios.cveEntidadFederativa
							  AND cconsignatarios.municipio=cmunicipios.cveMunicipio
							  WHERE cveGuia= '$cveGuia'),
							  recoleccion)) AS observacion
							  FROM cguias 
							  INNER JOIN ccliente ON cguias.cveCliente=ccliente.cveCliente 
							  LEFT JOIN cconsignatarios ON cguias.cveConsignatario = cconsignatarios.cveConsignatario	
							  WHERE cveGuia= '$cveGuia'");
		
		
$facturaEnviar="";

$facturas = $bd->Execute("SELECT facturaSoporte FROM cfacturassoporte WHERE cveGuia= '$cveGuia'");
		$facturaEnviar="";
		
		foreach($facturas as $factura){
			$facturaEnviar= $facturaEnviar.$factura["facturaSoporte"].",";
		}
		$facturaEnviar = substr($facturaEnviar, 0, strlen($facturaEnviar)-1);
		
		$enEnviar='';	
		$vales = $bd->Execute("SELECT entregasSoporte FROM centregassoporte WHERE cveGuia= '$cveGuia'");
		foreach($vales as $vale){
			$enEnviar= $enEnviar.$vale["entregasSoporte"].",";
		}
		$enEnviar = substr($enEnviar, 0, strlen($enEnviar)-1);
		
		if($facturaEnviar=="")
		{ 
			$facturaEnviar=$enEnviar;
			
		}
				
		$valeEnviar="";
		$vales = $bd->Execute("SELECT valeSoporte FROM cvalessoporte WHERE cveGuia= '$cveGuia'");
		$valeEnviar="";
		foreach($vales as $vale){
			$valeEnviar= $valeEnviar.$vale["valeSoporte"].",";
		}
		$valeEnviar = substr($valeEnviar, 0, strlen($valeEnviar)-1);
		
		$sqlC="SELECT DISTINCT(ctarifas.cvetipoc) FROM ctarifas INNER JOIN ccliente ON ctarifas.cveTipoc=ccliente.cveTipoCliente INNER JOIN cguias ON cguias.cveCliente=ccliente.cveCliente WHERE cguias.cveGuia='$cveGuia'";
		//echo $sqlC;
		$tipoc=$bd->soloUno($sqlC);
		//echo $tipo;
//echo $tipoc;		
foreach ($datos as $dato)
		{
			 $tipoE=$dato["tipoEnvio"];
	
			 $sqlt="SELECT cargo99,cargo299,cargo300,cuartoRango,cargoMinimo FROM ctarifas WHERE estadoOrigen='9' AND estadoDestino='".$dato["estadoDestinatario"]."' AND origen='17' AND destino='".$dato["municipioDestinatario"] ."' AND tipoEnvio='$tipoE' AND cvetipoc='$tipoc' AND estatus=1";
			 
		//echo $sqlt;
$tarifas = $bd->Execute($sqlt);
	
			$i=count($tarifas);

//echo $i;
			if($i==0)
			{
				$tarifaa= 0;
				$tarifab= 0;
				$tarifac=0;
				$tarifad=0;
				$cargoMinimo=0;
			}else
			{
				foreach($tarifas as $tarifa){
					$tarifaa= $tarifa["cargo99"];
					$tarifab= $tarifa["cargo299"];
					$tarifac= $tarifa["cargo300"];
					$tarifad= $tarifa["cuartoRango"];
					$cargoMinimo=$tarifa["cargoMinimo"];
				} 
			}
			if($dato["kg"] > $dato["volumen"])
			{$peso=$dato["kg"];}else{$peso=$dato["volumen"];}
			$sqlr="SELECT primerRango,segundoRango,tercerRango FROM ctarifascorresponsales WHERE cveCorresponsal=0";
	
			$rangos = $bd->Execute($sqlr);
			foreach($rangos as $rango){
				 $rango1= valorRango($rango['primerRango']);
				 $rango2= valorRango($rango['segundoRango']);
				 $rango3= valorRango($rango['tercerRango']);
			}
			 
			if($peso<= $rango1)
			{
				$cargo=$tarifaa;
			}
			if($peso > $rango1 AND $peso<= $rango2){
				$cargo=$tarifab;
			}
			if($peso> $rango2 AND $peso<= $rango3){
				$cargo=$tarifac;
			}
			if($peso>= $rango3 ){
				$cargo=$tarifad;
			}
			
			$porcentajeIva=$dato["cveImpuesto"]/100;
			$flete= $cargo*$peso;
			if($flete>$cargoMinimo){$flete=$flete;}else{$flete=$cargoMinimo;}
			$acuse=150;
			$importe=$flete+$acuse;
			$iva=$importe*$porcentajeIva;
			$subtotal=$iva+$importe ;

			$valorDeclarado=redondeaNum($dato["valorDeclarado"]);
			$piezas=redondeaNum($dato["piezas"]);
			$peso=redondeaNum($peso);
			$cargo=redondeaNum($cargo);
			$flete=redondeaNum($flete);
			$acuse=redondeaNum($acuse);
			$importe=redondeaNum($importe);
			$iva=redondeaNum($iva);
			$subtotal=redondeaNum($subtotal);
			$cargoMinimo=redondeaNum($cargoMinimo);
			$cveImpuesto=redondeaNum($dato["cveImpuesto"]);


			$respuesta .= "{recepcionCYE: '" . $dato["recepcionCYE"] . "', cveGuia: '" . $dato["cveGuia"] . "', sucursalDestino: '" . $dato["estacion"] . "', nombreDestinatario: '" . $dato["nombre"] . "', tipoEnvio: '" . $dato["tipoEnvio"] . "', valorDeclarado: '" . $valorDeclarado . "', piezas: '" . $piezas . "', peso: '" . $peso . "', cargo: '" . $cargo . "', cvefacturas: '" . $facturaEnviar . "', flete: '" . $flete . "', acuse: '" . $acuse . "', importe: '" . $importe . "', iva: '" . $iva . "', subtotal: '" . $subtotal ."', cargoMinimo: '" . $cargoMinimo .
"', cveIva: '" . $cveImpuesto ."', obsGuia: '" . $dato["observacion"] ."', planificacionFolio: '" . $valeEnviar."'},";//echo $respuesta;
		}
	}
	else if($operacion==4) //Viene de una consulta de Factura
	{
		foreach ($aGpups as $gpup){
			$cveGuia=$gpup["cveGuia"];
			//Tarifas
			$sqlC="SELECT DISTINCT(ctarifas.cvetipoc) FROM ctarifas INNER JOIN ccliente ON ctarifas.cveTipoc=ccliente.cveTipoCliente INNER JOIN cguias ON cguias.cveCliente=ccliente.cveCliente WHERE cguias.cveGuia='$cveGuia'";



			$tipoc=$bd->soloUno($sqlC);
			$sqlConsulta="SELECT cfacturasdetalle.`observacionB`,TRUNCATE(cfacturasdetalle.total,2) AS total,
								  TRUNCATE(cfacturasdetalle.retencionIva,2) AS retencionIva,
								  TRUNCATE(cfacturasdetalle.subtotal,2) AS subtotal,TRUNCATE(cfacturasdetalle.cveIva,2) AS cveIva,
								  TRUNCATE(cfacturasdetalle.importe,2) AS importe,TRUNCATE(cfacturasdetalle.acuse,2) AS acuse, 
								  TRUNCATE(cfacturasdetalle.seguro,2) AS seguro,TRUNCATE(cfacturasdetalle.flete,2) AS flete,
								  TRUNCATE(cfacturasdetalle.tarifa,2) AS tarifa,TRUNCATE(cfacturasdetalle.peso,2) AS peso,
								  TRUNCATE(cfacturasdetalle.valorDeclarado,2) AS valorDeclarado,`destinatario`,`destino`,`PlanificacionFolio`,
								  `facturaRemicion`,`fechaEntrega`,`piezas`,`cveGuia`,observacion,ccliente.cveImpuesto
								   FROM `cfacturasdetalle`
								   INNER JOIN ccliente ON cfacturasdetalle.cveCliente=ccliente.cveCliente
								   INNER JOIN cfacturas ON cfacturasdetalle.cveFactura=cfacturas.cveFactura
								   WHERE cveGuia= '$cveGuia' AND cfacturas.seguro=0 AND 
								   cfacturasdetalle.cveFactura='$folio'";
			$datos = $bd->Execute($sqlConsulta);



			$datos2 = $bd->Execute("SELECT tipoEnvio,municipioRemitente,estadoRemitente,
					  cconsignatarios.estado AS estadoDestinatario,cconsignatarios.municipio AS municipioDestinatario
					  FROM cguias 
					  LEFT JOIN cconsignatarios ON cguias.cveConsignatario = cconsignatarios.cveConsignatario	
					  WHERE cveGuia= '$cveGuia'");


			
			foreach ($datos2 as $dato2)
			{
				$tipoE=$dato2["tipoEnvio"];
				
				$sqlt="SELECT cargoMinimo FROM ctarifas WHERE estadoOrigen='9' AND estadoDestino='".$dato2["estadoDestinatario"]."' AND origen='17' AND destino='".$dato2["municipioDestinatario"] ."' AND tipoEnvio='$tipoE' AND cvetipoc='$tipoc' AND estatus=1";

				$tarifas = $bd->Execute($sqlt);
			
				$i=count($tarifas);
				if($i==0) { 	$cargoMinimo=0;  }
				else
				{
					foreach($tarifas as $tarifa)
					{
						$cargoMinimo=$tarifa["cargoMinimo"];
					}
				}
			}
			
			foreach ($datos as $dato)
			{

				$valorDeclarado=redondeaNum($dato["valorDeclarado"]);
				$piezas=redondeaNum($dato["piezas"]);
				$peso=redondeaNum($dato["peso"]);
				$cargo=redondeaNum($dato["tarifa"]);
				$flete=redondeaNum($dato["flete"]);

				$acuse=redondeaNum($dato["acuse"]);
				$importe=redondeaNum($dato["importe"]);
				$subtotal=redondeaNum($dato["subtotal"]);
				$cargoMinimo=redondeaNum($dato["cargoMinimo"]);
				$cveIva=redondeaNum($dato["cveIva"]);
				$cveImpuesto=redondeaNum($dato["cveImpuesto"]);
				$total=redondeaNum($dato["total"]);		



				$respuesta .= "{retencionIva: '" . $dato["retencionIva"] ."',cvefacturas: '" . $dato["facturaRemicion"] ."', cargo: '" . $dato["tarifa"] ."' ,recepcionCYE: '" . $dato["fechaEntrega"] . "', cveGuia: '" . $dato["cveGuia"] . "', sucursalDestino: '" . $dato["destino"] . "', nombreDestinatario: '" . $dato["destinatario"]. "', observacionB: '" . $dato["observacionB"] . "', tipoEnvio: '" . $dato["observacion"] . "', valorDeclarado: '" . $valorDeclarado . "', piezas: '" . $piezas . "', peso: '" . $peso ."', flete: '" . $flete . "', acuse: '" . $acuse . "', importe: '" . $importe . "', subtotal: '" . $subtotal ."', iva: '" . $cveIva ."', planificacionFolio: '" . $dato["PlanificacionFolio"]."',cargoMinimo: '" . $cargoMinimo ."',cveIva: '" . $cveImpuesto ."',total: '" . $total."'},";

			}								
		}
		//Agregamos los totales de la Factura
		$sqlConsulta="SELECT 
				  TRUNCATE(cfacturas.importe,2) AS importeFactura,
				  TRUNCATE(cfacturas.iva,2) AS ivaFactura,
				  TRUNCATE(cfacturas.subtotal,2) AS subtotalFactura,
				  TRUNCATE(cfacturas.retencion,2) AS retencionFactura,
				  TRUNCATE(cfacturas.total,2) AS totalFactura
				  FROM cfacturas
				  WHERE cfacturas.seguro=0 AND cfacturas.cveFactura='$folio'";

		$datos = $bd->Execute($sqlConsulta);
		foreach ($datos as $dato)
		{
			$importeFactura   = redondeaNum($dato["importeFactura"]);
			$ivaFactura       = redondeaNum($dato["ivaFactura"]);
			$subtotalFactura  = redondeaNum($dato["subtotalFactura"]);
			$retencionFactura = redondeaNum($dato["retencionFactura"]);
			$totalFactura     = redondeaNum($dato["totalFactura"]);
		}
		
		$respuesta .= "{ importeTFactura: '" . $importeFactura . "', ivaTFactura: '" . $ivaFactura ."', subtotalTFactura: '" . $subtotalFactura."',retencionTFactura: '" . $retencionFactura."',totalTFactura: '" . $totalFactura ."'},";
			
	}
	else if($operacion==5) //Viene de una consulta de Factura (datos de retención y seguro)
	{
		foreach ($aGpups as $gpup){			
			$respuesta .= "{porcentajeRetI: '" . $gpup["porRetencion"] ."',tope: '" . $gpup["tope"]."',porcentajeSeg: '" . $gpup["porSeguro"]."'},";									
		}
	}
	else if($operacion==6) //Viene de una consulta de Factura (encabezados de la factura)
	{
		$i=0;
		foreach($aGpups as $datos)
		{		
			
			$valorDeclarado=redondeaNum($datos[7]);
			$piezas=redondeaNum($datos[8]);
			$peso=redondeaNum($datos[9]);
			$flete=redondeaNum($datos[11]);
			$acuse=redondeaNum($datos[13]);
			$importe=redondeaNum($datos[14]);
			$iva=redondeaNum($datos[15]);
			$subtotal=redondeaNum($datos[16]);
			$total=redondeaNum($datos[18]);


			$respuesta .= "{fecha:'".$datos[0]."',noGuia:'".$datos[1]."',remicion:'".$datos[2]."',planificacion:'".$datos[3].
			"',destino:'".$datos[4]."',destinatario:'".$datos[5]."',observacion:'".$datos[6]."',valorDeclarado:'". $valorDeclarado.
			"',piezas:'".$piezas."',peso:'".$peso."',tarifa:'".$datos[10]."',flete:'".$flete."',seguro:'".$datos[12].
			"',acuse:'".$acuse."',importe:'".$importe."',iva: '" . $iva ."',subtotal: '" . $subtotal ."',retencion: '" . 
			$datos[17] ."',total: '" . $total ."',observaciones: '" . $datos[19]."'},";
		}
	}
	else
	{
		$total=count($aGpups);
		if($total==0)
		{
			$respuesta .= "{totalR: '".$total."'},";									
		}
		else
		{
			foreach ($aGpups as $gpup){
				$cveGuia=$gpup["cveGuia"];
				
				$datos = $bd->Execute("SELECT recepcionCYE,cveGuia,tipoEnvio,valorDeclarado,piezas,kg,volumen,municipioRemitente,cguias.cveCliente,
					  ccliente.cveImpuesto,estadoRemitente,ccliente.razonSocial,cconsignatarios.estacion,cconsignatarios.nombre,
					  cconsignatarios.estado AS estadoDestinatario,cconsignatarios.municipio AS municipioDestinatario,
					  (IF(reexpedicion=1,
					  (SELECT CONCAT('Reexpedicion',' ',cmunicipios.nombre,' ',recoleccion) FROM cguias 
					  LEFT JOIN cconsignatarios ON cguias.cveConsignatario = cconsignatarios.cveConsignatario
					  INNER JOIN cmunicipios ON cconsignatarios.estado=cmunicipios.cveEntidadFederativa
					  AND cconsignatarios.municipio=cmunicipios.cveMunicipio
					  WHERE cveGuia= '$cveGuia'),
					  recoleccion)) AS observacion
					  FROM cguias 
					  INNER JOIN ccliente ON cguias.cveCliente=ccliente.cveCliente 
					  LEFT JOIN cconsignatarios ON cguias.cveConsignatario = cconsignatarios.cveConsignatario	
					  WHERE cveGuia= '$cveGuia'");
				
				$facturas = $bd->Execute("SELECT facturaSoporte FROM cfacturassoporte WHERE cveGuia= '$cveGuia'");
				$facturaEnviar="";
				foreach($facturas as $factura){
					$facturaEnviar= $facturaEnviar.$factura["facturaSoporte"].",";
				}
				$facturaEnviar = substr($facturaEnviar, 0, strlen($facturaEnviar)-1);
				
				$vales = $bd->Execute("SELECT entregasSoporte FROM centregassoporte WHERE cveGuia= '$cveGuia'");
				$enEnviar='';
				foreach($vales as $vale){
					$enEnviar= $enEnviar.$vale["entregasSoporte"].",";
				}
				$enEnviar = substr($enEnviar, 0, strlen($enEnviar)-1);
				
				if($facturaEnviar=="")
				{ 
					$facturaEnviar=$enEnviar;				
				}
				
				$valeEnviar="";
				$vales = $bd->Execute("SELECT valeSoporte FROM cvalessoporte WHERE cveGuia= '$cveGuia'");
				$valeEnviar="";
				foreach($vales as $vale){
					$valeEnviar= $valeEnviar.$vale["valeSoporte"].",";
				}
				$valeEnviar = substr($valeEnviar, 0, strlen($valeEnviar)-1);
				
				$sqlC="SELECT DISTINCT(ctarifas.cvetipoc) FROM ctarifas INNER JOIN ccliente ON ctarifas.cveTipoc=ccliente.cveTipoCliente 
				INNER JOIN cguias ON cguias.cveCliente=ccliente.cveCliente WHERE cguias.cveGuia='$cveGuia'";
	
				$tipoc=$bd->soloUno($sqlC);
				foreach ($datos as $dato)
				{
					 $tipoE=$dato["tipoEnvio"];
					 $cons="SELECT cargo99,cargo299,cargo300,cuartoRango,cargoMinimo FROM ctarifas WHERE estadoOrigen='9' 

					 AND estadoDestino='".$dato["estadoDestinatario"]."' AND origen='17' AND destino='".$dato["municipioDestinatario"] .
					 "' AND tipoEnvio='$tipoE' AND cvetipoc='$tipoc' AND estatus=1";
	
					$tarifas = $bd->Execute($cons);
					$i=count($tarifas);
					if($i==0)
					{
						$tarifaa= 0;
						$tarifab= 0;
						$tarifac=0;
						$tarifad=0;
						$cargoMinimo=0;
					}else
					{
						foreach($tarifas as $tarifa){
							$tarifaa= $tarifa["cargo99"];
							$tarifab= $tarifa["cargo299"];
							$tarifac= $tarifa["cargo300"];
							$tarifad= $tarifa["cuartoRango"];
							$cargoMinimo=$tarifa["cargoMinimo"];
						} 
					}
					if($dato["kg"] > $dato["volumen"])
					{$peso=$dato["kg"];}else{$peso=$dato["volumen"];}
					$rangos = $bd->Execute("SELECT primerRango,segundoRango,tercerRango FROM ctarifascorresponsales WHERE cveCorresponsal=0");
					foreach($rangos as $rango){
						$rango1= valorRango($rango['primerRango']);
						$rango2= valorRango($rango['segundoRango']);
						$rango3= valorRango($rango['tercerRango']);
					}
					if($peso<= $rango1)
					{
						$cargo=$tarifaa;
					}
					if($peso > $rango1 AND $peso<= $rango2){
						$cargo=$tarifab;
					}
					if($peso> $rango2 AND $peso<= $rango3){
						$cargo=$tarifac;
					}
					if($peso>= $rango3 ){
						$cargo=$tarifad;
					}
					$porcentajeIva=$dato["cveImpuesto"]/100;
					$flete= $cargo*$peso;
					if($flete>$cargoMinimo){$flete=$flete;}else{$flete=$cargoMinimo;}
					$acuse=150;
					$importe=$flete+$acuse;
					$iva=$importe*$porcentajeIva;
					$subtotal=$iva+$importe ;

					$valorDeclarado=redondeaNum($dato["valorDeclarado"]);
					$piezas=redondeaNum($dato["piezas"]);
					$peso=redondeaNum($peso);
					$cargo=redondeaNum($cargo);
					$flete=redondeaNum($flete);
					$acuse=redondeaNum($acuse);
					$importe=redondeaNum($importe);
					$iva=redondeaNum($iva);
					$subtotal=redondeaNum($subtotal);
					$cargoMinimo=redondeaNum($cargoMinimo);
					$cveImpuesto=redondeaNum($dato["cveImpuesto"]);
					$total=redondeaNum($dato["total"]);	



					$respuesta .= "{recepcionCYE: '" . $dato["recepcionCYE"] . "', cveGuia: '" . $dato["cveGuia"] . "', sucursalDestino: '" . $dato["estacion"] . "', nombreDestinatario: '" . $dato["nombre"] . "', tipoEnvio: '" . $dato["tipoEnvio"] . "', valorDeclarado: '" . $valorDeclarado. "', piezas: '" . $piezas . "', peso: '" . $peso . "', cargo: '" . $cargo . "', cvefacturas: '" . $facturaEnviar . "', flete: '" . $flete . "', acuse: '" . $acuse . "', importe: '" . $importe . "', iva: '" . $iva . "', subtotal: '" . $subtotal ."', cargoMinimo: '" . $cargoMinimo ."', cveIva: '" . $cveImpuesto."', planificacionFolio: '" . $valeEnviar."', razon: '" . $dato["razonSocial"] ."',total: '".$total
 ."', obsGuia: '" . $dato["observacion"] ."', totalR: '" . count($datos)."'},";
				}
			}
		}
	}
	
	$respuesta = substr($respuesta, 0, strlen($respuesta)-1);
	$respuesta .= "]";
	echo $respuesta;

	function valorRango($valorRango){
		$separar = explode(' ',$valorRango);
		return $separar[2];
	}

	function redondeaNum($numero){
		$valor = (is_float($numero)) ? (round($numero * 100)/100) : $numero;
		return $valor;
	}


?>
