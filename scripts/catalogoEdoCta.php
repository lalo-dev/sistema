<?php

/**
 * @author miguel
 * @copyright 2010
 */

header("Content-Type: text/Text; charset=ISO-8859-1 Cache-Control: no-store, no-cache, must-revalidate");
header ("Expires: Fri, 14 Mar 1980 20:53:00 GMT"); //la pagina expira en fecha pasada
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); //ultima actualizacion ahora cuando la cargamos
header ("Cache-Control: no-cache, must-revalidate"); //no guardar en CACHE
header ("Pragma: no-cache"); //PARANOIA, NO GUARDAR EN CACHE

include("bd.php");
include("libreriaGeneral.php");

$codigo=$_GET['codigo'];
$operacion=$_GET['opc'];
$inicio=$_GET['inicio'];

$tabla=($operacion==1)?"cliente":"corresponsal";

$inicio	= cambiaf_a_mysql($inicio);
$termino=$_GET['termino'];
$termino=cambiaf_a_mysql($termino);


$sql="SELECT referencia AS ref,fecha, folioDocumento, cveTipoDocumento, montoNeto AS Cargo, 0.00 AS Abono, saldo FROM dedocta WHERE tipoEstadoCta='$tabla' ".
"AND cveTipoDocumento = 'FAC' AND dedocta.cveCliente='$codigo' AND dedocta.fecha BETWEEN '$inicio' AND '$termino' ". 
"UNION ".
"SELECT documentoReferencia AS ref,fecha,folioDocumento, cveTipoDocumento, 0.00 AS Cargo, montoNeto AS Abono, saldo FROM dedocta WHERE tipoEstadoCta='$tabla' ".
"AND cveTipoDocumento = 'PAG' AND dedocta.cveCliente='$codigo' AND dedocta.fecha BETWEEN '$inicio' AND '$termino' ".
"UNION ".
"SELECT documentoReferencia AS ref,fecha,folioDocumento,cveTipoDocumento, 0.00 AS Cargo,montoNeto AS Abono, 0.00 AS saldo FROM dedocta ".
"WHERE tipoEstadoCta='$tabla' ".
"AND cveTipoDocumento = 'NCE' AND dedocta.cveCliente='$codigo' AND dedocta.fecha BETWEEN '$inicio' AND '$termino' ".
"ORDER BY fecha";

$campos = $bd->Execute($sql);

$sqlsaldoI="SELECT IFNULL(SUM(saldo),0) AS saldo FROM dedocta WHERE dedocta.cveCliente='$codigo' AND dedocta.fecha<'$inicio' ORDER BY fecha;";
$saldosI = $bd->soloUno($sqlsaldoI);

$sqlsaldoF="SELECT IFNULL(SUM(saldo),0) AS saldo FROM dedocta WHERE dedocta.cveCliente='$codigo' AND dedocta.fecha<='$termino';";
$saldosF = $bd->soloUno($sqlsaldoF);

$respuesta = "[";
if(count($campos)==0)
{
	$respuesta .="{total: '0', saldoI: '0', saldoF: '0'},";
}
else
{
	foreach($campos as $campo){
		$respuesta .="{total:'" . count($campos) . "', ref: '" . $campo["ref"] . "', fecha: '" . $campo["fecha"] ."', folioDocumento: '" . $campo["folioDocumento"] .
		"', cveTipoDocumento: '" . $campo["cveTipoDocumento"] ."', Cargo: '" . $campo["Cargo"] ."', Abono: '" . $campo["Abono"] .
		"', saldo: '" . $campo["saldo"] ."', saldoI: '" . $saldosI ."', saldoF: '" . $saldosF ."'},";
	}
}

$respuesta = substr($respuesta, 0, strlen($respuesta)-1);
$respuesta .= "]";
echo $respuesta;

?>
