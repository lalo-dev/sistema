<?php

/**
 * @author Jose Miguel Pantaleon
 * @copyright 2010
 */

header("Content-Type: text/Text; charset=ISO-8859-1 Cache-Control: no-store, no-cache, must-revalidate");
header("Expires: Fri, 14 Mar 1980 20:53:00 GMT"); //la pagina expira en fecha pasada
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); //ultima actualizacion ahora cuando la cargamos
header("Cache-Control: no-cache, must-revalidate"); //no guardar en CACHE*/
header("Pragma: no-cache"); //PARANOIA, NO GUARDAR EN CACHE*/

include ("bd.php");
$caracteres = $_POST["caracteres"];
$campo = $_GET["campo"];
$tabla = $_GET["tabla"];
$operacion = $_GET["operacion"];


if ($caracteres != "")
{
				switch ($operacion)
				{
					case 1:
					{
						$dests = $bd->ExecuteField($tabla, "$campo", "$campo LIKE '$caracteres%%' LIMIT 0,5");
					}
					break;
					case 2:
					{
						$dests = $bd->ExecuteField($tabla, "$campo", "estatus='1' AND $campo LIKE '$caracteres%%' LIMIT 0,5");
					}
					break;
					case 3:
					{
						$campo2=$_GET["campo2"];
						$valor2=$_GET["valor2"];
						$dests = $bd->ExecuteField($tabla, "$campo", "estatus='1' AND $campo2=$valor2 and $campo LIKE '$caracteres%%' LIMIT 0,5");
					}
					break;
					case 4:             //AGREGADA
					{
						$campo2=$_GET["campo2"];
						$condicion=$campo." LIKE '".$caracteres."%' OR ".$campo2." LIKE '".$caracteres."%' LIMIT 0,5;";
						$dests = $bd->ExecuteField($tabla, "$campo", $condicion);
					}
					break;
					case 5:				//AGREGADA
					{				
						$campo2=$_GET["campo2"];
						$condicion=$campo." LIKE '".$caracteres."%' OR ".$campo2." LIKE '".$caracteres."%' LIMIT 0,5;";
						$sql="SELECT $campo,$campo2 FROM $tabla WHERE $condicion";
						$dests = $bd->ExecuteFieldf($sql);

					}
					break;
					case 6:
					{
						$campo2=$_GET["campo2"];
						$condicion=$campo." LIKE '".$caracteres."%' OR ".$campo2." LIKE '".$caracteres."%' LIMIT 0,5;";
						$dests = $bd->ExecuteFieldn($tabla, "$campo,$campo2", $condicion);
					}
					break;	
					case 7:
					{
						$campo2="nombre";
						$campo3="apeidoPaterno";
						$campo4="apeidoMaterno";
						$condicion=$campo." LIKE '".$caracteres."%' OR ".$campo2." LIKE '".$caracteres."%' OR ".$campo3." LIKE '".$caracteres."%' OR ".$campo4." LIKE '".$caracteres."%' LIMIT 0,5;";
						$nombre="CONCAT($campo2,' ',$campo3,' ',$campo4) AS nombre";
						$campos=$campo.",".$nombre;																
						$dests = $bd->ExecuteFieldn($tabla,$campos,$condicion);
					}
					break;
					case 8:
					{
						$campo2="nombre";
						$campo3="apeidoPaterno";
						$campo4="apeidoMaterno";
						$condicion=$campo." LIKE '".$caracteres."%' OR ".$campo2." LIKE '".$caracteres."%' OR ".$campo3." LIKE '".$caracteres."%' OR ".$campo4." LIKE '".$caracteres."%' LIMIT 0,5;";
						$nombre="CONCAT($campo2,' ',$campo3,' ',$campo4) AS nombre";
						$campos=$campo.",".$nombre;																
						$dests = $bd->ExecuteFieldn($tabla,$campos,$condicion);
					}
					break;
					case 9://Agregada para Destino(sucursales)
					{						

						$campo2="cveDestino";
						$campo3="cestados.nombre";
						$condicion=$campo." LIKE '".$caracteres."%' OR ".$campo2." LIKE '".$caracteres."%' OR ".$campo3." LIKE '".$caracteres."%' LIMIT 0,5;";
						$campos=$campo.",".$campo3;
						$inner="INNER JOIN cestados ON cestados.cveEstado=cdestinos.estado";

						$sql="SELECT $campos FROM $tabla $inner WHERE $condicion";
						$dests = $bd->ExecuteFieldfn($sql);

					}
					break;
					case 10://Agregada para Sucursales
					{						

						$campo2=$_GET['campo2'];
						$campo3=$_GET['campo3'];
						$condicion="cempresas.estatus=1 AND (".$campo." LIKE '".$caracteres."%' OR ".$campo2." LIKE '".$caracteres."%') LIMIT 0,5;";

						$campos=$campo.",cempresas.razonSocial,".$campo2.",cempresas.".$campo3;

						$inner="INNER JOIN cempresas ON cempresas.cveEmpresa=$tabla.cveEmpresa";

						$sql="SELECT $campos FROM $tabla $inner WHERE $condicion";
						$dests = $bd->ExecuteFieldfn($sql);

					}
					break;	
					case 11:      //Tipos de Documentos
					{
						$campo2=$_GET["campo2"];
						$campo3=$_GET["campo3"];
						$condicion=$campo." LIKE '".$caracteres."%' OR ".$campo2." LIKE '".$caracteres ."%' OR ".$campo3." LIKE '".$caracteres."%' LIMIT 0,5;";
						$dests = $bd->ExecuteFieldn($tabla, "$campo,$campo2,$campo3", $condicion);
					}
					break;	
					case 12:      //Bancos
					{
						$campo="cveBanco";
						$campo2="descripcion";
						$tabla="cbancos";

						$condicion=$campo." LIKE '".$caracteres."%' OR ".$campo2." LIKE '".$caracteres."%' LIMIT 0,5;";
						$campos=$campo.",".$campo2;

						$sql="SELECT $campos FROM $tabla WHERE $condicion";
						$dests = $bd->ExecuteFieldfn($sql);

					}
					break;
					case 13:      //Reporte Incidencias
					{
						$campo  = "cveReporte";
						$campo2 = "cveGuia";
						$tabla  = "creporteincidencias";
						$guia   = $_GET['guia'];

						$condicion = $campo." LIKE '".$caracteres."%' AND ".$campo2."='".$guia."' LIMIT 0,5;";
						$campos    = $campo;

						$dests = $bd->ExecuteField($tabla,$campos,$condicion);
					}
					break;					
					default:
					break;
				}
				if(($operacion==6)||($operacion==7)||($operacion==9)||($operacion==12))
				{
					$respuesta = "<ul>";
					foreach ($dests as $dest)
					{
								$respuesta .= "<li>" . $dest[0] ." - ".$dest[1]. "</li>";
					}
					$respuesta .= "</ul>";
				}else if($operacion==10)
				{
					$respuesta = "<ul>";
					foreach ($dests as $dest)
					{
								$respuesta .= "<li>" . $dest[0] ." - ".$dest[2]." - ".$dest[1]." - ".$dest[3]. "</li>";
					}
					$respuesta .= "</ul>";
				}
				else if($operacion==11)
				{
					$respuesta = "<ul>";
					foreach ($dests as $dest)
					{
								$respuesta .= "<li>" . $dest[2] ." - ".$dest[1]." - ".$dest[0]. "</li>";
					}
					$respuesta .= "</ul>";
				}
				else
				{
					$respuesta = "<ul>";
					foreach ($dests as $dest)
					{
									$respuesta .= "<li>" . $dest . "</li>";
					}
					$respuesta .= "</ul>";
				}
				echo $respuesta;

}
 
?>
