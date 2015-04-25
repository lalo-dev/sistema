<?php

include_once('bd.php');
extract($_REQUEST);
//Limpiar Datos
$empresa=trim($empresa);
$slcEstado=trim($slcEstado);
$slcMunicipios=trim($slcMunicipios);
$txtColonia=trim($txtColonia);
$txtCP=trim($txtCP);
$usuario=trim($usuario);


$fecha = date("Y/m/d");
$operacion = $_GET["operacion"];

$empresa=str_replace ( "-", "'", $empresa);
$empresaS=explode(',',$empresa);
$fecha = date("Y/m/d");


//Primero verificaremos que no existe la clave que se está insertando						
$checar=true;

if($operacion==2){
	if(($txtCP==$txthCP)&&($txtColonia==$txthColonia)&&($slcEstado==$slchEstado)&&($slcMunicipios==$slchMunicipios)){ 
		$checar=false;
	}
}

if($checar){
	//Primero verificaremos que no existe la clave que se está insertando						
	$sql="SELECT 1 FROM ccodigospostales 
	  WHERE cveEstado='$slcEstado' 
	  AND cveMunicipio='$slcMunicipios'
	  AND nombre='$txtColonia'
	  AND codigoPostal='$txtCP'
	  AND cveEmpresa=".$empresaS[0]." AND cveSucursal=".$empresaS[1].";";
	
	$sql=utf8_decode($sql);
	$res1  = $bd->Execute($sql);
	$total = $bd->numRows($sql);
}else $total=0;

if($total!=0) //Significa que cambió el valor de la cve de la línea e intetan ingresar uno exitente
{
	echo "No se pudo realizar la Operación; los datos ya han sido registrados.";
}
else
{	
	switch ($operacion)
	{
		case 1:
			{
			   
			$fecha = date("Y/m/d");
			$cveCP  = $bd->get_Id('ccodigospostales','cveCP');

			if($nuevo==1){											//Significa que se tendrá que definir un nuevo Municipio
				$sql="SELECT IFNULL(MAX(cveMunicipio),0)+1 AS id FROM cmunicipios WHERE cveEntidadFederativa=".$slcEstado.";";
				$cveMun = $bd->soloUno($sql);
				
				$sql="INSERT INTO cmunicipios (cveEmpresa ,cveSucursal ,cveMunicipio, cveEntidadFederativa,  nombre,usuarioCreador,fechaCreacion) VALUES (".$empresa.",'$cveMun','$slcEstado','$slcMunicipios','$usuario',NOW());";
				$sql=utf8_decode($sql);
				$res1  = $bd->ExecuteNonQuery($sql);
				
				$cveAs=1;
				$cveMunicipio=$cveMun;
			}
			else{
				$sql="SELECT IFNULL(MAX(cveAsentamiento),0)+1 AS cve FROM ccodigospostales WHERE 
					cveEstado=$slcEstado AND cveMunicipio=$slcMunicipios
					AND cveEmpresa=".$empresaS[0]." AND cveSucursal=".$empresaS[1].";";
				$cve = $bd->soloUno($sql);				
				
				$cveAs=$cve;
				$cveMunicipio=$slcMunicipios;
			}
		
			$sql="INSERT INTO ccodigospostales (cveEmpresa ,cveSucursal ,cveCP, cveEstado, cveMunicipio, cveAsentamiento, nombre,codigoPostal,usuarioCreador,fechaCreacion) VALUES (".$empresa.",'$cveCP','$slcEstado','$cveMunicipio','$cveAs','$txtColonia','$txtCP',$usuario,NOW());";
	
	
			 $mensaje="El C.P. se creó exitosamente.";
			}
			break;
	   case 2:
			{
				
			if($nuevo==1){											//Significa que se tendrá que definir un nuevo Municipio
				$sql="SELECT IFNULL(MAX(cveMunicipio),0)+1 AS id FROM cmunicipios WHERE cveEntidadFederativa=".$slcEstado.";";
				$id = $bd->soloUno($sql);

				$cveMun=$id;
				
				$sql="INSERT INTO cmunicipios (cveEmpresa ,cveSucursal ,cveMunicipio, cveEntidadFederativa, nombre,usuarioCreador,fechaCreacion) VALUES (".$empresa.",'$cveMun', '$slcEstado','$slcMunicipios','$usuario',NOW());";
				$sql=utf8_decode($sql);
				$res1 =$bd->ExecuteNonQuery($sql);
				
				$cveAs=1;
				$cveMunicipio=$cveMun;
			}else { $cveMunicipio=$slcMunicipios;}
	
				$sql="UPDATE ccodigospostales SET 
				cveEstado = '$slcEstado',
				cveMunicipio='$cveMunicipio',
				nombre='$txtColonia',
				codigoPostal='$txtCP',
				usuarioModifico='$usuario',
				fechaModificacion=NOW()            
				WHERE 
				cveCP='$txthCveCP'
				AND cveEmpresa=".$empresaS[0]." AND cveSucursal=".$empresaS[1];

				$mensaje="El C.P. se modificó exitosamente.";
			}
			break;
		case 3:
			{
				$sql="DELETE FROM ccodigospostales 
				WHERE cveCP='$txthCveCP'
				AND cveEmpresa=".$empresaS[0]." AND cveSucursal=".$empresaS[1];
				$mensaje="El C.P se borró exitosamente.";
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
