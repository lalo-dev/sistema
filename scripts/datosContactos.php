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
$cveCliente = $_GET["cliente"];
$cvesucursal = $_GET["sucursal"];
$tabla=$_GET["tabla"];
if($tabla=="cliente")
 {
 	$tabla="cdireccionescliente";
        $tablaContactos="ccontactoscliente";
 	$campoClave="cveCliente";
	$campoClave2="$tabla.cveDireccion";
 }
 else
 {
 	$tabla="cdireccionesprovedores";
        $tablaContactos="ccontactosproveedores";
 	$campoClave="cveCorresponsal";
	$campoClave2="$tabla.cveDireccion";
 }
$condicion=" AND  $tablaContactos.sucursalCliente = '$cvesucursal'";

$sql="SELECT $tablaContactos.cveContacto,$tabla.cveDireccion,$tabla.sucursalCliente,$tablaContactos.nombre,$tablaContactos.apellidoPaterno,$tablaContactos.apellidoMaterno,$tablaContactos.puesto,$tablaContactos.email,$tablaContactos.telefono,$tablaContactos.lada,$tablaContactos.celular, $tablaContactos.departamento,$tablaContactos.contactoFacturacion FROM $tablaContactos 
INNER JOIN $tabla ON ($campoClave2=$tablaContactos.sucursalCliente) AND ($tabla.$campoClave=$tablaContactos.$campoClave) 
WHERE $tablaContactos.$campoClave = '$cveCliente'";

if ( isset($_GET["sucursal"]) ){
	$sql= $sql . $condicion;
}
if ( isset($_GET["contacto"]) ){
	$contacto=$_GET["contacto"];
	$sql= $sql . " AND cveContacto='$contacto'";
}

$campos = $bd->Execute($sql);

	$respuesta = "[";
 	$total=count($campos);
	if($total==0)
	{	$respuesta .= "{total: '0'}";
		}else{
		foreach($campos as $campo){
			$respuesta .= "{total:'".$total."',cveDireccion:'" . $campo["cveDireccion"] ."',sucursalCliente: '" . $campo["sucursalCliente"] . "', cveContacto: '" . $campo["cveContacto"] ."', nombre: '" . $campo["nombre"] ."', apellidoPaterno: '" . $campo["apellidoPaterno"] ."', apellidoMaterno: '" . $campo["apellidoMaterno"] ."', puesto: '" . $campo["puesto"] ."', email: '" . $campo["email"] ."', telefono: '" . $campo["telefono"] ."', lada: '" . $campo["lada"] ."', celular: '" . $campo["celular"] ."', departamento: '" . $campo["departamento"] ."', contactoFacturacion: '" . $campo["contactoFacturacion"] ."'},";
		}
		$respuesta = substr($respuesta, 0, strlen($respuesta)-1);
	}
$respuesta .= "]";
echo $respuesta;

?>
