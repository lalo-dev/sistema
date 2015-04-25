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

	$query = $_POST["query"];
	$opc = $_GET["opc"];


	$campos = $bd->Execute($query);

	$respuesta = "<table id = 'contenido' class='gridView'>
					<tr>
						<th>Gu&iacute;a House</th>
						<th>Recepc&iacute;on CYE</th>
						<th>Cliente</th>
						<th>Destino</th>
						<th>Destinatario</th>
						<th>Piezas</th>
						<th>Kilos</th>
						<th>Volumen</th>
						<th>Recibio</th>
						<th>Fecha Entrega</th>
						<th>Status</th>
					</tr>";

	$total=count($campos);

	if(($total==0) && ($opc==0))
		$mensaje="La Guía no existe.";
	else if(($total==0) && ($opc==1))
		$mensaje="No hay guías en ese rango.";

	if($total!=0){	
		foreach($campos as $campo){
			$sello = $campo["sello"];
			$firma = $campo["firma"];
			if ($sello == 1)
			{
				$recibido = "SELLO ";
				if ($firma == 1)
				{ $recibido = $recibido . "Y FIRMA "; }			
			}
			else
			{
				$recibido = "";
				if ($firma == 1)
				{ $recibido = $recibido . "FIRMA ";	}
			}
			
			$recibido.= $firma = $campo["recibio"];
			$respuesta .= "<tr>
						<td>".$campo["cveGuia"]."</td>
						<td>".$campo["recepcionCYE"]."</td>
						<td>".$campo["nombreRemitente"]."</td>
						<td>".$campo["estadoDestinatario"]."</td>
						<td>".$campo["nombreDestinatario"]."</td>
						<td>".$campo["piezas"]."</td>
						<td>".$campo["kg"]."</td>
						<td>".$campo["volumen"]."</td>
						<td>".$recibido."</td>
						<td>".$campo["fechaEntrega"]."</td>
						<td>".$campo["status"]."</td>
					</tr>";
		}

	}else
	{
		$respuesta =  "<table width='503' class='gridView' id = 'contenido'>
					<tr>
						<th height='10'></th>
					</tr>
					<tr>
						<td align='center'>".$mensaje."</td>
				    </tr> 
                    </table>";

	}

	$respuesta .= "</table>";
	echo $respuesta;

?>