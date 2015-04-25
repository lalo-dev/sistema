<?php
	session_start();
	$permiso=$_SESSION["permiso"];
	$permisoNum=0;
	
	//Asignar el menú que le corresponde a cada usuario
	if($permiso=="Administrador"){
		$permisoNum=1;
		$menu="scripts/menuGeneral.php";
	}
	elseif($permiso=="Corresponsal"){
		$menu="";  //No tendrá menú
		$permisoNum=2;
	}
	elseif($permiso=="Cliente"){
		$menu="";  //No tendrá menú		
		$permisoNum=3;
	}
	elseif($permiso=="Facturacion"){
		$menu="scripts/menuFacturacion.php";		
		$permisoNum=4;
	}
	elseif($permiso=="Guias"){
		$menu="scripts/menuGuia.php";		
		$permisoNum=5;
	}
	elseif($permiso=="Usuario"){
		$menu="scripts/menuGeneral.php";		
		$permisoNum=6;
	}
?>