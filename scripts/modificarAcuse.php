<?php

	include("bd.php");
	$cveCliente    = trim($_POST['cliente']);
	$txtFolio      = trim($_POST['folio']);	
	$usuario       = trim($_POST["usuario"]);
	$txttotalTotal = trim($_POST['txttotalTotal']);
	$empresa       = trim($_POST["empresa"]);
	$empresa       = str_replace ( "-", "'", $empresa);
	$empresaS      = explode(',',$empresa);	
	$nuevoFolio    = $txtFolio - 1;	
	$hdnContador   = trim($_POST['hdnContador']);
	$fechaTermino  = trim($_POST['fecha']);
	$opcion        = $_GET['opcion'];
	
	if($opcion==0){
		
		for ($i = 1; $i <= $hdnContador; $i++)
		{
			$sql1="";
			$guia = $_POST['guia_' . $i];
			$sqluno="SELECT COUNT(cveguia) AS existe FROM cacuse WHERE cveGuia='".$guia."' AND folio='".$txtFolio."' AND cveCliente='".$cveCliente."'";
			$datos = $bd->Execute($sqluno);
			
			foreach($datos as $dato)
			{
				$respuesta=$dato['existe'];
			}
			$existe=$respuesta;
			
			if($existe==0) //Si no existe la Guía en el acuse, se agregará
			{
				$sql="SELECT IFNULL(MAX(cveAcuse),0)+1 AS id FROM cacuse";
				$id = $bd->soloUno($sql);
				
				$sql1 = "INSERT INTO cacuse (cveEmpresa,cveSucursal ,folio ,cveGuia ,cveAcuse,cveCliente,fechaTermino,usuarioCreador,fechaCreacion)VALUES (".$empresa.",'$txtFolio', '$guia', '$id','$cveCliente','$fechaTermino','$usuario',NOW());";
				$x++;
			}else //Como ya no hay guías entonces hay que actualizar la tabla de folios
			{
				 $sql1 = "UPDATE cacuse SET usuarioModifico='".$usuario."',fechaModificacion=NOW() WHERE cveGuia='".$guia."' AND folio='".$txtFolio."' AND cveCliente='".$cveCliente."'";
			}	
			$my_error1 = $bd->ExecuteNonQuery($sql1);
			//echo $sql1."<br>";
		}
		if(!empty($my_error1)){
			echo "Error: Sintaxis , verifique-0";
		}
		else 
		{
			echo "El acuse se ha modificado exitosamente.-1";
		}
	}
	else
	{	
		//Checar si ya no hay datos en ese folio, se debe actualizar los folios
		$sqluno="SELECT COUNT(*) AS total FROM cacuse WHERE folio='$txtFolio' AND cveCliente='$cveCliente'";
		$totalGuias = $bd->soloUno($sqluno);
		if($totalGuias==0)
		{
			//Verificar que en caso de que se hayan borrado todas las guias, el acuse este actualizado correctamente
			$sql="SELECT MAX(folio) FROM cacuse WHERE cveCliente='".$cveCliente."'";
			$folio=soloUno($sql);
			$sqlUp = "UPDATE cfolios SET folio=".$folio.",usuarioModifico='".$usuario."',fechaModificacion=NOW() WHERE cveCliente='".$cveCliente."'";
			$my_error1 = $bd->ExecuteNonQuery($sqlUp);
		}
	}
?>
