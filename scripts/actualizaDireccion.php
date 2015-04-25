<?php

/**
 * @author miguel
 * @copyright 2009
 */
 include_once('conexion.php');
extract($_REQUEST);
//Limpiar Datos
$empresa=trim($empresa);
$cveCliente=trim($cveCliente);
$txtNumeroD=trim($txtNumeroD);
$slcSucursal=trim($slcSucursal);
$txtCalle=trim($txtCalle);
$txtNumeroInterior=trim($txtNumeroInterior);
$txtNumeroExterior=trim($txtNumeroExterior);
$txtColonia=trim($txtColonia);
$slcMunicipios=trim($slcMunicipios);
$slcEstados=trim($slcEstados);
$txtCodigoPostal=trim($txtCodigoPostal);
$slcTiposDireccion=trim($slcTiposDireccion);
$slcPaises=trim($slcPaises);
$txtTelefono=trim($txtTelefono);
$usuario=trim($usuario);

$fecha = date("Y/m/d");
$empresa=str_replace ( "-", "'", $empresa);
$empresaS=explode(',',$empresa);
$tabla=$_GET["tabla"];
 if($tabla=="cliente")
 {
 	$tabla="cdireccionescliente";
 	$campoClave="cveCliente";
 }
 else
 {
 	$tabla="cdireccionesprovedores";
 	$campoClave="cveCorresponsal";
 }
  if($txtTabla!="cliente")
  {
	
		if($slcSucursal!=$slchSucursal)
		{
			//Verificar que el Corresponsal no tenga registrada ese sucursal , o que otro Correponsal la tenga
			$sql="SELECT 1 FROM cdireccionesprovedores 
				  WHERE sucursalCliente='$slcSucursal'
				  AND estatus=1 AND cveEmpresa=".$empresaS[0]." AND cveSucursal=".$empresaS[1].";";
			
			$sql=utf8_decode($sql);
			$res1 = mysql_query($sql,$conexion);
			$total=mysql_num_rows($res1);
			if($total!=0){
				echo utf8_encode("La Dirección no se ha podido registrar,ya existe una dirección registrada para la sucursal $slcSucursal.");
				exit();
			}else
			{
				//Se asignará el Corresponsal a la tabla de Destinos
				$sql="UPDATE cdestinos SET cveCorresponsal=NULL WHERE `cveDestino`='$slchSucursal';";
				$res1 = mysql_query($sql,$conexion);
				$my_error1 = mysql_error($conexion);
				
				
				$sql="UPDATE cdestinos
				SET cveCorresponsal=$cveCliente
				WHERE cveDestino='$slcSucursal'";
				$res1 = mysql_query($sql,$conexion);
				$my_error1 = mysql_error($conexion);
			}	
		}
  }
$sql1="UPDATE $tabla SET sucursalCliente = '$slcSucursal',
calle = '$txtCalle',
numeroInterior = '$txtNumeroInterior',
numeroexterior = '$txtNumeroExterior',
colonia = '$txtColonia',
cveMunicipio = '$slcMunicipios',
cveEstado = '$slcEstados',
codigoPostal = '$txtCodigoPostal',
tipoDireccion = '$slcTiposDireccion',
cvePais = '$slcPaises',
telefono = '$txtTelefono',
usuarioModifico='$usuario',
fechaModificacion=NOW(),
estatus='$estado'
WHERE $campoClave= '$cveCliente' AND cveDireccion =$txtNumeroD AND cveEmpresa=".$empresaS[0]." AND cveSucursal=".$empresaS[1];
$sql1=utf8_decode($sql1);

$res1 = mysql_query($sql1,$conexion);
$my_error1 = mysql_error($conexion);


// Verifica si existe error en la sintaxis en MySql
if(!empty($my_error1)){
	echo "Error: Sintaxis MySql, verifique";
}
else {
	echo "La dirección se ha modificado exitosamente.";
}
mysql_close($conexion);



?>
