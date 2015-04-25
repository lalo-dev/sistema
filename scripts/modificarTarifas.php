<?php

/**
 * @author miguel
 * @copyright 2009
 */
include_once('conexion.php');
extract($_REQUEST);
//Limpiar Datos
$tarifa1=trim($tarifa1);
$tarifa2=trim($tarifa2);
$tarifa3=trim($tarifa3);
$tarifa4=trim($tarifa4);
$estatus=trim($estatus);
$tarifaMinima=trim($tarifaMinima);
$usuario=trim($usuario);

$empresa=str_replace ( "-", "'", $empresa);
$fecha = date("Y/m/d");
$sql1="UPDATE ctarifas set 
cargo99='$tarifa1'
,cargo299='$tarifa2'
,cargo300='$tarifa3'
,cuartoRango='$tarifa4',
estatus='$estatus'
,cargoMinimo='$tarifaMinima'
,usuarioModifico='$usuario'
,fechaModificacion=NOW()
 Where cveTarifa='$cveTarifa'";

$res1 = mysql_query($sql1,$conexion);
$my_error1 = mysql_error($conexion);
// Verifica si existe error en la sintaxis en MySql
if(!empty($my_error1)){
echo "Error: Sintaxis MySql, verifique";


}
else {
echo "La tarifa se ha modificado exitosamente...";
}
mysql_close($conexion);


?>
