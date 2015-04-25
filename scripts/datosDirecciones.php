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
if(isset($_GET["opc"]))
	 $opcion=0;
else $opcion=1;

$cveCliente = $_GET["cliente"];
$cvesucursal = $_GET["sucursal"];
$tabla=$_GET["tabla"];
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

if ( isset($_GET["direccion"]) ){
	$direccion=$_GET["direccion"];
	if($opcion==0)
	{
		$sql= "SELECT $tabla.estatus AS estatus,cveDireccion, sucursalCliente , calle , numeroInterior , numeroexterior , colonia , $tabla.cveMunicipio , $tabla.cveEstado , $tabla.codigoPostal , tipoDireccion , $tabla.cvePais , telefono
		FROM $tabla WHERE cveDireccion = '$direccion' AND $campoClave = '$cveCliente'";
	
	}
	else{
		$sql= "SELECT $tabla.estatus AS estatus,cveDireccion, sucursalCliente , calle , numeroInterior , numeroexterior , colonia , $tabla.cveMunicipio , $tabla.cveEstado , $tabla.codigoPostal , tipoDireccion , $tabla.cvePais , telefono,cestados.nombre AS estado, cmunicipios.nombre AS municipio
		FROM $tabla INNER JOIN cestados ON cestados.cveEstado=$tabla.cveEstado
		INNER JOIN cmunicipios ON $tabla.cveMunicipio=cmunicipios.cveMunicipio WHERE cveDireccion = '$direccion' AND $campoClave = '$cveCliente'";
	}
}

if ( isset($_GET["ver"]) ){
	$sql= "SELECT $tabla.estatus AS estatus,cveDireccion, sucursalCliente , calle , numeroInterior , 
		numeroexterior , colonia , $tabla.codigoPostal , tipoDireccion , 
		$tabla.cvePais , telefono
		FROM $tabla WHERE cveDireccion = '$cvesucursal' AND $campoClave = '$cveCliente'";
	$campos = $bd->Execute($sql);
	foreach($campos as $campo){
		$pais = $campo["cvePais"];
	}
	if($pais==156){ //Si es México se hará la comparación con Estados y Municipio
			$sql= "SELECT $tabla.estatus AS estatus,cveDireccion, sucursalCliente , calle , numeroInterior , numeroexterior , colonia , $tabla.cveMunicipio , $tabla.cveEstado , $tabla.codigoPostal , tipoDireccion , $tabla.cvePais , telefono,cestados.nombre AS estado, cmunicipios.nombre AS municipio
		FROM $tabla INNER JOIN cestados ON cestados.cveEstado=$tabla.cveEstado
		INNER JOIN cmunicipios ON $tabla.cveMunicipio=cmunicipios.cveMunicipio AND $tabla.cveEstado=cmunicipios.cveEntidadFederativa WHERE cveDireccion = '$cvesucursal' AND $campoClave = '$cveCliente'";
	}
}

$campos = $bd->Execute($sql);

$respuesta = "[";
foreach($campos as $campo){
	if($campo["cvePais"]==156) //Si es México
		$respuesta .= "{sucursalCliente: '" . $campo["sucursalCliente"] . "', calle: '" . $campo["calle"] ."', numeroInterior: '" . $campo["numeroInterior"] ."', numeroexterior: '" . $campo["numeroexterior"] ."', cvePais: '" . $campo["cvePais"] ."', colonia: '" . $campo["colonia"] ."', cveMunicipio: '" . $campo["cveMunicipio"] ."', cveEstado: '" . $campo["cveEstado"] ."', codigoPostal: '" . $campo["codigoPostal"] ."', tipoDireccion: '" . $campo["tipoDireccion"] ."', telefono: '" . $campo["telefono"] ."', estado: '" . $campo["estado"] ."', municipio: '" . $campo["municipio"] ."', estatus: '" . $campo["estatus"] ."', cveDireccion: '" . $campo["cveDireccion"] ."'},";
	else
		$respuesta .= "{sucursalCliente: '" . $campo["sucursalCliente"] . "', calle: '" . $campo["calle"] ."', numeroInterior: '" . $campo["numeroInterior"] ."', numeroexterior: '" . $campo["numeroexterior"] ."', cvePais: '" . $campo["cvePais"] ."', colonia: '" . $campo["colonia"] ."', cveMunicipio: '0', cveEstado: '0', codigoPostal: '" . $campo["codigoPostal"] ."', tipoDireccion: '" . $campo["tipoDireccion"] ."', telefono: '" . $campo["telefono"] ."', estado: 'N/A" . "', municipio: 'N/A', estatus: '" . $campo["estatus"] ."', cveDireccion: '" . $campo["cveDireccion"] ."'},";
}
$respuesta = substr($respuesta, 0, strlen($respuesta)-1);
$respuesta .= "]";
echo $respuesta;


?>
