<?php
	
	header("Content-Type: text/Text; charset=ISO-8859-1 Cache-Control: no-store, no-cache, must-revalidate");
	header("Expires: Fri, 14 Mar 1980 20:53:00 GMT"); //la pagina expira en fecha pasada
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); //ultima actualizacion ahora cuando la cargamos
	header("Cache-Control: no-cache, must-revalidate"); //no guardar en CACHE
	header("Pragma: no-cache"); //PARANOIA, NO GUARDAR EN CACHE
	header("Content-Type: text/Text; charset=ISO-8859-1 Cache-Control: no-store, no-cache, must-revalidate");
	
	require_once("bd.php");
	
	$caso = $_GET["operacion"];
	
	switch($caso)
	{
		case 11: //SELECCIONAMOS LOS IDs Y EL NOMBRE DE "LO QUE DICE CONTENER"
		{
			$queryTitulos = "SELECT idContenido, titulo FROM cdicecontener ORDER BY titulo ASC;";
			$resTitulos = $bd->Execute($queryTitulos);

			$respuesta = "[";

			foreach ($resTitulos as $gpup){
				$respuesta .= "{id: '" . $gpup["idContenido"] . "', desc: '" . $gpup["titulo"] . "'},";
			}

			$respuesta = substr($respuesta, 0, strlen($respuesta)-1);
			$respuesta .= "]";
			
			echo $respuesta;
		}
		break;		
		case 1:
		{
			$queryTitulos = "SELECT idContenido, titulo FROM cdicecontener ORDER BY titulo ASC;";
			$resTitulos = $bd->Execute($queryTitulos);
			
			$respuesta = "[";

			foreach ($resTitulos as $gpup){
			$respuesta .= "{id: '" . $gpup["idContenido"] . "', desc: '" . $gpup["titulo"] . "'},";
			}
			
			if(!isset($_GET["zxc"]))
			{
				$respuesta .= "{id: 'N', desc: 'Nuevo registro'},";
			}
			if(!isset($_GET["edit"]))
			{
				$respuesta .= "{id: 'O', desc: 'Otros'},";
			}
			
			$respuesta = substr($respuesta, 0, strlen($respuesta)-1);
			$respuesta .= "]";
			
			echo $respuesta;

		}
		break;
		
		case 2:
		{
			$queryDescripcion = "SELECT titulo, descripcion FROM cdicecontener WHERE idContenido = '".$_GET["id"]."';";
			$resDescripcion = $bd->Execute($queryDescripcion);
			$alfo[1]= $resDescripcion[0]["titulo"];
			$alfo[0]= $resDescripcion[0]["descripcion"];
			echo json_encode($alfo);
		}
		break;
		
		case 3:
		{
			$queryInsert = "INSERT INTO cdicecontener
							(titulo,descripcion,usuarioCreador,fechaCreacion,usuarioModifico,fechaModificacion)
							VALUES
							('".$_GET["nombre"]."', '".$_GET["descripcion"]."',".$_GET["usuario"].", NOW(), ".$_GET["usuario"].", NOW());";
			//exit($queryInsert);
			$resInsert = $bd->ExecuteNonQuery($queryInsert);
			echo $resInsert;
		}
		break;
		
		case 4:
		{
			/*$queryUpdate = "UPDATE cdicecontener SET
							  titulo='".$_GET["nuevoTitulo"]."',
							  descripcion='".$_GET["nuevaDescripcion"]."',
							  usuarioModifico='".$_GET["usario"]."',
							  fechaModificacion=NOW()
							WHERE
							 titulo='".$_GET["id"]."';";*/
			$queryUpdate = "UPDATE cdicecontener SET
							  titulo='".$_GET["nuevoTitulo"]."',
							  descripcion='".$_GET["nuevaDescripcion"]."',
							  usuarioModifico='".$_GET["usario"]."',
							  fechaModificacion=NOW()
							WHERE
							 idContenido='".$_GET["id"]."';";
			//exit($queryUpdate);
			$resUpdate = $bd->ExecuteNonQuery($queryUpdate);
			echo $resUpdate;
		}
		break;
		
		default:
		{
		}
		break;
	}
?>