<?php
	//header("Content-Type: text/Text; charset=ISO-8859-1"); 
include("BD.php");
$wb_id = $_GET["wb_id"];

$campos = $bd->Execute("select * from cguias where cveGuia = '$wb_id'");
	$respuesta = "[";
	foreach($campos as $campo){
	$respuesta .= "{wb_airline: '".$campo["wb_airline"]."', wb_flightnum: '" . $campo["wb_flightnum"] . "', wb_airwb: '" . $campo["wb_airwb"] . "', wb_flightdate: '" . $campo["wb_flightdate"] ."', wb_rcye: '" . $campo["wb_rcye"] . "', wb_cName: '" . $campo["wb_cName"] . "', wb_cAdd: '" . $campo["wb_cAdd"] . "', wb_cPhone: '" . $campo["wb_cPhone"] . "', wb_cExt: '" . $campo["wb_cExt"] . "', wb_cRfc: '" . $campo["wb_cRfc"] . "', wb_consignee: '" . $campo["wb_consignee"] . "', wb_pieces: '" . $campo["wb_pieces"] . "', wb_kg: '" . $campo["wb_kg"] . "', wb_vol: '" . $campo["wb_vol"] . "', wb_validity: '" . $campo["wb_validity"] . "', wb_destbranch: '" . $campo["wb_destbranch"] . "', wb_destzp: '" . $campo["wb_destzp"] . "', wb_destadd: '" . $campo["wb_destadd"] . "', wb_status: '" . $campo["wb_status"] . "', wb_delivdate: '" . $campo["wb_delivdate"] . "', wb_receivedby: '" . $campo["wb_receivedby"] . "', wb_receiptdate: '" . $campo["wb_receiptdate"] . "', wb_dlvnbr: '" . $campo["wb_dlvnbr"] . "', wb_vlnbr: '" . $campo["wb_vlnbr"] . "', wb_invnbr: '" . $campo["wb_invnbr"] . "', wb_receiptdate: '" . $campo["wb_receiptdate"] . "', wb_rmks: '" . $campo["wb_rmks"] . "'},";
		}
		$respuesta = substr($respuesta, 0, strlen($respuesta)-1);
		$respuesta .= "]";
		echo $respuesta;
?>