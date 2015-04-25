<?php
header("Content-Type: text/Text; charset=ISO-8859-1");
include("bd.php");
$dest_code = $_GET["branch_id"];

$campos = $bd->Execute("select * from branch_mstr where branch_id = '$dest_code'");
	$respuesta = "[";
	foreach($campos as $campo){
	$respuesta .= "{branch_desc: '".$campo["branch_desc"]."', branch_state: '" . $campo["branch_state"] . "', branch_municip: '" . $campo["branch_municip"] . "', branch_city: '" . $campo["branch_city"] ."', branch_county: '" . $campo["branch_county"] . "', branch_add: '" . $campo["branch_add"] . "', branch_zipcode: '" . $campo["branch_zipcode"] . "', branch_contact: '" . $campo["branch_contact"] . "', branch_phone: '" . $campo["branch_phone"] . "'},";
		}
		$respuesta = substr($respuesta, 0, strlen($respuesta)-1);
		$respuesta .= "]";
		echo $respuesta;
?>