<?php

/**
 * @author Jose Miguel Pantaleon
 * @copyright 2010
 */
include_once('bd.php');
extract($_REQUEST);
	//Limpiar Datos
	$txtClave=trim($txtClave);
	$txtNombre=trim($txtNombre);
	$txtNick=trim($txtNick);
	$txtPassword=trim($txtPassword);
	$slcPerfil=trim($slcPerfil);
	$txtApellidoMaterno=trim($txtApellidoMaterno);
	$txtApellidoPaterno=trim($txtApellidoPaterno);
	$txtAreas=trim($txtAreas);
	$txtDepartamento=trim($txtDepartamento);
	$usuario=trim($usuario);
	$estacion=trim($estacion);
	$empresa=trim($empresa);


	$empresa=str_replace ( "-", "'", $empresa);
	$empresaS=explode(',',$empresa);
	$fecha = date("Y/m/d");
	$operacion = $_GET["operacion"];
	$checar=true;
	$txtPassword=md5($txtPassword);

	if($operacion==2){
		if($txtNick==$txthNick){ 
			$checar=false;
		}
	}

	if($checar){
		//Primero verificaremos que no existe la clave que se está insertando						
		$sql="SELECT 1 FROM  cusuarios 
			  WHERE nick='$txtNick'";
			  
		$sql=utf8_decode($sql);
		$res1  = $bd->Execute($sql);
		$total = $bd->numRows($sql);
	}else $total=0;


	if($total!=0) //Significa que cambió el valor de la cve de la línea e intetan ingresar uno exitente
	{
		echo "No se pudo realizar la Operación; el nick ya está registrado.";
	}
	else
	{	

		switch ($operacion)
		{
			case 1:
				{
					$sql="INSERT INTO cusuarios (cveUsuario, nombre, nick, password, permiso, apeidoMaterno, apeidoPaterno, cveArea, cveDepartamento,
					usuarioCreador, fechaCreacion,estatus, cveEmpresa ,cveSucursal,estacion,empresa,sucursal) 
					VALUES ('$txtClave','$txtNombre','$txtNick','$txtPassword','$slcPerfil','$txtApellidoMaterno','$txtApellidoPaterno','$txtAreas',
					'$txtDepartamento',$usuario,NOW(),'1','$empresa','$sucursal','$estacion','$empresa','$sucursal');";
					$mensaje="El usuario se creó exitosamente.";
				}
				break;
		   case 2:
				{
					$sql="UPDATE cusuarios SET 
					nombre = '$txtNombre', 
					nick = '$txtNick', 
					password = '$txtPassword', 
					permiso = '$slcPerfil', 
					apeidoMaterno = '$txtApellidoMaterno', 
					apeidoPaterno = '$txtApellidoPaterno', 
					cveArea = '$txtAreas', 
					cveDepartamento = '$txtDepartamento', 
					usuarioModifico='$usuario',
					fechaModificacion=NOW(),  
					estatus = '$estatus',
					estacion='$estacion',
					empresa='$empresa',
					sucursal='$sucursal'
					WHERE cveUsuario = '$txtClave' ";
	
					$mensaje="El usuario se modificó exitosamente.";
				}
				break;
		 case 3:
				{
				   $sql="DELETE FROM cusuarios                         
					WHERE cveUsuario = '$wb_id' AND cveEmpresa='$empresa' AND cveSucursal='$sucursal'";
				   $mensaje="El usuario se eliminó exitosamente.";
				 }
				break;  
		   default:
				break;
		}
	

		$sql=utf8_decode($sql);
		$my_error1 =$bd->ExecuteNonQuery($sql);	
	
		// Verifica si existe error en la sintaxis en MySql
		if(!empty($my_error1))
			echo "Ocurrió un error.";				else
			echo $mensaje;
	}

?>

