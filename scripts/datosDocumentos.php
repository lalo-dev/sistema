<?php
    header("Content-Type: text/Text; charset=ISO-8859-1 Cache-Control: no-store, no-cache, must-revalidate");
    header ("Expires: Fri, 14 Mar 1980 20:53:00 GMT"); //la pagina expira en fecha pasada
    header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); //ultima actualizacion ahora cuando la cargamos
    header ("Cache-Control: no-cache, must-revalidate"); //no guardar en CACHE
    header ("Pragma: no-cache"); //PARANOIA, NO GUARDAR EN CACHE

    include("bd.php");
    
    $sql="SELECT IF(`estatus`=0,'Desactivado','Activado') AS estado,`descripcion`,`tipoDocumento`,`folio`
FROM `cfoliosdocumentos` ORDER BY cfoliosdocumentos.estatus DESC,tipoDocumento ASC;";
    
    $campos = $bd->Execute($sql);
    
        $respuesta = "[";
        $total=count($campos);
        if($total==0)
        {	$respuesta .= "{total: '0'}";
            }else{
            foreach($campos as $campo){
                $respuesta .= "{total:'".$total. "', estado: '" . $campo["estado"] ."', descripcion: '" . $campo["descripcion"] ."',tipoDocumento: '" . $campo["tipoDocumento"] ."',folio: '" . $campo["folio"] ."'},";
            }
            $respuesta = substr($respuesta, 0, strlen($respuesta)-1);
        }
    $respuesta .= "]";
    echo $respuesta;
?>
