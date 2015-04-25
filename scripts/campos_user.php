<?php
header("Content-Type: text/Text; charset=ISO-8859-1");
include("BD.php");
$user_id = $_GET["user_id"];

$campos = $bd->Execute("select * from user_mstr where user_id = '$user_id'");
	$respuesta = "[";
	foreach($campos as $campo){
	$respuesta .= "{user_name: '".$campo["user_name"]."', user_type: '" . $campo["user_type"] . "', user_branch: '" . $campo["user_branch"] . "', user_add: '" . $campo["user_add"] ."', user_phone: '" . $campo["user_phone"] . "', user_email: '" . $campo["user_email"] . "'},";
		}
		$respuesta = substr($respuesta, 0, strlen($respuesta)-1);
		$respuesta .= "]";
		echo $respuesta;
?>