<?php

/**
 * @author Jose Miguel Pantaleon
 * @copyright 2010
 */
include("bd.php");
include_once('conexion.php');
 $tamano = $_FILES["fleArchivo"]['size'];
    $tipo = $_FILES["fleArchivo"]['type'];
    $archivo = $_FILES["fleArchivo"]['name'];
    $prefijo = substr(md5(uniqid(rand())),0,6);
   
    if ($archivo != "") {
        // guardamos el archivo a la carpeta files
        $nombreArchivo=$prefijo."_".$archivo;
        $destino =  "archivosExcel/".$nombreArchivo;
        if (copy($_FILES['fleArchivo']['tmp_name'],$destino)) {
            $status = "Archivo subido: <b>".$archivo."</b>";
        } else {
            $status = "Error al subir el archivo";
        }
    } else {
        $status = "Error al subir archivo";
    }


  require_once 'Excel/reader.php';
    $data = new Spreadsheet_Excel_Reader();
    $data->setOutputEncoding('CP1251');
    $data->read("archivosExcel/".$nombreArchivo);
	function cambiaf_a_mysql($fecha){
    preg_match( "#([0-9]{1,2})/([0-9]{1,2})/([0-9]{2,4})#", $fecha, $mifecha);
    $lafecha=$mifecha[3]."-".$mifecha[2]."-".$mifecha[1];
    return $lafecha;
} 
    error_reporting(E_ALL ^ E_NOTICE);
    $dato1='';
        $dato2='';
         
$insert="INSERT INTO ccodigospostales(cveEmpresa,cveSucursal,codigoPostal,nombre,cveEstado,cveMunicipio) VALUES (";
    for ($i = 62621; $i <= $data->sheets[0]['numRows']; $i++) {
       
        $values="'1','1',";
    	for ($j = 1; $j < 6; $j++) {
    	   if($j==5)
           {
            if($data->sheets[0]['cells'][$i][$j]==$dato1)
            {
                
            }
            else
            {
               $values= $values.'"'.$data->sheets[0]['cells'][$i][1].'",'; 
               $values= $values.'"'.$data->sheets[0]['cells'][$i][2].'",'; 
               $values= $values.'"'.$data->sheets[0]['cells'][$i][3].'",';
               $values= $values.'"'.$data->sheets[0]['cells'][$i][4].'",';
               
               $dato1=$data->sheets[0]['cells'][$i][$j];
            }
               
               
           }
           
    	}
        if($values!="'1','1',")
        {
        $values = substr($values, 0, strlen($values)-1);
        $myqry= $insert.$values.");";
               
    	
  $res1 = mysql_query($myqry,$conexion);
          $my_error1 = mysql_error($conexion);
 echo $myqry.$i.$my_error1." <br/>"; 
        }
        
        

    }
 
 


?>