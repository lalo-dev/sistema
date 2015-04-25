<?php

include_once('bd.php');
extract($_REQUEST);
//Limpiar Datos
$slcEmpresa=trim($slcEmpresa);
$cveSuc=trim($cveSuc);
$txtrazon=trim($txtrazon);
$usuario=trim($usuario);


$fecha = date("Y/m/d");
$empresa=str_replace ( "-", "'", $empresa);
$empresaS=explode(',',$empresa);
$operacion = $_GET["operacion"];
$checar=true;


if($operacion==2){
	if($txtrazon==$txthrazon){ 
		$checar=false;
	}
}

if($checar){
	//Primero verificaremos que no existe la clave que se está insertando						
	$sql="SELECT 1 FROM csucursales 
		  WHERE nombre='$txtrazon'
		  AND cveEmpresa=".$slcEmpresa.";";
	
	$sql=utf8_decode($sql);
	$res1  = $bd->Execute($sql);
	$total = $bd->numRows($sql);
}else $total=0;

if($total!=0) //Significa que cambió el valor de la cve de la línea e intetan ingresar uno exitente
{
	echo "No se pudo realizar la Operación; el nombre de la Sucursal ya está regsitrado para ese Empresa.";
}
else
{	
	switch ($operacion)
	{
		case 1:
			{
				 $sql="SELECT IFNULL(MAX(cveSucursal),0)+1 AS id FROM csucursales WHERE cveEmpresa=".$slcEmpresa.";";
				 $cveSuc=$bd->soloUno($sql);
					
			 $sql="INSERT INTO csucursales (cveEmpresa,cveSucursal,nombre,estatus,usuarioCreador,fechaCreacion) 
				  VALUES ($slcEmpresa,'$cveSuc','$txtrazon','1','$usuario', NOW());";
			 $mensaje="La sucursal se creó exitosamente. ";
			}
			break;
	  	 case 2:
			{
				//Checar si cambió de Empresa
				if($hdnClaveEm==$slcEmpresa){
					$sql="UPDATE csucursales SET
					cveEmpresa='$slcEmpresa',
					nombre = '$txtrazon', 
					usuarioModifico='$usuario',
					fechaModificacion=NOW(),
					estatus=$estado
					WHERE cveSucursal = '$hdnClave' AND cveEmpresa='$hdnClaveEm'";
					$mensaje="La sucursal se modificó exitosamente. ";
				}else //Borrar y crear uno nuevo
				{
					
					
					$sql="SELECT 1 FROM csucursales 
					  WHERE nombre='$txtrazon'
					  AND cveEmpresa=".$slcEmpresa.";";
				
					$sql=utf8_decode($sql);
					$res1  = $bd->Execute($sql);
					$total = $bd->numRows($sql);
					if($total!=0){
						echo utf8_decode("No se pudo realizar la Operación; el nombre de la Sucursal ya está regsitrado para ese Empresa.");
						exit();

					}else{
	
						$sql="SELECT IFNULL(MAX(cveSucursal),0)+1 AS id FROM csucursales WHERE cveEmpresa=".$slcEmpresa.";";
						$cveSuc=$bd->soloUno($sql);
						
						 $sql="INSERT INTO csucursales (cveEmpresa,cveSucursal,nombre,estatus,usuarioCreador,fechaCreacion) 
					  VALUES ($slcEmpresa,'$cveSuc','$txtrazon','1','$usuario',NOW());";
						$sql=utf8_decode($sql);
						$res=$bd->ExecuteNonQuery($sql);
						$sql="DELETE FROM csucursales WHERE cveSucursal = '$hdnClave' AND cveEmpresa='$hdnClaveEm'";
					}
					$mensaje="La sucursal se modificó exitosamente.";
					
				}

		   }
			break;
	 case 3:
			{
			   $sql="UPDATE csucursales SET 
				estatus = '0', 
				usuarioModifico='$usuario',
				fechaModificacion = NOW()                         
				WHERE cveSucursal = '$wb_id' AND cveEmpresa='$slcEmpresa'";
			   $mensaje="La sucursal se eliminó exitosamente. ";
			 }
			break;  
	   default:
			break;
	}

	$sql=utf8_decode($sql);
	$my_error1 =$bd->ExecuteNonQuery($sql);	
	
	// Verifica si existe error en la sintaxis en MySql
	if(!empty($my_error1))
		echo "Ocurrió un error.";			else
		echo $mensaje;
}
?>
