<?php

header("Content-Type: text/Text; charset=ISO-8859-1 Cache-Control: no-store, no-cache, must-revalidate");
header ("Expires: Fri, 14 Mar 1980 20:53:00 GMT"); //la pagina expira en fecha pasada
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); //ultima actualizacion ahora cuando la cargamos
header ("Cache-Control: no-cache, must-revalidate"); //no guardar en CACHE
header ("Pragma: no-cache"); //PARANOIA, NO GUARDAR EN CACHE 

include("bd.php");
$respesta = "id: ";
$keys =  $_GET["keys"];
$table =  $_GET["table"];
$filAnd =  $_GET["filAnd"];
$fV =  $_GET["fV"];
if($_GET["filAnd"]){
$filtroAnd = "AND ".$filAnd."="."'".$fV."'";}

switch($keys){

		case 1:
		{
			
			$field1 =  $_GET["field1"];
			$f1value =  $_GET["f1value"];

			//Toma únicamente el nombre ,separando la cve
			list($val1,$val2)=explode(" - ",$f1value);
			if(($field1=="nombre")||($fiedl1=="razonSocial"))
				$valor=$val2;
			else
				$valor=$val1;
			$sql="SELECT COUNT(*) AS existe,$field1 AS campo FROM $table WHERE $field1 = '$valor' ".$filtroAnd.";";
			$exists = $bd->Execute($sql);
			$respuesta = "[";
			foreach($exists as $exist){
				$respuesta .= "{
					existe: '".$exist['existe']."',
					cve: '".$exist['campo']."'}";
			}
			$respuesta.="]";
			echo $respuesta;
				
				
		}
		
		
		break;
		case 2:
			{
			$field1 =  $_GET["field1"];
			$field2 =  $_GET["field2"];
			$f1value =  $_GET["f1value"];
			$f2value =  $_GET["f2value"];
			$exists = $bd->Execute("SELECT COUNT(*) AS existe FROM $table where $field1 = '$f1value' AND $field2 = '$f2value' ".$filAnd);
			$respuesta = array();
			foreach ($exists as $existe){
			$respuesta[]=$existe[0];
				}
				echo "[{existe: '" . $respuesta[0] . "',
						campo1: '" . $f1value . "',
						campo2: '" . $f2value . "'
					  }]";
			}
			
			
		break;
		case 3:
			{
			$field1 =  $_GET["field1"];
			$field2 =  $_GET["field2"];
			$field3 =  $_GET["field3"];
			$f1value =  $_GET["f1value"];
			$f2value =  $_GET["f2value"];
			$f3value =  $_GET["f3value"];
			$exists = $bd->Execute("SELECT COUNT(*) AS existe FROM $table where $field1 = '$f1value' AND $field2 = '$f2value' AND $field3 = '$f3value' ".$filAnd);
			$respuesta = array();
			foreach ($exists as $existe){
			$respuesta[]=$existe[0];
				}
				echo "[{existe: " . $respuesta[0] . "}]";
			}
		break;
			case 4:
			{			
			$condicion =  $_GET["condicion"];
			$exists = $bd->Execute("SELECT COUNT(*) AS existe FROM $table WHERE $condicion");
			$respuesta = array();
			foreach ($exists as $existe){
			$respuesta[]=$existe[0];
				}
				echo "[{existe: " . $respuesta[0] . "}]";
			}
		break;
		case 5:
			{			
			$field1 =  $_GET["field1"];
			$f1value =  $_GET["f1value"];
			$sql="SELECT COUNT(*) AS existe FROM $table where $field1 = '$f1value' ".$filtroAnd;
			$exists = $bd->Execute($sql);
			$respuesta = "[";
			foreach ($exists as $existe){
				
				if($existe["existe"]>0)
				{
					$estados = $bd->Execute("SELECT COUNT(cveGuia) AS existe, estadoRemitente,estadoDestinatario FROM cguias WHERE cveGuia='$f1value' GROUP BY estadoRemitente");
					foreach ($estados as $estad){
							 if($estad["estadoRemitente"]=="")
                           {
                            $estado=0;
                           }
                           else
                           {
                            $estado=$estad["estadoRemitente"];
                           }
                       if($estad["estadoDestinatario"]=="")
                           {
                            $estadod=0;
                           }
                           else
                           {
                            $estadod=$estad["estadoDestinatario"];
                           }
							$respuesta .= "{existe: '".$estad["existe"]."', estadoRemitente: '".$estado."', estadoDestinatario: '".$estadod."'},";
						}
				}
				else
				{
				    $no=0;
						$respuesta .= "{existe: '".$existe["existe"]."', estadoRemitente: '".$no."', estadoDestinatario: '".$no."'},";
				}
		
				}
				$respuesta = substr($respuesta, 0, strlen($respuesta)-1);
				$respuesta .= "]";
				echo $respuesta;
			}
		break;
		case 6:
			{			
			$f1value =  $_GET["f1value"];
			$separar = explode('-',$f1value);
			$exists = $bd->Execute("SELECT COUNT(cveDireccion) AS existe, cveEstado FROM cdireccionescliente WHERE cveDireccion='".$separar[1]."' AND cveCliente='".$separar[0]."' GROUP BY cveDireccion");
			$respuesta = "[";
			$total=count($exists);
			if($total==0) $respuesta="{existe: '0', estadoRemitente: ''}";
			else{

				foreach ($exists as $existe){
				$respuesta .= "{existe: '".$existe["existe"]."', estadoRemitente: '".$existe["cveEstado"]."'},";
					}
					$respuesta = substr($respuesta, 0, strlen($respuesta)-1);

			}
				$respuesta .= "]";
				echo $respuesta;
			}
		break;
		case 7: //Agregada
		{
			$field1 =  $_GET["field1"];
			$f1value =  $_GET["f1value"];			
			
			$sql="SELECT COUNT(*) AS existe,cveCliente FROM $table WHERE $field1 = '$f1value' ".$filtroAnd;
			$exists = $bd->Execute($sql);
			$respuesta = "[";
			foreach ($exists as $existe){
				$respuesta.= "{existe: '" . $existe["existe"] ."', cve: '".$existe["cveCliente"]."'},";
			}
				$respuesta = substr($respuesta, 0, strlen($respuesta)-1);
				$respuesta .= "]";
				echo $respuesta;
		}
		break;
		case 8: //Agregada
		{

			$field1 =  $_GET["field1"];
			$f1value =  $_GET["f1value"];

			//Toma únicamente el nombre ,separando la cve
			list($val1,$val2)=explode(" - ",$f1value);

			$sql="SELECT COUNT(*) AS existe,$field1 AS campo FROM $table WHERE $field1 = '$val1' ".$filtroAnd.";";
			//echo $sql;
			$exists = $bd->Execute($sql);
			$respuesta = "[";
			foreach($exists as $exist){
				$respuesta .= "{
					existe: '".$exist['existe']."',
					cve: '".$exist['campo']."'}";
			}
			$respuesta.="]";
			echo $respuesta;	
		}
		break;
		case 9: //Agregada
			{
			$field1 =  $_GET["field1"];
			$field2 =  $_GET["field2"];
			
			$f1value =  $_GET["f1value"];
			$f2value =  $_GET["f2value"];
			list($val1,$val2)=explode(" - ",$f1value);
			$f1value=$val1;			
			
			$exists = $bd->Execute("SELECT COUNT(*) AS existe FROM $table where $field1 = '$f1value' and $field2 = '$f2value' ".$filAnd);
			//echo "SELECT COUNT(*) AS existe FROM $table where $field1 = '$f1value' and $field2 = '$f2value' ".$filAnd;
			$respuesta = array();
			foreach ($exists as $existe){
			$respuesta[]=$existe[0];
				}
				echo "[{existe: " . $respuesta[0] . ",cve: " . $f1value . "}]";
		}
		break;
		case 10: //Agregada
		{
			$f1value =  $_GET["f1value"]; //cve Cliente
			$f2value =  $_GET["f2value"]; //cve Direccion


			$exists = $bd->Execute("SELECT COUNT(cveDireccion) AS existe, cveEstado FROM cdireccionescliente WHERE cveDireccion='".$f2value."' AND cveCliente='".$f1value."' GROUP BY cveDireccion");
			$respuesta = "[";
			foreach ($exists as $existe){
			$respuesta .= "{existe: '".$existe["existe"]."', estadoRemitente: '".$existe["cveEstado"]."'},";
				}
			$respuesta = substr($respuesta, 0, strlen($respuesta)-1);
			$respuesta .= "]";
			echo $respuesta;
		}
		break;
		case 11://Agregada
		{
			$field1 =  $_GET["field1"];
			$f1value =  $_GET["f1value"];
			$exists = $bd->Execute("SELECT COUNT(*) AS existe FROM $table where $field1 = '$f1value' ".$filtroAnd);
			$respuesta = array();
			foreach ($exists as $existe){
			$respuesta[]=$existe[0];
				}
				echo "[{existe: " . $respuesta[0] . "}]";
		}			
		break;
		case 12://Agregada
		{
			$field1 =  $_GET["field1"];
			$f1value =  $_GET["f1value"];
			$field2 =  $_GET["field2"];
			$f2value =  $_GET["f2value"];	
			$field3 =  $_GET["field3"];
			$f3value =  $_GET["f3value"];				
//			echo "SELECT COUNT(*) AS existe FROM $table where $field1 = '$f1value' AND $field2='".$f2value."' AND $field3='".$f3value."' ".$filtroAnd;
			$exists = $bd->Execute("SELECT COUNT(*) AS existe FROM $table where $field1 = '$f1value' AND $field2='".$f2value."' AND $field3='".$f3value."' ".$filtroAnd);
			$respuesta = array();
			foreach ($exists as $existe){
			$respuesta[]=$existe[0];
				}
				echo "[{existe: " . $respuesta[0] . "}]";
		}			
		break;		
		case 13: //Agregada
		{
			$field1 =  $_GET["field1"];
			$f1value =  $_GET["f1value"];			
			
			$sql="SELECT COUNT(*) AS existe,cveCorresponsal FROM $table WHERE $field1 = '$f1value' ".$filtroAnd;
			$exists = $bd->Execute($sql);
			$respuesta = "[";
			foreach ($exists as $existe){
				$respuesta.= "{existe: '" . $existe["existe"] ."', cve: '".$existe["cveCorresponsal"]."'},";
			}
				$respuesta = substr($respuesta, 0, strlen($respuesta)-1);
				$respuesta .= "]";
				echo $respuesta;
		}
		break;
		case 14:
		{			
			$estadoOrigen =  $_GET["estadoOrigen"];
			$origen =  $_GET["origen"];
			$estadoDestino =  $_GET["estadoDestino"];
			$cveTipoc =  $_GET["cveTipoc"];
			$tipoEnvio =  $_GET["tipoEnvio"];
			
			$condicion=" estadoOrigen='$estadoOrigen' AND origen='$origen' AND estadoDestino='$estadoDestino' AND cveTipoc='$cveTipoc' AND tipoEnvio='$tipoEnvio'";
			
			$exists = $bd->Execute("SELECT COUNT(*) AS existe FROM $table WHERE $condicion");
			$respuesta = array();
			foreach ($exists as $existe){
			$respuesta[]=$existe[0];
				}
				echo "[{existe: " . $respuesta[0] . "}]";
		}
		break;
		case 15:
		{			
			$cveDireccion =  $_GET["direccion"];
			$cveCorresponsal =  $_GET["corresponsal"];
			$table="ccorresponsales,cdireccionesprovedores";
			
			$condicion=" ccorresponsales.cveCorresponsal='$cveCorresponsal' AND (cdireccionesprovedores.cveDireccion='$cveDireccion' AND ccorresponsales.cveCorresponsal=cdireccionesprovedores.cveCorresponsal)";
			
			$exists = $bd->Execute("SELECT COUNT(*) AS existe FROM $table WHERE $condicion");
			$respuesta = array();
			foreach ($exists as $existe){
			$respuesta[]=$existe[0];
				}
				echo "[{existe: " . $respuesta[0] . ",corresponsal: " . $cveCorresponsal . ",direccion: " . $cveDireccion . "}]";
		}
		break;	
		case 16:
		{			
			$table="ccodigospostales";
			$f1value=$_GET['codigo'];
			$total=count(explode("-",$f1value));
			list($val1,$val2,$val3,$val4)=explode("-",$f1value);
			if($total>1){
				$condicion=" codigoPostal='$val1' AND cveCP='$val4'";
				$exists = $bd->Execute("SELECT COUNT(*) AS existe,codigoPostal FROM $table WHERE $condicion");
				$respuesta = array();
				foreach ($exists as $existe){
				//$respuesta[]=$existe[0];
					}
					echo "[{existe: " . $existe[0] .",codigo: " . $existe[1] .",cve: " . $val4 ."}]";
			}
			else{
				$condicion=" codigoPostal='$val1'";				
				$exists = $bd->Execute("SELECT COUNT(*) AS existe,codigoPostal FROM $table WHERE $condicion");
				$respuesta = array();
				foreach ($exists as $existe){
					}
					echo "[{existe: " . $existe[0]."}]";
			}
		}
		break;	
		case 17:
		{			
			$table="ccodigospostales";
			$f1value=$_GET['codigo'];
						
			$condicion=" codigoPostal='$val1'";
			
			$exists = $bd->Execute("SELECT COUNT(*) AS existe,codigoPostal FROM $table WHERE $condicion");
			$respuesta = array();
			foreach ($exists as $existe){
			//$respuesta[]=$existe[0];
				}
				echo "[{existe: " . $existe[0] .",codigo: " . $existe[1] .",cve: " . $val4 ."}]";
		}
		break;	
		case 18: //Para verificar direcciones de acuses
		{			
			$valor1=$_GET['f1value'];
			$valor2=$_GET['f2value'];
								
			$exists = $bd->Execute("SELECT cveDireccion AS clave
									FROM  `cguias` 
									WHERE  `cveGuia` =$valor1
									UNION 
									SELECT cveDireccion AS clave
									FROM  `cguias` 
									WHERE  `cveGuia` =$valor2");

			$total=count($exists);
						
			if($total>1)
				echo "[{iguales: 0 ,cveGuia:'".$valor2."'}]";
			else
				echo "[{iguales: 1 ,cveGuia:'".$valor2."'}]";
		}
		break;
		case 19: //Para verificar consignatarios
		{			
			$valor1=$_GET['f1value'];
			$table=" cconsignatarios";
			$condicion="cveConsignatario=".$valor1;
								
			$exists = $bd->Execute("SELECT COUNT(*) AS existe,cveConsignatario FROM $table WHERE $condicion");
			$respuesta = array();
			foreach ($exists as $existe){
				echo "[{existe: " . $existe[0].",cve:" . $existe[1]."}]";
			}
			
		}
		break;		
		case 20: //Para verificar consignatarios
		{			
			$valor1=$_GET['f1value'];
			$table=" cconsignatarios";
			$condicion="nombre='".$valor1."'";
								
			$exists = $bd->Execute("SELECT COUNT(*) AS existe,cveConsignatario FROM $table WHERE $condicion");
			$respuesta = array();
			foreach ($exists as $existe){
				echo "[{existe: " . $existe[0].",cve:'" . $clave."'}]";
			}
			
		}
		break;	
		case 21: //Para verificar que no se repitan cliente y/o estaciones en los usuarios
		{			
			$valor1=$_GET['f1value'];
			$table=" cusuarios";
			$condicion="estacion='".$valor1."'";
								
			$exists = $bd->Execute("SELECT COUNT(*) AS existe FROM $table WHERE $condicion");
			$respuesta = array();
			foreach ($exists as $existe){
				echo "[{existe: " . $existe[0]."}]";
			}
			
		}
		break;	
		case 22: //Para ver si es nueva o vieja una Factura en envíos
		{			
			$valor1=$_GET['f1value'];
			$table="cfacturas";
			$condicion="cveFactura='".$valor1."'";
								
			$exists = $bd->Execute("SELECT COUNT(*) AS existe FROM $table WHERE $condicion");
			$respuesta = array();
			foreach ($exists as $existe){
				echo "[{existe: " . $existe[0]."}]";
			}
			
		}
		break;		
		case 23:
		{			
			$f1value=$_GET['folio'];
			$f2value=$_GET['cliente'];
			session_start();
			$permiso=$_SESSION["permiso"];	
			if($permiso=="Administrador") $permiso=1;
			else $permiso=0;
			$condicion=" codigoPostal='$val1'";
			$sqlConsulta="SELECT COUNT(*) AS total FROM `cacuse` WHERE cveCliente=".$f2value." AND folio > ".$f1value;	    

			$exists = $bd->Execute($sqlConsulta);
			$respuesta = array();
			foreach ($exists as $existe){
				echo "[{existe: '" . $existe[0]."' ,permiso:'".$permiso."'}]";
			}
		}
		break;	
		case 24:
		{
			
			$f1value =  $_GET["f1value"];

			$sql="SELECT cveDireccion AS clave FROM cguias WHERE cveGuia='$f1value';";
			$exists = $bd->Execute($sql);
			$respuesta = "[";
			foreach($exists as $exist){
				$respuesta .= "{
					cve: '".$exist['clave']."'}";
			}
			$respuesta.="]";
			echo $respuesta;
				
				
		}	
		break;
		case 25:
		{

			$f1value =  $_GET["f1value"];
			//Toma únicamente el nombre ,separando la cve
			$valores=explode(" - ",$f1value);

			if(count($valores)>1)
				$valor=$valores[0];
			else
				$valor=$f1value;
				
			$f1value =  $_GET["f1value"];

			$sql="SELECT COUNT(*) AS existe FROM cbancos WHERE cveBanco='$valor';";
			
			$exists = $bd->Execute($sql);
			$respuesta = "[";
			foreach($exists as $exist){
				$respuesta .= "{
					existe: '".$exist['existe']."'}";
			}
			$respuesta.="]";
			echo $respuesta;

		}	
		break;	
		case 26:
		{
			$f1value =  $_GET["f1value"];
			$sql="SELECT COUNT(*) AS existe FROM cfacturascorresponsal WHERE cveFactura='$f1value';";

			$exists = $bd->Execute($sql);
			$respuesta = "[";
			foreach($exists as $exist){
				$respuesta .= "{
					existe: '".$exist['existe']."'}";
			}
			$respuesta.="]";
			echo $respuesta;	
		}
		break;
		case 27:
		{
			$f1value =  $_GET["f1value"];
			$sql="SELECT COUNT(*) AS existe FROM ccontrarecibo WHERE noContrarecibo='$f1value';";

			$exists = $bd->Execute($sql);
			$respuesta = "[";
			foreach($exists as $exist){
				$respuesta .= "{
					existe: '".$exist['existe']."'}";
			}
			$respuesta.="]";
			echo $respuesta;	
		}
		break;
		case 28: //Factura del Corresponsal
		{
			$f1value =  $_GET["f1value"];
			$corresponsal =  $_GET["corresponsal"];
			$anyo =  $_GET["anyo"];

			$sql="SELECT COUNT(*) AS existe FROM cfacturascorresponsal WHERE cveFactura='$f1value' AND cveCorresponsal='$corresponsal' AND anyoFactura='$anyo';";

			$exists = $bd->Execute($sql);
			$respuesta = "[";
			foreach($exists as $exist){
				$respuesta .= "{
					existe: '".$exist['existe']."'}";
			}
			$respuesta.="]";
			echo $respuesta;	
		}
		break;	
		case 29: //Acuse
		{

			$f1value =  $_GET["f1value"];
			$f2value =  $_GET["f2value"];
			
			$exists = $bd->Execute("SELECT COUNT(*) AS existe FROM cguias WHERE cveGuia='$f1value' AND cveCliente='$f2value';");
			$respuesta = array();
			foreach ($exists as $existe){
				$respuesta[]=$existe[0];
			}
			echo "[{existe: " . $respuesta[0] . "}]";
		}
		break;			
		default:break;
		}



?>
