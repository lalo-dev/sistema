<?php

/**
 * @author Jose Miguel Pantaleon
 * @copyright 2010
 */
include_once('bd.php');
extract($_REQUEST);
//Limpiar Datos
$txtEnvio=trim($txtEnvio);
$txtDescripcion=trim($txtDescripcion);
$usuario=trim($usuario);


$fecha = date("Y/m/d");
$empresa=str_replace ( "-", "'", $empresa);
$empresaS=explode(',',$empresa);
$operacion = $_GET["operacion"];
$checar=true;

if($operacion==2){
	if($txtEnvio==$txthClaveEnvio){ 
		$checar=false;
	}
}

if($checar){
	//Primero verificaremos que no existe la clave que se está insertando						
	$sql="SELECT 1 FROM ctipoenvio 
		  WHERE cveTipoEnvio='$txtEnvio'
		  AND cveEmpresa=".$empresaS[0]." AND cveSucursal=".$empresaS[1].";";
	$sql=utf8_decode($sql);
	$res1  = $bd->Execute($sql);
	$total = $bd->numRows($sql);
}else $total=0;

if($total!=0) //Significa que cambió el valor de la cve de la línea e intetan ingresar uno exitente
{
	echo "No se pudo realizar la Operación; la clave del Tipo de Envío ya está registrada.";
}
else
{	
	switch ($operacion)
	{
		case 1:
			{
			$sql="INSERT INTO ctipoenvio (cveEmpresa ,cveSucursal , cveTipoEnvio,descripcion,usuarioCreador,fechaCreacion) 
				  VALUES (".$empresa.", '$txtEnvio','$txtDescripcion','$usuario',NOW());";
			 $mensaje="El tipo de envío se creó exitosamente.";
			}
			break;
	   case 2:
			{
			$sql="UPDATE ctipoenvio SET 
				cveTipoEnvio = '$txtEnvio',
				descripcion = '$txtDescripcion', 
				usuarioModifico='$usuario',
				fechaModificacion=NOW(),
				estatus='$estado'
				WHERE cveTipoEnvio = '$txthClaveEnvio' AND cveEmpresa=".$empresaS[0]." AND cveSucursal=".$empresaS[1];
				
				$mensaje="El tipo de envío se modificó exitosamente.";
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
