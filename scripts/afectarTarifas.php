<?php

	include_once('conexion.php');
	
	extract($_REQUEST);
	
	$extra="";
	if($opcion==0){
		$valor=1+($porcentaje/100);
		$valor=trim($valor);	
		$sql1="UPDATE `ctarifas`
		SET
		cargo99=(cargo99*$valor),
		cargo299=(cargo299*$valor),
		cargo300=(cargo300*$valor),
		cuartoRango=(cuartoRango*$valor)";
		
		if($slctipoClienteRangos!=""){
			$sql1=$sql1." WHERE cveTipoc='$slctipoClienteRangos'";
			$extra=" para el tipo de cliente ".$slctipoClienteRangos;
		}
		$accion="m�s";

	}
	else if($opcion==1){
		$valor=($porcentaje/100);
		$valor=trim($valor);	
		$sql1="UPDATE `ctarifas` 
		SET
		cargo99=(cargo99-(cargo99*$valor)),
		cargo299=(cargo299-(cargo299*$valor)),
		cargo300=(cargo300-(cargo300*$valor)),
		cuartoRango=(cuartoRango-(cuartoRango*$valor))";
		$slctipoClienteRangos=trim($slctipoClienteRangos);

		if($slctipoClienteRangos!=""){ 
			$sql1=$sql1." WHERE cveTipoc='$slctipoClienteRangos'";
			$extra=" para el tipo de cliente ".$slctipoClienteRangos;
		}
		$accion="menos";
	
	}else if($opcion==2)
	{
		$valor=($porcentaje/100);
		$valor=trim($valor);	
		
		$slctipoClienteCargoMin=trim($slctipoClienteCargoMin);		
		$slctipoEnvio=trim($slctipoEnvio);		
		
		$sql1="UPDATE `ctarifas`
		SET
		cargoMinimo=(cargoMinimo-(cargoMinimo*$valor)) ";
				
		if($slctipoClienteCargoMin!="" && $slctipoEnvio!="")
		{
			$condicion="WHERE cveTipoc='$slctipoClienteCargoMin' AND tipoEnvio='$slctipoEnvio'";
			$extra=" para el tipo de cliente ".$slctipoClienteCargoMin." y el env�o ".$slctipoEnvio;
		}
		else
		{	
			if($slctipoClienteCargoMin!="")
			{
				$condicion="WHERE cveTipoc='$slctipoClienteCargoMin'";
				$extra=" para el tipo de cliente ".$slctipoClienteCargoMin;				
			}
			
			if($slctipoEnvio!="")
			{
				$condicion.="WHERE tipoEnvio='$slctipoEnvio'";
				$extra=" para el tipo de env�o ".$slctipoEnvio;
			}
		}
		
		$sql1.=$condicion;
		$accion="menos";
	}
	else if($opcion==3)
	{
		$valor=1+($porcentaje/100);
		$valor=trim($valor);	
				
		$slctipoClienteCargoMin=trim($slctipoClienteCargoMin);		
		$slctipoEnvio=trim($slctipoEnvio);		
		
		$sql1="UPDATE `ctarifas`
		SET
		cargoMinimo=cargoMinimo*$valor ";
		
		if($slctipoClienteCargoMin!="" && $slctipoEnvio!="")
		{
			$condicion="WHERE cveTipoc='$slctipoClienteCargoMin' AND tipoEnvio='$slctipoEnvio'";
			$extra=" para el tipo de cliente ".$slctipoClienteCargoMin." y el env�o ".$slctipoEnvio;
		}
		else
		{	
			if($slctipoClienteCargoMin!="")
			{
				$condicion="WHERE cveTipoc='$slctipoClienteCargoMin'";
				$extra=" para el tipo de cliente ".$slctipoClienteCargoMin;				
			}
			
			if($slctipoEnvio!="")
			{
				$condicion.="WHERE tipoEnvio='$slctipoEnvio'";
				$extra=" para el tipo de env�o ".$slctipoEnvio;
			}
		}
		
		$sql1.=$condicion;
		$accion="m�s";
	}
	
	$sql1=$sql1.";";

	$res1 = mysql_query($sql1,$conexion);
	$my_error1 = mysql_error($conexion);
	
	// Verifica si existe error en la sintaxis en MySql
	if(!empty($my_error1)){
	echo "Error: Sintaxis MySql, verifique";
	echo "Error: $my_error1";
	
	}
	else {
		if($opcion==2 || $opcion==3) 
			echo utf8_encode("El cargo m�nimo ".$extra." fue afectado con el $porcentaje% $accion.");
		else
			echo utf8_encode("Todas las Tarifas".$extra." fueron afectadas con el $porcentaje% $accion.");
	}
	mysql_close($conexion);


?>
