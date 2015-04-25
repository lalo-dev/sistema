<?php

/**
 * @author miguel
 * @copyright 2009
 */
require_once("direccionamiento.php");
if ((!isset($_SESSION["usuario_valido"]))||(($_SESSION["permiso"]!="Administrador") && ($_SESSION["permiso"]!="Usuario")))
{
    header("Location: login.php");
}
$usuario=$_SESSION["usuario_valido"];
$empresa=$_SESSION["cveEmpresa"];
$sucursal=$_SESSION["cveSucursal"];


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script type="text/javascript" src="scripts/ajaxTarifasEntregas.js"></script>
<script type="text/javascript" src="scripts/prototype.js"></script>
<script type="text/javascript" src="scripts/AjaxLib.js"></script>
<script type="text/javascript" src="jscripts/globalscripts.js"></script>
<script type="text/javascript" src="scripts/scriptaculous.js?load=builder,effects,controls"></script>

<link rel="stylesheet" type="text/css" href="css/mtoForm.css" media="all"/>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>EWebFac - Tarifas Corresponsales</title>

<link href="estilos/ewebfac1.css" rel="stylesheet" type="text/css" />

</head>
<body>
 <div id="contenedor">
<?php
	if($menu!="")	
		require_once($menu);
?>
         <form id= "form2" name="form2" action="reportepdf.php" method="post">
         	<div id="completo">
         	 
      	<div style="width: 942px;">
         	<div class="cabecera">
                    <div id="ruta">Datos Base | Tarifas Corresponsales</div>
                    <h2 id="titulo">Tarifas Corresponsales</h2>
                </div>	
            <br />
            <span id="status"></span>
            <br />
            <fieldset  style="width:auto;" id="generales">
            <legend>
                Datos Generales
            </legend>
						<table width="640">
									<tr>
                                <td width="154">C&oacute;digo Corresponsal</td>

                                <td colspan="3">
                                    <input name="txtCodigo" type="text" maxlength="20" id="txtCodigo" size="40" onclick = "value = ''" tabindex="1"/>
                                    <input type="text" id="totalReg" name="totalReg" class="totalReg" readonly="readonly"/>
                                </td>
                                <div id="autoClienteC"></div>
                            </tr>
                            <tr>
                                <td>Raz&oacute;n social</td>

                                <td colspan="3">
								<input name="txtRazonSocial" type="text" maxlength="80" id="txtRazonSocial" value = "<?php echo $razon; ?>"  size="65" class="mayuscula" tabindex="2"/>
                                </td>
                                <div id="autoCliente"></div>
                            </tr>
                            	
                            <tr>
                                <td>Numero de Tarifa</td>
                                <td colspan="3">
                                	<input name="txtcveTarifa" type="text" maxlength="80" id="txtcveTarifa" size="40"  onclick = "value = ''" tabindex="3"/>
                          			<input type="button" name='btnBuscar' id='btnBuscar' value='Buscar' />
                                <div id="autoTarifa"></div>
                                </td>                                
                            </tr>
                            <tr>
                                <td>Estado Origen</td>
                                <td width="161"><select name="slcEstados" id="slcEstados" tabindex="4" ></select></td>
                                <td width="121">Zona Origen</td>
								<td width="184"><select name="slcMunicipios" id="slcMunicipios" tabindex="5"></select></td>
                               
                            </tr>
                             <tr>
                                <td>Estado Destino</td>
                                <td><select name="slcEstadosD" id="slcEstadosD" tabindex="6"></select></td>
                                <td>Zona Destino</td>
				<td><select name="slcMunicipiosD" id="slcMunicipiosD" tabindex="7"></select>
				    <input type="hidden" name="txthEdoO" id="txthEdoO" />
                                    <input type="hidden" name="txthEdoD" id="txthEdoD" />
                                    <input type="hidden" name="txthMunO" id="txthMunO" />
                                    <input type="hidden" name="txthMunD" id="txthMunD" />  
				</td>
                               
                            </tr>
                            	
                            <tr>
                                <td>Primer Rango</td>
                                <td ><input name="txtprimerRango" type="text" maxlength="80" id="txtprimerRango" size="20" tabindex="8" />
                                </td>
                                <td>Segundo Rango</td>
                                <td><input name="txtsegundoRango" type="text" maxlength="80" id="txtsegundoRango" size="20" tabindex="9"/>
                                </td>
                            </tr>
                            <tr>
                                <td>Tercer Rango</td>
                                <td ><input name="txttercerRango" type="text" maxlength="80" id="txttercerRango" size="20" tabindex="10" />
                                </td>
                                <td>Cuarto Rango</td>
                                <td ><input name="txtcuartoRango" type="text" maxlength="80" id="txtcuartoRango" size="20" tabindex="11"/>
                                </td>
                            </tr>
                            
                            <tr>
                                <td colspan="4" align='center'>
                                    <br />
                                </td>
                            </tr>		
                            <tr>
                                <td colspan="4" align='center'>
                                    <input type="button" name='btnCrear' id='btnCrear' value='Crear Tarifa'tabindex="12" />
                                    <input type="button" name='btnModificar' id='btnModificar' value='Modificar Tarifa' tabindex="13"/>
                                    <input type="button" name='btnCancelar' id='btnCancelar' value='Cancelar' tabindex="14"/>
                                </td>
                            </tr>						
						</table>
												
		</fieldset>
       
                        <span id="loading" style="display: none">Por favor espere...</span>
            			
                  
		  <div id="divRangos" class="oculto">	
			<fieldset>
				<legend>Tarifa Detalle</legend>	
									<table class="gridView" align="center" >
										<tr>
											<th>Tipo de Envio</th>
											<th id="thPrimero" align="center"></th>
											<th id="thSegundo" align="center"></th>
											<th id="thTercero" align="center"></th>
                                            <th id="thCuarto" align="center"></th>
                                            <th align="center">Sobrepeso</th> 
                                            <th align="center">Costo Sobrepeso</th>
                                            <th align="center">Distancia</th>
                                            <th align="center">Costo Distancia</th>
                                            <th align="center">Costo Entrega</th>
                                            <th align="center">Costo Especial</th>
                                            <th align="center">Vi&aacute;ticos</th>                                           
                                            <th align="center">Cargo Minimo</th>
										</tr>
										<tr>	
											<td><select id = "slcTipoe" name="slcTipoe" tabindex="15" ></select></td>
											<td><input type="text" tabindex="16"  size="4" id="txtRango1" name="txtRango1" class="moneda" onkeydown="return chk_val(this.id,event);" /></td>
											<td><input type="text" tabindex="17" size="4" id="txtRango2" name="txtRango2" class="moneda" onkeydown="return chk_val(this.id,event);" /></td>
											<td><input type="text" tabindex="18" size="4" id="txtRango3" name="txtRango3" class="moneda" onkeydown="return chk_val(this.id,event);" /></td>
                                            <td><input type="text" tabindex="19" size="4" id="txtRango4" name="txtRango4" class="moneda" onkeydown="return chk_val(this.id,event);" /></td>
											<td><input type="text" tabindex="20" size="4" id="txtSobrepeso" name="txtSobrepeso" class="moneda" onkeydown="return chk_val(this.id,event);" /></td>
											<td><input type="text" tabindex="21" size="4" id="txtCSobrepeso" name="txtCSobrepeso" class="moneda" onkeydown="return chk_val(this.id,event);" /></td>
                                            <td><input type="text" tabindex="22" size="4" id="txtDistancia" name="txtDistancia" class="moneda" onkeydown="return chk_val(this.id,event);" /></td>
                                            <td><input type="text" tabindex="23" size="4" id="txtCDistancia" name="txtCDistancia" class="moneda" onkeydown="return chk_val(this.id,event);" /></td>
                                            <td><input type="text" tabindex="24" size="4" id="txtCEntrega" name="txtCEntrega" class="moneda" onkeydown="return chk_val(this.id,event);" /></td>
                                            <td><input type="text" tabindex="25" size="4" id="txtCEspecial" name="txtCEspecial" class="moneda" onkeydown="return chk_val(this.id,event);" /></td>
                                            <td><input type="text" tabindex="26" size="4" id="txtCViaticos" name="txtCViaticos" class="moneda" onkeydown="return chk_val(this.id,event);" /></td>
											<td><input type="text" tabindex="27" size="4" id="txtTarifaMin" name="txtTarifaMin" class="moneda" onkeydown="return chk_val(this.id,event);" /></td>
										</tr>																	
								</table>
										
							</fieldset>
			</div>
			<div align="center" style="width: 900px;" id="botones">
				<input type="button" name="btnGuardar" id="btnGuardar" value="Agregar Detalle" tabindex="28"  />
                <input type="button" name="btnModificarD" id="btnModificarD" class="oculto" value="Modificar Precios" tabindex="29" />
                <input type="button" name="btnCancelarD" id="btnCancelarD" class="oculto" value="Cancelar" tabindex="30" />	
			</div>	
			<div id="visible" class="oculto">	
			<fieldset>
				<legend>Tarfias Agregadas por tipo de Env&iacute;o</legend>	
									<table class="gridViewMoneda" align="center" id="tablaFormulario" width="900" >
										<tr>
											<th>Tipo de Envio</th>
											<th align="center" id="thPrimero2"></th>
											<th align="center" id="thSegundo2"></th>
											<th align="center" id="thTercero2"></th>
                                            <th align="center" id="thCuarto2"></th> 
                                            <th align="center">Sobrepeso</th> 
                                            <th align="center">Costo Sobrepeso</th>
                                            <th align="center">Distancia</th>
                                            <th align="center">Costo Distancia</th>
                                            <th align="center">Costo Entrega</th>
                                            <th align="center">Costo Especial</th>
                                            <th align="center">Vi&aacute;ticos</th>                                           
                                            <th align="center">Cargo Minimo</th>
										</tr>
								</table>	
									
							</fieldset>
			</div>		
		
						
						
</div>
</div>
</div>
</body>
</html>
