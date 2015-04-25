<?php

/**
 * @author Jose Miguel Pantaleon
 * @copyright 2010
 */
include_once('bd.php');
extract($_REQUEST);
//Limpiar Datos
$txtMoneda=trim($txtMoneda);
$txtDescripcion=trim($txtDescripcion);
$usuario=trim($usuario);

$empresa=str_replace ( "-", "'", $empresa);
$fecha = date("Y/m/d");
$empresaS=explode(',',$empresa);
$operacion = $_GET["operacion"];
$checar=true;

if($operacion==2){
	if($txtMoneda==$txthClaveMon){ 
		$checar=false;
	}
}

if($checar){
	//Primero verificaremos que no existe la clave que se está insertando						
	$sql="SELECT 1 FROM cmonedas 
		  WHERE cveMoneda='$txtMoneda'
		  AND cveEmpresa=".$empresaS[0]." AND cveSucursal=".$empresaS[1].";";
	
	$sql=utf8_decode($sql);
	$res1  = $bd->Execute($sql);
	$total = $bd->numRows($sql);
}else $total=0;

if($total!=0) //Significa que cambió el valor de la cve de la línea e intetan ingresar uno exitente
{
	echo "No se pudo realizar la Operación; la Moneda ya está regsitrada.";
}
else
{	

		switch ($operacion)
		{
			case 1:
				{
					$sql="INSERT INTO cmonedas (cveEmpresa ,cveSucursal , cveMoneda ,descripcion,usuarioCreador,fechaCreacion) 
						  VALUES (".$empresa.",'$txtMoneda','$txtDescripcion','$usuario',NOW());";
					$mensaje="La moneda se creó exitosamente.";
				}
				break;
		   case 2:
				{
					$sql="UPDATE cmonedas SET 
					cveMoneda='$txtMoneda',
					descripcion = '$txtDescripcion', 
					usuarioModifico='$usuario',
					fechaModificacion=NOW(),
					estatus='$estado'
					WHERE cveMoneda = '$txthClaveMon' AND cveEmpresa=".$empresaS[0]." AND cveSucursal=".$empresaS[1];
					$mensaje="La moneda se modificó exitosamente.";
				}
				break;
		 case 3:
				{
				    $sql="UPDATE cmonedas SET 
					estatus = '0', 
					usuarioModifico='$usuario',
					fechaModificacion = NOW()                         
					WHERE cveMoneda = '$wb_id' AND cveEmpresa=".$empresaS[0]." AND cveSucursal=".$empresaS[1];
				    $mensaje="La moneda se eliminó exitosamente.";
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
