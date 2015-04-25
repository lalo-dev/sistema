<?php

include_once("bd.php");

$operacion    = $_GET['operacion'];

if($operacion!=3){
	//Datos Generales
	$empresa	  = $_POST['empresa'];
	$empresa      = str_replace ("-", "'", $empresa);
	$empresaS     = explode(',',$empresa);
	$usuario      = trim($_POST['usuario']);
}

if($operacion==3)
{
	//Borrará una factura del contrarecibo
	$cveCR          = $_GET['idContr'];
	
	$sql="DELETE FROM ccontrarecibo ".
	"WHERE cveContrarecibo='".$cveCR."'";
	
	$error=$bd->ExecuteNonQuery($sql);
	
	$msj="La factura ha sido eliminada del Contrarecibo.";
}
else if($operacion==1)
{

	//Creará un nuevo Contrarecibo
	$cveCliente     = trim($_POST['noCliente']);
	$totalFact      = trim($_POST['total']);
	$sql="SELECT folio+1 AS clave FROM cfoliosdocumentos WHERE tipoDocumento='CONTR';";
	$noContrarecibo = $bd->soloUno($sql);
	
	$totalFactura = 0;
	
	for($i=1;$i<=$totalFact;$i++){
		//Obtenemos los Datos
		$facturaFolio   = trim($_POST['folio_' . $i]);
		$facturaFecha   = trim($_POST['fecha_' . $i]);
		$facturaImporte = trim($_POST['monto_' . $i]);
		$totalFactura+=$facturaImporte;
		
		//Creamos la consulta
		$cveCR=$bd->get_Id("ccontrarecibo","cveContrarecibo");
		$sql="INSERT INTO ccontrarecibo ".
			 "(cveEmpresa,cveSucursal,cveContrarecibo,noContrarecibo,cveFactura,fechaFactura,importe,cveCliente,usuarioCreador,fechaCreacion) ".
			 "VALUES".
			 "(".$empresa.",'".$cveCR."','".$noContrarecibo."','".$facturaFolio."','".$facturaFecha."','".$facturaImporte."','".$cveCliente."','".$usuario."',NOW())";
			 
		$sql=utf8_decode($sql);
		$error+=$bd->ExecuteNonQuery($sql);
	}
	
	if($error==0)
		$msj="Se ha guardado exitosamente el contrarecibo.-1";
	else
		$msj="Ocurrió un error,inténtelo más tarde.-0";
}
else
{
	$cveCliente     = trim($_POST['noCliente']);
	//Calculamos el total de actualizaciones,de nuevos y de eliminaciones de facturas
	$totalBD     = trim($_POST['totUp']);
	$totalActual = trim($_POST['total']);
	
	if($totalBD>$totalActual) $totalGrl=$totalBD;
	else $totalGrl=$totalActual;
	
	if($totalActual>$totalBD) //Significa que habrán actualizaciones e inserciones
	{
		$totalUP   = $totalBD;
		$totalIND  = $totalActual-$totalBD;
	}
	else
	{
		$totalUP   = $totalActual;
		$totalIND  = $totalBD-$totalActual;
	}

	$contador=0;		
	
	/*Como pudieron haberse hecho modificaciones en la factura misma, pe: que cambie el importe total de la misma, al haber retirado una guía,
	  se actualizarán todas las facturas*/
	
	$noContrarecibo = trim($_POST['no_contra']);
	$totalFact      = trim($_POST['total']);
	
	$totalFactura = 0;
	for($i=1;$i<=$totalFact;$i++){
		
		$sql="";
		//Obtenemos los Datos
		$facturaFolio   = trim($_POST['folio_' . $i]);
		$facturaFecha   = trim($_POST['fecha_' . $i]);
		$facturaImporte = trim($_POST['monto_' . $i]);
		$totalFactura+=$facturaImporte;
		
		if($contador<$totalUP)	
		{
			//Creamos la consulta
			$cveCR = trim($_POST['cveCR_' . $i]);
		
			$sql="UPDATE 
				 ccontrarecibo SET
				 cveFactura='".$facturaFolio."',
			     importe='".$facturaImporte."',
				 fechaFactura='".$facturaFecha."',
			     usuarioModifico='".$usuario."',
			     fechaModificacion=NOW()
			     WHERE cveContrarecibo='".$cveCR."'";
			$contador++;
		}
		else
		{
			//Creamos la consulta
			$cveCR=$bd->get_Id("ccontrarecibo","cveContrarecibo");
			$sql="INSERT INTO ccontrarecibo ".
				 "(cveEmpresa,cveSucursal,cveContrarecibo,noContrarecibo,cveFactura,fechaFactura,importe,cveCliente,usuarioCreador,fechaCreacion) ".
				 "VALUES".
				 "(".$empresa.",'".$cveCR."','".$noContrarecibo."','".$facturaFolio."','".$facturaFecha."','".$facturaImporte."','".$cveCliente."','".$usuario."',NOW())";
		}
		
		$sql=utf8_decode($sql);
		$error+=$bd->ExecuteNonQuery($sql);
	}
	
	if(empty($error))
		$msj="Se ha guardado exitosamente el contrarecibo.-1";
	else
		$msj="Ocurrió un error,inténtelo más tarde.-0".$$error;

}

echo $msj;

?>
