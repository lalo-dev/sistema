<style>
	label
	{
		font-family:Arial;
		font-size:12px;
	}
	#lblError
	{
		color:#CD2F1D;
	}
	#lblMsj
	{
		font-size:16px;
	}
	a
	{
		font-family:Arial;
		font-size:12px;
		color:#06F;
	}
</style>
<?php

	/**
	 * @author Jose Miguel Pantaleon
	 * @copyright 2010
	 */
	 
	include("bd.php");
	$conexion = mysql_connect("localhost","webcom","webcom") or die (mysql_error());
	$db = mysql_select_db("cargayex",$conexion) or die (mysql_error());
	mysql_query("SET NAMES 'utf8'");
	$tamano = $_FILES["fleArchivo"]['size'];
	$tipo = $_FILES["fleArchivo"]['type'];
	$tipo_valido="application/vnd.ms-excel";
	$tipo_valido2="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet";

	$archivo = $_FILES["fleArchivo"]['name'];
    $prefijo = substr(md5(uniqid(rand())),0,6);
   
    if ($archivo != "") {
        if($tipo!=$tipo_valido){
			echo "El archivo no es v&aacute;lido (extensi&oacute;n .xls)";
			echo "<a href='../importarExcel.php'>Regresar al sistema</a>";
			exit();
		}
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
		$trozos=explode("/",$fecha,3);
		$lafecha= "'".$trozos[2]."-".$trozos[1]."-".$trozos[0]."'";
		return $lafecha;
	} 
    error_reporting(E_ALL ^ E_NOTICE);
	
	//Para prevenir errores se verificará primero que el Consignatario exista, y se meterá en un arreglo para evitar llamadas a la BD
	$consignatarios="";
	for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++)
	{
	   if($data->sheets[0]['cells'][$i][11]!="")
		   $consignatarios.="'".$data->sheets[0]['cells'][$i][11]."',";
	}
	$consignatarios = substr($consignatarios, 0, strlen($consignatarios) - 1);
	
	$sqlConsulta="SELECT cveConsignatario FROM cconsignatarios WHERE cveConsignatario IN(".$consignatarios.")";

	$existeCon=$bd->Execute($sqlConsulta);
	$posicion=0;
	$arrConsignatarios="";
	foreach($existeCon as $cveCon)
	{
		$arrConsignatarios[$posicion]=$cveCon[0];
		$posicion++;
	}
	
	$insert="INSERT INTO cguias(usuarioCreador,fechaCreacion,cveEmpresa,cveSucursal,cveLineaArea,guiaArea,recepcionCYE,cveGuia,nombreRemitente,piezas,kg,volumen,validezDias,observaciones,cveConsignatario) VALUES (1,1,";																																									   																																																																											   
	$totalerroneos=0;
	$totalexitosos=0;
    for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
        $values="";
		$my_error1="";
		$myqry="";
    	for ($j = 1; $j <= 11; $j++) {
		  
		   if(($j==3)||($j==9)) //Cambia el formato de la fecha
		   {
			   $fecha= $data->sheets[0]['cells'][$i][$j];
			   if($fecha!="")
			   	   $fecha	= excel_date($fecha);			  
			   else
				   $fecha="";
			    $values= $values."'".$fecha."',";
		   }
		   else if($j==11) //Checará si es válido o no el Consignatario
		   {
				if(($data->sheets[0]['cells'][$i][11]=="") || (!(in_array($data->sheets[0]['cells'][$i][11], $arrConsignatarios))))
				{	$claveCon=0; }
				else
				{	$claveCon=$data->sheets[0]['cells'][$i][11]; }
				$values= $values."'".trim($claveCon)."',";
		   }
		   else
		   {
			  $values= $values."'".trim($data->sheets[0]['cells'][$i][$j])."',";
		   }
    	}
        $values = substr($values, 0, strlen($values)-1);
        $myqry= $insert.$values.");";

		if($data->sheets[0]['cells'][$i][4]=="")
		{
			$my_error1="ERROR";
			$numGuia="desconocido";
		}
		else{
			$res1 = mysql_query($myqry,$conexion);
			$my_error1 = mysql_error($conexion);
			$numero=mysql_errno($conexion);
			$numGuia=$data->sheets[0]['cells'][$i][4];
		}
		
        // Verifica si existe error en la sintaxis en MySql
       	if(!empty($my_error1)){
			if($my_error1=="ERROR")
				$agregado=" (es necesario un n&uacute;mero de Gu&iacute;a).";
			else if($numero==1062)
				$agregado=" (la Gu&iacute;a ya existe).";
			else
				$agregado=" (NO se pudo crear la Gu&iacute;a).";
			echo "<label id='lblError'>ERROR: Rengl&oacute;n ".($i)." con n&uacute;mero de guia ".$numGuia.$agregado." </label><br>";   
			$totalerroneos++;
        }
    }
	
	

	$totalexitosos=$data->sheets[0]['numRows']-$totalerroneos-1;	
	echo "<br /><label id='lblMsj'>Total de Gu&iacute;as: ".($data->sheets[0]['numRows']-1)."</label>";
	echo "<br /><label id='lblMsj'>Importadas Exitosamente: ".$totalexitosos."</label><br/>";
	echo "<label id='lblMsj'>Importadas con Error:".$totalerroneos."</label><br>";
	echo "<a href='../importarExcel.php'>Regresar al sistema</a>";
	
 function excel_date($serial){
	// Excel/Lotus 123 have a bug with 29-02-1900. 1900 is not a
	// leap year, but Excel/Lotus 123 think it is...
	if ($serial == 60) {
	$day = 29;
	$month = 2;
	$year = 1900;
	return sprintf('%04d/%02d/%02d', $year, $month, $day);
	}
	else if ($serial < 60) {
	// Because of the 29-02-1900 bug, any serial date
	// under 60 is one off... Compensate.
	$serial++;
	}
	// Modified Julian to YYYYMMDD calculation with an addition of 2415019
	$l = $serial + 68569 + 2415019;
	$n = floor(( 4 * $l ) / 146097);
	$l = $l - floor(( 146097 * $n + 3 ) / 4);
	$i = floor(( 4000 * ( $l + 1 ) ) / 1461001);
	$l = $l - floor(( 1461 * $i ) / 4) + 31;
	$j = floor(( 80 * $l ) / 2447);
	$day = $l - floor(( 2447 * $j ) / 80);
	$l = floor($j / 11);
	$month = $j + 2 - ( 12 * $l );
	$year = 100 * ( $n - 49 ) + $i + $l;
	return sprintf('%04d-%02d-%02d', $year, $month, $day);
 }
 

?>
