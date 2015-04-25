<?php
	if(!session_start())
		session_start();
?>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>Consulta de Gu&iacute;as</title>
<script type="text/javascript" src="scripts/prototype.js"></script>
<script type="text/javascript" src="scripts/window.js"> </script>
<link href="themes/default.css" rel="stylesheet" type="text/css"/> 
<!-- Add this to have a specific theme--> 
<link href="themes/mac_os_x.css" rel="stylesheet" type="text/css"/> 
<script language="javascript" type="text/javascript">
function imprimir(valor){	//Función para abrir reporte	
		var win = new Window({className: "mac_os_x", title: "Reporte de Confirmaciones Pendientes", top:70, left:100, width:1200, height:500, url: "scripts/reporteGuias.php?tipo="+valor, showEffectOptions: {duration:.5}}); 
		win.show(); 
}
</script>

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
</style>
<?php

	//Se inicalizan los datos
	$guiaDesde="";
	$guiaHasta="";
	$clienteDesde="";
	$clienteHasta="";
	$destinoDesde="";
	$destinoHasta="";
	$destinatarioDesde="";
	$destinatarioHasta="";
	$estadoDesde="";
	$estadoHasta="";
	$fechaRecDesde="";
	$fechaRecHasta="";
	$fechaEntDesde="";
	$fechaEntHasta="";
	$tipoReporte="";
	
	
	//Consulta General
	
	$tipoReporte=$_POST['rdbtnFactura'];

	if($tipoReporte==0) //Se deberán tomar los datos de reporte normal
	{
		$sqlConsulta="SELECT cguias.cveGuia,IFNULL(cconsignatarios.estacion,'') AS estacion,IFNULL(cguias.cveLineaArea,'') AS cveLineaArea,".
			 "IFNULL(cguias.noVuelo,'') AS noVuelo,IFNULL(cguias.guiaArea,'') AS guiaArea,IFNULL(cguias.fechaVuelo,'') AS fechaVuelo,".
			 "IFNULL(cguias.recepcionCYE,'') AS recepcionCYE,".				 
			 "IFNULL(ccliente.razonSocial,'') AS nombreCli,".
			 "IFNULL(CONCAT(cguias.calleRemitente,' ',cguias.coloniaRemitente,' C.P.',(IFNULL(cguias.codigoPostalRemitente,' sin asignar '))), '') AS dirCli,".				 				
			 "IFNULL(cguias.telefonoRemitente,'') AS telCli,".
			 "IFNULL(ccliente.rfc,'') AS rfcCli,".
			 "IFNULL(cconsignatarios.nombre,'') AS nombreCon,".
			 "IFNULL(CONCAT('Col. ',cconsignatarios.colonia,' ',cconsignatarios.calle),'') AS dirCon,".
			 "IFNULL(cconsignatarios.codigoPostal,'') AS cpCon,".
			 "IFNULL(cguias.piezas,'') AS piezas,".
			 "IFNULL(IF(cguias.kg>cguias.volumen,cguias.kg,cguias.volumen),0) AS peso,".
			 "IFNULL(cguias.validezDias,'') AS validezDias,".
			 "IFNULL(cguias.status,'') AS status,".
			 "IFNULL(cguias.fechaEntrega,'') AS fechaEntrega,".
			 "IF(cguias.sello=1,(CONCAT(IF(cguias.firma=1,'Sello y Firma ','Sello '),IFNULL(cguias.recibio,''))),".
			 "(CONCAT(IF(cguias.firma =1,'Firma ',''),IFNULL(cguias.recibio,'')))) AS datEntrega,".
			 "IFNULL(centregassoporte.entregasSoporte,'') AS entregasSoporte,".
			 "IFNULL(cvalessoporte.valeSoporte,'') AS valeSoporte,".
			 "IFNULL(cfacturassoporte.facturaSoporte,'') AS facturaSoporte,".
			 "IFNULL(cguias.llegadaAcuse,'') AS llegadaAcuse,".
			 "IFNULL(cguias.tipoEnvio,'') AS tipoEnvio,".				
			 "(IF(cguias.reexpedicion=1,".
			 "(SELECT CONCAT(observaciones,' ','Reexpedicion',' ',cmunicipios.nombre,' ',recoleccion) AS obervacion FROM cguias AS cguias2 ".
			 "LEFT JOIN cconsignatarios ON cguias2.cveConsignatario = cconsignatarios.cveConsignatario ".
			 "INNER JOIN cmunicipios ON cconsignatarios.estado=cmunicipios.cveEntidadFederativa ".
			 "AND cconsignatarios.municipio=cmunicipios.cveMunicipio ".
			 "WHERE cguias2.cveGuia=cguias.cveGuia),".
			 "observaciones)) AS observacion,".	
			 "IFNULL(cacuse.fechaCreacion,'') AS fechaRelacion ".
			 "FROM cguias ".
			 "LEFT JOIN cconsignatarios ON cguias.cveConsignatario=cconsignatarios.cveConsignatario ".
			 "LEFT JOIN ccliente ON cguias.cveCliente=ccliente.cveCliente ".		 
			 "LEFT JOIN cacuse ON cguias.cveGuia=cacuse.cveGuia ".
			 "LEFT JOIN cvalessoporte ON cguias.cveGuia=cvalessoporte.cveGuia ".
			 "LEFT JOIN centregassoporte ON cguias.cveGuia=centregassoporte.cveGuia ".
			 "LEFT JOIN cfacturassoporte ON cguias.cveGuia=cfacturassoporte.cveGuia ";
	}
	elseif($tipoReporte==1) //Se deberán tomar los datos de reporte factura
	{
		$sqlConsulta="SELECT cguias.cveGuia,IFNULL(cconsignatarios.estacion,'') AS estacion,".
			 "IFNULL(cguias.cveLineaArea,'') AS cveLineaArea,".
			 "IFNULL(cguias.guiaArea,'') AS guiaArea,".		
			 "IFNULL(cguias.recepcionCYE,'') AS recepcionCYE,".			 
			 "IFNULL(cguias.cveCliente,0) AS cveCli,".
			 "IFNULL(ccliente.cveImpuesto,0) AS cveImpuestoCli,".
			 "IFNULL(ccliente.razonSocial,'') AS nombreCli,".
			 "IFNULL(CONCAT(cguias.calleRemitente,' ',cguias.coloniaRemitente,' C.P.',(IFNULL(cguias.codigoPostalRemitente,' sin asignar '))), '') AS dirCli,".				 				
			 "IFNULL(ccliente.rfc,'') AS rfcCli,".
			 "IFNULL(cconsignatarios.estado,0) AS estadoDestinatario,".
			 "IFNULL(cconsignatarios.municipio,0) AS municipioDestinatario,".
			 "IFNULL(cconsignatarios.nombre,'') AS nombreCon,".
			 "IFNULL(CONCAT('Col. ',cconsignatarios.colonia,' ',cconsignatarios.calle),'') AS dirCon,".
			 "IFNULL(cconsignatarios.codigoPostal,'') AS cpCon,".		  	 
			 "IFNULL(cguias.fechaEntrega,'') AS fechaEntrega,".
			 "IF(cguias.sello=1,(CONCAT(IF(cguias.firma=1,'Sello y Firma ','Sello '),IFNULL(cguias.recibio,''))),".
			 "(CONCAT(IF(cguias.firma =1,'Firma ',''),IFNULL(cguias.recibio,'')))) AS datEntrega,".
			 "IFNULL(cguias.llegadaAcuse,'') AS llegadaAcuse,".			
			 "IFNULL(cguias.piezas,0) AS piezas,".			 
			 "IFNULL(IF(cguias.kg>cguias.volumen,cguias.kg,cguias.volumen),0) AS peso,".			 
			 "IFNULL(centregassoporte.entregasSoporte,'') AS entregasSoporte,".
			 "IFNULL(cvalessoporte.valeSoporte,'') AS valeSoporte,".
			 "IFNULL(cfacturassoporte.facturaSoporte,'') AS facturaSoporte,".
			 "IFNULL(cguias.tipoEnvio,'') AS tipoEnvio,".
			 "(IF(cguias.reexpedicion=1,".
			 "(SELECT CONCAT('Reexpedicion',' ',cmunicipios.nombre,' ',recoleccion) AS obervacion FROM cguias AS cguias2 ".
			 "LEFT JOIN cconsignatarios ON cguias2.cveConsignatario = cconsignatarios.cveConsignatario ".
			 "INNER JOIN cmunicipios ON cconsignatarios.estado=cmunicipios.cveEntidadFederativa ".
			 "AND cconsignatarios.municipio=cmunicipios.cveMunicipio ".
			 "WHERE cguias2.cveGuia=cguias.cveGuia),".
			 "observaciones)) AS observacion,".			 
 			 "IFNULL(cguias.valorDeclarado,'0') AS seguro,".
			 "IFNULL(cacuse.fechaCreacion,'') AS fechaRelacion ".
			 "FROM cguias ".
			 "LEFT JOIN cconsignatarios ON cguias.cveConsignatario=cconsignatarios.cveConsignatario ".
			 "LEFT JOIN ccliente ON cguias.cveCliente=ccliente.cveCliente ".				 
			 "LEFT JOIN cacuse ON cguias.cveGuia=cacuse.cveGuia ".
			 "LEFT JOIN cvalessoporte ON cguias.cveGuia=cvalessoporte.cveGuia ".
			 "LEFT JOIN centregassoporte ON cguias.cveGuia=centregassoporte.cveGuia ".
			 "LEFT JOIN cfacturassoporte ON cguias.cveGuia=cfacturassoporte.cveGuia ";
	}
	

	require_once("scripts/bd.php");	

	$condicion="WHERE cguias.estatus=1 ";
	//Se capturan los datos
	
	//Guias
	$guiaDesde=$_POST['txtGuiaD'];
	$guiaHasta=$_POST['txtGuiaH'];
	
	if(($guiaDesde!="" || $guiaHasta!="") && ($guiaDesde!="Desde" || $guiaHasta!="Hasta")){
		
		if(($guiaDesde=="") || ($guiaDesde=="Desde"))
		{  $guiaDesde = $bd->minMax("MIN(cveGuia)",'');	} 
		
		if(($guiaHasta=="") || ($guiaHasta=="Hasta"))
		{  $guiaHasta = $bd->minMax("MAX(cveGuia)",'');	}
		
		$condicion.="AND (cguias.cveGuia BETWEEN '".$guiaDesde."' AND '".$guiaHasta."') ";		
	}
	
	//Clientes
	$clienteDesde=$_POST['txtClienteD'];
	$clienteHasta=$_POST['txtClienteH'];
	
	if(($clienteDesde!="" || $clienteHasta!="") && ($clienteDesde!="Desde" || $clienteHasta!="Hasta")){
		
		if(($clienteDesde=="") || ($clienteDesde=="Desde"))
		{  $clienteDesde = $bd->minMax("MIN(cveCliente)",'');	  } 
		
		if(($clienteHasta=="") || ($clienteHasta=="Hasta"))
		{  $clienteHasta = $bd->minMax("MAX(cveCliente)",'');	  }
		
		$condicion.="AND (cguias.cveCliente BETWEEN '".$clienteDesde."' AND '".$clienteHasta."') ";		
	}

	//Destinos	
	$destinoDesde=$_POST['sltDestinoD'];
	$destinoHasta=$_POST['sltDestinoH'];
	
   	if(($destinoDesde!='0' || $destinoHasta!='0') && ($destinoDesde!="Desde" || $destinoHasta!="Hasta")){
		
		if(($destinoDesde=='0') || ($destinoDesde=="Desde"))
		{  $destinoDesde = $bd->minMax("MIN(estacion)",'cconsignatarios');	 } 
		
		if(($destinoHasta=='0') || ($destinoHasta=="Hasta"))
		{ $destinoHasta = $bd->minMax("MAX(estacion)",'cconsignatarios');	 }

		
		$condicion.="AND (estacion BETWEEN '".$destinoDesde."' AND '".$destinoHasta."') ";		
	}

	//Destinatarios
	$destinatarioDesde=$_POST['txtDestinatarioD'];
	$destinatarioHasta=$_POST['txtDestinatarioH'];
	
	if(($destinatarioDesde!="" || $destinatarioHasta!="") && ($destinatarioDesde!="Desde" || $destinatarioHasta!="Hasta")){	
		if(($destinatarioDesde=="") || ($destinatarioDesde=="Desde"))
		{  $destinatarioDesde = $bd->minMax("MIN(nombre)",'cconsignatarios');	  } 
		
		if(($destinatarioHasta=="") || ($destinatarioHasta=="Hasta"))
		{  $destinatarioHasta = $bd->minMax("MAX(nombre)",'cconsignatarios');	  }
		
		$condicion.="AND (cconsignatarios.nombre BETWEEN '".$destinatarioDesde."' AND '".$destinatarioHasta."') ";		
	}
	
	//Estado de la Guía
	$estadoDesde=$_POST['sltStatusD'];
	$estadoHasta=$_POST['sltStatusH'];
	
	if(($estadoDesde!="0" || $estadoHasta!="0") && ($estadoDesde!="Desde" || $estadoHasta!="Hasta")){	
	
		$arrEstado=array('Carga Documentandose','Enviada A destino','En proceso de Entrega','Sin localizar destinatario','Dirrecion erronea','Recabando Sello','Faltan documentos','Con cita para entrega','Entrega Rechazada','Cancelada','Entregada','Concluida');
	
		if(($estadoDesde=="0") || ($estadoDesde=="Desde"))
		{  $estadoDesde =$arrEstado[0];                      } 
		
		if(($estadoHasta=="0") || ($estadoHasta=="Hasta"))
		{  $estadoHasta =$arrEstado[(count($arrEstado)-1)];	 }
		
			//Buscar posiciones en arreglo
		$limInf = array_search($estadoDesde, $arrEstado); 
		$limSup = array_search($estadoHasta, $arrEstado); 
		
		if($limInf>$limSup){
			$tmp=$limSup;
			$limSup=$limInf;
			$limInf=$tmp;
		}
	
			//Tomar valores a partir del rango
		$estados="";
		for($i=$limInf;$i<=$limSup;$i++)
		{
			$estados.="'".$arrEstado[$i]."',";
		}
		$estados = substr($estados, 0, strlen($estados)-1);
		
		$condicion.="AND (cguias.status IN(".$estados.")) ";		
	}

	//Fechas de CyE
	$fechaRecDesde=$_POST['txtRecepcionD'];
	$fechaRecHasta=$_POST['txtRecepcionH'];
	
	if(($fechaRecDesde!="" || $fechaRecHasta!="") && ($fechaRecDesde!="Desde" || $fechaRecHasta!="Hasta")){	
		
		if(($fechaRecDesde=="") || ($fechaRecDesde=="Desde"))
		{  $fechaRecDesde = $bd->minMax("MIN(recepcionCYE)",'');    } 
		else $fechaRecDesde=convFecha($fechaRecDesde);
		
		if(($fechaRecHasta=="") || ($fechaRecHasta=="Hasta"))
		{  $fechaRecHasta = $bd->minMax("MAX(recepcionCYE)",'');  	}
		else $fechaRecHasta=convFecha($fechaRecHasta);
		
		$condicion.="AND (cguias.recepcionCYE BETWEEN '".$fechaRecDesde."' AND '".$fechaRecHasta."') ";		
	}

	//Fechas Entrega
	$fechaEntDesde=$_POST['txtFechaEntregaD'];
	$fechaEntHasta=$_POST['txtFechaEntregaH'];
	
	if(($fechaEntDesde!="" || $fechaEntHasta!="") && ($fechaEntDesde!="Desde" || $fechaEntHasta!="Hasta")){	
		if(($fechaEntDesde=="") || ($fechaEntDesde=="Desde"))
		{  $fechaEntDesde = $bd->minMax("MIN(fechaEntrega)",''); $fechaRecDesde=convFecha($fechaRecHasta); } 
		else $fechaEntDesde=convFecha($fechaEntDesde);
		
		if(($fechaEntHasta=="") || ($fechaEntHasta=="Hasta"))
		{  $fechaEntHasta = $bd->minMax("MAX(fechaEntrega)",'');  $fechaRecHasta=convFecha($fechaRecHasta); }
		else $fechaEntHasta=convFecha($fechaEntHasta);
		
		$condicion.="AND (cguias.fechaEntrega BETWEEN '".$fechaEntDesde."' AND '".$fechaEntHasta."') ";		
	}
	 
	$sqlFinal=$sqlConsulta.$condicion." ORDER BY cveLineaArea ASC";

	if($tipoReporte==0)
	 {
		$guias = $bd->Execute($sqlFinal);
	?>	
    	<!--Encabezados-->
		<a href="excel.php?tipo=<?php echo $tipoReporte; ?>" title="Consultar Excel">Consultar Excel</a>
        <br />
        <br />
        <label id="lblImprimir" onclick="imprimir('<?php echo $tipoReporte?>');" title="Imprimir Reporte">Imprimir</label>
		<br />
		<br /> 
        <table> 
            <tr>
                <td style='font-weight:bold;'>
                    <?php echo "TOTAL DE GUÍAS: ".count($guias);?>        
                </td>    
            </tr>
        </table>
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
			foreach($guias as $datGuias)
			{
				$sqlFact="SELECT cfacturasdetalle.cveFactura FROM cfacturasdetalle ".
						 "INNER JOIN cfacturas ON cfacturas.cveFactura=cfacturasdetalle.cveFactura ".
						 "WHERE cfacturas.seguro=0 AND cfacturasdetalle.cveGuia='".$datGuias['cveGuia']."' ".
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
                    <td align="center"><?php echo $datGuias['recepcionCYE'];?></td>
                    <td align="center"><?php echo $datGuias['cveLineaArea'];?></td>
                    <td><?php echo $datGuias['guiaArea'];?></td>
                    <td><?php echo $datGuias['cveGuia'];?></td>
                    <td><?php echo $datGuias['nombreCli'];?></td>  
                    <td align="center"><?php echo $datGuias['estacion'];?></td>
                    <td align="center"><?php echo redondeaNum($datGuias['piezas']);?></td>
                    <td align="center"><?php echo redondeaNum($datGuias['peso']);?></td>                    
                    <td><?php echo $datGuias['nombreCon'];?></td>
                    <td align="center"><?php echo $datGuias['fechaEntrega'];?></td>
                    <td><?php echo $datGuias['datEntrega'];?></td>
                    <td align="center"><?php echo $datGuias['fechaRelacion'];?></td> 
                    <td><?php echo $datGuias['observacion'];?></td>
                    <td align="center"><?php echo $datGuias['llegadaAcuse'];?></td>
                    <td align="center"><?php echo $datGuias['fechaVuelo'];?></td>
                    <td><?php echo $noFact;	?></td>
                </tr>
	<?php 	}?>        
    	</table>
<?php }
	elseif($tipoReporte==1) //Se deberán tomar los datos de la factura
	 {	
		
		//Obtenemos los datos de la Guía		
		$datos = $bd->Execute($sqlFinal);
		
		$sqlr="SELECT primerRango,segundoRango,tercerRango FROM ctarifascorresponsales WHERE cveCorresponsal=0";	
		$rangos = $bd->Execute($sqlr);
		
		foreach($rangos as $rango)
		{
			 $rango1= valorRango($rango['primerRango']);
			 $rango2= valorRango($rango['segundoRango']);
			 $rango3= valorRango($rango['tercerRango']);
		}		
		
		?>
        
        <!--Encabezados-->
		<a href="excel.php?tipo=<?php echo $tipoReporte; ?>" title="Consultar Excel">Consultar Excel</a>
        <br />
        <br />
        <label id="lblImprimir" onclick="imprimir('<?php echo $tipoReporte;?>');" title="Imprimir Reporte">Imprimir</label>
		<br />
		<br />                   
        <table> 
            <tr>
                <td style='font-weight:bold;'>
                    <?php echo "TOTAL DE GUÍAS: ".count($datos);?>        
                </td>    
            </tr>
        </table>
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
				<th width="72">No. DE FACTURA</th>                                                                      
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
        
        
        
<?php	$h=0;
		$i=0;
		$con=0;
		$guiaBase="";
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
                <td align="center"><?php echo $datoGuia['recepcionCYE']; ?></td>   
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
                <td align="center"><?php echo $datoGuia['fechaEntrega']; ?></td>   
                <td><?php echo $datoGuia['datEntrega']; ?></td>
                <td align="center"><?php echo $datoGuia['llegadaAcuse']; ?></td>                
                <td align="center"><?php echo $datoGuia['fechaRelacion']; ?></td>                
                <td align="center"><?php echo $datoGuia['tipoEnvio']; ?></td>                
                <td><?php echo $noFact;  ?></td>               
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
<?php   }  ?>
		</table>
<?php }
	
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
		$numero=$numero+0; //Para convertir la cadena a número
        $valor = (is_float($numero)) ? (round($numero * 100)/100) : $numero;
        return $valor;
    }
?>   
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
