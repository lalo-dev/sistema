<?php
	require_once("scripts/bd.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
</head>

<body>
<?php

	$guiasUtilizadas = array(); // NUMEROS REALES DE GUIAS EN LINEA (sin letras)
	
	$sql = "SELECT cveGuia FROM cguias ORDER BY cveGuia ASC";
	$res = $bd->Execute($sql);
	sort($res);
	
	echo "Registros pa procesar: ".sizeof($res)."<br /><br />";
	
	$contador = 0;
	foreach($res as $reg)
	{
		$guiaInt = 0;
		$guia = $reg['cveGuia'];
		if(is_numeric($guia))
		{
			$guiaInt = (int) $guia;
		}
		/*else
		{
			$temporal = "";
			$caracteres = str_split($guia);
			for($i=0;$i<sizeof($caracteres);$i++)
			{
				if(is_numeric($caracteres[$i]))
				{
					$temporal .= $caracteres[$i];
				}
			}
			$guiaInt = (int) ($temporal * -1);
		}*/
		$contador++;
		
		$sqlUpdate = "UPDATE cguias SET cveGuiaInt = $guiaInt WHERE cveGuia = '". $reg['cveGuia']."';";
		//echo $contador." ".$sqlUpdate."<br>";
		echo $sqlUpdate."<br>";
		
	}
		
	echo "<br /><br />Registros Procesados: $contador<br /><br />Finaliza insercion<br /><br />";
?>
</body>
</html>