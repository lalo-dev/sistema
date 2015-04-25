<?php

	include("scripts/bd.php");	
	session_start();
	
	$sqlFinal=$_SESSION['sqlFinal'];
	$_SESSION['sqlFinal']='';
	$fechaPerDesde=$_GET['desde'];
	$fechaPerHasta=$_GET['hasta'];	
	
	function convFecha($fecha)
	{
		$fechafinal="";
		
		if($fecha!=""){
			list($dia,$mes,$anyo)=explode("-",$fecha);
			$fechafinal=$anyo."/".$mes."/".$dia;
		}
		if($fechafinal=="00/00/0000")
			$fechafinal="";
		return $fechafinal;
	}
	
	function valorRango($valorRango){
		$separar = explode(' ',$valorRango);
		return $separar[2];
	}
	
	function redondeaNum($numero){
		$numero=$numero+0; //Para convertir la cadena a número		
        $valor = (is_float($numero)) ? (round($numero * 100)/100) : $numero;
        return $valor;
    }
	
	header('Content-type: application/vnd.ms-excel');
	header("Content-Disposition: attachment; filename=\"" . $filename . "\"" );
	header("Content-Disposition: attachment; filename=Reporte Guias.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
?>
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
		font-size:12px;
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
	 	$meses = array("","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
		date_default_timezone_set("America/Mexico_City");
	  	$fecha=date('d/m/Y');
		list($dia,$mes,$anyo)=explode("/",$fecha);
		$fechaFinal=$dia."/".$meses[$mes+0]."/".$anyo;
		
	?>    
        <table>
            <tr height="80">
                <td></td>
                <td style="vertical-align:bottom;font-size:24px;color:#009" colspan="6" align="center">CARGA Y EXPRESS</td>
            </tr>
            <tr height="20">
                <td></td>        
                <td style="vertical-align:middle;font-weight:bold;font-size:14px;" colspan="6" align="center">Fecha de Elaboración: <?php echo $fechaFinal;?></td>
            </tr>               
        </table> 
<?php     
	   if($sqlFinal==''){
	   ?>
         <table bgcolor='#C8CDFF' width="579">
			<tr><td></td></tr>
            <tr>
                <td colspan="13" height="20" style="color:#666;font-weight:bold">Ya ha descargado el archivo</td>
            </tr>
         </table>
<?php 
		exit();
	  }
	  //Obtenemos los datos de la Guía		
	  $datos = $bd->Execute($sqlFinal);
		
       if(count($datos)==0) 
	   { ?>
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
						//Se les da formato a los números
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

					if(count($datosCorresponsal)==0)  //Si no hay tarifa registrada,el costo será de 0
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
				
				
				//Aquí se muestran los datos de la guía	
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
					<td align="center"><?php echo convFecha($datoGuia['recepcionCYE']); ?></td>     
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
<?php       } ?>
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
				
				//Promedio Volúmen por Día
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
                	<td class="encabezado" colspan="9">&nbsp;&nbsp;REAL</td>
                </tr>
                <tr></tr>
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
                            <td class="encabezado" colspan="9">&nbsp;&nbsp;PROYECTADO</td>
                        </tr>
                        <tr></tr>
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
                	<td class="encabezado" colspan="9">&nbsp;&nbsp;RES&Uacute;MEN DEL MES</td>
                </tr>
                <tr></tr>
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
<?php }?>

    
    
