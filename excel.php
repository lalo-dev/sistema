<?php

	include("scripts/bd.php");	
	session_start();
	$sqlFinal=$_SESSION['sqlFinal'];
	$_SESSION['sqlFinal']='';
	$tipo=$_GET['tipo'];
	
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
		font-family:Verdana, Geneva, sans-serif;
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

	if($tipo==0)
	{
	?>
		<table width="2796" border="1"> 
			<tr id="Encabezados">
				<th width="120">FECHA DE ENV&Iacute;O</th>
				<th width="76">L&iacute;NEA A&Eacute;REA</th>
				<th width="75">No. DE GU&iacute;A A&Eacute;REA</th>
                <th width="67">GU&iacute;A CYE</th>
				<th width="340">CLIENTE</th>
				<th width="77">DESTINO</th>
				<th width="72">PIEZAS</th>
				<th width="72">KILOS</th>      
				<th width="466">DESTINATARIO</th>
                <th width="80">FECHA DE ENTREGA</th>
				<th width="331">NOMBRE DE QUIEN RECIBE</th>                
                <th width="120">FECHA DE RELACI&Oacute;N</th>
                <th width="298">OBSERVACIONES</th> 
    			<th width="120">F. DE LLEGADA DEL ACUSE</th>
				<th width="120">FECHA DE VUELO</th>
				<th width="362">N&Uacute;MERO DE FACTURA</th>   
			</tr>
            
<?php 
			$i=0;
			$con=0;
			$guiaBase="";
			$guias = $bd->Execute($sqlFinal);
			foreach($guias as $datGuias)
			{
				
				$sqlFact="SELECT cfacturasdetalle.cveFactura FROM cfacturasdetalle ".
						 "INNER JOIN cfacturas ON cfacturas.cveFactura=cfacturasdetalle.cveFactura ".
						 "AND cfacturas.seguro=0 AND cfacturasdetalle.cveGuia='".$datGuias['cveGuia']."' ".
					     "ORDER BY cfacturasdetalle.cveDetalle DESC LIMIT 1";
						
				$noFact = $bd->soloUno($sqlFact);
				
				$a=$i%2;	
				if($guiaBase!=$datGuias['cveLineaArea'] && $con!=0)
				{
					$i++;
			?>
       				<tr <?php if($a==1) echo "bgcolor='#C8CDFF'"?> >
                    	<td colspan="16" height="20"></td>
	                </tr>
					
          <?php }
		  		$guiaBase=$datGuias['cveLineaArea'];	
			    $a=$i%2;
			    $con++;
			    $i++;
		  ?>	
                 <tr <?php if($a==1) echo "bgcolor='#C8CDFF'"?> >
                    <td align="center"><?php echo convFecha($datGuias['recepcionCYE']);?></td>
                    <td align="center"><?php echo $datGuias['cveLineaArea'];?></td>
                    <td><?php echo $datGuias['guiaArea'];?></td>
                    <td><?php echo $datGuias['cveGuia'];?></td>
                    <td><?php echo $datGuias['nombreCli'];?></td>  
                    <td align="center"><?php echo $datGuias['estacion'];?></td>
                    <td align="center"><?php echo redondeaNum($datGuias['piezas']);?></td>
                    <td align="center"><?php echo redondeaNum($datGuias['peso']);?></td>                    
                    <td><?php echo $datGuias['nombreCon'];?></td>
                    <td align="center"><?php echo convFecha($datGuias['fechaEntrega']);?></td>
                    <td><?php echo $datGuias['datEntrega'];?></td>
                    <td align="center"><?php echo convFecha($datGuias['fechaRelacion']);?></td> 
                    <td><?php echo $datGuias['observacion'];?></td>
                    <td align="center"><?php echo convFecha($datGuias['llegadaAcuse']);?></td>
                    <td align="center"><?php echo $datGuias['fechaVuelo'];?></td>
                    <td><?php echo $noFact;?></td>
                </tr>
	<?php 	}          
	}else{?>
   		<table width="4444" border="1"> 
			<tr id="Encabezados">
	            <th width="86">FECHA DE ENV&Iacute;O</th>
				<th width="76">L&iacute;NEA A&Eacute;REA</th>
				<th width="86">No. DE GU&iacute;A A&Eacute;REA</th>
                <th width="67">GU&iacute;A CYE</th>
                <th width="340">CLIENTE</th>
				<th width="90">DESTINO</th>
                <th width="460">FACTURA / REMISI&Oacute;N</th>
				<th width="460">PLANIFICACI&Oacute;N / FOLIO</th> 
				<th width="566">DESTINATARIO</th>
                <th width="80">FECHA DE ENTREGA</th>
				<th width="331">NOMBRE DE QUIEN RECIBE</th>
                <th width="120">F. DE LLEGADA DEL ACUSE</th>
                <th width="86">FECHA DE RELACI&Oacute;N</th>
                <th width="100">TIPO DE ENV&iacute;O</th>
                <th width="100">No. DE FACTURA</th>
				<th width="72">VALOR DECLARADO</th>                                                                      
                <th width="72" align="center">PIEZAS</th>
				<th width="72" align="center">PESO</th>
                <th width="80" align="center">TARIFA</th>
                <th width="80" align="center">FLETE</th>
                <th width="80" align="center">SEGURO(2%)</th>
                <th width="80" align="center">ACUSE</th>
                <th width="80" align="center">IMPORTE</th>
                <th width="80" align="center">IVA</th>                                                  
                <th width="80" align="center">SUBTOTAL</th>
                <th width="80" align="center">RETENCI&Oacute;N (4%)</th>
                <th width="80" align="center">TOTAL</th>
                <th width="460">OBSERVACI&Oacute;N</th>                                                                                                     
			</tr>
	<?php	 $h=0;
			 $datos = $bd->Execute($sqlFinal);
			 foreach ($datos as $datoGuia)
			 {
					
				$cveGuia=$datoGuia['cveGuia'];
				$sqlFact="SELECT cfacturasdetalle.cveFactura FROM cfacturasdetalle ".
						"INNER JOIN cfacturas ON cfacturas.cveFactura=cfacturasdetalle.cveFactura ".
						"AND cfacturas.seguro=0 AND cfacturasdetalle.cveGuia='".$cveGuia."'";
						
				$noFact = $bd->soloUno($sqlFact);
				
				if($noFact!="") //Consultar los datos de la Factura real
				{
					$sqlDatosFactura="SELECT acuse,peso,piezas,tarifa,flete,importe,cveIva,retencionIVA,subtotal,total ".
							 "FROM cfacturasdetalle WHERE cveFactura='".$noFact."' AND cveGuia='".$cveGuia."'";
							 
					$datosFact = $bd->Execute($sqlDatosFactura);
					
					foreach($datosFact as $datoFact)
					{
						//Se les da formato a los números
						$acuse=redondeaNum($datoFact['acuse']);
						$peso=redondeaNum($datoFact['peso']);
						$piezas=redondeaNum($datoFact['piezas']);
						$cargo=redondeaNum($datoFact['tarifa']);
						$flete=redondeaNum($datoFact['flete']);
						$valorDec=redondeaNum($datoGuia['seguro']);			
						$seguro=redondeaNum($datoGuia['seguro']*.02);			
						$importe=redondeaNum($datoFact['importe']);
						$iva=redondeaNum($datoFact['cveIva']);
						$retIva=redondeaNum($datoFact['retencionIVA']);
						$subtotal=redondeaNum($datoFact['subtotal']);
						$total=redondeaNum($datoFact['total']);
					}
				}
				else{
				
					$sqlC="SELECT DISTINCT(ctarifas.cvetipoc) FROM ctarifas ".
						   "INNER JOIN ccliente ON ctarifas.cveTipoc=ccliente.cveTipoCliente ".
						   "INNER JOIN cguias ON cguias.cveCliente=ccliente.cveCliente ".
						   "WHERE cguias.cveGuia='".$cveGuia."'";
					
					$tipoc=$bd->soloUno($sqlC);
					$tipoE=$datoGuia["tipoEnvio"];
					
					$tarifaa= 0;
					$tarifab= 0;
					$tarifac=0;
					$tarifad=0;
					$cargoMinimo=0;
						
					if($tipoc!="" && $tipoE!="")
					{
						$sqlt="SELECT cargo99,cargo299,cargo300,cuartoRango,cargoMinimo FROM ctarifas ".
						  "WHERE estadoOrigen='9' AND estadoDestino='".$datoGuia["estadoDestinatario"]."' ".
						  "AND origen='17' AND destino='".$datoGuia["municipioDestinatario"] ."' AND tipoEnvio='$tipoE' ".
						  "AND cvetipoc='$tipoc' AND estatus=1";
						//echo $cveGuia."   ".$sqlt."<br>";
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
					$peso=$datoGuia['peso'];
						 
					if($peso<= $rango1)
					{ $cargo=$tarifaa; }
					
					if($peso > $rango1 AND $peso<= $rango2)
					{ $cargo=$tarifab; }
					
					if($peso> $rango2 AND $peso<= $rango3)
					{ $cargo=$tarifac; }
					
					if($peso>= $rango3)
					{ $cargo=$tarifad; }
					
					$porcentajeIva=$datoGuia["cveImpuestoCli"]/100;
					$flete= $cargo*$peso;
					
					if($flete>$cargoMinimo){$flete=$flete;}else{$flete=$cargoMinimo;}
					
					$acuse=150;
					$importe=$flete+$acuse;
					$iva=$importe*$porcentajeIva;
					$retIva=($importe*.04);
					$subtotal=$iva+$importe;
					$total=$subtotal-$retIva;
						
					//Se les da formato a los números
					$peso=redondeaNum($peso);
					$piezas=redondeaNum($datoGuia['piezas']);
					$cargo=redondeaNum($cargo);
					$flete=redondeaNum($flete);
					$valorDec=redondeaNum($datoGuia['seguro']);			
					$seguro=redondeaNum($datoGuia['seguro']*.02);			
					$importe=redondeaNum($importe);
					$iva=redondeaNum($iva);
					$retIva=redondeaNum($retIva);
					$subtotal=redondeaNum($subtotal);
					$total=redondeaNum($total);
				}
				//Aquí se tendrán que imprimir los datos de las Guías
			
				$a=$h%2;
				if($guiaBase!=$datoGuia['cveLineaArea'] && $con!=0)
				{
						$h++;
				?>
						<tr <?php if($a==1) echo "bgcolor='#C8CDFF'"?> >
							<td colspan="28" height="20"></td>
						</tr>
						
		   <?php } 
				 $guiaBase=$datoGuia['cveLineaArea'];	
				 $a=$h%2;
				 $con++;
				 $h++;
		  ?>
				 <tr <?php if($a==1) echo "bgcolor='#C8CDFF'"?> >            
					<td align="center"><?php echo convFecha($datoGuia['recepcionCYE']); ?></td>   
					<td align="center"><?php echo $datoGuia['cveLineaArea']; ?></td>   
					<td><?php echo $datoGuia['guiaArea']; ?></td>   
					<td><?php echo $datoGuia['cveGuia']; ?></td>                
					<td><?php echo $datoGuia['nombreCli']; ?></td>
					<td align="center"><?php echo $datoGuia['estacion']; ?></td>
					<td>
						<?php if($datoGuia['facturaSoporte']=="")
								$facRem=$datoGuia['entregasSoporte'];
							  else
								$facRem=$datoGuia['facturaSoporte'];
							echo $facRem;
						?>                
					</td>
					<td><?php echo $datoGuia['valeSoporte']; ?></td>
					<td><?php echo $datoGuia['nombreCon']; ?></td>
					<td align="center"><?php echo convFecha($datoGuia['fechaEntrega']); ?></td>   
					<td><?php echo $datoGuia['datEntrega']; ?></td>
					<td align="center"><?php echo convFecha($datoGuia['llegadaAcuse']); ?></td>
                    <td align="center"><?php echo convFecha($datoGuia['fechaRelacion']); ?></td>
					<td><?php echo $datoGuia['tipoEnvio']; ?></td>                
                    <td><?php echo $noFact; ?></td>                
					<td align="center"><?php echo $valorDec; ?></td>
					<td align="center"><?php echo $piezas; ?></td>
					<td align="center"><?php echo $peso; ?></td>  
					<td align="center"><?php echo $cargo; ?></td>  
					<td align="center"><?php echo $flete; ?></td>                                                                                   
					<td align="center"><?php echo $seguro; ?></td>
					<td align="center"><?php echo $acuse; ?></td>
					<td align="center"><?php echo $importe; ?></td>
					<td align="center"><?php echo $iva; ?></td>
					<td align="center"><?php echo $subtotal; ?></td>
					<td align="center"><?php echo $retIva; ?></td>
					<td align="center"><?php echo $total; ?></td>
					<td><?php echo $datoGuia['observacion'];?></td>
				</tr>
<?php  		 }  
	}?>
       </table>
    
    
