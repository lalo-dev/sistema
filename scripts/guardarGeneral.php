<?php

/**
 * @author Jose Miguel Pantaleon
 * @copyright 2010
 */
include_once('conexion.php');
$operacion = $_GET["operacion"];
		switch ($operacion)
				{
								case 1:
												{
												                extract($_REQUEST);
																$sql="";
												}
												break;
								case 2:
												{
																$dests = $bd->ExecuteField("cacuse", "folio", "facturado='0' AND cveCliente=$cliente AND folio LIKE '$caracteres%%' LIMIT 0,10");
												}
												break;
								default:
												break;
				}
				$respuesta = "<ul>";
				foreach ($dests as $dest)
				{
								$respuesta .= "<li>" . $dest . "</li>";
				}
				$respuesta .= "</ul>";
				echo $respuesta;


?>