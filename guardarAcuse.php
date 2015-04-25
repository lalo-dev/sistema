<?php

	/**
	 * @author Jose Miguel Pantaleon
	 * @copyright 2010
	 */
 
	include("scripts/bd.php");
	$cveCliente = trim($_POST['cliente']);
	$txtFolio = trim($_POST['folio']);
	$usuario=trim($_POST["usuario"]);
	$empresa=trim($_POST["empresa"]);
	$empresa=str_replace ( "-", "'", $empresa);
	$empresaS=explode(',',$empresa);

	$clavesGuias="";
 	$hdnContador = $_POST['hdnContador'];
	for ($i=1;$i<=$hdnContador;$i++)
	{
		$clavesGuias .= "'".$_POST['guia_' . $i]."',";
	}

	$clavesGuias = substr($clavesGuias, 0, strlen($clavesGuias)-1);
	$sqlConsulta="SELECT COUNT(*) FROM cacuse WHERE cveGuia IN(".$clavesGuias.")";
	
	$resultado=$bd->soloUno($sqlConsulta);
	if($resultado>0)
	{
		echo "El acuse tiene guías que ya se han agregado en otro acuse.Rectifique sus datos.-0";
		exit();
	}

	$sql="SELECT IFNULL(MAX(folio),0)+1 AS id FROM cfolios WHERE cveCliente='".$cveCliente."';";
	$txtFolio = $bd->soloUno($sql);
	$nuevoFolio = $txtFolio - 1;
	
	
	$sql="SELECT IFNULL(MAX(cveAcuse),0)+1 AS id FROM cacuse";
	$datos = $bd->Execute($sql);
	foreach ($datos as $dato){
		$id=$dato['id'];
	}
					
	$sql = "UPDATE cfolios SET folio = '$txtFolio',usuarioModifico='$usuario',fechaModificacion=NOW() WHERE cveEmpresa = '1' AND cveSucursal = '1' AND folio =$nuevoFolio AND cveCliente=$cveCliente;";
	$my_error1=$bd->ExecuteNonQuery($sql);
		
	
	$fechaTermino = $_POST['fecha'];
	
	for ($i = 1; $i <= $hdnContador; $i++)
	{			
		$guia = $_POST['guia_' . $i];
		
		$sql1 = "INSERT INTO cacuse (cveEmpresa ,cveSucursal ,folio ,cveGuia ,cveAcuse,cveCliente,fechaTermino,usuarioCreador,fechaCreacion)VALUES (".$empresa.",'$txtFolio', '$guia', '$id','$cveCliente','$fechaTermino','$usuario',NOW());";
		$my_error1=$bd->ExecuteNonQuery($sql1);
		//echo $sql1;
		
		$sql="SELECT IFNULL(MAX(cveAcuse),0)+1 AS id FROM cacuse";
		$id = $bd->soloUno($sql);
		$x++;
		
	}
	
	if(!empty($my_error1)){
		echo "Error: Sintaxis , verifique-0";
	}
	else {
		echo "El acuse se ha registrado exitosamente.-1";
	}

?>
