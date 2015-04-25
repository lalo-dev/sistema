<?php

include ("bd.php");
extract($_REQUEST);
$empresa=str_replace ( "-", "'", $empresa);
$empresaS=explode(',',$empresa);
$operacion = $_GET["operacion"];
$checar=true;

$txthNombre=trim($txthNombre);
$txtNombre=trim($txtNombre);

if($operacion==2){
	if($txtNombre==$txthNombre){ 
		$checar=false;
	}
}


if($checar){

	//Primero verificaremos que no existe la clave que se está insertando						
	$sql="SELECT 1 FROM cbancos 
		  WHERE descripcion='$txtNombre'
		  AND cveEmpresa=".$empresaS[0]." AND cveSucursal=".$empresaS[1].";";

	$sql=utf8_decode($sql);	
	$datos = $bd->Execute($sql);
	$datos=count($datos);


}else $datos=0;

if($datos!=0) //Significa que cambió el valor del nombre e intetan ingresar uno exitente
{
	echo "No se pudo realizar la Operación; el nombre del banco ya está regsitrado.";
}
else
{	

	switch ($operacion)
	{
		case 1:
			{
				
				$sql="SELECT IFNULL(MAX(cveBanco),0)+1 AS id FROM cbancos";
				$id = $bd->soloUno($sql);

				$sql="INSERT INTO cbancos (cveEmpresa,cveSucursal,cveBanco,descripcion,estatus,usuarioCreador,fechaCreacion) 
					  VALUES (".$empresa.",'$id','$txtNombre', '1', '$usuario',NOW());";
				$sql=utf8_decode($sql);
				$mensaje="El banco se creó exitosamente.";
			}
			break;
	   case 2:
			{					
				$sql="UPDATE cbancos SET 
				descripcion = '$txtNombre', 
				usuarioModifico='$usuario',
				fechaModificacion=NOW(),                                                
				estatus='$estado'
				WHERE cveBanco = '$txtClave' AND cveEmpresa=".$empresaS[0]." AND cveSucursal=".$empresaS[1];
				$sql=utf8_decode($sql);
				$mensaje="El banco se modificó exitosamente.";
					
			}
			break;
	 //APF JULIO Quite el caso tres, de ser necesario implementarlo nuevamente checar respaldo de app 
	   default:
			break;
	}

	$error=$bd->ExecuteNonQuery($sql);
	
	// Verifica si existe error en la sintaxis en MySql
	if($error>0){
		echo "Error: No se pudo concluír la operacion";	
	}
	else {
		echo $mensaje;
	}
}

?>
