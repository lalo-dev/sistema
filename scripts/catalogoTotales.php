<?php

header("Content-Type: text/Text; charset=ISO-8859-1 Cache-Control: no-store, no-cache, must-revalidate");
header("Expires: Fri, 14 Mar 1980 20:53:00 GMT"); //la pagina expira en fecha pasada
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); //ultima actualizacion ahora cuando la cargamos
header("Cache-Control: no-cache, must-revalidate"); //no guardar en CACHE*/
header("Pragma: no-cache"); //PARANOIA, NO GUARDAR EN CACHE 
include ("bd.php");
$operacion = $_GET["operacion"];
$opcion = $_GET["opc"];
$val1=$_GET["valor1"];
$val2=$_GET["valor2"];

if($operacion!=""){
				switch ($operacion)
				{
					case 1: //Total de Tarifas de ese corresponsal
							{
								if($val1!=""){
									$sql="SELECT IFNULL(MAX(cveTarifa),0) AS totalReg FROM ctarifascorresponsales WHERE cveCorresponsal=".$val1.";";							
									$datos = $bd->Execute($sql);
									$respuesta = "[";
									foreach ($datos as $dato)
									{
										$respuesta .= "{ total_reg:'" . $dato['totalReg'] . "'}";
									}
									$respuesta .= "]";
								}
							}
							break;
					case 2: //Total de Aerolineas
							{
								if($opcion==0) $tabla="clineasaereas";
								else if($opcion==1) $tabla="cempresas";
								else if($opcion==2) $tabla="cmonedas";
								else if($opcion==3) $tabla="cdestinos";
								else if($opcion==4) $tabla="csucursales";
								else if($opcion==5) $tabla="ctipoenvio";
								else if($opcion==6) $tabla="cfoliosdocumentos";
								else if($opcion==7) $tabla="ccodigospostales";
								else if($opcion==8) $tabla="cusuarios";
								else if($opcion==9) $tabla="cbancos";
								
								
								$sql="SELECT COUNT(*) AS totalReg FROM ".$tabla.";";
								$datos = $bd->Execute($sql);
								$respuesta = "[";
								foreach ($datos as $dato)
								{
									$respuesta .= "{ total_reg:'" . $dato['totalReg'] . "'}";
								}
								$respuesta .= "]";
							}
							break;
					case 3: //Total de Documentos
							{
								$sql="SELECT IFNULL(MAX(cveDocumento),0) AS clave FROM  cfoliosdocumentos;";
								$datos = $bd->Execute($sql);
								$respuesta = "[";
								foreach ($datos as $dato)
								{
									$respuesta .= "{ clave:'" . $dato['clave'] . "'}";
								}
								$respuesta .= "]";

							}
							break;
					case 4: //Siguiente de Usuarios
							{
								$sql="SELECT IFNULL(MAX(cveUsuario),0)+1 AS clave FROM  cusuarios;";
								$datos = $bd->Execute($sql);
								$respuesta = "[";
								foreach ($datos as $dato)
								{
									$respuesta .= "{ clave:'" . $dato['clave'] . "'}";
								}
								$respuesta .= "]";

							}
							break;
					case 5: //Total de Tarifas de ese corresponsal
							{
								if($val1!=""){
									$sql="SELECT COUNT(*) AS totalReg FROM cguias WHERE facturada=0 AND cveCLiente=".$val1.";";							
									$datos = $bd->Execute($sql);
									$respuesta = "[";
									foreach ($datos as $dato)
									{
										$respuesta .= "{ total_reg:'" . $dato['totalReg'] . "'}";
									}
									$respuesta .= "]";
								}
							}
							break;
					case 6: //Contra Recibo
							{
								$sql="SELECT folio+1 AS clave FROM cfoliosdocumentos WHERE tipoDocumento='CONTR';";
								$folio = $bd->soloUno($sql);
								$respuesta = "[{ clave:'" . $folio . "'}]";
							}
							break;	
					case 7: //Contra Recibo
							{
								$sql="UPDATE cfoliosdocumentos SET folio=folio+1 WHERE tipoDocumento='CONTR';";
								$datos = $bd->ExecuteNonQuery($sql);
							}
							break;								
					case 8: //Nota de Crédito
							{
								$sql="SELECT folio+1 AS clave FROM cfoliosdocumentos WHERE tipoDocumento='NOTAS';";
								$folio = $bd->soloUno($sql);
								$respuesta = "[{ clave:'" . $folio . "'}]";
							}
							break;	
					case 9: //Nota de Crédito
							{
								$sql="UPDATE cfoliosdocumentos SET folio=folio+1 WHERE tipoDocumento='NOTAS';";
								$datos = $bd->ExecuteNonQuery($sql);
							}
							break;
					case 10: //Reporte de Incidencias
							{
								$sql="SELECT IFNULL(MAX(cveReporte),0)+1 AS clave FROM creporteincidencias;";
								$folio = $bd->soloUno($sql);
								$respuesta = "[{ clave:'" . $folio . "'}]";
							}
							break;							
					default:					
					break;						
					
				}
				
				echo $respuesta;
}

?>
