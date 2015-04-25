<?php

	include ("bd.php");
	
	$cveFactura=$_GET["factura"];	
	$opcion=$_GET["opcion"];
	$usuario=$_POST["usuario"];
	
	$empresa=$_POST["empresa"];
	$empresa=str_replace("-","'",$empresa);
	$empresaS=explode(',',$empresa);
	
	if($opcion==0) //Cuando se elimina guías de la factura
	{
				  
		$cveGuia=$_GET["guia"];	
		
		$txttotalImporte   = $_POST['txttotalImporte'];
		$txttotalIva       = $_POST['txttotalIva'];
		$txttotalSubtotal  = $_POST['txttotalSubtotal'];
		$txttotalRetencion = $_POST['txttotalRetencion'];
		$txttotalTotal     = $_POST['txttotalTotal'];
		$txtcveCliente     = $_POST['txtcveCliente'];
		
		//Datos de la Guía
		$seguro            = $_POST['seguro'];
		$valorDeclarado    = $_POST['valorDeclarado'];	
		$importe           = $_POST['importe'];
		$iva               = $_POST['iva'];
		$subtotal          = $_POST['subtotal'];
		$retencion         = $_POST['retencion'];
		$total             = $_POST['total'];
		
		//Primero se borrará del Detalle de la Factura
		$sql1="DELETE FROM cfacturasdetalle WHERE cveFactura='".$cveFactura."' AND cveGuia='".$cveGuia."' AND cveEmpresa=".$empresaS[0]." AND cveSucursal=".$empresaS[1].";";	
		$res1=$bd->ExecuteNonQuery($sql1);
	
		//Actualizar el acuse, para ponerlo como NO facturado
		$sql2="UPDATE cacuse SET 
			   usuarioModifico='".$usuario."',
			   fechaModificacion=NOW(),
			   facturado=0 WHERE cveGuia='".$cveGuia."' AND cveEmpresa=".$empresaS[0]." AND cveSucursal=".$empresaS[1].";";
		$res2=$bd->ExecuteNonQuery($sql2);
	
		//Actualizar la guía, para ponerla como NO facturada
		$sql3="UPDATE cguias SET 
			   usuarioModifico='".$usuario."',
			   fechaModificacion=NOW(),
			   facturada=0 WHERE cveGuia='".$cveGuia."' AND cveEmpresa=".$empresaS[0]." AND cveSucursal=".$empresaS[1].";";
		$res3=$bd->ExecuteNonQuery($sql3);
		
		//Actualizar los totales de la Factura
		$importeFin=$txttotalImporte-$importe;     
		$ivaFin=$txttotalIva-$iva;      
		$subtotalFin=$txttotalSubtotal-$subtotal;       
		$retencionFin=$txttotalRetencion-$retencion;      
		$totalFin=$txttotalTotal-$total;     
		
		$sql4="UPDATE cfacturas SET
			   usuarioModifico='".$usuario."',
			   fechaModificacion=NOW(),		   	
			   importe='".$importeFin."',
			   iva='".$ivaFin."',
			   subtotal='".$subtotalFin."',
			   retencion='".$retencionFin."',
			   total='".$totalFin."'
			   WHERE cveFactura='".$cveFactura."' AND cveCliente='".$txtcveCliente."' AND cveEmpresa=".$empresaS[0]." AND cveSucursal=".$empresaS[1].";";
		$res4=$bd->ExecuteNonQuery($sql4);
			 
		
	
		$consulta1="SELECT montoNeto,saldo FROM dedocta WHERE folioDocumento='".$cveFactura."' AND cveEmpresa=".$empresaS[0]." AND cveSucursal=".$empresaS[1].";";
		
		$dedoctas = $bd->Execute($consulta1);
		foreach ($dedoctas as $dedocta)
		{
			$montobd=$dedocta["montoNeto"];
			$saldobd=$dedocta["saldo"];
			
		}
	
		$extra="";
		
		//Si cambian saldo y monto NETO NO cambiar solo restar si no actualizar
		if($montobd==$saldobd)
		{
			$extra=",saldo='".$importeFin."' ";
		}else
		{
			$diferencia=$montobd-$saldobd;
			$saldoFinal=$importeFin-$diferencia;
			if($saldoFinal<0) $saldoFinal=0;
			$extra=",saldo='".$saldoFinal."' ";
		}
	
		//Actualizar los totales de la 'dedocta'
		$sql5="UPDATE dedocta SET
			   usuarioModifico='".$usuario."',
			   fechaModificacion=NOW(),		   
			   montoBruto='".$importeFin."',
			   montoIva='".$ivaFin."',
			   montoNeto='".$totalFin."'".$extra."
			   WHERE folioDocumento='".$cveFactura."' AND cveEmpresa=".$empresaS[0]." AND cveSucursal=".$empresaS[1].";";
		$res5=$bd->ExecuteNonQuery($sql5);		   
			   
		//Finalmente Actualizar el Seguro de la Factura
		if($seguro!=0){
			
			$sqlConsulta="SELECT importe,iva,subtotal,retencion,total,porImpuesto,porRetencion,porSeguro 
			FROM cfacturas WHERE (cveFactura='".$cveFactura."' OR referencia='".$cveFactura."') AND seguro=1;";
			$datos=$bd->Execute($sqlConsulta);	
			$total=count($datos);
			$principal=$cveFactura;
			
			if($total==0) //Checar si es una factura derivada
			{
				$sqlConsulta="SELECT referencia FROM cfacturas WHERE cveFactura='".$cveFactura."';";
				$principal=$bd->soloUno($sqlConsulta);
		
				$sqlConsulta="SELECT importe,iva,subtotal,retencion,total,porImpuesto,porRetencion,porSeguro 
				FROM cfacturas WHERE referencia='".$principal."' AND seguro=1;";		
				$datos=$bd->Execute($sqlConsulta);	
				$total=count($datos);
			}
		
			if($total>0){ //Significa que la Factura si generó seguro
			
				foreach($datos as $dato) 
				{
					$importeSeg     = $dato["importe"];
					$ivaSeg         = $dato["iva"];
					$subtotalSeg    = $dato["subtotal"];
					$retencionSeg   = $dato["retencion"];
					$totalSeg       = $dato["total"];
					$porImpuesto    = $dato["porImpuesto"];
					$porRetencion   = $dato["porRetencion"];
					$porSeguro      = $dato["porSeguro"];
				}
				
				$importeFinal=$importeSeg-$seguro;     
				
				$ivaParcial=$seguro*($porImpuesto/100);	
				$ivaFinal=$ivaSeg-$ivaParcial;    
				$subtotalFinal=$importeFinal+$ivaFinal;       		
				$retencionFinal=0;
				$totalFinal=$subtotalFinal;     
			
				$sql6="UPDATE cfacturas SET
				   usuarioModifico='".$usuario."',
				   fechaModificacion=NOW(),		   	
				   importe='".$importeFinal."',
				   iva='".$ivaFinal."',
				   subtotal='".$subtotalFinal."',
				   total='".$totalFinal."'
				   WHERE referencia='".$principal."' AND seguro=1 AND cveCliente='".$txtcveCliente."' AND cveEmpresa=".$empresaS[0]." AND cveSucursal=".$empresaS[1].";";
			}
			$res6=$bd->ExecuteNonQuery($sql6);
		}
	
		$error=$res1.$res2.$res3.$res4.$res5.$res6;
		if(!empty($error))
		{ $msj="Ocurrió un problema, inténtelo más tarde.";}
		else
		{ $msj="La guía ha sido eliminada exitosamente.";}
	
	}
	else if($opcion==1) //Cuando se modifican los campos de envío y en dado caso los vales
	{

		//Campos de la base
		$campos[2]="fecha";
		$campos[3]="noGuia";
		$campos[4]="remicion";
		$campos[5]="planificacion";
		$campos[6]="destino";
		$campos[7]="destinatario";
		$campos[8]="observacion";
		$campos[9]="valorDeclarado";
		$campos[10]="piezas";
		$campos[11]="peso";
		$campos[12]="tarifa";
		$campos[13]="flete";
		$campos[14]="seguro";
		$campos[15]="acuse";
		$campos[16]="importe";
		$campos[17]="iva";
		$campos[18]="subtotal";
		$campos[19]="retencion";
		$campos[20]="total";
		$campos[21]="observaciones";

		$update='';
		
		
		for ($i = 2; $i < 21; $i++)
		{
			if ($_POST['condicion_' . $i] == "on")
			{	$valor = 1;	}
			else
			{	$valor = 0;	}			
			$update=$update.$campos[$i]."='".$valor."',";
			$valores = $valores . "'" . $valor . "', ";
		}
		
		if ($_POST['condicion_21'] == "on")
		{	$valor = 1;	}
		else
		{	$valor = 0; }
		
		$update=$update.$campos[$i]."='".$valor."',";
		
		$sql1 = "UPDATE ccamposenvio SET ".$update." usuarioModifico='".$usuario."', fechaModificacion=NOW() 
		WHERE cveFactura='".$cveFactura."' AND cveEmpresa=".$empresaS[0]." AND cveSucursal=".$empresaS[1].";";
		$res1=$bd->ExecuteNonQuery($sql1);
		
		//Checar el formato, si el formato es con Vales, actualizar los vales
		$formato=$_POST["formato"];
		if($formato==3)
		{
			$valesAbierta=$_POST["valesAbierta"];
			//Vales
			$datos=split(",",$valesAbierta);
			$a=0;
			for($i=0;$i<(count($datos));$i++)
			{
				if($datos[$i]!=""){ //Si el dato no es vacío se agregará a los vales
					$datos2[$a]=$datos[$i];
					$a++;
				}
			}
			$valesAbierta = implode(",", $datos2);
			
			$sql2 = "UPDATE cfacturas SET vales='".$valesAbierta."',usuarioModifico='".$usuario."', fechaModificacion=NOW() 
			WHERE cveFactura='".$cveFactura."' AND cveEmpresa=".$empresaS[0]." AND cveSucursal=".$empresaS[1].";";
			$res2=$bd->ExecuteNonQuery($sql2);
		}
		
		$error=$res1.$res2;
		if(!empty($error))
		{ $msj="Ocurrió un problema, inténtelo más tarde.";}
		else
		{ $msj="La factura ha sido actualizada exitosamente.";}
	}
	echo $msj;

?> 


