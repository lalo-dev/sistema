<?php

/**
 * @author miguel
 * @copyright 2009
 */
	session_start();
if (!isset($_SESSION["usuario_valido"]))
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
<script type="text/javascript" src="scripts/ajaxDatos.js"></script>
<script type="text/javascript" src="scripts/prototype.js"></script>
<script type="text/javascript" src="scripts/AjaxLib.js"></script>
<script type="text/javascript" src="scripts/scriptaculous.js?load=builder,effects,controls"></script>
<link rel="stylesheet" type="text/css" href="css/mtoForm.css" media="all"/>
<link rel="stylesheet" href="estilos/calendar.css" type="text/css"/>
<script type="text/javascript" language="JavaScript" src="jscripts/calendar_es.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="estilos/MenuGeneral.css" rel="stylesheet" type="text/css" />
<link href="estilos/ewebfac.css" rel="stylesheet" type="text/css" />
<link href="estilos/ewebfacd.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript">

			function InsertaNuevaLinea(sender,e){
				if(event.keyCode != 13 || Trim(sender.value) == '')return;
				var textArea01 = document.getElementById(e);
				
				if(textArea01.value != '')textArea01.value += '\n';
				textArea01.value += Trim(sender.value);
				sender.value='';
			}
			function Trim(cadena){
				var len = cadena.length;
				var index = Number();
				var resultado = String();
				
				for(index=0; index<len; index++){
					if(cadena.substring(index,index+1) != ' ' && resultado == ''){
						resultado += cadena.substring(index,len);
						break;
					}
				}
				
				len = resultado.length;
				while(resultado.substring(len-1,len) == ' '){
					resultado = resultado.substring(0,len-1);
					len = resultado.length;
				}
				
				return resultado;
			}
		</script>

</head>

<body>
 <div id="contenedor">
        <div id="encabezado">
            <img id="logotipo" src="imagenes/e-webfac.jpg" alt="e-webfac" />
            <div id="menuayuda">
                | <a href="">Ayuda</a> | <a href='logout.php'>Salir</a> |
            </div>
            <img id="cabecera" src="imagenes/Cabecera.jpg" alt="cabecera" width="950"/>
        </div>
        <div id="cuerpo">
       
         <form id= "form2" name="form2">
         	<div id="partesderecha" >
							
									
										<fieldset>
											<legend>Generales</legend>
											<table>
												<tr>
													<td>Guia </td>
													<td>
													<input type="text" id="txtGuia" maxlength="10" value = "Guia House" onclick = "value = ''"/>
													<input type="button" name="btnbuscar" id="btnbuscar" value= "Buscar" />									</td>
											</tr>
											<tr>	
											<div id="autoGuia"></div>
											<tr>
													<td>Cliente</td>
													<td>
											<input type="text" class="disabled" disabled="true" id="txtWbId" maxlength="25" value = "Razon Social" onclick = "value = ''"/>
											</td>
											</tr>
												<tr>
												<td>Estatus</td>
													<td><select class="disabled" disabled="true" id= "slcStatus"> 
														<option value="">Seleccione Status</option>
														<option value="doc">Carga Documentandose</option>
														<option value="sent">Enviada A destino</option>
														<option value="delproc">En proceso de Entrega</option>
														<option value="cantlc">Sin localizar destinatario</option>
														<option value="wadd">Dirrecion erronea</option>
														<option value="wadd">Recabando Sello</option>
														<option value="mdocs">Faltan documentos</option>
														<option value="apptodel">Con cita para entrega</option>
														<option value="rejdel">Entrega Rechazada</option>
														<option value="dlvd">Entregada</option>
														<option value="ended">Concluida</option>
														<option value="ended">Cancelada</option>
													</select> 
												</td>
											</tr>
										  </table>		
										</fieldset>
			
							<fieldset>
								<legend>Datos del envío Áereo</legend>	
									<table>
										<tr>
											<td>Línea Área</td>											
											<td><select class="disabled" disabled="true" id="slcLineaA" name="slcLineaA"></select></td>
											<td>Número de Vuelo</td>
											<td><input class="disabled" disabled="true" id= "txtNumeroVuelo" name= "txtNumeroVuelo" type="text" size="15" /></td>
										</tr>
										<tr>	
											<td>Guía Área </td>
											<td><input class="disabled" disabled="true" id= "txtGuiaAerea" name= "txtGuiaAerea" type="text" size="15"  /></td>
											<td>Fecha de Vuelo</td>
											<td> <input name="txtFechaVuelo" type="text" class="disabled" disabled="true" id="txtFechaVuelo"  size="10" />
											</td>
										</tr>
																	
								</table>		
							</fieldset>
					
							<fieldset>
							<legend>Recepción CYE</legend>
								<table>	
									<tr>
										<td>Recepción CYE</td> 
										<td><input name="txtRecepcioncye" type="text" class="disabled" disabled="true" id="txtRecepcioncye"  size="10" /></td>
								    </tr>
								    <tr>
										<td>Tipo de Envio</td>
										<td>
											<select class="disabled" disabled="true" id= "slcTipoe" name="slcTipoe"> 
											<option value="">Normal</option>
											<option value="doc">Urgente</option>
											<option value="sent">Perecedero</option>
										</td>				
									</tr>			
								</table>
								</fieldset> 
				


					<fieldset >
								<legend>REMITENTE</legend>	
									<table>
										<tr>
											<td>Nombre  
											</td>
											<td><input type="text" name="txtRemitente" class="disabled" disabled="true" id="txtRemitente" /> </td>
											<td>RFC</td>
											<td><input type="text" name="txtRfcR" class="disabled" disabled="true" id="txtRfcR" /></td>
										</tr>
										<tr>
											<td>Estado  
											</td>
											<td><input type="text" name="txtNombredo" class="disabled" disabled="true" id="txtNombredo" /></td>
											<td>Código Postal </td>
											<td><input type="text" name="txtCodigoPr" class="disabled" disabled="true" id="txtCodigoPr" /></td>
										</tr>
										<tr>
											<td>Municipio / Delegación  
											</td>
											<td><input type="text" name="txtMunR" class="disabled" disabled="true" id="txtMunR" /></td>
											<td>Colonia </td>
											<td><input type="text" name="txtColR" class="disabled" disabled="true" id="txtColR" /></td>
										</tr>
										<tr>
											<td>Calle</td>
											<td><input type="text"  name="txtCalleR" class="disabled" disabled="true" id="txtCalleR" /></td>
											<td>Teléfono </td>
											<td><input type="text" name="txtTelefonoR" class="disabled" disabled="true" id="txtTelefonoR" />
											</td>
										
											
										</tr>
								</table>		
						</fieldset>			
				
						<fieldset >
								<legend>DESTINATARIO</legend>	
									<table>
										<tr>
											<td>Sucursal Destino 
											</td>
											<td><select class="disabled" disabled="true" id="slcSucursal"></select>
											
											</td>									
											<td>Nombre  
											</td>
											<td><input type="text" name="txtNombreDes" class="disabled" disabled="true" id="txtNombreDes" /> 
											</td>
										
										</tr>
										<tr>
											<td>Estado  
											</td>
											<td><input type="text" name="txtEstadoD" class="disabled" disabled="true" id="txtEstadoD" />
											</td>
											
											<td>Municipio / Delegación  
											</td>
											<td><input type="text" name="txtMunicipioD" class="disabled" disabled="true" id="txtMunicipioD" />
											</td>
											
										</tr>
										<tr>
											<td>Colonia  
											</td>
											<td><input type="text" name="txtColoniaD" class="disabled" disabled="true" id="txtColoniaD" />
											</td>
											<td>Calle 
											</td>
											<td><input type="text"  name="txtCalleD" class="disabled" disabled="true" id="txtCalleD" />
											</td>
											
										</tr>
										<tr>
											<td>Código Postal 
											</td>
											<td><input type="text" name="txtCodigoPD" class="disabled" disabled="true" id="txtCodigoPD" />
											</td>
											<td>Teléfono 
											</td>
											<td><input type="text" name="txtTelefonoD" class="disabled" disabled="true" id="txtTelefonoD" />
											</td>
										</tr>
										
								</table>		
							</fieldset>
							
									<fieldset>
								<legend>Datos del Envio</legend> 
									<table>	 
										<tr>
											<td>Piezas</td>
											<td><input type="text" name="txtPiezas" class="disabled" disabled="true" id="txtPiezas" size="7" /></td>
											<td>KG</td>
											<td><input type="text" name="txtKg" class="disabled" disabled="true" id="txtKg" size="7" /></td>																				
										</tr>
										<tr>
											<td>Volumen</td>
											<td><input type="text" name="txtVol" class="disabled" disabled="true" id="txtVol" size="7" /></td>
											<td>Recoleccion</td>
											<td><select class="disabled" disabled="true" id= "slcRecoleccion" name="slcRecoleccion"> 
												<option value="no"></option>
												<option value="Recoleccion">Recoleccion</option>
												<option value="Cange">Cange</option>				
												</select>
											</td>
											
										</tr>
										<tr>
											<td>Vigencia</td>
											<td ><input name="txtVigencia" type="text" class="disabled" disabled="true" id="txtVigencia"  /></td>
											<td>Valor Declarado</td>
											<td><input type="text" name="txtValor" class="disabled" disabled="true" id="txtValor" size="15" /></td>
										</tr>										
									</table>
								</fieldset>
					
								<fieldset>
								<legend>Acuse</legend>
							<table>
								<tr align="center">
								<tr>
										<td colspan="2">Fecha de llegada<br /> de acuse</td>
								</tr><tr>
										<td colspan="2"><input name="txtFechaA" type="text" class="disabled" disabled="true" id="txtFechaA"  size="10" />												
												</td>
											</tr>
										</table>
							</fieldset>	
					
								<fieldset>
									<legend>Entrega </legend>
							<table>
									<tr>
														<td >Fecha
														</td>
														<td><input name="txtFechaEntrega" type="text" class="disabled" disabled="true" id="txtFechaEntrega" size="10" />
														</td>
															
													</tr>
													<tr>
														<td>Recibio
														</td >
														<td colspan="3"><input type="text" size="50" name="txtRecibio" class="disabled" disabled="true" id="txtRecibio" />
														</td>
													</tr>	
											<tr align="justify" >
												<td colspan="3"><input type="checkbox" value="1" class="disabled" disabled="true" id="chkSello"/>Sello
												<input type="checkbox" value="1" class="disabled" disabled="true" id="chkFirma"/>Firma
												<input type="checkbox" value="1" class="disabled" disabled="true" id="chkRespaldo"/>Respaldos</td>
												
											</tr>	
							</table>
							</fieldset>	
				
								<fieldset>
								<legend>Factura (s)</legend>
									<table>
										<tr>
										<td><input type="text" name="txtFacturas" class="disabled" disabled="true" id="txtFacturas" size="10" onkeypress="javascript:InsertaNuevaLinea(this,'txaFacturas');" />
											</td>
										<td>
										<textarea rows="4" class="disabled" disabled="true" id="txaFacturas" name="txaFacturas" cols="13"></textarea></label></p>
									</td>
									</tr>	
								</table>										
							</fieldset>
				
							<fieldset>
									<legend>Vale (s)</legend>
										<table>
										<tr>
										<td>
										<input type="text" name="txtVales" class="disabled" disabled="true" id="txtVales" size="10" onkeypress="javascript:InsertaNuevaLinea(this,'txaVales');"/>
										</td>
										<td>
										<textarea rows="4" class="disabled" disabled="true" id="txaVales" name="txaVales" cols="13"></textarea>
										</td>
									</tr>	
								</table>
							</fieldset>
							
							<fieldset>
												
												<legend>Entrega (s)</legend>								 
													<table>
																							
													<tr>
														<td><input type="text" size="8" name="nombre" class="disabled" disabled="true" id="nombre" onkeypress="javascript:InsertaNuevaLinea(this,'txaEntregas');"/>
														</td>
													
														<td >
														<textarea rows="4"  class="disabled" disabled="true" id="txaEntregas" name="txaEntregas" cols="13"></textarea></label></p>
														</td>
															
													</tr>
												
																
												</table>				
								</fieldset>
				
								<legend>Observaciones</legend><textarea name="txaObservaciones" class="disabled" disabled="true" id="txaObservaciones"  rows="4" cols="100" ></textarea>
							</fieldset>
						
					
	<div class="controles" align="center">
		  	<input class = "button" type="button" name="btnCancelar" id="btnCancelar" value= "Cancelar" />

			<input class = "button" type="button" name="btnContinuar" id="btnContinuar" value= "Continuar" />

			<input class = "button" type="button" name="btnBorrar" id="btnBorrar" value= "Borrar" />
			<div align="center">
			
								<span id="loading" style="display: none">Por favor espere...</span>
								<span id="aviso" style="display: none">aver aque horas...</span>
								<span id="status"></span>
						
			</div>
			
		  </div >
		  	</div>
		  	</form>
	</div>
		  
		  
       
   

</body>
</html>