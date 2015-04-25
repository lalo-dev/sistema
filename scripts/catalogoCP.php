<?php
	/**
	 * @author Jose Miguel Pantaleon
	 * @copyright 2010
	 */
	header("Content-Type: text/Text; charset=ISO-8859-1 Cache-Control: no-store, no-cache, must-revalidate");
	header ("Expires: Fri, 14 Mar 1980 20:53:00 GMT"); //la pagina expira en fecha pasada
	header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); //ultima actualizacion ahora cuando la cargamos
	header ("Cache-Control: no-cache, must-revalidate"); //no guardar en CACHE
	header ("Pragma: no-cache"); //PARANOIA, NO GUARDAR EN CACHE
	
	include("bd.php");
	$caracteres =  $_POST["caracteres"];
	$codigo=$_GET['codigo'];
	
	
	$operacion=$_GET["operacion"];

	switch($operacion){

		case 1:
		{
					
			if ($caracteres != ""){				
				$tabla="ccodigospostales";
				$campos="ccodigospostales.nombre AS colonia,ccodigospostales.codigoPostal,cestados.nombre AS estado,ccodigospostales.cveCP";

				$condicion="INNER JOIN cestados ON cestados.cveEstado=ccodigospostales.cveEstado AND (ccodigospostales.cveCP LIKE '$caracteres%%' OR ccodigospostales.codigoPostal LIKE '$caracteres%%'
OR cestados.nombre LIKE '$caracteres%%' ) LIMIT 0,8";
			
				$sql= "";
				$dests = $bd->ExecuteFieldInner2($tabla,$campos,$condicion);
				$respuesta = "<ul>";
				
				foreach ($dests as $dest){
					$respuesta .= "<li>" . $dest[1] ."-". $dest[2] ."-". $dest[0] ."-". $dest[3] . "</li>";
				}
				$respuesta .= "</ul>";
				echo $respuesta;
			  }
		}
		break;
		case 2:
		{
			$f1value=$codigo;
			
			list($val1,$val2,$val3,$val4)=explode("-",$f1value);
			$query="SELECT cveCP,codigoPostal,cveEstado,cveMunicipio,nombre,cveAsentamiento FROM ccodigospostales WHERE cveCP='$val4' AND codigoPostal='$val1'";

			$dests = $bd->Execute($query);
			$respuesta = "[";
			foreach($dests as $campo){		
				$respuesta .= "{ clave: '".$campo["cveCP"]."',codigoPostal: '" . $campo["codigoPostal"] . "', cveEstado: '" . $campo["cveEstado"] ."', cveMunicipio: '" . $campo["cveMunicipio"] ."', colonia: '" . $campo["nombre"] ."',cveAsentamiento: '" . $campo["cveAsentamiento"] ."'},";
			}
			$respuesta = substr($respuesta, 0, strlen($respuesta)-1);
			$respuesta .= "]";
			echo $respuesta;
	
		}
		break;
		case 3:
		{
			$cveMunicipio = $_GET["municipio"];
			$cveEstado=$_GET["estado"];
			
			$aGpups = $bd->Execute("SELECT cveMunicipio,nombre FROM cmunicipios WHERE cveEntidadFederativa = '$cveEstado'  ORDER BY nombre");
			
			$respuesta = "[";
			
			foreach ($aGpups as $gpup){
			$respuesta .= "{id: '" . $gpup["cveMunicipio"] . "', desc: '" . $gpup["nombre"] . "', estado: '" . $cveEstado ."', mun: '" . $cveMunicipio ."'},";
			}
			$respuesta = substr($respuesta, 0, strlen($respuesta)-1);
			$respuesta .= "]";
			echo $respuesta;
	
		}
		break;
		case 4:
		{
		 $estado=$_GET["estado"];
			$aGpups = $bd->Execute("SELECT cveDestino,descripcion FROM cdestinos WHERE estado='$estado'");

			$respuesta = "[";
			
			foreach ($aGpups as $gpup){
			$respuesta .= "{id: '" . $gpup["cveDestino"] . "', desc: '" . $gpup["descripcion"] . "'},";
			}
			$respuesta = substr($respuesta, 0, strlen($respuesta)-1);
			$respuesta .= "]";
			echo $respuesta;
	
		}
		break;
		case 5:
		{
			$estado=$_GET['estado'];
			$municipio=$_GET['municipio'];
			
		
			$sql= "SELECT cestados.nombre AS edo,cmunicipios.nombre AS mun,ccodigospostales.nombre,ccodigospostales.`codigoPostal`,ccodigospostales.`cveCP`
FROM  `ccodigospostales` 
INNER JOIN cestados ON cestados.cveEstado = ccodigospostales.cveEstado
INNER JOIN cmunicipios ON cmunicipios.cveMunicipio = ccodigospostales.cveMunicipio
WHERE cmunicipios.cveEntidadFederativa= ccodigospostales.cveEstado
AND ccodigospostales.cveEstado ='$estado'
AND ccodigospostales.cveMunicipio ='$municipio';";

			$codigos = $bd->Execute($sql);				
			$total=count($codigos);
			if($total==0)
			{
				$respuesta = "[{total:'" .$total."'}";
			}
			else{
				$respuesta = "[";                
				foreach ($codigos as $cp){
				$respuesta .= "{total:'" .$total."', estado: '" . $cp["edo"] . "', mun: '" . $cp["mun"]."',colonia: '" . $cp["nombre"] . "', cp: '" . $cp["codigoPostal"] ."', clave:'" . $cp["cveCP"]."'},";
				}
				$respuesta = substr($respuesta, 0, strlen($respuesta)-1);
				$respuesta .= "]";
			}
			
			echo $respuesta;
		}
		break;
		case 6: //Para las guías
		{
			$f1value=$codigo;
			
			list($val1,$val2,$val3,$val4)=explode(",",$f1value);
			$query="SELECT cveCP,codigoPostal,cveEstado,cveMunicipio,nombre,cveAsentamiento FROM ccodigospostales WHERE cveCP='$val4' AND codigoPostal='$val1'";

			$dests = $bd->Execute($query);
			$respuesta = "[";
			foreach($dests as $campo){		
				$respuesta .= "{ clave: '".$campo["cveCP"]."',codigoPostal: '" . $campo["codigoPostal"] . "', cveEstado: '" . $campo["cveEstado"] ."', cveMunicipio: '" . $campo["cveMunicipio"] ."', colonia: '" . $campo["nombre"] ."',cveAsentamiento: '" . $campo["cveAsentamiento"] ."'},";
			}
			$respuesta = substr($respuesta, 0, strlen($respuesta)-1);
			$respuesta .= "]";
			echo $respuesta;
	
		}
		break;		
		case 7:
		{
			$cveMunicipio = $_GET["municipio"];
			$cveEstado    = $_GET["estado"];
			
			$aGpups = $bd->Execute("SELECT nombre FROM cmunicipios WHERE cveEntidadFederativa='$cveEstado' AND cveMunicipio='$cveMunicipio'");
			
			$respuesta = "[";
			
			foreach ($aGpups as $gpup)
			{
				$respuesta .= "{nombreMun:'".$gpup["nombre"]."'},";
			}
			$respuesta = substr($respuesta, 0, strlen($respuesta)-1);
			$respuesta .= "]";
			echo $respuesta;
	
		}
		break;
		default:break;
	}
		
		
	
	?>
	
