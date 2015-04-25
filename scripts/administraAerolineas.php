<?php

/**
 * @author Jose Miguel Pantaleon
 * @copyright 2010
 */
 

include_once('bd.php');

extract($_REQUEST);

//Limpiar Datos
$txtClave=trim($txtClave);
$txtDescripcion=trim($txtDescripcion);
$txtContacto=trim($txtContacto);
$txtTelefono=trim($txtTelefono);
$usuario=trim($usuario);


$fecha = date("Y/m/d");
$empresa=str_replace ( "-", "'", $empresa);
$empresaS=explode(',',$empresa);
$operacion = $_GET["operacion"];
$checar=true;


if($operacion==2){
	if($txtClave==$txthClave){ 
		$checar=false;
	}
}

if($checar){
	//Primero verificaremos que no existe la clave que se está insertando						
	$sql="SELECT 1 FROM clineasaereas 
		  WHERE cveLineaArea='$txtClave'
		  AND cveEmpresa=".$empresaS[0]." AND cveSucursal=".$empresaS[1].";";
	
	$sql=utf8_decode($sql);
	$res1  = $bd->Execute($sql);
	$total = $bd->numRows($sql);
}else $total=0;

if($total!=0) //Significa que cambió el valor de la cve de la línea e intetan ingresar uno exitente
{
	echo "No se pudo realizar la Operación; la clave de la Línea ya está regsitrada.";
}
else
{	

	switch ($operacion)
	{
		case 1:
			{
				$sql="INSERT INTO clineasaereas (cveEmpresa ,cveSucursal , cveLineaArea, descripcion, contacto, telefono,estatus,usuarioCreador,fechaCreacion) 
					  VALUES (".$empresa.",'$txtClave','$txtDescripcion','$txtContacto','$txtTelefono','1','$usuario',NOW());";
				
				$mensaje="La aerolinea se creó exitosamente.";
			}
			break;
	   	case 2:
			{					
				$sql="UPDATE clineasaereas SET 
				cveLineaArea = '$txtClave',
				descripcion = '$txtDescripcion', 
				contacto = '$txtContacto', 
				telefono = '$txtTelefono', 
				usuarioModifico='$usuario',
				fechaModificacion=NOW(),                                                
				estatus='$estado'
				WHERE cveLineaArea = '$txthClave' AND cveEmpresa=".$empresaS[0]." AND cveSucursal=".$empresaS[1];
			
				$mensaje="La aerolinea se modificó exitosamente.";
					
			}
			break;
	 //APF JULIO Quite el caso tres, de ser necesario implementarlo nuevamente checar respaldo de app 
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
