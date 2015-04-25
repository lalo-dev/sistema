<?php

/**
 * @author miguel
 * @copyright 2010
 */
/*header("Content-Type: text/Text; charset=ISO-8859-1 Cache-Control: no-store, no-cache, must-revalidate");
header ("Expires: Fri, 14 Mar 1980 20:53:00 GMT"); //la pagina expira en fecha pasada
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); //ultima actualizacion ahora cuando la cargamos
header ("Cache-Control: no-cache, must-revalidate"); //no guardar en CACHE
header ("Pragma: no-cache"); //PARANOIA, NO GUARDAR EN CACHE*/

include("bd.php");
$cveCliente = $_GET["cliente"];
$cvesucursal = $_GET["sucursal"];
$tabla=$_GET["tabla"];

if(isset($_GET["opc"]))
	 $opcion=0;
else $opcion=1;


 if($tabla=="cliente")
 {
 	$tabla="cdireccionescliente";
 	$campoClave="cveCliente";
 }
 else
 {
 	$tabla="cdireccionesprovedores";
 	$campoClave="cveCorresponsal";
 }

$sql="SELECT $tabla.estatus AS estatus,cveDireccion, sucursalCliente , calle , numeroInterior , numeroexterior , colonia , cveMunicipio , cveEstado , $tabla.codigoPostal , tipoDireccion , cvePais , telefono FROM $tabla WHERE cveDireccion  = '$cvesucursal' AND $campoClave = '$cveCliente'";
//echo $sql;

if ( isset($_GET["direccion"]) ){
	//echo "1111111";
	$direccion=$_GET["direccion"];
	if($opcion==0)//El país no es México
	{
	
	$sql= "SELECT $tabla.estatus AS estatus,cveDireccion, sucursalCliente , calle , numeroInterior , numeroexterior , colonia , $tabla.cveMunicipio , $tabla.cveEstado , $tabla.codigoPostal , tipoDireccion , $tabla.cvePais , telefono
	FROM $tabla WHERE cveDireccion = '$direccion' AND $campoClave = '$cveCliente'";
	}
	else
	{
		$sql= "SELECT $tabla.estatus AS estatus,cveDireccion, sucursalCliente , calle , numeroInterior , numeroexterior , colonia , $tabla.cveMunicipio , $tabla.cveEstado , $tabla.codigoPostal , tipoDireccion , $tabla.cvePais , telefono,cestados.nombre AS estado, cmunicipios.nombre AS municipio
	FROM $tabla INNER JOIN cestados ON cestados.cveEstado=$tabla.cveEstado
	INNER JOIN cmunicipios ON $tabla.cveMunicipio=cmunicipios.cveMunicipio WHERE cveDireccion = '$direccion' AND $campoClave = '$cveCliente'";
	
	}
}
if ( isset($_GET["ver"]) ){
/*$sql= "SELECT cveDireccion, sucursalCliente , calle , numeroInterior , numeroexterior , colonia , $tabla.cveMunicipio , $tabla.cveEstado , $tabla.codigoPostal , tipoDireccion , $tabla.cvePais , telefono,cestados.nombre AS estado, cmunicipios.nombre AS municipio
FROM $tabla INNER JOIN cestados ON cestados.cveEstado=$tabla.cveEstado
INNER JOIN cmunicipios ON $tabla.cveMunicipio=cmunicipios.cveMunicipio WHERE cveDireccion = '$cvesucursal' AND $campoClave = '$cveCliente'";*/
//	echo "2222222222222";	
	$sql="SELECT $tabla.estatus AS estatus,cveDireccion, sucursalCliente , calle , numeroInterior , numeroexterior , colonia , $tabla.cveMunicipio , $tabla.cveEstado , $tabla.codigoPostal , tipoDireccion , $tabla.cvePais , telefono,cestados.nombre AS estado, cmunicipios.nombre AS municipio
	FROM $tabla INNER JOIN cestados ON cestados.cveEstado=$tabla.cveEstado 
	INNER JOIN cmunicipios ON $tabla.cveMunicipio=cmunicipios.cveMunicipio 
	WHERE cveDireccion = '$cvesucursal' AND $campoClave = '$cveCliente' AND cmunicipios .cveEntidadFederativa= estados.cveEstado;";
}

/*echo $sql;
exit();*/
$campos = $bd->Execute($sql);

	$respuesta = "[";
	foreach($campos as $campo){
$respuesta .= "{sucursalCliente: '" . $campo["sucursalCliente"] . "', calle: '" . $campo["calle"] ."', numeroInterior: '" . $campo["numeroInterior"] ."', numeroexterior: '" . $campo["numeroexterior"] ."', cvePais: '" . $campo["cvePais"] ."', colonia: '" . $campo["colonia"] ."', cveMunicipio: '" . $campo["cveMunicipio"] ."', cveEstado: '" . $campo["cveEstado"] ."', codigoPostal: '" . $campo["codigoPostal"] ."', tipoDireccion: '" . $campo["cvePais"] ."', telefono: '" . $campo["telefono"] ."', estado: '" . $campo["estado"] ."', municipio: '" . $campo["municipio"] ."', estatus: '" . $campo["estatus"] ."', cveDireccion: '" . $campo["cveDireccion"] ."'},";
}
$respuesta = substr($respuesta, 0, strlen($respuesta)-1);
$respuesta .= "]";
echo $respuesta;

?>