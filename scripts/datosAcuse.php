<?php

/**
 * @author miguel
 * @copyright 2009
 */
	header("Content-Type: text/Text; charset=UTF-8 Cache-Control: no-store, no-cache, must-revalidate");
	header("Expires: Fri, 14 Mar 1980 20:53:00 GMT"); //la pagina expira en fecha pasada
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); //ultima actualizacion ahora cuando la cargamos
	header("Cache-Control: no-cache, must-revalidate"); //no guardar en CACHE
	header("Pragma: no-cache"); //PARANOIA, NO GUARDAR EN CACHE
	
	
	include ("bd.php");
	if(isset($_GET["folio"]))
		$folio = $_GET["folio"];
	if(isset($_GET["cliente"]))
		$cliente = $_GET["cliente"];
	if(isset($_GET["fechaAcuse"]))
		$fechaAcuse = $_GET["fechaAcuse"];		

	if ($folio > 0)
	{
		$respuesta = "[";
		$guias = $bd->Execute("SELECT cveGuia FROM cacuse WHERE folio='$folio' AND cveCliente='$cliente'");
		
		foreach ($guias as $dguia)
		{
			$facturaEnviar="";	
			$entregaEnviar="";						
			$cveguia = $dguia["cveGuia"];
			$facturas = $bd->Execute("SELECT facturasoporte FROM cfacturassoporte WHERE cveGuia= '$cveguia'");
	
			foreach ($facturas as $factura)
			{
				$facturaEnviar = $facturaEnviar . $factura["facturasoporte"] . ",";
				utf8_encode($facturaEnviar);
	
			}
			$facturaEnviar = substr($facturaEnviar, 0, strlen($facturaEnviar) - 1);
			$entregas = $bd->Execute("SELECT entregasSoporte FROM centregassoporte WHERE cveGuia= '$cveguia'");
	
			foreach ($entregas as $entrega)
			{
				$entregaEnviar = $entregaEnviar . $entrega["entregasSoporte"] . ",";						
			}
	
			$entregaEnviar = substr($entregaEnviar, 0, strlen($entregaEnviar) - 1);
	
			if ($facturaEnviar == "")
			{
				$facturaEnviar = $entregaEnviar;
				utf8_encode($facturaEnviar);												
			}
			
			$sql="SELECT cguias.recepcionCYE, cguias.sello, cguias.firma, cguias.indicadorRespaldos, cguias.recibio,cmunicipios.nombre AS municipioDestinatario,cveDireccion FROM cguias LEFT JOIN cconsignatarios ON cguias.cveConsignatario = cconsignatarios.cveConsignatario	
	INNER JOIN cestados ON cconsignatarios.estado = cestados.cveEstado
	INNER JOIN cmunicipios ON cconsignatarios.municipio = cmunicipios.cveMunicipio WHERE cestados.cveEstado=cmunicipios.cveEntidadFederativa AND cguias.cveGuia= '$cveguia';";
			
			$campos = $bd->Execute($sql);
			
	
			foreach ($campos as $campo)
			{
				$guia ="";				
				$recibido="";
				$recibio="";
				$respaldo="";				

				$sello    = utf8_encode($campo["sello"]);
				$firma    = utf8_encode($campo["firma"]);
				$recibio  = utf8_encode($campo["recibio"]);
				$respaldo = utf8_encode($campo["indicadorRespaldos"]);
				
				if ($sello == 1)
				{
					$recibido = "SELLO ";
					if ($firma == 1)
					{ $recibido = $recibido . "Y FIRMA "; }
					else
					{ $recibido = $recibido;			  }
					
					if ($recibio != '')
					{ $recibido = $recibido . $recibio;   }
					else
					{ $recibido = $recibido;    		  }
				}
				else
				{
					$recibido = "";
					if ($firma == 1)
					{ $recibido = $recibido . "FIRMA ";  }
					else
					{ $recibido = $recibido;			 }
					if ($recibio != '')
					{ $recibido = $recibido . $recibio;  }
					else
					{ $recibido = $recibido; 			 }
				}
				
				$guia = "GUIA ";
				if ($facturaEnviar != '')
				{	$guia .= "Y FACT. " . $facturaEnviar; } 
				elseif ($respaldo == 1)
				{	$guia .= "Y RESPALDO";				  }
				
				$fechac=cambiaf_a_normal($campo["recepcionCYE"]);
				$respuesta .= "{llegadaacuse: '" . $fechac . "', recibio: '" . $recibido .
							  "', branch_municip: '" . utf8_encode($campo["municipioDestinatario"]) . "', facturas: '" . $guia .
							  "', cvedireccion: '" . utf8_encode($campo["cveDireccion"]) . "', cveguia: '" . $cveguia ."'},"; 
			}
		}
	}
	else if ($fechaAcuse !="")
	{
		list($dia,$mes,$anyo)=explode("/",$fechaAcuse);
		$fecha=$anyo."-".$mes."-".$dia;
		$respuesta = "[";

		$guias = $bd->Execute("SELECT DISTINCT(cguias.cveGuia) FROM cguias WHERE cveGuia NOT IN(SELECT DISTINCT(cacuse.cveGuia) ".
							  "FROM cacuse WHERE cacuse.cveCliente='".$cliente. "') AND cguias.cveCliente='".$cliente. "' ".
							  "AND cguias.status='Concluida' AND cguias.facturada='0' AND estatus='1' AND llegadaAcuse='".$fecha."' ORDER BY cveGuia ASC");
		
		foreach ($guias as $dguia)
		{
			$facturaEnviar="";
			$entregaEnviar="";
			$cveguia = $dguia["cveGuia"];
			
			$facturas = $bd->Execute("SELECT facturasoporte FROM cfacturassoporte WHERE cveGuia= '$cveguia'");
	
			foreach ($facturas as $factura)
			{
				$facturaEnviar = $facturaEnviar . $factura["facturasoporte"] . ",";
				utf8_encode($facturaEnviar);	
			}
			$facturaEnviar = substr($facturaEnviar, 0, strlen($facturaEnviar) - 1);
			$entregas = $bd->Execute("SELECT entregasSoporte FROM centregassoporte WHERE cveGuia= '$cveguia'");
	
			foreach ($entregas as $entrega)
			{
				$entregaEnviar = $entregaEnviar . $entrega["entregasSoporte"] . ",";							
			}
	
			$entregaEnviar = substr($entregaEnviar, 0, strlen($entregaEnviar) - 1);
	
			if ($facturaEnviar == "")
			{
				$facturaEnviar = $entregaEnviar;
				utf8_encode($facturaEnviar);												
			}
			
			$sql="SELECT cguias.recepcionCYE, cguias.sello, cguias.firma, cguias.indicadorRespaldos, cguias.recibio,cmunicipios.nombre AS municipioDestinatario,cveDireccion FROM cguias LEFT JOIN cconsignatarios ON cguias.cveConsignatario = cconsignatarios.cveConsignatario	
	INNER JOIN cestados ON cconsignatarios.estado = cestados.cveEstado
	INNER JOIN cmunicipios ON cconsignatarios.municipio = cmunicipios.cveMunicipio WHERE cestados.cveEstado=cmunicipios.cveEntidadFederativa AND cguias.cveGuia= '$cveguia' ORDER BY cveGuia ASC;";
			
			$campos = $bd->Execute($sql);
			
	
			foreach ($campos as $campo)
			{
				$guia ="";				
				$recibido="";
				$recibio="";
				$respaldo="";
				
				$sello    = utf8_encode($campo["sello"]);
				$firma    = utf8_encode($campo["firma"]);
				$recibio  = utf8_encode($campo["recibio"]);
				$respaldo = utf8_encode($campo["indicadorRespaldos"]);
				if ($sello == 1)
				{
					$recibido = "SELLO ";
					if ($firma == 1)
					{ $recibido = $recibido . "Y FIRMA "; }
					else
					{ $recibido = $recibido;			  }
					
					if ($recibio != '')
					{ $recibido = $recibido . $recibio;   }
					else
					{ $recibido = $recibido;    		  }
				}
				else
				{
					$recibido = "";
					if ($firma == 1)
					{ $recibido = $recibido . "FIRMA ";  }
					else
					{ $recibido = $recibido;			 }
					if ($recibio != '')
					{ $recibido = $recibido . $recibio;  }
					else
					{ $recibido = $recibido; 			 }
				}
				
				$guia = "GUIA ";
				if ($facturaEnviar != '')
				{	$guia .= "Y FACT. " . $facturaEnviar; } 
				elseif ($respaldo == 1)
				{	$guia .= "Y RESPALDO";				  }
				
				$fechac=cambiaf_a_normal($campo["recepcionCYE"]);
				$respuesta .= "{llegadaacuse: '" . $fechac . "', recibio: '" . $recibido .
							  "', branch_municip: '" . utf8_encode($campo["municipioDestinatario"]) . "', facturas: '" . $guia .
							  "', cvedireccion: '" . utf8_encode($campo["cveDireccion"]) . "', cveguia: '" . $cveguia ."'},"; 
			}
	
		}
	}
	else
	{
		$cveguia = $_GET["cveguia"];

		//Valor de Factura
		$facturas = $bd->Execute("SELECT facturasoporte FROM cfacturassoporte WHERE cveGuia='$cveguia'");
	
		foreach ($facturas as $factura)
		{
			$facturaEnviar = utf8_encode($facturaEnviar . $factura["facturasoporte"] . ",");							    
		}
		$facturaEnviar = substr($facturaEnviar, 0, strlen($facturaEnviar) - 1);
		
		//Valor de Entrega
		$entregas = $bd->Execute("SELECT entregasSoporte FROM centregassoporte WHERE cveGuia='$cveguia'");
	
		foreach ($entregas as $entrega)
		{
			$entregaEnviar = utf8_encode($entregaEnviar . $entrega["entregasSoporte"] . ",");							  
		}
		$entregaEnviar = substr($entregaEnviar, 0, strlen($entregaEnviar) - 1);
	
		//Valor de "Envíar"
		if ($facturaEnviar == "")
		{
			$facturaEnviar = utf8_encode($entregaEnviar);							   
		}
	
			
		$cons="SELECT cguias.recepcionCYE, cguias.sello, cguias.firma, cguias.indicadorRespaldos, cguias.recibio,cmunicipios.nombre AS municipioDestinatario,".
			  "cveDireccion FROM cguias LEFT JOIN cconsignatarios ON cguias.cveConsignatario = cconsignatarios.cveConsignatario ".	
			  "INNER JOIN cestados ON cconsignatarios.estado = cestados.cveEstado ".
			  "INNER JOIN cmunicipios ON cconsignatarios.municipio = cmunicipios.cveMunicipio ".
			  "WHERE cestados.cveEstado=cmunicipios.cveEntidadFederativa AND cguias.cveGuia= '$cveguia';";
	
		$campos = $bd->Execute($cons);
	
		$respuesta = "[";
		foreach ($campos as $campo)
		{							  
			$guia ="";				
			$recibido="";
			$recibio="";
			$respaldo="";
			
			$sello    = utf8_encode($campo["sello"]);
			$firma    = utf8_encode($campo["firma"]);
			$recibio  = utf8_encode($campo["recibio"]);
			$respaldo = utf8_encode($campo["indicadorRespaldos"]);
			if ($sello == 1)
			{
					$recibido = "SELLO ";
					
					if ($firma == 1)		{ $recibido = $recibido . "Y FIRMA ";	}
					
					if ($recibio != '')		{ $recibido = $recibido . $recibio;		}
					else 					{ $recibido = $recibido;				}
			}
			else
			{
					$recibido = "";
					if ($firma == 1)		{ $recibido = $recibido . "FIRMA ";	   }
					
					if ($recibio != '')		{ $recibido = $recibido . $recibio;	   }
					else					{ $recibido = $recibido;			   }
			}
	
			
			$guia = "GUIA ";
			if ($facturaEnviar != '')
			{
				$guia .= "Y FACT. " . $facturaEnviar;
			}elseif ($respaldo == 1)
			{
				$guia .= "Y RESPALDO";
			}
			
			$fechac=cambiaf_a_normal($campo["recepcionCYE"]);
			$respuesta .= "{llegadaacuse: '" . $fechac . "', recibio: '" . $recibido .
							"', branch_municip: '" . utf8_encode($campo["municipioDestinatario"]) . "', facturas: '" . $guia .
							"', cvedireccion: '" . utf8_encode($campo["cveDireccion"]) . "', cveguia: '" . $cveguia .
							"'},";
		}
	}
	
	$respuesta = substr($respuesta, 0, strlen($respuesta) - 1);
	$respuesta .= "]";
	
	echo $respuesta;

	function cambiaf_a_normal($fecha){ 
		preg_match( "#([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2})#", $fecha, $mifecha); 
		$lafecha=$mifecha[3]."/".$mifecha[2]."/".$mifecha[1]; 
		return $lafecha; 
	} 
?>
