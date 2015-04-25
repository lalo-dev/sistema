<?php

/**
 * @author miguel
 * @copyright 2009
 */
include_once('conexion.php');
extract($_REQUEST);
//Limpiar Datos
$empresa=trim($empresa);
$tipoTarifa=trim($tipoTarifa);
$munOrigen=trim($munOrigen);
$origen=trim($origen);
$munDestino=trim($munDestino);
$destino=trim($destino);
$tipoCliente=trim($tipoCliente);
$tipoEnvio=trim($tipoEnvio);
$tarifa1=trim($tarifa1);
$tarifa2=trim($tarifa2);
$tarifa3=trim($tarifa3);
$tarifa4=trim($tarifa4);
$tarifaMinima=trim($tarifaMinima);
$usuario=trim($usuario);
$estatus=trim($estatus);

$empresa=str_replace ( "-", "'", $empresa);

$sql="SELECT IFNULL(MAX(cveTarifa),0)+1 AS id FROM ctarifas";
$res1 = mysql_query($sql,$conexion);
while($row=mysql_fetch_assoc($res1))
{
	$id=$row['id'];	
}


$fecha = date("Y/m/d");
$sql1="INSERT INTO ctarifas (cveEmpresa ,cveSucursal ,cveTarifa, tipoTarifa,origen ,estadoOrigen ,destino ,estadoDestino ,cveTipoc,tipoEnvio ,cargo99 ,cargo299 ,cargo300,cuartoRango ,cargoMinimo,usuarioCreador,fechaCreacion,estatus ) VALUES (".$empresa.",'$id','$tipoTarifa', '$munOrigen', '$origen', '$munDestino', '$destino','$tipoCliente', '$tipoEnvio', '$tarifa1', '$tarifa2','$tarifa3','$tarifa4', '$tarifaMinima','$usuario',NOW(),'$estatus')";

$res1 = mysql_query($sql1,$conexion);
$my_error1 = mysql_error($conexion);
// Verifica si existe error en la sintaxis en MySql
if(!empty($my_error1)){
echo "Error: La tarifa ya había sido dada de alta";
}
else {
echo "La tarifa se ha registrado exitosamente...";
}
mysql_close($conexion);


?>
