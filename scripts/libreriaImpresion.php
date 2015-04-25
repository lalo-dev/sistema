<?php
	require_once("bd.php");
	$opc = $_REQUEST['case'];
	/**** PRE-IMPRESION DE GUIAS ****/
	// COMPLETAMOS EL NOMBRE DEL REMITENTE
	
	switch($opc)
		{
			case 1:
			{
				if($_REQUEST['txthAlta'] == 0)
				{
					$existe = buscaExiste($bd);
					if($existe == 0) // EL NOMBRE DEL DESTINATARIO NO EXISTE
					{
						$_REQUEST['txthNombreDes'] = nuevoConsignatario($bd);//echo "regresa de nuevoConsignatario";
						$resultado = actualizaGuia($bd);
						if($resultado=="1")
						{
							$mensaje.="La guia se modifico exitosamente.";
						}
						else
						{
							$mensaje.="Error Interno.";
						}
					}
					else // EL DESTINATARIO YA EXISTE
					{
						$mensaje="ERROR. Se intento duplicar el Consignatario.";
					}
				}
				else if($_REQUEST['txthAlta'] == 1)
				{
					$mensaje="";
					//Checar que NO se haya modificado el nombre del Consignatario
					if($_REQUEST['txthNombreDesP']!=$_REQUEST['txtNombreDes'])
					{
						$mensaje="NO tiene permisos para modificar los datos de consignatario.\n";
					}
					$resultado = actualizaGuia($bd);
					if($resultado=="1")
					{
						$mensaje.="La guia se actualizo exitosamente.\nEnviando PDF.";
					}
					else
					{
						$mensaje.="Error Interno.";
					}
				}
				echo $mensaje;
			}
			break;			
			case 2:
			{
				$sql22 = "SELECT grupo FROM cconsignatarios WHERE cveConsignatario=".$_REQUEST['clave'];
				$ressql22 = $bd->Execute($sql22);
				echo $ressql22[0]['grupo'];
			}
			break;
			
			case 3:
			{
				$sqlBus = "SELECT COUNT(*) AS existe FROM cguias WHERE cveGuia = '".$_REQUEST['numGuia']."' AND cveConsignatario != 0";
				$resBus = $bd->Execute($sqlBus);
				echo $resBus[0]['existe'];
			}
			break;
			
			case 4:
			{//BUSCA SI YA EXISTE EL NUMERO DE GUIA INTRODUCIDO EN txtNumInicio
				$sqlB = "SELECT COUNT(*) AS existe FROM cguias WHERE cveGuia = ".$_REQUEST['numGuia'];
				$existe = $bd->soloUno($sqlB);
				echo $existe;
			}
			break;
			
			case 5:
			{//DETERMINA CUANTOS NUMERO DE GUIAS ESTAN DISPONIBLES A PARTIR DEL NUMERO DADO
				$inicioRango = $_REQUEST['inicio']; // 1
				$cantidad = $_REQUEST['cantidad']; // 10
				$finRango = $inicioRango + $cantidad; // 11
				
				// 1ro: VERIFICAMOS QUE NO EXISTA REGISTRADO ALGUN NUMERO DE GUIA ENTRE LOS QUE YA ESTAN REGISTRADOS
				//		SE EJECUTA UNA CONSULTA QUE BUSQUE LOS NUMEROS QUE SE ENCUENTRAN DENTRO DEL RANGO
				$sqlVerifica = "SELECT COUNT(*) AS encontrados
								FROM cguias
								WHERE cveGuiaInt >= $inicioRango AND cveGuiaInt < $finRango;";
				//echo "<br />";
				$resVerifica = $bd->Execute($sqlVerifica);
				
				if($resVerifica[0][0] == 0) // SI EL RESULTADO ES "0" ES PORQUE NO EXISTE NINGUNA GUIA REGISTRADA CON ALGUN NUMERO DEL RANGO DADO
				{
					echo $resVerifica[0][0];
				}
				else
				{
					// SE BUSCARA EL NUMERO INMEDIATO AL INICIO DE RANGO DADO PARA TERMINAR CUANTOS NUMEROS DE GUIAS CONTINUOS EXISTEN
					$sqlBusqueda = "SELECT cveGuiaInt
									FROM cguias
									WHERE cveGuiaInt >= $inicioRango AND cveGuiaInt < $finRango ORDER BY cveGuiaInt ASC LIMIT 1;";
					$resBusqueda = $bd->Execute($sqlBusqueda);
					
					$rangoDisponible = ($resBusqueda[0][0] - $inicioRango);
					echo $rangoDisponible;
				}
			}
			break;
			
			case 51:
			{
				$sqlBusqueda = "SELECT cveGuiaInt
								FROM cguias
								WHERE cveGuiaInt >= $inicioRango AND cveGuiaInt < $finRango ORDER BY cveGuiaInt ASC LIMIT 1;";
				$resBusqueda = $bd->Execute($sqlBusqueda);
				
				$rangoDisponible = ($resBusqueda[0][0] - $inicioRango);
				echo $rangoDisponible;
			}
			
			case 52:
			{
				//$sqlGuias = "SELECT cveGuiaInt FROM cguias WHERE cveGuiaInt NOT IN (".$_REQUEST['notIn'].") ORDER BY cveGuiaInt";
				$sqlGuias = "SELECT cveGuiaInt FROM cguias WHERE cveGuiaInt != 0 ORDER BY cveGuiaInt";
				//$sqlGuias = "SELECT cveGuiaInt FROM cguias WHERE cveGuiaInt != 0";
				#echo "<br />";
				$resGuias = $bd->Execute($sqlGuias);
				
				$diponible = 0;
				$notIn = explode(",", $_REQUEST['notIn']);
				for($i=0;$i<sizeof($resGuias);$i++)
				{
					$x = $i+1;
					if($x < $resGuias[$i][0])
					{
						$i = $resGuias[$i+1][0];
						if(!in_array($x, $notIn))
						{
							echo $x;
							exit();
						}
					}
				}
				
				echo $disponible;
			}
			break;
				
			case 6: // GENERA CADA UNO DE LOS INSERT PARA LAS NUEVAS GUIAS QUE ESTAN DENTRO DEL RANGO SOLICITADO
			{
				$empresa = $_REQUEST['empresa'];
				$sucursal = $_REQUEST['sucursal'];
				$cveGuia = $_REQUEST['cveGuiaInt'];
				$cveCliente = $_REQUEST['cveCliente'];
				$nombre = $_REQUEST['nombre'];
				$calle = $_REQUEST['calle'];
				$colonia = $_REQUEST['colonia'];
				$municipio = $_REQUEST['municipio'];
				$estado = $_REQUEST['estado'];
				$cp = $_REQUEST['cp'];
				$telefono = $_REQUEST['telefono'];
				$rfc = $_REQUEST['rfc'];
				//$cveGuiaInt = $_REQUEST['cveGuiaInt'];
				$usuario = $_REQUEST['usuario'];
				
				$totalFolios=$_REQUEST['totalFolios'];
				$ultimoFolio=($_REQUEST['ultimoFolio']+1);
				//$todosNumerosGuias = trim($_REQUEST['todosNumerosGuias']);
				
				$conGuias = 0;
				$disponibles = "";
				$sqlInsertGuia = "INSERT INTO cguias
								   (cveEmpresa,
								   cveSucursal,
								   fechaVuelo,
								   fechaEntrega,
								   recepcionCYE,
								   llegadaacuse,
								   validezDias,
								   cveGuia,
								   cveCliente,
								   nombreRemitente,
								   calleRemitente,
								   coloniaRemitente,
								   municipioRemitente,
								   estadoRemitente,
								   codigoPostalRemitente,
								   telefonoRemitente,
								   rfcRemitente,
								   cveGuiaInt,
								   usuarioCreador,
								   fechaCreacion)
								  VALUES ";
				
				/*$sqlGuias = "SELECT cveGuiaInt FROM cguias WHERE cveGuiaInt != 0 ORDER BY cveGuiaInt";
				$resGuias = $bd->Execute($sqlGuias);
				$cadena = "";
				
				for($x=0;$x<sizeof($resGuias);$x++)
				{
					$inicio = $resGuias[$x][0]+1;
					$fin=$resGuias[$x+1][0];
					//echo "inicio: --> ".$inicio." fin: --> ".$fin."<br />";
					for($y=$inicio;$y<$fin;$y++)
					{
						$conGuias++;*/
				for($y=$cveGuia;$y<$ultimoFolio;$y++)
				{
						$sqlInsertGuia .= "($empresa,
										   $sucursal,'0000-00-00','0000-00-00','0000-00-00','0000-00-00','0000-00-00',
										   '$y',
										   $cveCliente,
										   '".$nombre."',
										   '".$calle."',
										   '".$colonia."',
										   '".$municipio."',
										   '".$estado."',
										   '".$cp."',
										   '".$telefono."',
										   '".$rfc."',
										   '$y',
										   '$usuario',
										   NOW()), ";
				}
						/*$disponibles .= $y.",";
						if($conGuias == $totalFolios)
						{*/
							$sqlInsertGuia = substr($sqlInsertGuia, 0, -2);
							$sqlInsertGuia = utf8_decode($sqlInsertGuia.";");
						//exit($sqlInsertGuia);
							$resInsertGuia = $bd->ExecuteNonQuery($sqlInsertGuia);
							echo $resInsertGuia;
						/*}
					}
				}*/
			}
			break;
			
			/*case 7: // GENERA CADA UNO DE LOS INSERT PARA LAS NUEVAS GUIAS QUE ESTAN DENTRO DEL RANGO SOLICITADO
			{
				$empresa = $_REQUEST['empresa'];
				$sucursal = $_REQUEST['sucursal'];
				$cveGuia = $_REQUEST['cveGuiaInt'];
				$cveCliente = $_REQUEST['cveCliente'];
				$nombre = $_REQUEST['nombre'];
				$calle = $_REQUEST['calle'];
				$colonia = $_REQUEST['colonia'];
				$municipio = $_REQUEST['municipio'];
				$estado = $_REQUEST['estado'];
				$cp = $_REQUEST['cp'];
				$telefono = $_REQUEST['telefono'];
				$rfc = $_REQUEST['rfc'];
				
				$usuario = $_REQUEST['usuario'];
				$numGuiaInicio = $_REQUEST['numGuiaInicio'];
				$totalGuias = $_REQUEST['totalGuias'];
				
				$sqlInsertGuia = "INSERT INTO cguias
								   (cveEmpresa,
								   cveSucursal,
								   fechaVuelo,
								   fechaEntrega,
								   recepcionCYE,
								   llegadaacuse,
								   validezDias,								   
								   cveGuia,
								   cveCliente,
								   nombreRemitente,
								   calleRemitente,
								   coloniaRemitente,
								   municipioRemitente,
								   estadoRemitente,
								   codigoPostalRemitente,
								   telefonoRemitente,
								   rfcRemitente,
								   cveGuiaInt,
								   usuarioCreador,
								   fechaCreacion)
								  VALUES ";
								  
				//$sqlGuias = "SELECT cveGuiaInt FROM cguias WHERE cveGuiaInt != 0 ORDER BY cveGuiaInt";
				//$resGuias = $bd->Execute($sqlGuias);
				
				$inicio = $cveGuia;
				$fin = $inicio + $totalGuias;

				for($a=$inicio;$a<$fin;$a++)
				{
					$sqlInsertGuia .= "($empresa,
									   $sucursal,'0000-00-00','0000-00-00','0000-00-00','0000-00-00','0000-00-00',
									   '$a',
									   $cveCliente,
									   '".$nombre."',
									   '".$calle."',
									   '".$colonia."',
									   '".$municipio."',
									   '".$estado."',
									   '".$cp."',
									   '".$telefono."',
									   '".$rfc."',
									   '$a',
									   $usuario,
									   NOW()), ";
				}
				$sqlInsertGuia = substr($sqlInsertGuia, 0, -2);
				$sqlInsertGuia = utf8_decode($sqlInsertGuia.";");
				$resInsertGuia = $bd->ExecuteNonQuery($sqlInsertGuia);
				
				if($resInsertGuia == 0)
				{
					echo $totalGuias;
				}
				else
				{
					$resInsertGuia;
				}
			}
			break;*/
			
			default:
			break;
		}
	
	function buscaExiste($bd)
	{
		if($_REQUEST['grupo'] == "rdbEmpresa")
		{
			$destinatario= $_REQUEST['txtNombreDes']."-".$_REQUEST['estacion'];
		}
		else
		{
			$destinatario=$_REQUEST['txtNombreDes'];
		}
		$sql="SELECT COUNT(*) AS existe FROM cconsignatarios WHERE nombre = '".$destinatario."'";
		$existe = $bd->soloUno($sql);
		
		return $existe;
	}
	
	function nuevoConsignatario($bd)
	{
		session_start();
		$usuario=$_SESSION["usuario_valido"];
		$empresa=$_SESSION["cveEmpresa"];
		$sucursal=$_SESSION["cveSucursal"];
		
		$sql2 = "SELECT IFNULL(MAX(cveConsignatario),0)+1 AS id FROM cconsignatarios";
		$res2 = $bd->Execute($sql2);
		$id = $res2[0]['id'];
		
	//	echo "nuevo id: ".$id."<br /><br />";
		if($_REQUEST['grupo'] == "rdbEmpresa")
		{
			$destinatario= $_REQUEST['txtNombreDes']."-".$_REQUEST['estacion'];
		}
		else
		{
			$destinatario=$_REQUEST['txtNombreDes'];
		}
		
		$sqlInsert="INSERT INTO cconsignatarios
					(cveEmpresa,
					cveSucursal,
					cveConsignatario,
					estacion,
					grupo,
					nombre,
					estado,
					municipio,
					colonia,
					calle,
					codigoPostal,
					telefono,
					usuarioCreador,
					fechaCreacion)
				   VALUES
					(".$empresa.",
					'".$sucursal."',
					'".$id."',
					'".$_REQUEST['estacion']."',
					'".$_REQUEST['grupo']."',
					'".$destinatario."',
					'".$_REQUEST['estadoD']."',
					'".$_REQUEST['municipioD']."',
					'".$_REQUEST['coloniaD']."',
					'".$_REQUEST['calleD']."',
					'".$_REQUEST['cpD']."',
					'".$_REQUEST['telefonoD']."',
					'".$usuario."',
					NOW())";
		$resInsert = $bd->ExecuteNonQuery($sqlInsert); 
		return $id;
	}
	
	function actualizaGuia($bd)
	{
		$sqlUptade = "UPDATE cguias SET
					  contCarga = '".$_REQUEST['desContenido']."',
					  piezas = '". $_REQUEST['numPiezas']."',
					  kg = '".$_REQUEST['kilos']."',
					  volumen = '".$_REQUEST['volumen']."',
					  cveConsignatario = ".$_REQUEST['txthNombreDes'].",
					  obsRemitente = '".$_REQUEST['obsCliente']."'
					  WHERE cveGuiaInt = ".$_REQUEST['numGuia'];
		$sqlUptade = utf8_decode($sqlUptade);
		$result = $bd->ExecuteNonQuery($sqlUptade);
		if($result == "")
		{
			return "1";
		}
		else
		{
			return "0";
		}
	}
?>