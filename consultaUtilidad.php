<?php
	if(!session_start())
		session_start();
?>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>Reporte de Utilidad Bruta</title>
<script type="text/javascript" src="scripts/AjaxLib.js"></script>
<script type="text/javascript" src="scripts/prototype.js"></script>
<script type="text/javascript" src="scripts/window.js"> </script>
<link href="themes/default.css" rel="stylesheet" type="text/css"/> 
<!-- Add this to have a specific theme--> 
<link href="themes/mac_os_x.css" rel="stylesheet" type="text/css"/> 
<style>
	body
	{
		font-family:Verdana, Geneva, sans-serif;
		font-size:10px;
	}
	body a
	{
		font-family::Verdana, Geneva, sans-serif;
		font-size:12px;
		cursor:pointer;
	}
	table th
	{
		background-color:#C8CDFF;
		border-color:#D2D7FF;
		color:#5B5B5B;
		font-size:10px;
		font-family:"Lucida Calligraphy";
		font-weight:500;
	}
	table
	{
		border:0px;
		font-size:12px;
	}
	table td
	{
		border-bottom-color:#999999;
		border-left-color:#999999;
		border-right-color:#999999;
		border-top-color:#999999;
	}
	a,label
	{
		text-decoration:underline;	
		font-size:12px;
		color:#06F;
		cursor:pointer;	
	}
	.tblCaclulos
	{
		font-size:10px;
		border-bottom-color:#06C;
		border-left-color:#06C;
		border-right-color:#06C;
		border-top-color:#06C;
	}
	.sinBorde
	{
		border:0px;
	}
	.encabezado
	{
		background-color:#9B9BFF;
		background-color:#B8C0FA;
		color:#333;
		height:25px;
		font-size:14px;
	}
</style>
<?php

	//Se inicalizan los datos
	$cliente="";
	$destino="";
	$fechaPerDesde="";
	$fechaPerHasta="";
	
	//Consulta General
	
		//Se deberán tomar los datos de reporte factura
	$sqlConsulta="SELECT cguias.cveGuia,IFNULL(cconsignatarios.estacion,'') AS estacion,".
		 "IFNULL(cguias.recepcionCYE,'') AS recepcionCYE,".			 
		 "IFNULL(ccliente.razonSocial,'') AS nombreCli,".
		 "IFNULL(cconsignatarios.estado,0) AS estadoDestinatario,".
		 "IFNULL(cconsignatarios.municipio,0) AS municipioDestinatario,".
		 "IFNULL(cguias.piezas,0) AS piezas,".			 
		 "IFNULL(IF(cguias.kg>cguias.volumen,cguias.kg,cguias.volumen),0) AS peso,".			 
		 "IFNULL(cguias.tipoEnvio,'') AS tipoEnvio,".
		 "IFNULL(cguias.valorDeclarado,'0') AS seguro,".
		 "IFNULL(cguias.observaciones,'') AS observacion,".
		 "cguias.costoGuiaParcial AS ctoGuiaA ".
		 "FROM cguias ".
		 "LEFT JOIN cconsignatarios ON cguias.cveConsignatario=cconsignatarios.cveConsignatario ".
		 "LEFT JOIN ccliente ON cguias.cveCliente=ccliente.cveCliente ".				 
		 "LEFT JOIN cacuse ON cguias.cveGuia=cacuse.cveGuia ".
		 "LEFT JOIN cvalessoporte ON cguias.cveGuia=cvalessoporte.cveGuia ".
		 "LEFT JOIN centregassoporte ON cguias.cveGuia=centregassoporte.cveGuia ".
		 "LEFT JOIN cfacturassoporte ON cguias.cveGuia=cfacturassoporte.cveGuia ";

	$condicion="WHERE cguias.estatus=1 ";
	
	require_once("scripts/bd.php");	

	
	//Se capturan los datos	
	
		//Clientes
	$cliente=$_POST['txtCliente'];

	if($cliente!="" && $cliente!="Cliente")
		$condicion.="AND cguias.cveCliente='".$cliente."' ";		

		//Destinos	
	$destino=$_POST['sltDestino'];

   	if($destino!='0' && $destino!="Elija un Destino")
		$condicion.="AND estacion='".$destino."' ";		

		//Fechas de Guías
	$fechaPerDesde=convFecha($_POST['txtPeriodoD']);
	$fechaPerHasta=convFecha($_POST['txtPeriodoH']);

	$condicion.="AND (cguias.recepcionCYE BETWEEN '".$fechaPerDesde."' AND '".$fechaPerHasta."') ";		


	//Armamos el query final 
	$sqlFinal=$sqlConsulta.$condicion." ORDER BY recepcionCYE ASC";

	//Obtenemos los datos de la Guía		
	$datos = $bd->Execute($sqlFinal);
		
	
	if(count($datos)!=0) 
	{
?>        
        <!--Encabezados-->
        <a href="excelReporte.php?desde=<?php echo $fechaPerDesde;?>&hasta=<?php echo $fechaPerHasta;?>
                <?php if ($cliente!="" && $cliente!="Cliente") echo "&cliente=$cliente" ?>" title="Consultar Excel">Consultar Excel</a>
        <br />
        <br />
        <label id="lblImprimir" onclick="imprimir(<?php echo "'".$fechaPerDesde."','".$fechaPerHasta."',"; 
                                                  if($cliente!="" && $cliente!="Cliente") 
                                                    echo "'".$cliente."'"; 
                                                  else 
                                                    echo '0'; ?>);" title="Imprimir Reporte">Imprimir</label>
<?php } ?>
        <br />
        <br />                   
        <table> 
            <tr>
                <td style='font-weight:bold;'>
                    <?php echo utf8_decode("TOTAL DE GU&Iacute;AS: ").count($datos);?>        
                </td>    
            </tr>
        </table>
        <br />
    <?php 
	if(count($datos)==0) 
	{?>
        <table bgcolor='#C8CDFF' width="579">
            <tr><td></td></tr>
            <tr>
                <td colspan="13" height="20" style="color:#666;font-weight:bold">No hay gu&iacute;as registradas con esos par&aacute;metros </td>
            </tr>
        </table>
<?php
    } 
    else
	{
   ?>
        <table width="1769" border="1"> 
            <tr id="Encabezados">
                <th width="86">FECHA DE ENV&Iacute;O</th>
                <th width="67">GU&iacute;A CYE</th>
                <th width="360">CLIENTE</th>
                <th width="90">DESTINO</th>
                <th width="100">TIPO DE ENV&iacute;O</th>
                <th width="72">VALOR DECLARADO</th>
                <th width="72" align="center">PIEZAS</th>
                <th width="72" align="center">PESO</th>
                <th width="80" align="center">IVA</th>
                <th width="80" align="center">SUBTOTAL</th>
                <th width="80" align="center">RETENCI&Oacute;N (4%)</th>
                <th width="80" align="center">COBRADO</th>
                <th width="80" align="center">PAGO A CORRESPONSAL.</th>
                <th width="80" align="center">PAGO GU&Iacute;A A&Eacute;REA</th>
                <th width="80" align="center">UTILIDAD</th>
                <th width="360">OBSERVACI&Oacute;N</th>
            </tr>
<?php	
			$sumaPiezas        = 0;
			$sumaPeso          = 0;
			$sumaIva           = 0;
			$sumaSubtotal      = 0;
			$sumaRetencion     = 0;
			$sumaTotalPagado   = 0;
			$sumaTotalCobrado  = 0;
			$sumaTotalUtilidad = 0;
			$sumaTotalGuiaAerea= 0;			
			
			//Datos Generales a todas las guías
			$sqlr="SELECT primerRango,segundoRango,tercerRango FROM ctarifascorresponsales WHERE cveCorresponsal=0";	
			$rangos = $bd->Execute($sqlr);
			
			foreach($rangos as $rango)
			{
				 $rango1= valorRango($rango['primerRango']);
				 $rango2= valorRango($rango['segundoRango']);
				 $rango3= valorRango($rango['tercerRango']);
			}	
			
			$h=0;
			$i=0;
			$con=0;
			$guiaBase="";
			foreach ($datos as $datoGuia)
			{
				
				$cveGuia = $datoGuia['cveGuia'];
				$peso    = $datoGuia['peso'];
				$piezas  = $datoGuia['piezas'];
				$seguro  = $datoGuia['seguro'];
				$ctoGuiaA = $datoGuia['ctoGuiaA'];

				//Obtener los datos de tarifa del cliente
												
				//Buscamos primero si la guía ya fue facturada
				$sqlFact="SELECT cfacturasdetalle.cveFactura FROM cfacturasdetalle ".
						 "INNER JOIN cfacturas ON cfacturas.cveFactura=cfacturasdetalle.cveFactura ".
						 "WHERE cfacturas.seguro=0 AND cfacturasdetalle.cveGuia='".$cveGuia."' ".
						 "ORDER BY cfacturasdetalle.cveDetalle DESC LIMIT 1";
							
				$noFact = $bd->soloUno($sqlFact);
				
				if($noFact!="")  //Consultar los datos de la Factura real
				{
					$sqlDatosFactura="SELECT peso,piezas,tarifa,flete,importe,cveIva,retencionIVA,subtotal,total ".
									 "FROM cfacturasdetalle WHERE cveFactura='".$noFact."' AND cveGuia='".$cveGuia."'";
									 
					$datosFact = $bd->Execute($sqlDatosFactura);
					
					foreach($datosFact as $datoFact)
					{
						//Se les da formato a los nÃºmeros
						$peso=redondeaNum($datoFact['peso']);
						$piezas=redondeaNum($datoFact['piezas']);
						$valorDec=redondeaNum($seguro);			
						//$importe=redondeaNum($datoFact['importe']);
						$iva=redondeaNum($datoFact['cveIva']);
						$retIva=redondeaNum($datoFact['retencionIVA']);
						$subtotal=redondeaNum($datoFact['subtotal']);
						$total=redondeaNum($datoFact['total']);
					}
				}
				else		    //Realizar el cálculo
				{					
					$sqlConsulta="SELECT DISTINCT(ctarifas.cvetipoc) FROM ctarifas ".
							     "INNER JOIN ccliente ON ctarifas.cveTipoc=ccliente.cveTipoCliente ".
							     "INNER JOIN cguias ON cguias.cveCliente=ccliente.cveCliente ".
							     "WHERE cguias.cveGuia='".$cveGuia."'";
					$tipoc=$bd->soloUno($sqlConsulta);
					$tipoE= $datoGuia['tipoEnvio'];

					$tarifaa     = 0;
					$tarifab     = 0;
					$tarifac     = 0;
					$tarifad     = 0;
					$cargoMinimo = 0;
					
					if($tipoc!="" && $tipoE!="")
					{
						$sqlt="SELECT cargo99,cargo299,cargo300,cuartoRango,cargoMinimo FROM ctarifas ".
							  "WHERE estadoOrigen='9' AND estadoDestino='".$datoGuia["estadoDestinatario"]."' ".
							  "AND origen='17' AND destino='".$datoGuia["municipioDestinatario"] ."' AND tipoEnvio='$tipoE' ".
							  "AND cvetipoc='$tipoc' AND estatus=1";
						$tarifas = $bd->Execute($sqlt);
					
						$i=count($tarifas);
						if($i>0)                       //Sólo si se tienen los datos necesarios(cliente,consiganatario y q existe la tarifa), se tendrán datos de TARIFA
						{
							foreach($tarifas as $tarifa)
							{
								$tarifaa= $tarifa["cargo99"];
								$tarifab= $tarifa["cargo299"];
								$tarifac= $tarifa["cargo300"];
								$tarifad= $tarifa["cuartoRango"];
								$cargoMinimo=$tarifa["cargoMinimo"];
							} 
					    } 
					}

					if($peso<= $rango1)
					{ $cargo=$tarifaa; }
					
					if($peso > $rango1 AND $peso<= $rango2)
					{ $cargo=$tarifab; }
					
					if($peso> $rango2 AND $peso<= $rango3)
					{ $cargo=$tarifac; }
					
					if($peso>= $rango3)
					{ $cargo=$tarifad; }
					
					$sqlConsulta="SELECT IFNULL(ccliente.cveImpuesto,0) AS cveImpuestoCli ".
								 "FROM ccliente ".
								 "INNER JOIN cguias ON ccliente.cveCliente=cguias.cveCliente ".
								 "WHERE cguias.cveGuia='".$cveGuia."'";
					$porcentajeIva = $bd->soloUno($sqlConsulta);
					
					if($porcentajeIva=='')
						$porcentajeIva=0;
					else
						$porcentajeIva=$porcentajeIva/100;
					
					$flete= $cargo*$peso;
					
					if($flete>$cargoMinimo){$flete=$flete;}else{$flete=$cargoMinimo;}
					
					$acuse=150;
					$importe=$flete+$acuse;
					$iva=$importe*$porcentajeIva;
					$retIva=($importe*.04);
					$subtotal=$iva+$importe;
					$total=$subtotal-$retIva;
					
					//Se les da formato a los números
					$valorDec=redondeaNum($seguro);			
					$importe=redondeaNum($importe);
					$iva=redondeaNum($iva);
					$retIva=redondeaNum($retIva);
					$subtotal=redondeaNum($subtotal);
					$total=redondeaNum($total);
				}
				
				
				//Obtener los datos de tarifa del corresponsal
				
				//Buscamos primero si la guía ya fue facturada
				$sqlFact="SELECT cfacturasdetallecorresponsales.cveFactura FROM cfacturasdetallecorresponsales ".
						 "INNER JOIN cfacturascorresponsal ON cfacturascorresponsal.cveFactura=cfacturasdetallecorresponsales.cveFactura ".
						 "WHERE cfacturasdetallecorresponsales.cveGuia='".$cveGuia."' ".
						 "ORDER BY cfacturasdetallecorresponsales.cveDetalle DESC LIMIT 1;";
							
				$noFact = $bd->soloUno($sqlFact);
				
				if($noFact!="") //Consultar los datos de la Factura real
				{
					$sqlDatosFactura="SELECT costoEntrega ".
									 "FROM cfacturasdetallecorresponsales ".
									 "WHERE cveFactura='".$noFact."' AND cveGuia='".$cveGuia."' ".
									 "ORDER BY cveDetalle DESC LIMIT 1;";
									 
					$costoEntrega = $bd->soloUno($sqlDatosFactura);					
				}
				else		    //Obtenemos el costo de entrega	  
				{
						//Consultar si hay tarifa para la guía
					$sqlConsulta="SELECT cguias.piezas,".
							 "rango1,rango2,rango3,rango4,".					 
							 "datostarifas.primerRango,datostarifas.segundoRango,datostarifas.Tercerrango,datostarifas.cuartoRango,".
							 "datostarifas.tipoEnvio,datostarifas.cargoMinimo,".
							 "datostarifas.municipioDestino,cconsignatarios.municipio,".
							 "datostarifas.estadoDestino,cconsignatarios.estado ".
							 "FROM cconsignatarios,cguias ".
							 "INNER JOIN (".
										 "SELECT cdetalletarifa.tipoEnvio,cdetalletarifa.cargoMinimo,".
										 "ctarifascorresponsales.estadoDestino,ctarifascorresponsales.municipioDestino,".								 
										 "cdetalletarifa.primerRango,cdetalletarifa.segundoRango,".								 
										 "cdetalletarifa.Tercerrango,cdetalletarifa.cuartoRango,".								 								 
										 "ctarifascorresponsales.primerRango AS rango1,ctarifascorresponsales.segundoRango AS rango2,".								 
										 "ctarifascorresponsales.tercerRango AS rango3,ctarifascorresponsales.cuartoRango AS rango4 ".
										 "FROM cdetalletarifa ".
										 "INNER JOIN ctarifascorresponsales ".
										 "ON ctarifascorresponsales.cveCorresponsal=cdetalletarifa.cveCorresponsal ".
										 "AND ctarifascorresponsales.estadoOrigen=9 ".
										 "AND ctarifascorresponsales.municipioOrigen=17 ".
										 ") AS datostarifas ".
							"ON cguias.tipoEnvio=datostarifas.tipoEnvio ".
							"WHERE cguias.cveGuia='".$cveGuia."' ".
							"AND datostarifas.municipioDestino=cconsignatarios.municipio ".
							"AND datostarifas.estadoDestino=cconsignatarios.estado ".
							"AND cguias.cveConsignatario=cconsignatarios.cveConsignatario ".
							"LIMIT 1;"; 

					$datosCorresponsal = $bd->Execute($sqlConsulta);

					if(count($datosCorresponsal)==0)  //Si no hay tarifa registrada,el costo serÃ¡ de 0
					{
						$costoEntrega=0;
					}
					else  							 //Realizar el cálculo
					{
						foreach($datosCorresponsal as $datosC)
						{	
							$rango1= valorRango($datosC['rango1']);
							$rango2= valorRango($datosC['rango2']);
							$rango3= valorRango($datosC['rango3']);
								
							if($peso<= $rango1)
							{
								$cargo=$datosC['primerRango'];
							}
							if($peso > $rango1 AND $peso<= $rango2){
								$cargo=$datosC['segundoRango'];
							}
							if($peso> $rango2 AND $peso<= $rango3){
								$cargo=$datosC['Tercerrango'];
							}
							if($peso>= $rango3 ){
								$cargo=$datosC['cuartoRango'];
							}
							$costoEntrega=$peso*$cargo;
							$cargoMinimo=$datosC['cargoMinimo'];
							if($costoEntrega<$cargoMinimo)
								$costoEntrega=$cargoMinimo;
						}
					}
				}
				
				
				//Aquí­ se muestran los datos de la guía	
				$a=$h%2;
				if($guiaBase!=$datoGuia['cveLineaArea'] && $con!=0)
				{
						$h++;
?>
						<tr <?php if($a==1) echo "bgcolor='#C8CDFF'"?> >
							<td colspan="28" height="20"></td>
						</tr>
						
<?php 			} 
				$guiaBase=$datoGuia['cveLineaArea'];	
				$a=$h%2;
				$con++;
				$h++;
				$totalCobrar=$total-$costoEntrega-$ctoGuiaA;

				if($datoGuia['observacion']=='')
					$observaciones='&nbsp;';
				else
					$observaciones=$datoGuia['observacion'];
				
				//Sumatorias Generales
				$sumaPiezas        += $piezas;
				$sumaPeso          += $peso;
				$sumaIva           += $iva;
				$sumaSubtotal      += $subtotal;
				$sumaRetencion     += $retIva;
				$sumaGuiaAerea     += $ctoGuiaA;					
				
				$sumaTotalACobrar  += $total;
				$sumaTotalAPagar   += $costoEntrega;
				$sumaTotalUtilidad += $totalCobrar;
				$sumaTotalAPagarFIN+= $costoEntrega+$ctoGuiaA;		
				
				$patron = "/\[br\]/";
				$observaciones=preg_replace($patron,' ',$observaciones);
				
?>
				 <tr <?php if($a==1) echo "bgcolor='#C8CDFF'"?> >     
					<td align="center"><?php echo $datoGuia['recepcionCYE']; ?></td>     
					<td><?php echo $datoGuia['cveGuia']; ?></td>                
					<td><?php echo $datoGuia['nombreCli']; ?></td>
					<td align="center"><?php echo $datoGuia['estacion']; ?></td>               
					<td align="center"><?php echo $datoGuia['tipoEnvio']; ?></td>                             
					<td align="center"><?php echo $valorDec; ?></td>
					<td align="center"><?php echo $piezas; ?></td>
					<td align="center"><?php echo $peso; ?></td>  
					<td align="center"><?php echo "$".number_format($iva,2); ?></td>                    
					<td align="center"><?php echo "$".number_format($subtotal,2); ?></td>
					<td align="center"><?php echo "$".number_format($retIva,2); ?></td>
					<td align="center"><?php echo "$".number_format($total,2); ?></td>
                    <td align="center"><?php echo "$".number_format($costoEntrega,2); ?></td>
					<td align="center"><?php echo "$".number_format($ctoGuiaA,2); ?></td>                    
					<td align="center"><?php echo "$".number_format($totalCobrar,2); ?></td>
                    <td><?php echo $observaciones;?></td>
				</tr>
<?php       }  ?>
				<tr>
                	<td colspan="6" class="sinBorde"></td>
                    <td align="center"><?php echo $sumaPiezas;?></td>
                    <td align="center"><?php echo $sumaPeso;?></td>
                    <td align="center"><?php echo "$".number_format($sumaIva,2);?></td>                    
                    <td align="center"><?php echo "$".number_format($sumaSubtotal,2);?></td>
                    <td align="center"><?php echo "$".number_format($sumaRetencion,2);?></td>
                    <td align="center"><?php echo "$".number_format($sumaTotalACobrar,2);?></td>
                    <td align="center"><?php echo "$".number_format($sumaTotalAPagar,2);?></td>
                    <td align="center"><?php echo "$".number_format($sumaGuiaAerea,2);?></td> 
                    <td align="center"><?php echo "$".number_format($sumaTotalUtilidad,2);?></td>
                </tr>
            </table>
            <br />
            <br />
            <!--Se mostrarán los calculos -->
            <?php
            	//Se realizan los calculos de las tablas
				
					//Fechas
				list($anyoD,$mesD,$diaD) = explode("-",$fechaPerDesde);
				list($anyoH,$mesH,$diaH) = explode("-",$fechaPerHasta);
				
					//Obtenemos el número de días que tiene el mes
				$diasMes = date('t',mktime(0,0,0,$mesD,1,$anyoD));
				
				//Días Efectivos
				$diasEfectivos=$diaH-$diaD+1;
				
				//Días por Transcurrir
				$diasporTranscurrir=$diasMes-$diaH;

					//Real				
				//Promedio del Periodo
				$piezasDiaPPR        = redondeaNum($sumaPiezas/$diasEfectivos);
				$volumenDiaPPR       = redondeaNum($sumaPeso/$diasEfectivos);
				$ivaDiaPPR           = redondeaNum($sumaIva/$diasEfectivos);				
				$subtotalDiaPPR      = redondeaNum($sumaSubtotal/$diasEfectivos);
				$retencionDiaPPR     = redondeaNum($sumaRetencion/$diasEfectivos);
				$totalCobradoDiaPPR  = redondeaNum($sumaTotalACobrar/$diasEfectivos);
				$totalPagadoDiaPPR   = redondeaNum($sumaTotalAPagarFIN/$diasEfectivos);				
				$totalUtilidadDiaPPR = redondeaNum($sumaTotalUtilidad/$diasEfectivos);
				
				//Promedio Piezas por Día
				$piezasDiaPPDR        = $piezasDiaPPR;
				$totalCobradoDiaPPDR  = redondeaNum(($sumaTotalACobrar/$diasEfectivos)/$piezasDiaPPDR);
				$totalPagadoDiaPPDR   = redondeaNum(($sumaTotalAPagarFIN/$diasEfectivos)/$piezasDiaPPDR);				
				$totalUtilidadDiaPPDR = redondeaNum(($sumaTotalUtilidad/$diasEfectivos)/$piezasDiaPPDR);
				
				//Promedio Volúmen por Día
				$volumenDiaPPVR       = $volumenDiaPPR;
				$totalCobradoDiaPPVR  = redondeaNum(($sumaTotalACobrar/$diasEfectivos)/$volumenDiaPPVR);
				$totalPagadoDiaPPVR   = redondeaNum(($sumaTotalAPagarFIN/$diasEfectivos)/$volumenDiaPPVR);				
				$totalUtilidadDiaPPVR = redondeaNum(($sumaTotalUtilidad/$diasEfectivos)/$volumenDiaPPVR);
				
				
					//Proyectado
				if($diasporTranscurrir!=0)
				{ 
					//Promedio del Periodo
					$piezasDiaPPP        = $piezasDiaPPR*$diasporTranscurrir;
					$volumenDiaPPP       = $volumenDiaPPR*$diasporTranscurrir;
					$ivaDiaPPP           = redondeaNum($ivaDiaPPR*$diasporTranscurrir);					
					$subtotalDiaPPP      = redondeaNum($subtotalDiaPPR*$diasporTranscurrir);
					$retencionDiaPPP     = redondeaNum($retencionDiaPPR*$diasporTranscurrir);
					$totalCobradoDiaPPP  = redondeaNum($totalCobradoDiaPPR*$diasporTranscurrir);
					$totalPagadoDiaPPP   = redondeaNum($totalPagadoDiaPPR*$diasporTranscurrir);				
					$totalUtilidadDiaPPP = redondeaNum($totalUtilidadDiaPPR*$diasporTranscurrir);
				} 
				
				
					//Resúmen Mensual
				//Promedio del Periodo
				$piezasDiaPPT        = redondeaNum($piezasDiaPPR*$diasMes);
				$volumenDiaPPT       = redondeaNum($volumenDiaPPR*$diasMes);
				$ivaDiaPPT           = redondeaNum($ivaDiaPPR*$diasMes);								
				$subtotalDiaPPT      = redondeaNum($subtotalDiaPPR*$diasMes);
				$retencionDiaPPT     = redondeaNum($retencionDiaPPR*$diasMes);
				$totalCobradoDiaPPT  = redondeaNum($totalCobradoDiaPPR*$diasMes);
				$totalPagadoDiaPPT   = redondeaNum($totalPagadoDiaPPR*$diasMes);
				$totalUtilidadDiaPPT = redondeaNum($totalUtilidadDiaPPR*$diasMes);
				
				//Promedio Piezas por Día
				$piezasDiaPPDT        = $piezasDiaPPR;		
				$totalUtilidadDiaPPDT = redondeaNum($totalUtilidadDiaPPT/$piezasDiaPPDT);
				
				//Promedio VolÃºmen por Día
				$volumenDiaPPVT       = $volumenDiaPPR;
				$totalUtilidadDiaPPVT = redondeaNum($totalUtilidadDiaPPT/$volumenDiaPPVT);	
				
				
					//Cambio de Formato
				//Real
				$piezasDiaPPR        = number_format($piezasDiaPPR,2);
				$volumenDiaPPR       = number_format($volumenDiaPPR,2);
				$ivaDiaPPR           = number_format($ivaDiaPPR,2);					
				$subtotalDiaPPR      = number_format($subtotalDiaPPR,2);
				$retencionDiaPPR     = number_format($retencionDiaPPR,2);
				$totalCobradoDiaPPR  = number_format($totalCobradoDiaPPR,2);
				$totalPagadoDiaPPR   = number_format($totalPagadoDiaPPR,2);
				$totalUtilidadDiaPPR = number_format($totalUtilidadDiaPPR,2);
				
				$piezasDiaPPDR        = number_format($piezasDiaPPDR,2);
				$totalCobradoDiaPPDR  = number_format($totalCobradoDiaPPDR,2);
				$totalPagadoDiaPPDR   = number_format($totalPagadoDiaPPDR,2);
				$totalUtilidadDiaPPDR = number_format($totalUtilidadDiaPPDR,2);
				
				$volumenDiaPPVR       = number_format($volumenDiaPPVR,2);
				$totalCobradoDiaPPVR  = number_format($totalCobradoDiaPPVR,2);
				$totalPagadoDiaPPVR   = number_format($totalPagadoDiaPPVR,2);
				$totalUtilidadDiaPPVR = number_format($totalUtilidadDiaPPVR,2);
				
				//Proyectado
				$piezasDiaPPP        = number_format($piezasDiaPPP,2);
				$volumenDiaPPP       = number_format($volumenDiaPPP,2);
				$ivaDiaPPP           = number_format($ivaDiaPPP,2);				
				$subtotalDiaPPP      = number_format($subtotalDiaPPP,2);
				$retencionDiaPPP     = number_format($retencionDiaPPP,2);
				$totalCobradoDiaPPP  = number_format($totalCobradoDiaPPP,2);
				$totalPagadoDiaPPP   = number_format($totalPagadoDiaPPP,2);
				$totalUtilidadDiaPPP = number_format($totalUtilidadDiaPPP,2);
					
				//Mensual
				$piezasDiaPPT        = number_format($piezasDiaPPT,2);
				$volumenDiaPPT       = number_format($volumenDiaPPT,2);
				$ivaDiaPPT           = number_format($ivaDiaPPT,2);										
				$subtotalDiaPPT      = number_format($subtotalDiaPPT,2);
				$retencionDiaPPT     = number_format($retencionDiaPPT,2);
				$totalCobradoDiaPPT  = number_format($totalCobradoDiaPPT,2);
				$totalPagadoDiaPPT   = number_format($totalPagadoDiaPPT,2);
				$totalUtilidadDiaPPT = number_format($totalUtilidadDiaPPT,2);

				$piezasDiaPPDT        = number_format($piezasDiaPPDT,2);
				$totalUtilidadDiaPPDT = number_format($totalUtilidadDiaPPDT,2);
				
				$volumenDiaPPVT       = number_format($volumenDiaPPVT,2);
				$totalUtilidadDiaPPVT = number_format($totalUtilidadDiaPPVT,2);
								
			?>
            <table>
                <tr>
                    <td>D&Iacute;AS DEL PERIODO</td>
                    <td><?php echo $diasMes; ?><td>
                </tr>
                <tr>
                    <td>D&Iacute;AS EFECTIVOS</td>
                    <td><?php echo $diasEfectivos; ?><td>
                </tr>
                <tr>
                    <td>D&Iacute;AS POR TRANSCURRIR</td>
                    <td><?php echo $diasporTranscurrir; ?><td>
                </tr>                                
            </table>
            <br />            
            <!--Lo real-->                                                             
            <table width="1170">
            	<tr>
                	<td class="encabezado">&nbsp;&nbsp;REAL</td>
                </tr>
            </table>
            <table class="tblCaclulos" border="1">
                <tr>
                    <td width="150" class="sinBorde"></td>                
                    <td width="115" class="sinBorde"></td>
                    <td width="110">PROMEDIO DEL PERIODO</td>            
                    <td width="150" class="sinBorde"></td>
                    <td width="115" class="sinBorde"></td>
                    <td width="110">PROMEDIO POR D&Iacute;A</td>            
                    <td width="150" class="sinBorde"></td>                    
                    <td width="115" class="sinBorde"></td>
                    <td width="110">PROMEDIO PESO/VOLUMEN POR D&Iacute;A</td>                    
                </tr>      
                <tr>				
                    <td class="sinBorde"></td>                                
                    <td align="right">D&Iacute;AS DEL PERIODO</td>
                    <td><?php echo $diasEfectivos; ?></td>
                    <td class="sinBorde"></td>
                    <td align="right">PIEZAS POR D&Iacute;A</td>                           
                    <td><?php echo $piezasDiaPPDR; ?></td>
                    <td class="sinBorde"></td>                   
                    <td align="right">PESO/VOL&Uacute;MEN</td>
                    <td><?php echo $volumenDiaPPVR; ?></td>
                </tr>
                <tr>
                    <td class="sinBorde"></td>                                                            
                    <td align="right">PIEZAS POR D&Iacute;A</td>
                    <td><?php echo $piezasDiaPPR; ?></td>
                    <td class="sinBorde"></td>                  
                    <td align="right">TOTAL COBRADO</td>       
                    <td><?php echo $totalCobradoDiaPPDR; ?></td>
                    <td class="sinBorde"></td>                  
                    <td align="right">TOTAL COBRADO</td>       
                    <td><?php echo $totalCobradoDiaPPVR; ?></td>
                </tr>
                <tr>
                    <td class="sinBorde"></td>                                            
                    <td align="right">PESO/VOLUMEN</td>
                    <td><?php echo $volumenDiaPPR; ?></td>
                    <td class="sinBorde"></td>                 
                    <td align="right">TOTAL PAGADO</td>       
                    <td><?php echo $totalPagadoDiaPPDR; ?></td>
                    <td class="sinBorde"></td>                  
                    <td align="right">TOTAL PAGADO</td>       
                    <td><?php echo $totalPagadoDiaPPVR; ?></td>
                </tr>
                <tr>
                    <td class="sinBorde"></td>                                                            
                    <td align="right">IVA</td>
                    <td><?php echo $ivaDiaPPR; ?></td>
                    <td class="sinBorde"></td>                   
                    <td align="right" style="font-weight:bold">UTILIDAD</td>
                    <?php if($totalUtilidadDiaPPDR<0){?>
                    	<td style="font-weight:bold;color:#FB0000"><?php echo $totalUtilidadDiaPPDR; ?></td>
                    <?php }else {?>
                        <td style="font-weight:bold"><?php echo $totalUtilidadDiaPPDR; ?></td>
                    <?php }?>
                    <td class="sinBorde"></td>                 
                    <td align="right" style="font-weight:bold">UTILIDAD</td>
                    <?php if($totalUtilidadDiaPPVR<0){?>
                    	<td style="font-weight:bold;color:#FB0000"><?php echo $totalUtilidadDiaPPVR; ?></td>
                    <?php }else {?>
                        <td style="font-weight:bold"><?php echo $totalUtilidadDiaPPVR; ?></td>
                    <?php }?>                    
                </tr>                
                <tr>
                    <td class="sinBorde"></td>                                                            
                    <td align="right">SUBTOTAL</td>
                    <td><?php echo $subtotalDiaPPR; ?></td>                    
                </tr>
                <tr>
                    <td class="sinBorde"></td>                                                             
                    <td align="right">RETENCI&Oacute;N</td>  
                    <td><?php echo $retencionDiaPPR; ?></td>
                </tr>
                <tr>
                    <td class="sinBorde"></td>                                                               
                    <td align="right">TOTAl COBRADO</td>
                    <td><?php echo $totalCobradoDiaPPR; ?></td>              
                </tr> 
                <tr>
                    <td class="sinBorde"></td>                                                               
                    <td align="right">TOTAL PAGADO</td>
                    <td><?php echo $totalPagadoDiaPPR; ?></td> 
                </tr> 
                <tr>
                    <td class="sinBorde"></td>                                                               
                    <td align="right" style="font-weight:bold">UTILIDAD</td>
                    <?php if($totalUtilidadDiaPPR<0){?>
                    	<td style="font-weight:bold;color:#FB0000"><?php echo $totalUtilidadDiaPPR; ?></td>
                    <?php }else {?>
                        <td style="font-weight:bold"><?php echo $totalUtilidadDiaPPR; ?></td>
                    <?php }?>   
                </tr>  
            </table>
             <!--Ahora lo proyectado--> 
            <?php if($diasporTranscurrir!=0){ ?>
                    <br />                                                
                    <table width="1170">
                        <tr>
                            <td class="encabezado">&nbsp;&nbsp;PROYECTADO</td>
                        </tr>
                    </table>
                    <table class="tblCaclulos" border="1">
                        <tr>
                            <td width="150" class="sinBorde"></td>                
                            <td width="115" class="sinBorde"></td>
                            <td width="110" class="sinBorde">&nbsp;</td>                             
                        </tr>      
                        <tr>
                            <td class="sinBorde"></td>                                
                            <td align="right">D&Iacute;AS POR TRANSCURRIR</td>
                            <td><?php echo $diasporTranscurrir; ?></td>
                        </tr>
                        <tr>
                            <td colspan="3" class="sinBorde">&nbsp;</td>                                            
                        </tr>                 
                        <tr>
                            <td class="sinBorde"></td>                                                            
                            <td align="right">PIEZAS A VENDER</td>
                            <td><?php echo $piezasDiaPPP; ?></td>
                        </tr>
                        <tr>
                            <td class="sinBorde"></td>                                            
                            <td align="right">PESO/VOLUMEN A VENDER</td>
                            <td><?php echo $volumenDiaPPP; ?></td>
                        </tr>        
                        <tr>
                            <td class="sinBorde"></td>                                                            
                            <td align="right">IVA</td>
                            <td><?php echo $ivaDiaPPP; ?></td>
                        </tr>                                                         
                        <tr>
                            <td class="sinBorde"></td>                                                            
                            <td align="right">SUBTOTAL</td>
                            <td><?php echo $subtotalDiaPPP; ?></td>
                        </tr>
                        <tr>
                            <td class="sinBorde"></td>                                                             
                            <td align="right">RETENCI&Oacute;N</td>  
                            <td><?php echo $retencionDiaPPP; ?></td>
                        </tr>
                        <tr>
                            <td class="sinBorde"></td>                                                               
                            <td align="right">TOTAL A COBRAR</td>
                            <td><?php echo $totalCobradoDiaPPP; ?></td>
                        </tr> 
                        <tr>
                            <td class="sinBorde"></td>                                                               
                            <td align="right">TOTAL A PAGAR</td>
                            <td><?php echo $totalPagadoDiaPPP; ?></td>   
                        </tr> 
                        <tr>
                            <td class="sinBorde"></td>                                                               
                            <td align="right" style="font-weight:bold">UTILIDAD</td>
                            <?php if($totalUtilidadDiaPPP<0){?>
                                <td style="font-weight:bold;color:#FB0000"><?php echo $totalUtilidadDiaPPP; ?></td>
                            <?php }else {?>
                                <td style="font-weight:bold"><?php echo $totalUtilidadDiaPPP; ?></td>
                            <?php }?>                     
                        </tr>  
                    </table>                     
            <?php } ?>    
            <!--Ahora resúmen del mes--> 
            <br />                             
			<table width="1170">
            	<tr>
                	<td class="encabezado">&nbsp;&nbsp;RES&Uacute;MEN DEL MES</td>
                </tr>
            </table>
            <table class="tblCaclulos" border="1">
                <tr>
                    <td width="150" class="sinBorde"></td>                
                    <td width="115" class="sinBorde"></td>
                    <td width="110" class="sinBorde"></td>            
                    <td width="150" class="sinBorde"></td>
                    <td width="115" class="sinBorde"></td>
                    <td width="110">UTILIDAD POR PIEZAS</td>            
                    <td width="150" class="sinBorde"></td>                    
                    <td width="115" class="sinBorde"></td>
                    <td width="110">UTILIDAD POR PESO/VOLUMEN</td>                    
                </tr>      
                <tr>				
                    <td class="sinBorde"></td>                                
                    <td align="right">D&Iacute;AS DEL PERIODO</td>
                    <td><?php echo $diasMes; ?></td>
                    <td class="sinBorde"></td>
                    <td align="right">PIEZAS POR D&Iacute;A</td>                           
                    <td><?php echo $piezasDiaPPDR; ?></td>
                    <td class="sinBorde"></td>                   
                    <td align="right">PESO/VOL&Uacute;MEN</td>
                    <td><?php echo $volumenDiaPPVR; ?></td>
                </tr>
                <tr>
                    <td class="sinBorde"></td>                                                            
                    <td align="right">PIEZAS</td>
                    <td><?php echo $piezasDiaPPT; ?></td>
                    <td class="sinBorde"></td>                  
                    <td align="right" style="font-weight:bold">UTILIDAD</td>
                    <?php if($totalUtilidadDiaPPDT<0){?>
                    	<td style="font-weight:bold;color:#FB0000"><?php echo $totalUtilidadDiaPPDT; ?></td>
                    <?php }else {?>
                        <td style="font-weight:bold"><?php echo $totalUtilidadDiaPPDT; ?></td>
                    <?php }?>
                    <td class="sinBorde"></td>                 
                    <td align="right" style="font-weight:bold">UTILIDAD</td>
                    <?php if($totalUtilidadDiaPPVT<0){?>
                    	<td style="font-weight:bold;color:#FB0000"><?php echo $totalUtilidadDiaPPVT; ?></td>
                    <?php }else {?>
                        <td style="font-weight:bold"><?php echo $totalUtilidadDiaPPVT; ?></td>
                    <?php }?>  
                </tr>
                <tr>
                    <td class="sinBorde"></td>                                            
                    <td align="right">PESO/VOLUMEN</td>
                    <td><?php echo $volumenDiaPPT; ?></td> 
                </tr>
                <tr>
                    <td class="sinBorde"></td>                                                            
                    <td align="right">IVA</td>
                    <td><?php echo $ivaDiaPPT; ?></td>                  
                </tr>                 
                <tr>
                    <td class="sinBorde"></td>                                                            
                    <td align="right">SUBTOTAL</td>
                    <td><?php echo $subtotalDiaPPT; ?></td>                  
                </tr>
                <tr>
                    <td class="sinBorde"></td>                                                             
                    <td align="right">RETENCI&Oacute;N</td>  
                    <td><?php echo $retencionDiaPPT; ?></td>
                </tr>
                <tr>
                    <td class="sinBorde"></td>                                                               
                    <td align="right">TOTAL COBRADO</td>
                    <td><?php echo $totalCobradoDiaPPT; ?></td>              
                </tr> 
                <tr>
                    <td class="sinBorde"></td>                                                               
                    <td align="right">TOTAL PAGADO</td>
                    <td><?php echo $totalPagadoDiaPPT; ?></td> 
                </tr> 
                <tr>
                    <td class="sinBorde"></td>                                                               
                    <td align="right" style="font-weight:bold">UTILIDAD</td>
                    <?php if($totalUtilidadDiaPPT<0){?>
                    	<td style="font-weight:bold;color:#FB0000"><?php echo $totalUtilidadDiaPPT; ?></td>
                    <?php }else {?>
                        <td style="font-weight:bold"><?php echo $totalUtilidadDiaPPT; ?></td>
                    <?php }?>   
                </tr>  
            </table>
<?php  
	} 

	$_SESSION['sqlFinal']="";
	$_SESSION['sqlFinal']=$sqlFinal;
	
	$_SESSION['sqlFinal2']="";
	$_SESSION['sqlFinal2']=$sqlFinal;

    function convFecha($fecha)
	{
		list($dia,$mes,$anyo)=explode("/",$fecha);
		$fechafinal=$anyo."-".$mes."-".$dia;
		return $fechafinal;
	}	
	
	function valorRango($valorRango){
		$separar = explode(' ',$valorRango);
		return $separar[2];
	}
	
	function redondeaNum($numero){
		$numero=$numero+0; //Para convertir la cadena a nÃºmero
        $valor = (is_float($numero)) ? (round($numero * 100)/100) : $numero;
        return $valor;
    }
	
?>   
<script language="javascript" type="text/javascript">
	function imprimir(fechasDesde,fechaHasta,cliente)   //Función para abrir reporte	
	{	
		//Se abre el estado de cuenta de las guías (como el anexo)
		var urlFin="scripts/reporteUtilidad.php?desde="+fechasDesde+"&hasta="+fechaHasta+"&cliente="+cliente;
		var win = new Window({className: "mac_os_x", title: "Reporte de Utilidad Bruta", top:65, left:690, width:600, height:450, 
				  url: urlFin, showEffectOptions: {duration:.2}}); 
		win.show();
		
		//Se toman los datos de las proyecciones
		var diasMes='<?php echo $diasMes; ?>';
		var diasEfectivos='<?php echo $diasEfectivos; ?>';
		var diasporTranscurrir='<?php echo $diasporTranscurrir; ?>';
		
		var piezasDiaPPR='<?php echo $piezasDiaPPR; ?>';
		var volumenDiaPPR='<?php echo $volumenDiaPPR; ?>';
		var ivaDiaPPR='<?php echo $ivaDiaPPR; ?>';
		var subtotalDiaPPR='<?php echo $subtotalDiaPPR; ?>';
		var retencionDiaPPR='<?php echo $retencionDiaPPR; ?>';
		var totalCobradoDiaPPR='<?php echo $totalCobradoDiaPPR; ?>';
		var totalPagadoDiaPPR='<?php echo $totalPagadoDiaPPR; ?>';
		var totalUtilidadDiaPPR='<?php echo $totalUtilidadDiaPPR; ?>';
		
		var piezasDiaPPDR='<?php echo $piezasDiaPPDR; ?>';
		var totalCobradoDiaPPDR='<?php echo $totalCobradoDiaPPDR; ?>';
		var totalPagadoDiaPPDR='<?php echo $totalPagadoDiaPPDR; ?>';
		var totalUtilidadDiaPPDR='<?php echo $totalUtilidadDiaPPDR; ?>';
		
		var volumenDiaPPVR='<?php echo $volumenDiaPPVR; ?>';
		var totalCobradoDiaPPVR='<?php echo $totalCobradoDiaPPVR; ?>';
		var totalPagadoDiaPPVR='<?php echo $totalPagadoDiaPPVR; ?>';
		var totalUtilidadDiaPPVR='<?php echo $totalUtilidadDiaPPVR; ?>';
		
		var piezasDiaPPP      ='<?php echo $piezasDiaPPP      ; ?>';
		var volumenDiaPPP='<?php echo $volumenDiaPPP; ?>';
		var ivaDiaPPP='<?php echo $ivaDiaPPP; ?>';		
		var subtotalDiaPPP='<?php echo $subtotalDiaPPP; ?>';
		var retencionDiaPPP='<?php echo $retencionDiaPPP; ?>';
		var totalCobradoDiaPPP='<?php echo $totalCobradoDiaPPP; ?>';
		var totalPagadoDiaPPP='<?php echo $totalPagadoDiaPPP; ?>';
		var totalUtilidadDiaPPP='<?php echo $totalUtilidadDiaPPP; ?>';
		
		var piezasDiaPPT='<?php echo $piezasDiaPPT; ?>';
		var volumenDiaPPT='<?php echo $volumenDiaPPT; ?>';
		var ivaDiaPPT='<?php echo $ivaDiaPPT; ?>';		
		var subtotalDiaPPT='<?php echo $subtotalDiaPPT; ?>';
		var retencionDiaPPT='<?php echo $retencionDiaPPT; ?>';
		var totalCobradoDiaPPT='<?php echo $totalCobradoDiaPPT; ?>';
		var totalPagadoDiaPPT='<?php echo $totalPagadoDiaPPT; ?>';
		var totalUtilidadDiaPPT='<?php echo $totalUtilidadDiaPPT; ?>';
		
		var piezasDiaPPDT='<?php echo $piezasDiaPPDT; ?>';
		var totalUtilidadDiaPPDT='<?php echo $totalUtilidadDiaPPDT; ?>';
		
		var volumenDiaPPVT='<?php echo $volumenDiaPPVT; ?>';
		var totalUtilidadDiaPPVT='<?php echo $totalUtilidadDiaPPVT; ?>';

		valores="&vpiezasDiaPPR="+piezasDiaPPR+"&vvolumenDiaPPR="+volumenDiaPPR+"&vsubtotalDiaPPR="+subtotalDiaPPR+
				"&vretencionDiaPPR="+retencionDiaPPR+"&vtotalCobradoDiaPPR="+totalCobradoDiaPPR+"&vtotalPagadoDiaPPR="+totalPagadoDiaPPR+
				"&vtotalUtilidadDiaPPR="+totalUtilidadDiaPPR+"&vpiezasDiaPPDR="+piezasDiaPPDR+"&vtotalCobradoDiaPPDR="+totalCobradoDiaPPDR+
				"&vtotalPagadoDiaPPDR="+totalPagadoDiaPPDR+"&vtotalUtilidadDiaPPDR="+totalUtilidadDiaPPDR+"&vvolumenDiaPPVR="+volumenDiaPPVR+
			    "&vtotalCobradoDiaPPVR="+totalCobradoDiaPPVR+"&vtotalPagadoDiaPPVR="+totalPagadoDiaPPVR+"&vtotalUtilidadDiaPPVR="+totalUtilidadDiaPPVR+
				"&vpiezasDiaPPP="+piezasDiaPPP +"&vvolumenDiaPPP="+volumenDiaPPP  +"&vsubtotalDiaPPP="+subtotalDiaPPP+"&vretencionDiaPPP="+retencionDiaPPP+
				"&vtotalCobradoDiaPPP="+totalCobradoDiaPPP+"&vtotalPagadoDiaPPP="+totalPagadoDiaPPP+"&vtotalUtilidadDiaPPP="+totalUtilidadDiaPPP+
				"&vpiezasDiaPPT="+piezasDiaPPT +"&vvolumenDiaPPT="+volumenDiaPPT +"&vsubtotalDiaPPT="+subtotalDiaPPT+"&vretencionDiaPPT="+retencionDiaPPT+
				"&vtotalCobradoDiaPPT="+totalCobradoDiaPPT+"&vtotalPagadoDiaPPT="+totalPagadoDiaPPT+"&vtotalUtilidadDiaPPT="+totalUtilidadDiaPPT+
				"&vpiezasDiaPPDT="+piezasDiaPPDT+"&vtotalUtilidadDiaPPDT="+totalUtilidadDiaPPDT+"&vvolumenDiaPPVT="+ volumenDiaPPVT+
				"&vtotalUtilidadDiaPPVT="+totalUtilidadDiaPPVT+"&vdiasMes="+diasMes+"&vdiasEfectivos="+diasEfectivos+
				"&vdiasporTranscurrir="+diasporTranscurrir;
				
		//El iva se agregó después; se pone a parte para no alterar variables
	    valores+="&vivaDiaPPR="+ivaDiaPPR+"&vivaDiaPPP="+ivaDiaPPP+"&vivaDiaPPT="+ivaDiaPPT;

		var urlFin="reporteUt/creaReporte.php?desde="+fechasDesde+"&hasta="+fechaHasta+"&cliente="+cliente;
		$Ajax(urlFin, {metodo: $metodo.POST, onfinish: function (archivo)
													   { 
													   		var urlFin="reporteUt/totalesUtilidad.php?arc="+archivo;
															var win = new Window({className: "mac_os_x", title: "Proyecci\u00F3n de Utilidad Bruta", top:65, 
																				 left:40, width:600, height:450, url: urlFin, showEffectOptions: {duration:.2} });  
															win.show();
													   }, parametros: valores, avisoCargando:"loading"});
		
	}
</script>    
<!--Son sólo para usar la clase de ajax-->
<span id="loading" style="display: none">Por favor espere...</span>
<span id="aviso" style="display: none">Cargando...</span>
<span id="status"></span>
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    