<?php

	/**
	 * @author miguel
	 * @copyright 2009
	 */
	require_once("direccionamiento.php");
	if ((!isset($_SESSION["usuario_valido"]))||(($_SESSION["permiso"]!="Administrador") && ($_SESSION["permiso"]!="Usuario") && ($_SESSION["permiso"]!="Guias")))
	{
		header("Location: login.php");
	}
	$usuario=$_SESSION["usuario_valido"];
	$empresa=$_SESSION["cveEmpresa"];
	$sucursal=$_SESSION["cveSucursal"];
	$cveGuia = $_GET["cveGuia"];
	include("scripts/bd.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<script type="text/javascript" src="jscripts/globalscripts.js"></script>
    <script type="text/javascript" src="scripts/ajaxDatos.js"></script>
    <script type="text/javascript" src="scripts/prototype.js"></script>
    <script type="text/javascript" src="scripts/AjaxLib.js"></script>
    <script type="text/javascript" src="scripts/scriptaculous.js?load=builder,effects,controls"></script>
    <link rel="stylesheet" type="text/css" href="css/mtoForm.css" media="all"/>
    <link rel="stylesheet" href="estilos/calendar.css" type="text/css"/>
    <script type="text/javascript" language="JavaScript" src="jscripts/calendar_es.js"></script>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" /> 
    <title>Guias</title>    
    <link href="estilos/ewebfac.css" rel="stylesheet" type="text/css" />
    <link href="estilos/ewebfacd.css" rel="stylesheet" type="text/css" />
</head>

<body>
 <div id="contenedor">
	<?php
	if($menu!="")	
		require_once($menu);
    ?>
    <br />
    <div id="cuerpo">
        <form id="form2">
            <div style="width: 1300px;  " >
                <table>
                    <tr id="act_des">
                        <td align="right"><input type="checkbox" id="chkActivado" name="chkActivado" onchange="Cambia(this.checked);" /></td>
                        <td>
                            <input type="text" id="lblActivado" name="lblActivado" readonly="readonly" style="border:0" value="" />
                        </td>                               
                    </tr> 
                </table>
                <fieldset id="izqd" class="fldgenerales">
                    <legend>Generales</legend>
                    <table>
                        <tr>
                            <td width="60">Guia </td>
                            <td>
                                <input type="text" id="txtGuia" value ="<?php echo $cveGuia;?>" size="35" maxlength="10" tabindex="1"/>
                                <div id="autoGuia"></div>
                            </td>
                        </tr>            
                        <tr>
                            <td>No. Cliente</td>
                            <td width="206">
                                <div id="autoCliente"></div>
                                <input name="txtRazonSocial" type="text" maxlength="80" id="txtRazonSocial" value = "Numero Cliente" size="35" onclick = "value = ''" tabindex="2"/>
                                <input name="txtCodigoC" type="hidden"  id="txtCodigoC" />
                            </td>
                        </tr>
                        <tr>
                            <td>Estatus</td>
                            <td>
                              <select id="slcStatus" name="slcStatus" tabindex="3"></select>
                              <input type="hidden" id="txthStatus" name="txthStatus" value="" />
                            </td>
                        </tr>
                    </table>		
                </fieldset>
                <fieldset id="centd" class="fldrecepcion">
                    <legend>Recepci&oacute;n CYE</legend>
                    <table>	
                        <tr>
                            <td width="82">Recepci&oacute;n CYE</td> 
                            <td width="76">
                                <input name="txtRecepcioncye" type="text" id="txtRecepcioncye" class="calendar" size="10" tabindex="4"/>
                                <script type="text/javascript" language="JavaScript"> new tcal ({'controlname': 'txtRecepcioncye'}); </script>
                            </td>
                        </tr>
                        <tr>
                            <td>Tipo de Envio</td>
                            <td>
                                <select id = "slcTipoe" name="slcTipoe" tabindex="5"></select>
                            </td>				
                        </tr>
                        <tr>
                            <td><img src="imagenes/Spacer.gif" alt="" border="0" width="1px" height="20px"/></td>
                        </tr>											
                    </table>
                </fieldset>
                <fieldset id="derd" class="fldaereo">
                  <legend>Datos del env&iacute;o &Aacute;ereo</legend>	
                    <table width="476">
                        <tr>
                            <td width="115">L&iacute;nea &Aacute;erea</td>											
                            <td width="120"><select id="slcLineaA" name="slcLineaA" tabindex="6"></select></td>
                        </tr>											
                        <tr>
                            <td>Gu&iacute;a &Aacute;erea </td>
                            <td><input id = "txtGuiaAerea" name= "txtGuiaAerea" type="text" size="15"  tabindex="7"/></td>											
                        </tr>
                        <tr>	
                            <td>N&uacute;mero de Vuelo</td>
                            <td><input id = "txtNumeroVuelo" name= "txtNumeroVuelo" type="text" size="15" tabindex="8"/></td>
                            <td width="102">Fecha de Vuelo</td>
                            <td width="119"> 
                                <input name="txtFechaVuelo" type="text" id="txtFechaVuelo" class="calendar" size="10" tabindex="9"/>
                                <script type="text/javascript" language="JavaScript"> new tcal ({'controlname': 'txtFechaVuelo'}); </script>
                            </td>
                        </tr>                
                    </table>		
                </fieldset>
            </div><br />
            <div style="width:1500px" >	
                <fieldset class="fldremitente">
                    <legend>Remitente</legend>	
                    <table>
                        <tr>
                            <td>Nombre</td>
                            <td><input type="text" name="txtRemitente" size="35" id="txtRemitente" class="arial10" tabindex="10"/></td>
                            <td>RFC</td>
                            <td><input type="text" name="txtRfcR" id="txtRfcR" tabindex="11"/><input type="hidden" name="hdncveDireccion" id="hdncveDireccion"/></td>
                        </tr>
                        <tr>
                            <td>Estado</td>
                            <td>
                                <select name="txtNombredo" id="txtNombredo" tabindex="12">
                                <?php $opciones = $bd->get_select('cestados','cveEstado','nombre',0,''); echo $opciones; ?>
                                </select>
                            </td>
                            <td>Municipio / Delegaci&oacute;n</td>
                            <td><select name="txtMunR" id="txtMunR" tabindex="13"></select></td>											
                        </tr>
                        <tr>
                            <td>Calle </td>
                            <td><input type="text"  name="txtCalleR" id="txtCalleR" class="arial10" tabindex="14"/></td>
                            <td>Colonia</td>
                            <td><input type="text" name="txtColR" id="txtColR" class="arial10" tabindex="15"/></td>										
                        </tr>
                        <tr>
                            <td>C&oacute;digo Postal</td>
                            <td><input type="text" name="txtCodigoPr" id="txtCodigoPr" tabindex="16"/></td>
                            <td>Tel&eacute;fono</td>
                            <td><input type="text" name="txtTelefonoR" id="txtTelefonoR" tabindex="17"/>
                            </td>							
                        </tr>										
                    </table>
                </fieldset>	            
                <fieldset id="derecho2" class="flddestinatario">
                    <legend>Destinatario</legend>	
                    <table>
                        <tr>
                            <td>Estacion Destino 
                            </td>
                            <td>
                                <select id="slcSucursal" name="slcSucursal" tabindex="18">
                                <?php $opciones = $bd->get_select('cestados','cveEstado','nombre',0,''); echo $opciones; ?>
                                </select>                    
                            </td>									
                            <td>Nombre</td>
                            <td>
                                <input type="text" name="txtNombreDes" id="txtNombreDes" size="35" class="arial10" tabindex="19"/>
                                <div id="autoDestinatario"></div>
                                <input type="hidden" id="txthNombreDes" name="txthNombreDes" value="0" />
                                <input type="hidden" id="txthNombreDesP" name="txthNombreDesP" value="0" />
                                <input type="hidden" id="txthAlta" name="txthAlta" value="0" />
                            </td>								
                        </tr>
                        <tr>
                            <td>Estado</td>
                            <td>
                                <select name="txtEstadoD" id="txtEstadoD" tabindex="23"></select>
                            </td>											
                            <td>Municipio / Delegaci&oacute;n</td>
                            <td>
                                <select name="txtMunicipioD" id="txtMunicipioD" tabindex="24"></select>
                            </td>											
                        </tr>
                        <tr>
			    <td>Calle</td>
                            <td><input type="text"  name="txtCalleD" class="arial10" id="txtCalleD" tabindex="21"/></td>
                            <td>Colonia</td>
                            <td><input type="text" name="txtColoniaD" class="arial10" id="txtColoniaD" tabindex="26"/></td>
                        </tr>
                        <tr>
                            <td>C&oacute;digo Postal</td>
                            <td>
                                <input type="text" name="txtCodigoPD" id="txtCodigoPD" tabindex="20" onblur="cargarDesCP()"/>
                                <div id="autoPostal"></div>
                            </td>
                            <td>Tel&eacute;fono</td>
                            <td>
                            	<input type="text" name="txtTelefonoD" id="txtTelefonoD" tabindex="22"/>
	                            <input type="button" name="btnLimpiar" id="btnLimpiar" value="Nuevo" tabindex="25"/>
                            </td>
                        </tr>
                    </table>		
                </fieldset>            
            </div><br />			
            <div style="width: 1300px;">            
                <fieldset id="izq" class="fldenvio">
                    <legend>Datos del Envio</legend> 
                    <table>	 
                        <tr>
                            <td>Piezas</td>
                            <td><input type="text" name="txtPiezas" id="txtPiezas" size="7" tabindex="27"/></td>
                            <td>KG</td>
                            <td><input type="text" name="txtKg" id="txtKg" size="7" tabindex="28"/></td>																				
                        </tr>
                        <tr>
                            <td>Volumen</td>
                            <td><input type="text" name="txtVol" id="txtVol" size="7" tabindex="29"/></td>
                            <td>Recoleccion</td>
                            <td>
                                <select id = "slcRecoleccion" name="slcRecoleccion" tabindex="30"> 
                                    <option value=""></option>
                                    <option value="Recoleccion">Recoleccion</option>
                                    <option value="Canje">Canje</option>				
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Vigencia</td>
                            <td>
                                <input name="txtVigencia" type="text" id="txtVigencia" class="calendar" size="10" tabindex="31"/>
                                <script type="text/javascript" language="JavaScript"> new tcal ({'controlname': 'txtVigencia'}); </script>
                            </td>
                            <td>Valor Declarado</td>
                            <td><input type="text" name="txtValord" id="txtValord" size="15" tabindex="32"/></td>
                        </tr>										
                    </table>
                </fieldset>            
                <fieldset id="cent" class="fldentrega">
                    <legend>Entrega </legend>
                    <table>
                        <tr>
                            <td>Fecha</td>
                            <td>
                                <input name="txtFechaEntrega" type="text" id="txtFechaEntrega" class="calendar" size="10" tabindex="33"/>
                                <script type="text/javascript" language="JavaScript"> new tcal ({'controlname': 'txtFechaEntrega'}); </script>
                            </td>                    
                        </tr>
                        <tr>
                            <td>Recibio</td>
                            <td colspan="3"><input type="text" size="50" name="txtRecibio" id="txtRecibio" tabindex="34"/></td>
                        </tr>	
                        <tr align="justify" >
                            <td colspan="3">
                                <input type="checkbox" id="chkSello" tabindex="35"/>Sello
                                <input type="checkbox" id="chkFirma" tabindex="36"/>Firma
                                <input type="checkbox" id="chkRespaldo" tabindex="37"/>Respaldos
                                <input type="checkbox" id="chkReexpedicion" tabindex="38"/>Reexpedicion
                            </td>
                        </tr>
                        <tr align="center">
                            <td colspan="3">
                                <span id="error"></span>
                            </td>
                        </tr>	
                    </table>
                </fieldset>	
                <fieldset id="der" class="fldacuse">
                    <legend>Acuse</legend>
                    <table width="159">							
                        <tr height="80">
                            <td width="166" height="92" colspan="2">
                                Fecha de llegada de acuse
                                <input name="txtFechaA" type="text" id="txtFechaA" class="calendar" size="10" tabindex="39"/>
                                <script type="text/javascript" language="JavaScript"> new tcal ({'controlname': 'txtFechaA'}); </script>            
                            </td>
                        </tr>
                    </table>
                </fieldset>		            
            </div><br />
            <div style="width: 1300px;">
                <?php
                    $facturaEnviar="";
                    $cveFactura="";
                    $entregaEnviar="";
                    $cveEntrega="";
                    if($cveGuia!="")
                    {
                        $facturas = $bd->Execute("SELECT facturaSoporte,cveFacturaS FROM cfacturassoporte WHERE cveGuia= '$cveGuia'");
                        foreach($facturas as $factura){
                            $facturaEnviar = $facturaEnviar.$factura["facturaSoporte"].",";
                            $cveFactura    = $cveFactura.$factura["cveFacturaS"].",";
                        }
                        $facturaEnviar = substr($facturaEnviar, 0, strlen($facturaEnviar)-1);
                        $cveFactura = substr($cveFactura, 0, strlen($cveFactura)-1);
                        //$facturaEnviar=str_replace(find,replace,string);
                        
                        $entregas = $bd->Execute("SELECT cveEntregaS,entregasSoporte FROM centregassoporte WHERE cveGuia= '$cveGuia'");
                        
                        foreach($entregas as $entrega){
                            $entregaEnviar=	$entregaEnviar.$entrega["entregasSoporte"].",";
                            $cveEntrega=	$cveEntrega.$entrega["cveEntregaS"].",";
                        }
                        $entregaEnviar = substr($entregaEnviar, 0, strlen($entregaEnviar)-1);
                        $cveEntrega = substr($cveEntrega, 0, strlen($cveEntrega)-1);
                        $observacion = $bd->soloUno("SELECT observaciones FROM cguias WHERE cveGuia= '$cveGuia'");
                    }
                ?>
                <fieldset id="izq" class="fldfactura">
                    <legend>Factura (s)</legend>
                    <table>
                        <tr>
                            <td>
                                <input type="text" name="txtFacturas" id="txtFacturas" size="10" onkeyup="javascript:InsertaNuevaLinea(this,'txaFacturas',event);" tabindex="40"/>												
                                <input type="hidden" name="hdncveFacturas" id="hdncveFacturas" value="<?php echo $cveFactura; ?>" />
                            </td>
                            <td>
                                <textarea rows="4" id="txaFacturas" name="txaFacturas" cols="33" tabindex="41"><?php echo $facturaEnviar; ?></textarea>
                            </td>
                        </tr>	
                    </table>										
                </fieldset>            
                <fieldset id="cent" class="fldentregas">            
                    <legend>Entrega (s)</legend>								 
                    <table>
                        <tr>
                        <td>
                            <input type="text" size="8" name="txtEntrega" id="txtEntrega" onkeyup="javascript:InsertaNuevaLinea(this,'txaEntregas',event);" tabindex="42"/>
                            <input type="hidden" name="hdncveEntregas" id="hdncveEntregas" value="<?php echo $cveEntrega; ?>"/>
                        </td>
                        <td>
                            <textarea rows="4"  id="txaEntregas" name="txaEntregas" cols="33" tabindex="43"><?php echo $entregaEnviar; ?></textarea>
                        </td>
                        </tr>
                    </table>				
                </fieldset>
                <fieldset id="der" class="fldvale">
                    <legend>Vale</legend>
                    <table>
                        <tr height="80" >
                            <td align="center">
                                <input type="text" name="txtVales" id="txtVales" size="15" tabindex="44"/>
                                <input type="hidden" name="hdnVales" id="hdnVales"/>
                            </td>										
                        </tr>									   									   	
                    </table>
                </fieldset>    
            </div>
            <div>
                <div class="observaciones">
                    <fieldset class="fldobservaciones">
                        <legend>Observaciones</legend>
                        <textarea name="txaObservaciones" id="txaObservaciones"  rows="4" cols="74" tabindex="45"><?php echo $observacion; ?></textarea>
                    </fieldset>
                </div>
                <div class="controlesbtn">
                    <fieldset>
                        <legend>Controles</legend>
                        <table>
                            <tr>
                                <td>	                        
                                    <div>	
                                        <span id="status"></span>
                                        <span id="loading" style="display: none">Por favor espere...</span>			
                                    </div>
                                    <input type="button" name="btnGuardar" id="btnGuardar" value= "Guardar" tabindex="45"/>
                                    <input type="button" name="btnModificar" id="btnModificar" value= "Modificar" tabindex="46"/>
                                    <input type="button" name="btnCancelar" id="btnCancelar" value= "Cancelar" tabindex="47"/>
                                </td>
                            </tr>
                        </table>
                    </fieldset>
                </div>
            </div>
        </form>
	</div>
</div>	
</body>
</html>
