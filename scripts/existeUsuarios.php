<?php
	header("Content-Type: text/Text; charset=ISO-8859-1 Cache-Control: no-store, no-cache, must-revalidate");
	header ("Expires: Fri, 14 Mar 1980 20:53:00 GMT"); //la pagina expira en fecha pasada
	header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); //ultima actualizacion ahora cuando la cargamos
	header ("Cache-Control: no-cache, must-revalidate"); //no guardar en CACHE
	header ("Pragma: no-cache"); //PARANOIA, NO GUARDAR EN CACHE 
	
	include("bd.php");
	$keys =  $_GET["keys"];
	$table =  $_GET["table"];
	
	switch($keys){
	
			case 1:  //Busca la guía para el Corresponsal Logueado
			{
				
				$field1   =  $_GET["field1"];
				$f1value  =  $_GET["f1value"];
				$estacion =  $_GET["estacion"];
				//El filtro por sucursal ya no sería necesario, ya que el listado muestra ese filtro, sin embargo se considera que el Usuario puede meter el número de guía y dar clic en el boton de Buscar, para lo cual es encesario aplicar el filtro
				$filtroAnd=" AND sucursalDestino='".$estacion."'";  				
				//Toma únicamente el nombre ,separando la cve
				list($val1,$val2)=explode(" - ",$f1value);
				$valor=$val1;
				$sql="SELECT COUNT(*) AS existe,$field1 AS campo FROM $table 
				INNER JOIN cconsignatarios ON
				cguias.cveConsignatario=cconsignatarios.cveConsignatario 
				WHERE $field1 = '$valor' AND facturada='0' AND status NOT IN('Carga Documentandose','Cancelada','Entregada','Concluida') AND cconsignatarios.estacion='".$estacion."'";
				$exists = $bd->Execute($sql);
				$respuesta = "[";
					foreach($exists as $exist){						
							if($exist['existe']==0)
							{							
								$sql="SELECT COUNT(*) AS existe,$field1 AS campo FROM $table INNER JOIN cconsignatarios ON
								cguias.cveConsignatario=cconsignatarios.cveConsignatario WHERE $field1 = '$valor' AND cconsignatarios.estacion='".$estacion."'";
								$exists = $bd->Execute($sql);
								foreach($exists as $exist){
									$respuesta .= "{
										existe: '".$exist['existe']."',
										cve: '".$exist['campo']."',";
									if($exist['existe']==0)
									{  $respuesta .= "modificar: '0'}"; }							
									else
									{  $respuesta .= "modificar: '1'}"; }	
							 	}
							}	
							else
							{
								$respuesta .= "{
								existe: '".$exist['existe']."',
								cve: '".$exist['campo']."'}";
							}
					}
				$respuesta.="]";
				echo $respuesta;
					
					
			}
			default:break;
	}

?>
