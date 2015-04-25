<?php

/**
 * @author Jose Miguel Pantaleon
 * @copyright 2010
 */
include_once('conexion.php');
extract($_REQUEST);
$empresa=str_replace ( "-", "'", $empresa);
$fecha = date("Y/m/d");
$empresaS=explode(',',$empresa);
$operacion = $_GET["operacion"];

				switch ($operacion)
				{
					case 1:
        				{
        				   
                        $sql="INSERT INTO cmunicipios (cveEmpresa ,cveSucursal ,cveMunicipio, cveEntidadFederativa, nombre,codigoPostal) VALUES (".$empresa.",NULL, '$txtNombredo', '$txtDescripcion','$txtCodigoP');";
                         $mensaje="El Municipio se creo exitosamente... ";
                        }
						break;
				   case 2:
					    {
					    $sql="UPDATE cmunicipios SET 
                            nombre = '$txtDescripcion',
                            codigoPostal='$txtCodigoP',
                            usuarioModifico='$usuario',
                            fechaModificacion='$fecha'            
                            WHERE cveMunicipio = '$hdnClave' AND cveEmpresa=".$empresaS[0]." AND cveSucursal=".$empresaS[1];
                            //usuarioModifico = 'usuarioModificom',
                            //cveEmpresa = 'cveEmpresam', 
                            //cveSucursal = 'cveSucursalm'
                            $mensaje="El Municipio se modifico exitosamente... ";
                        }
						break;
                 case 3:
					    {
					      /**
 *  $sql="UPDATE cfoliosdocumentos SET 
 *                             estatus = '0' 
 *                             WHERE cveDocumento = '$wb_id'";
 * 					       $mensaje="El documento se elimino exitosamente... ";
 */
					     }
						break;  
				   default:
						break;
				}
$res1 = mysql_query($sql,$conexion);
$my_error1 = mysql_error($conexion);


// Verifica si existe error en la sintaxis en MySql
if(!empty($my_error1)){
echo "Error: Sintaxis MySql, verifique";
echo "Error: $my_error1";

}
else {
echo $mensaje;
}
mysql_close($conexion);

?>