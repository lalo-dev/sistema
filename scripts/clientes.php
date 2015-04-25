<?php
include("bd.php");

$cveCliente = $_GET["cveCliente"];
$codigo=$_GET["codigo"];
$cveDireccion = $_GET["cveDireccion"];
$datos = $_GET["datos"];
$sql="SELECT cveCliente ,razonSocial ,nombreComercial ,rfc ,paginaWeb ,tipoCliente ,cveImpuesto ,cveMoneda ,condicionesPago ,estatus ,lada ,telefono ,fax ,curp,diasFactura,diasCobro,plazoCobro,requisitosCobro,noProveedor,revicionFactura,cveTipoC FROM ccliente WHERE cveCliente='$codigo'";
$campos = $bd->Execute($sql);
				//echo $sql;
					$respuesta = "[";
				foreach($campos as $campo){
				$respuesta .= "{cveCliente: '" . $campo["cveCliente"] . "', razonSocial: '" . $campo["razonSocial"] ."', nombreComercial: '" . $campo["nombreComercial"] ."', rfc: '" . $campo["rfc"] ."', paginaWeb: '" . $campo["paginaWeb"] ."', tipoCliente: '" . $campo["tipoCliente"] ."', cveImpuesto: '" . $campo["cveImpuesto"] ."', cveMoneda: '" . $campo["cveMoneda"] ."', condicionesPago: '" . $campo["condicionesPago"] ."', estatus: '" . $campo["estatus"] ."', lada: '" . $campo["lada"] ."', telefono: '" . $campo["telefono"] ."', fax: '" . $campo["fax"] ."', curp: '" . $campo["curp"] ."', diasFactura: '" . $campo["diasFactura"] ."', diasCobro: '" . $campo["diasCobro"] ."', plazoCobro: '" . $campo["plazoCobro"] ."', requisitosCobro: '" . $campo["requisitosCobro"] ."', noProveedor: '" . $campo["noProveedor"] ."', revicionFactura: '" . $campo["revicionFactura"] ."', cveTipoC: '" . $campo["cveTipoC"] ."'},";
				}
				$respuesta = substr($respuesta, 0, strlen($respuesta)-1);
				$respuesta .= "]";
				echo $respuesta;
 ?>