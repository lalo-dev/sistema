<?php

/**
 * @author Jose Miguel Pantaleon
 * @copyright 2010
 */
include("bd.php");
include_once('conexion.php');
extract($_REQUEST);
//Limpiar Datos
$empresa=trim($empresa);
$txtprimerRango=trim($txtprimerRango);
$txtsegundoRango=trim($txtsegundoRango);
$txttercerRango=trim($txttercerRango);
$txtcuartoRango=trim($txtcuartoRango);
$txtCodigo=trim($txtCodigo);
$slcEstados=trim($slcEstados);
$slcMunicipios=trim($slcMunicipios);
$slcEstadosD=trim($slcEstadosD);
$slcMunicipiosD=trim($slcMunicipiosD);
$usuario=trim($usuario);
$txtCodigo=trim($txtCodigo);
$txtcveTarifa=trim($txtcveTarifa);
$slcTipoe=trim($slcTipoe);
$txtRango1=trim($txtRango1);
$txtRango2=trim($txtRango2);
$txtRango3=trim($txtRango3);
$txtRango4=trim($txtRango4);
$txtSobrepeso=trim($txtSobrepeso);
$txtDistancia=trim($txtDistancia);
$txtTarifaMin=trim($txtTarifaMin);
$txtCSobrepeso=trim($txtCSobrepeso);
$txtCDistancia=trim($txtCDistancia);
$txtCEntrega=trim($txtCEntrega);
$txtCEspecial=trim($txtCEspecial);
$txtCViaticos=trim($txtCViaticos);


$empresa=str_replace ( "-", "'", $empresa);
$fecha = date("Y/m/d");
$operacion=$_GET["operacion"];
$empresaS=explode(',',$empresa);
	switch($operacion){

			case 1:
			{
				//Primero verificar si existe alguna Tarfia registrada ya con ese estado y municipio
				$sql="SELECT COUNT(*) AS total FROM ctarifascorresponsales WHERE cveCorresponsal=$txtCodigo AND ".
				     "estadoOrigen='$slcEstados' AND municipioOrigen='$slcMunicipios' ".
				     "AND estadoDestino='$slcEstadosD' AND municipioDestino=".$slcMunicipiosD.";";
			
				$res=mysql_query($sql,$conexion);
				while($row=mysql_fetch_assoc($res)){ $total=$row['total']; }

				if($total>0)
				{ 
					echo "Ya hay una tarifa registrada, para ese destino.";
					exit();
				}
				else
				{
					$sql="SELECT IFNULL(MAX(cveTarifa),0)+1 AS id FROM ctarifascorresponsales WHERE cveCorresponsal=".$txtCodigo.";";
					$res=mysql_query($sql,$conexion);
					while($row=mysql_fetch_assoc($res)){ $id=$row['id']; }
					$cveTar=$id;

					$sql1="INSERT INTO ctarifascorresponsales (cveEmpresa ,cveSucursal , cveTarifa, primerRango, segundoRango, tercerRango, cuartoRango, cveCorresponsal, estadoOrigen, municipioOrigen, estadoDestino, municipioDestino,usuarioCreador,fechaCreacion) VALUES (".$empresa.", '$cveTar', '$txtprimerRango ', '$txtsegundoRango ', '$txttercerRango ', '$txtcuartoRango ', '$txtCodigo', '$slcEstados', '$slcMunicipios', '$slcEstadosD', '$slcMunicipiosD','$usuario',NOW())";
					$res1 = mysql_query($sql1,$conexion);
					$my_error1 = mysql_error($conexion);
					// Verifica si existe error en la sintaxis en MySql
					if(!empty($my_error1)){
						echo "Error: Sintaxis MySql, verifique";
					}
					else {
						echo "La tarifa se ha registrado exitosamente.";
					}
				}
			}
			break;
			case 2:
			{

				$estadoAntO=$txthEdoO;
				$municipioAntO=$txthMunO;
				$estadoAntD=$txthEdoD;
				$municipioAntD=$txthMunD;
				
				if($estadoAntO!=$slcEstados || $estadoAntD!=$slcEstadosD || $txthMunO!=$slcMunicipios || $txthMunD!=$slcMunicipiosD)
				{
					//Checar que no se repitan los datos
					$sql="SELECT COUNT(*) AS total FROM ctarifascorresponsales WHERE cveCorresponsal=$txtCodigo AND ".
					"estadoOrigen='$slcEstados' AND municipioOrigen='$slcMunicipios' ".
					"AND estadoDestino='$slcEstadosD' AND municipioDestino=".$slcMunicipiosD.";";
					$res=mysql_query($sql,$conexion); 
					while($row=mysql_fetch_assoc($res)){ $total=$row['total']; }
					if($total>0)
					{ 
						echo "Ya hay una tarifa registrada, para ese destino.";
						exit();
					}else
						$checar=true;
				}
				else
					$checar=true;
				if($checar)
				{
					$sql1="UPDATE ctarifascorresponsales SET primerRango = '$txtprimerRango',
					segundoRango = '$txtsegundoRango',
					tercerRango = '$txttercerRango',
					cuartoRango = '$txtcuartoRango',
					usuarioModifico='$usuario',
					estadoOrigen='$slcEstados',
					municipioOrigen='$slcMunicipios',
					estadoDestino='$slcEstadosD',
					municipioDestino='$slcMunicipiosD',
					fechaModificacion= NOW()
					WHERE cveEmpresa=".$empresaS[0]." AND cveSucursal=".$empresaS[1].
					" AND ctarifascorresponsales.cveTarifa =$txtcveTarifa AND ctarifascorresponsales.cveCorresponsal =$txtCodigo ";
					$res1 = mysql_query($sql1,$conexion);
					$my_error1 = mysql_error($conexion);
					// Verifica si existe error en la sintaxis en MySql
					if(!empty($my_error1)){
						echo "Error: Sintaxis MySql, verifique";
					}
					else {
						echo "La tarifa se ha actualizado exitosamente.";
					}
				}


                    }
		   break;
                case 3:
				{
				    $sql1="DELETE FROM ctarifascorresponsales WHERE ctarifascorresponsales.cveEmpresa = '1' AND ctarifascorresponsales.cveSucursal = '1' AND ctarifascorresponsales.cveTarifa = 2 AND ctarifascorresponsales.primerRango = '1' AND ctarifascorresponsales.segundoRango = '2' AND ctarifascorresponsales.tercerRango = '3' AND ctarifascorresponsales.cuartoRango = '3' AND ctarifascorresponsales.cveCorresponsal = 11 AND ctarifascorresponsales.estadoOrigen = 11 AND ctarifascorresponsales.municipioOrigen = 11 AND ctarifascorresponsales.estadoDestino = 11 AND ctarifascorresponsales.municipioDestino = 11 LIMIT 1"; 
				    $res1 = mysql_query($sql1,$conexion);
                    $my_error1 = mysql_error($conexion);
                    // Verifica si existe error en la sintaxis en MySql
                    if(!empty($my_error1)){
                    echo "Error: Sintaxis MySql, verifique";

                    
                    }
                    else {
                    echo "La tarifa se ha registrado exitosamente.";
                    }
				    }
				break;
                case 4:
				{
				     $exists = $bd->Execute("SELECT COUNT(cveTarifa) AS existe FROM cdetalletarifa WHERE cveTarifa='$txtcveTarifa' AND cveCorresponsal='$txtCodigo' AND tipoEnvio='$slcTipoe'");
			         foreach ($exists as $existe){
			             $insertar=$existe["existe"];
				     }
				    if($insertar == 0)
                    {
                        $sql1="INSERT INTO cdetalletarifa (cveEmpresa, cveSucursal, cveCorresponsal, cveTarifa, tipoEnvio, primerRango, segundoRango, Tercerrango, cuartoRango, sobrepeso, distancia, cargoMinimo, costoSobrepeso, costoDistancia, costoEntrega, costoEspecial, costoViaticos, usuarioCreador, fechaCreacion) 
						
                            VALUES (".$empresa.", '$txtCodigo', '$txtcveTarifa', '$slcTipoe', '$txtRango1', '$txtRango2', '$txtRango3', '$txtRango4', '$txtSobrepeso', '$txtDistancia', '$txtTarifaMin','$txtCSobrepeso','$txtCDistancia','$txtCEntrega','$txtCEspecial','$txtCViaticos','$usuario',NOW())";
			
                            
							$res1 = mysql_query($sql1,$conexion);
                            $my_error1 = mysql_error($conexion);
                            // Verifica si existe error en la sintaxis en MySql
                            if(!empty($my_error1)){
                            echo "Error: Sintaxis MySql, verifique";
                            }
                            else {
                            echo "La tarifa se ha registrado exitosamente.";
                            }
        				    }
                    else
                    {
						echo utf8_encode("Los precios no se registraron porque ya existe una tarifa para ese tipo de Envío.");
                    }
                    }
				break;
                case 5:
				{				         
        				    $sql1="UPDATE cdetalletarifa SET tipoEnvio = '$slcTipoe',
                            primerRango    = '$txtRango1',
                            segundoRango   = '$txtRango2',
                            Tercerrango    = '$txtRango3',
                            cuartoRango    = '$txtRango4',
                            sobrepeso      = '$txtSobrepeso',
                            distancia      = '$txtDistancia',
                            cargoMinimo    = '$txtTarifaMin',							
							costoSobrepeso = '$txtCSobrepeso',
							costoDistancia = '$txtCDistancia',
							costoEntrega   = '$txtCEntrega',
							costoEspecial  = '$txtCEspecial',
							costoViaticos  = '$txtCViaticos',							
                            usuarioModifico='$usuario',
                            fechaModificacion= NOW()
                            WHERE cveTarifa='$txtcveTarifa' AND cveCorresponsal='$txtCodigo' AND tipoEnvio='$slcTipoe' AND cveEmpresa=".$empresaS[0]." AND cveSucursal=".$empresaS[1].";";
							$res1 = mysql_query($sql1,$conexion);
                            $my_error1 = mysql_error($conexion);
                            // Verifica si existe error en la sintaxis en MySql
                            if(!empty($my_error1)){

							echo "Error: Sintaxis MySql, verifique";
                            
                            }
                            else {
                            echo "Los precios se han modificado exitosamente.";
                            }
        				   
                    }
				break;
                 case 6:
				{				         
        				  $sql1="UPDATE ctarifascorresponsales SET primerRango = '$txtprimerRango',
                                    segundoRango = '$txtsegundoRango',
                                    tercerRango = '$txttercerRango',
                                    cuartoRango = '$txtcuartoRango',
                                    usuarioModifico='$usuario',
                                    fechaModificacion= NOW()
                                    WHERE cveEmpresa=".$empresaS[0]." AND cveSucursal=".$empresaS[1]." AND ctarifascorresponsales.cveTarifa ='1' AND ctarifascorresponsales.cveCorresponsal ='0' ";
                            $res1 = mysql_query($sql1,$conexion);
                            $my_error1 = mysql_error($conexion);
                            // Verifica si existe error en la sintaxis en MySql
                            if(!empty($my_error1)){
                            echo "Error: Sintaxis MySql, verifique";
                            
                            }
                            else {
                            echo "Los rangos se han modificado exitosamente.";
                            }
        				   
                    }
				break;
				default:break;
	
		
		}                	  
mysql_close($conexion);


?>
