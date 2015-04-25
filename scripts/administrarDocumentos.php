<?php

/**
 * @author Jose Miguel Pantaleon
 * @copyright 2010
 */
include_once('bd.php');
extract($_REQUEST);

//Limpiar Datos
$txtFolio=trim($txtFolio);
$txtOtro=trim($txtOtro);
$txtDescripcion=trim($txtDescripcion);
$usuario=trim($usuario);
$tipodocumento=trim($tipodocumento);


$fecha = date("Y/m/d");
$empresa=str_replace ( "-", "'", $empresa);
$empresaS=explode(',',$empresa);
$operacion = $_GET["operacion"];
$checar=true;

if($operacion==2){
	if($nuevo==1)
	{
		$checar=false;
		$tipodocumento=$txtOtro;
	}
	else{
		$tipodocumento=$sltTipoDoc;
		if($sltTipoDoc==$hdnTipo){ 
			$checar=false;
		}
	}
}else $tipodocumento=$txtOtro;

if($checar){
	//Primero verificaremos que no existe la clave que se está insertando						
	$sql="SELECT 1 FROM cfoliosdocumentos 
		  WHERE tipoDocumento='$tipodocumento'
		  AND cveEmpresa=".$empresaS[0]." AND cveSucursal=".$empresaS[1].";";
		  
	
	$sql=utf8_decode($sql);
	$res1  = $bd->Execute($sql);
	$total = $bd->numRows($sql);
}else $total=0;

if($total!=0) //Significa que cambió el valor de la cve de la línea e intetan ingresar uno exitente
{
	echo "No se pudo realizar la Operación; la decripción del Documento ya está regsitrada.";
}
else
{	
	switch ($operacion)
	{
		case 1:
			{
				$sql="SELECT IFNULL(MAX(cveDocumento),0)+1 AS id FROM cfoliosdocumentos";
				$id=$bd->soloUno($sql);				
			   
				$sql="INSERT INTO cfoliosdocumentos (cveEmpresa ,cveSucursal ,cveDocumento, folio, tipoDocumento, descripcion, estatus,usuarioCreador,fechaCreacion) VALUES (".$empresa.",$id,'$txtFolio','$tipodocumento','$txtDescripcion','1','$usuario',NOW());";
			 	$mensaje="El documento se creó exitosamente.";
			}
			break;
	   case 2:
			{
				
				//Antes que nada verficar si se introdujo un nuevo tipo de Documento,para darlo de alta
				if($nuevo==1)
				{
					$sql="SELECT IFNULL(MAX(cveDocumento),0)+1 AS id FROM cfoliosdocumentos";
					$id=$bd->soloUno($sql);		
					
			   
					$sql="INSERT INTO cfoliosdocumentos (cveEmpresa ,cveSucursal ,cveDocumento, folio, tipoDocumento, descripcion, estatus,usuarioCreador,fechaCreacion) VALUES (".$empresa.",$id,'$txtFolio','$txtOtro','$txtDescripcion','1','$usuario',NOW());";
					$sql=utf8_decode($sql);
					$res=$bd->ExecuteNonQuery($sql);
					
					$tipodocumento=$txtOtro;

				}else $tipodocumento=$sltTipoDoc;
				

				
				$sql="UPDATE cfoliosdocumentos SET 
				folio = '$txtFolio',
				tipoDocumento = '$tipodocumento', 
				descripcion = '$txtDescripcion',							
				usuarioModifico='$usuario',
				fechaModificacion=NOW(),
				estatus=$estado
				WHERE cveDocumento = '$hdnClave' AND cveEmpresa=".$empresaS[0]." AND cveSucursal=".$empresaS[1];

				$mensaje="El documento se modificó exitosamente.";
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
