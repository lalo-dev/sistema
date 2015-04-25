<?php

/**
 * @author Jose Miguel Pantaleon
 * @copyright 2010
 */
include_once('bd.php');
extract($_REQUEST);

//Limpiar Datos
$txtrazon=trim($txtrazon);
$txtRfc=trim($txtRfc);
$txtDireccion=trim($txtDireccion);
$usuario=trim($usuario);


$fecha = date("Y/m/d");
$empresa=str_replace ( "-", "'", $empresa);
$empresaS=explode(',',$empresa);
$operacion = $_GET["operacion"];

$checar=true;

if($operacion==2){ //Si es una Actualización
	if(($txtRfc==$txthRfc)&&($txtrazon==$txthrazon)){ //Significa que ninguna de los dos cambio
		$checar=false;
	}
}

if($checar){
	//Primero verificaremos que no existe la clave que se está insertando						
	if(($txtRfc!=$txthRfc)&&($txtrazon!=$txthrazon))
		$condicion="rfc='".$txtRfc."'
		  or razonSocial='".$txtrazon."';";
	else if($txtRfc!=$txthRfc) 
		$condicion="rfc='".$txtRfc."';";
	else if($txtrazon!=$txthrazon)
		$condicion="razonSocial='".$txtrazon."';";
		
	$sql="SELECT 1 FROM cempresas 
		  WHERE ".$condicion;
	
	$sql=utf8_decode($sql);
	$res1  = $bd->Execute($sql);
	$total = $bd->numRows($sql);
}else $total=0;

if($total!=0) //Significa que cambió el valor de la cve de la línea e intetan ingresar uno exitente
{
	echo "No se pudo realizar la Operación; el R.F.C. y/o Razón Social ya está(n) registrado(s).";
}
else
{	

		switch ($operacion)
		{
			case 1:
				{
					$sql="INSERT INTO cempresas (razonSocial , rfc, direccion,estatus,usuarioCreador,fechaCreacion) 
						  VALUES ('$txtrazon','$txtRfc','$txtDireccion','1','$usuario',NOW());";
					$sql=utf8_decode($sql);
					$mensaje="La empresa se creó exitosamente.";

				}
				break;
		   case 2:
				{
					$sql="UPDATE cempresas SET 
						razonSocial = '$txtrazon', 
						rfc = '$txtRfc', 
						direccion = '$txtDireccion', 
						usuarioModifico='$usuario',
						fechaModificacion=NOW(),
						estatus=$estado
						WHERE cveEmpresa='$hdnClave'";
					$sql=utf8_decode($sql);

					$mensaje="La empresa se modificó exitosamente.";
				}
				break;
		 case 3:
				{
				   $sql="UPDATE cempresas SET 
					estatus = '0', 
					usuarioModifico='$usuario',
					fechaModificacion = NOW()                         
					WHERE cveEmpresa = '$wb_id'";
  					$sql=utf8_decode($sql);

				   $mensaje="La empresa se eliminó exitosamente.";
				 }
				break;  
		   default:
				break;
		}
		$my_error1 =$bd->ExecuteNonQuery($sql);
		
		
		// Verifica si existe error en la sintaxis en MySql
		if(!empty($my_error1))
			echo "Ocurrió un error.";				else
			echo $mensaje;}
?>
