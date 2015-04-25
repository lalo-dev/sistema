<?php

/**
 * @author Jose Miguel Pantaleon
 * @copyright 2010
 */
include_once('bd.php');
extract($_REQUEST);
//Limpiar Datos
$txtDescripcion=trim($txtDescripcion);
$sltNombredo=trim($sltNombredo);
$usuario=trim($usuario);

$fecha = date("Y/m/d");
$empresa=str_replace ( "-", "'", $empresa);
$empresaS=explode(',',$empresa);
$operacion = $_GET["operacion"];
$checar=true;

if($operacion==2){
	if($hdnClave==$txtDescripcion){ 
		$checar=false;
	}
}

if($checar){
	//Primero verificaremos que no existe la clave que se está insertando						
	//Para este caso la clave y la descirpción siempre serán iguales
	$sql="SELECT 1 FROM cdestinos 
		  WHERE cveDestino='$txtDescripcion'   
		  AND cveEmpresa=".$empresaS[0]." AND cveSucursal=".$empresaS[1].";";
	
	$sql=utf8_decode($sql);
	$res1  = $bd->Execute($sql);
	$total = $bd->numRows($sql);
}else $total=0;

if($total!=0) //Significa que cambió el valor de la cve de la línea e intetan ingresar uno exitente
{
	echo "No se pudo realizar la Operación; la clave del Destino ya está regsitrada.";
}
else
{	
	switch ($operacion)
	{
		case 1:
			{
			   
				$sql="INSERT INTO cdestinos (cveEmpresa ,cveSucursal ,cveDestino, descripcion, estado,estatus,usuarioCreador,fechaCreacion) VALUES (".$empresa.",'$txtDescripcion','$txtDescripcion','$sltNombredo','1','$usuario',NOW());";
				$sql=utf8_decode($sql);
				$mensaje="El destino se creó exitosamente.";
			}
			break;
	   case 2:
			{
				$sql="UPDATE cdestinos SET 
				descripcion = '$txtDescripcion',
				cveDestino='$txtDescripcion',
				usuarioModifico='$usuario',
				estado='$sltNombredo',
				fechaModificacion=NOW(),
				estatus='$estatus'
				WHERE cveDestino = '$hdnClave' AND cveEmpresa=".$empresaS[0]." AND cveSucursal=".$empresaS[1];
				$sql=utf8_decode($sql);
				$mensaje="El destino se modificó exitosamente.";
			}
			break; 
	   default:
			break;
	}
	$my_error1 =$bd->ExecuteNonQuery($sql);
		
		
	// Verifica si existe error en la sintaxis en MySql
	if(!empty($my_error1))
		echo "Ocurrió un error.";			else
		echo $mensaje;
}
?>
