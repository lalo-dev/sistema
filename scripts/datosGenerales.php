<?php

/**
 * @author Jose Miguel Pantaleon
 * @copyright 2010
 */


header("Content-Type: text/Text; charset=ISO-8859-1 Cache-Control: no-store, no-cache, must-revalidate");
header("Expires: Fri, 14 Mar 1980 20:53:00 GMT"); //la pagina expira en fecha pasada
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); //ultima actualizacion ahora cuando la cargamos
header("Cache-Control: no-cache, must-revalidate"); //no guardar en CACHE
header("Pragma: no-cache"); //PARANOIA, NO GUARDAR EN CACHE
include ("bd.php");
$valor = $_GET["valor"];
$operacion = $_GET["operacion"];

				switch ($operacion)
				{
								case 1:
									{
										$campos = $bd->Execute("SELECT cveUsuario, nombre, nick, permiso, apeidoMaterno, apeidoPaterno, cveArea, cveDepartamento, estatus,cveEmpresa,cveSucursal,estacion,empresa,sucursal FROM cusuarios WHERE cveUsuario='$valor'");
				                        $respuesta = "[";
                                        foreach($campos as $campo){
                                            $respuesta .= "{cveUsuario: '" . $campo["cveUsuario"] . "', nombre: '" . $campo["nombre"] ."', nick: '" . $campo["nick"] ."', permiso: '" . $campo["permiso"] ."', apeidoMaterno: '" . $campo["apeidoMaterno"] ."', apeidoPaterno: '" . $campo["apeidoPaterno"] ."', cveArea: '" . $campo["cveArea"] ."', cveDepartamento: '" . $campo["cveDepartamento"] ."', estatus: '" . $campo["estatus"] ."', cveEmpresa: '" . $campo["cveEmpresa"] ."', cveSucursal: '" . $campo["cveSucursal"] ."', estacion: '" . $campo["estacion"] ."', sucursal: '" . $campo["sucursal"]."', empresa: '" . $campo["empresa"]."'},";
                                        }
                                        $respuesta = substr($respuesta, 0, strlen($respuesta)-1);
                                        $respuesta .= "]";
                                        echo $respuesta;
				
									}
									break;
								case 2:
									{
									    $campos = $bd->Execute("SELECT folio, tipoDocumento, descripcion,cveDocumento,estatus FROM cfoliosdocumentos WHERE cveDocumento='$valor' ");
				                        $respuesta = "[";
                                        foreach($campos as $campo){
                                            $respuesta .= "{folio: '" . $campo["folio"] . "', tipoDocumento: '" . $campo["tipoDocumento"] ."', descripcion: '" . $campo["descripcion"] ."', cveDocumento: '" . $campo["cveDocumento"]."', estado: '" . $campo["estatus"] ."'},";
                                        }
                                        $respuesta = substr($respuesta, 0, strlen($respuesta)-1);
                                        $respuesta .= "]";
                                        echo $respuesta;
                                    }
												break;
                                case 3:
									{
									    $campos = $bd->Execute("SELECT cveMunicipio, cveEntidadFederativa, nombre,codigoPostal FROM cmunicipios WHERE nombre='$valor' ");
				                        $respuesta = "[";
                                        foreach($campos as $campo){
                                            $respuesta .= "{cveMunicipio: '" . $campo["cveMunicipio"] . "', cveEntidadFederativa: '" . $campo["cveEntidadFederativa"] ."', nombre: '" . $campo["nombre"] ."', codigoPostal: '" . $campo["codigoPostal"] ."'},";
                                        }
                                        $respuesta = substr($respuesta, 0, strlen($respuesta)-1);
                                        $respuesta .= "]";
                                        echo $respuesta;
                                    }
												break;
                                case 4:
									$campos = $bd->Execute("SELECT cveLineaArea, descripcion, contacto,telefono,estatus FROM clineasaereas WHERE cveLineaArea='$valor';");
									   $respuesta = "[";
                                        foreach($campos as $campo){
                                            $respuesta .= "{cveLineaArea: '" . $campo["cveLineaArea"] . "', descripcion: '" . $campo["descripcion"] ."', contacto: '" . $campo["contacto"] ."', telefono: '" . $campo["telefono"]."', estado: '" . $campo["estatus"] ."'},";
                                        }
                                        $respuesta = substr($respuesta, 0, strlen($respuesta)-1);
                                        $respuesta .= "]";
                                        echo $respuesta;
												break;
                                case 5:
									{
									    $campos = $bd->Execute("SELECT cveDestino,descripcion,estado,estatus FROM cdestinos WHERE descripcion='$valor';");
				                        $respuesta = "[";
                                        foreach($campos as $campo){
                                            $respuesta .= "{cveDestino: '" . $campo["cveDestino"] . "', descripcion: '" . $campo["descripcion"] ."', estado: '" . $campo["estado"]."', estatus: '" . $campo["estatus"] ."'},";
                                        }
                                        $respuesta = substr($respuesta, 0, strlen($respuesta)-1);
                                        $respuesta .= "]";
                                        echo $respuesta;
                                    }
												break;
                                case 6:
									{
										$campos = $bd->Execute("SELECT cveEmpresa,razonSocial,rfc,direccion,estatus FROM cempresas WHERE razonSocial='$valor'");
				                        $respuesta = "[";
                                        foreach($campos as $campo){
                                            $respuesta .= "{cveEmpresa: '" . $campo["cveEmpresa"] . "', razonSocial: '" . $campo["razonSocial"] ."', rfc: '" . $campo["rfc"] ."', direccion: '" . $campo["direccion"] ."', estado: '" . $campo["estatus"] ."'},";
                                        }
                                        $respuesta = substr($respuesta, 0, strlen($respuesta)-1);
                                        $respuesta .= "]";
                                        echo $respuesta;                                    }
												break;
                                case 7:
									{
									    $campos = $bd->Execute("SELECT cveMoneda,descripcion,estatus FROM cmonedas WHERE cveMoneda='$valor';");
				                        $respuesta = "[";
                                        foreach($campos as $campo){
                                            $respuesta .= "{cveMoneda: '" . $campo["cveMoneda"] . "', estado: '" . $campo["estatus"] ."', descripcion: '" . $campo["descripcion"] ."'},";
                                        }
                                        $respuesta = substr($respuesta, 0, strlen($respuesta)-1);
                                        $respuesta .= "]";
                                        echo $respuesta;
                                    }
												break;
                                case 8:
									{
									$destino=$_GET["destino"];
									if($destino==1)
									{
										$campos = $bd->Execute("SELECT cveDestino,descripcion FROM cdestinos WHERE estatus='1' ");
										$dato="cveDestino";
									}
									else{
									    $campos = $bd->Execute("SELECT cveMoneda,descripcion,estatus FROM cmonedas ORDER BY estatus DESC;");
									   $dato="cveMoneda";
											}				                    
				                        $respuesta = "[";
                                        foreach($campos as $campo){
                                            $respuesta .= "{cveMoneda: '" . $campo[$dato] . "',estado: '". $campo["estatus"]."', descripcion: '" . $campo["descripcion"] ."'},";
                                        }
                                        $respuesta = substr($respuesta, 0, strlen($respuesta)-1);
                                        $respuesta .= "]";
                                        echo $respuesta;
                                    }
												break;
                                case 9:
									{
									    $campos = $bd->Execute("SELECT cveTipoEnvio,descripcion,estatus FROM ctipoenvio WHERE cveTipoEnvio='$valor' ");
				                        $respuesta = "[";
                                        foreach($campos as $campo){
                                            $respuesta .= "{cveMoneda: '" . $campo["cveTipoEnvio"] . "', descripcion: '" . $campo["descripcion"] . "', estado: '" . $campo["estatus"] ."'},";
                                        }
                                        $respuesta = substr($respuesta, 0, strlen($respuesta)-1);
                                        $respuesta .= "]";
                                        echo $respuesta;
                                    }
												break;
                                case 10:
									{
									    $campos = $bd->Execute("SELECT cveTipoEnvio,descripcion,estatus FROM ctipoenvio ORDER BY estatus DESC");
				                        $respuesta = "[";
                                        foreach($campos as $campo){
                                            $respuesta .= "{cveMoneda: '" . $campo["cveTipoEnvio"] . "', descripcion: '" . $campo["descripcion"] ."', estado: '" . $campo["estatus"] ."'},";
                                        }
                                        $respuesta = substr($respuesta, 0, strlen($respuesta)-1);
                                        $respuesta .= "]";
                                        echo $respuesta;
                                    }
												break;
                                case 11:
									{
									    $valor2=$_GET['valor2'];		  
										$campos = $bd->Execute("SELECT cveSucursal,nombre,cveEmpresa,estatus FROM csucursales WHERE  cveSucursal='$valor' AND cveEmpresa='$valor2'");
				                        $respuesta = "[";
                                        foreach($campos as $campo){
                                            $respuesta .= "{cveSucursal: '" . $campo["cveSucursal"] . "', nombre: '" . $campo["nombre"] ."', cveEmpresa: '" . $campo["cveEmpresa"]."', estado: '" . $campo["estatus"] ."'},";
                                        }
                                        $respuesta = substr($respuesta, 0, strlen($respuesta)-1);
                                        $respuesta .= "]";
                                        echo $respuesta;
                                    }
												break;
				case 12:
				{
					$destino=$_GET["destino"];
					if($destino==1)
					{
						$campos = $bd->Execute("SELECT cveDestino,descripcion FROM cdestinos WHERE estatus='1' ORDER BY cveDestino ");
						$dato="cveDestino";
					}
					else{
						$campos = $bd->Execute("SELECT cveMoneda,descripcion FROM cmonedas WHERE estatus='1' ");
						$dato="cveMoneda";
					}				                    
					$respuesta = "[";
					foreach($campos as $campo){
						$sql="SELECT ifnull(razonSocial,'') as razon FROM ccorresponsales INNER JOIN cdireccionesprovedores ON ccorresponsales.cveCorresponsal = cdireccionesprovedores.cveCorresponsal
						WHERE cdireccionesprovedores.sucursalCliente = '".$campo[$dato]."'";
						$razon = $bd->soloUno($sql);
						$respuesta .= "{cveMoneda: '" . $campo[$dato] . "', descripcion: '" . $razon  ."'},";
					}
					$respuesta = substr($respuesta, 0, strlen($respuesta)-1);
					$respuesta .= "]";
					echo $respuesta;
				}
				break;

				case 13:
				{
					$campos = $bd->Execute("SELECT cveBanco,descripcion,estatus FROM cbancos WHERE cveBanco='$valor'");

					$respuesta = "[";
					foreach($campos as $campo){
						$respuesta .= "{cveBan: '" . $campo["cveBanco"] . "', desc: '" . $campo["descripcion"] ."', estado: '" . $campo["estatus"] ."'},";
					}
					$respuesta = substr($respuesta, 0, strlen($respuesta)-1);
					$respuesta .= "]";
					echo $respuesta;
				}
				break;	
				case 14:
				{
				 
					$valor2=$_GET['guia'];
					$campos = $bd->Execute("SELECT cacuse.cveGuia,cacuse.folio FROM cacuse WHERE cacuse.cveCliente='".$valor."' AND cacuse.cveGuia='".$valor2."'");
					if(count($campos)==0)
					{
						$respuesta = "[{existe:'0'}]";
					}
					else{
						$respuesta = "[";
						foreach($campos as $campo)
						{
							$respuesta .= "{existe: '1', folAcuse: '" . $campo["folio"]."'},";
						}
						$respuesta = substr($respuesta, 0, strlen($respuesta)-1);
						$respuesta .= "]";
					}
					echo $respuesta;
				}
				break; 
				default:
				break;
			}
				



?>
