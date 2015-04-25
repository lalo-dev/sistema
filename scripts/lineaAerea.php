<?php
header("Content-Type: text/Text; charset=ISO-8859-1 Cache-Control: no-store, no-cache, must-revalidate");
header("Expires: Fri, 14 Mar 1980 20:53:00 GMT"); //la pagina expira en fecha pasada
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); //ultima actualizacion ahora cuando la cargamos
header("Cache-Control: no-cache, must-revalidate"); //no guardar en CACHE
header("Pragma: no-cache"); //PARANOIA, NO GUARDAR EN CACHE
header("Content-Type: text/Text; charset=ISO-8859-1 Cache-Control: no-store, no-cache, must-revalidate");
include("bd.php");
$aGpups = $bd->Execute("select cveLineaArea from clineasaereas WHERE estatus='1' order by cveLineaArea");

$respuesta = "[";

foreach ($aGpups as $gpup){
$respuesta .= "{id: '" . $gpup["cveLineaArea"] . "', desc: '" . $gpup["cveLineaArea"] . "'},";
}
$respuesta = substr($respuesta, 0, strlen($respuesta)-1);
$respuesta .= "]";
echo $respuesta;

?>
