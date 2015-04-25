<?php
	/**
	 * @author Jose Miguel Pantaleon
	 * @copyright 2010
	 */
	 
	header("Content-Type: text/Text; charset=ISO-8859-1 Cache-Control: no-store, no-cache, must-revalidate");
	header ("Expires: Fri, 14 Mar 1980 20:53:00 GMT"); //la pagina expira en fecha pasada
	header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); //ultima actualizacion ahora cuando la cargamos
	header ("Cache-Control: no-cache, must-revalidate"); //no guardar en CACHE
	header ("Pragma: no-cache"); //PARANOIA, NO GUARDAR EN CACHE/

	include("bd.php");
	$caracteres =  $_POST["caracteres"];
	$cliente=$_GET["cliente"];
	$operacion=$_GET["operacion"];

	if ($caracteres != ""){
		switch($operacion){

			case 1:
				{
					$dests = $bd->ExecuteField("cguias", "cveGuia","estatus='1' AND facturada='0' AND cveGuia LIKE '$caracteres%%' LIMIT 0,5");
				}
				break;
			case 2:
				{
					$dests = $bd->ExecuteField("cguias", "cveGuia","estatus='1' AND cveCliente=".$cliente. " AND facturada='0' AND cveGuia LIKE '$caracteres%%' LIMIT 0,5");
				}
				break;
			case 3:
			{
				$sql="SELECT DISTINCT(cguias.cveGuia) FROM cguias WHERE cguias.cveCliente='".$cliente. "' ".
				     "AND cguias.status='Concluida' AND cguias.facturada='0' AND estatus='1' ".
				     "AND cguias.cveGuia LIKE '$caracteres%' LIMIT 0,5";  
				$dests = $bd->ExecuteFieldf($sql);
			}
			break;
			case 4:
				{
					$dests = $bd->ExecuteField("cguias", "cveGuia","cveCliente='".$cliente. "' AND facturada='0' AND estatus='1' AND  cveGuia LIKE '$caracteres%%' LIMIT 0,5");
				}
				break;
			case 5://Agregada
				{
					$dests = $bd->ExecuteFieldn("cguias", "cveGuia,nombreRemitente"," (cveGuia LIKE '$caracteres%%' OR nombreRemitente LIKE '$caracteres%%') LIMIT 0,5");
				}
				break;	
			case 6:
				{
					$condicion="estatus='1' AND (cguias.status='Concluida' OR cguias.status='Entregada') AND cveGuia LIKE '$caracteres%' LIMIT 0,5";
					$dests = $bd->ExecuteField("cguias","cveGuia",$condicion);
				}
				break;	
			default:break;
		}
		
		$respuesta = "<ul>";
		if($operacion==5)
		{
			foreach ($dests as $dest){
				$respuesta .= "<li>". $dest[0] ." - ".$dest[1]. "</li>";
			}
		}
		else
		{
			foreach ($dests as $dest){
				$respuesta .= "<li>" . $dest . "</li>";
			}

		}	
		$respuesta .= "</ul>";
		echo $respuesta;
		
	}
	
?>
