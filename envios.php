<?php  
	/** 
	 * @author miguel 
	 * @copyright 2009 
	 */ 
	require_once("direccionamiento.php");
	if ((!isset($_SESSION["usuario_valido"]))||(($_SESSION["permiso"]!="Administrador") && ($_SESSION["permiso"]!="Usuario") && ($_SESSION["permiso"]!="Facturacion")))
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
	<script type="text/javascript" src="scripts/ajaxEnvios.js"></script> 
    <script type="text/javascript" src="scripts/prototype.js"></script> 
    <script type="text/javascript" src="scripts/AjaxLib.js"></script> 
    <script type="text/javascript" src="scripts/scriptaculous.js?load=builder,effects,controls"></script> 
    <script type="text/javascript" src="scripts/window.js"> </script> 
    <script type="text/javascript" src="jscripts/globalscripts.js"></script>
    <link href="themes/default.css" rel="stylesheet" type="text/css"/> 
    <!-- Add this to have a specific theme--> 
    <link href="themes/mac_os_x.css" rel="stylesheet" type="text/css"/>  
    <link rel="stylesheet" type="text/css" href="css/mtoForm.css" media="all"/> 
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" /> 
    <title>Soporte de Factura</title> 
    <link href="estilos/MenuGeneral.css" rel="stylesheet" type="text/css" /> 
    <link href="estilos/ewebfac1.css" rel="stylesheet" type="text/css" /> 
</head> 
<body>
 <div id="contenedor">
<?php 
	if($menu!="")	
		require_once($menu);
?> 
         
    <div id="derecho">
        <div class="cabecera">
            <div id="ruta">Control de Env&iacute;os | Envios</div> 
            <h2 id="titulo">Envios</h2> 
        </div><br />              
        <div class="izquierda">
            <span id="loading" style="display: none">Por favor espere...</span>
            <span id="aviso" style="display: none">Cargando...</span>
            <span id="status"></span>
        </div>
    </div><br /><br />
    <div id="pnlDatosGenerales">                  
		<form id= "form2" name="form2" action="reporteprueva.php" method="post"> 
          <fieldset id="cliente"> 
            <legend>Cliente</legend> 
            <table width="854" height="35"> 
                    <tr> 
                        <td width="100">Razon Social:</td> 
                        <td width="560">
                          <input type="text" id="txtRazonS" name="txtRazonS" size="85" maxlength="85" value = "Razon Social" onclick = "value = ''" /> 
                           <input type="hidden" name="hdncveCliente" id="hdncveCliente" /> 
                           <div id="autoCliente"></div> 
                        </td>                         
                        <td><input type="text" id="totalReg" name="totalReg" class="totalReg" readonly="readonly"/></td>
                    </tr> 
              </table>
          </fieldset>
          <fieldset> 
                <legend>Folio Acuse</legend> 
                <table width="251" height="35">     
                    <tr> 
                        <td width="64">Folio:</td>  
                        <td width="175">
                            <input name="txtFolio" type="text" id="txtFolio" size="16" value="Folio" onclick = "value = ''" /> 
                            <input type="hidden" value="" id="hdnFolios" name="hdnFolios" /> 
                            <input type="hidden" value="" id="hdnContador" name="hdnContador" /> 
                            <div id="autoFolio"></div> 
                        </td>                         
                    </tr> 
                </table> 
            </fieldset>  
            <fieldset> 
                <legend>Gu&iacute;a</legend> 
                <table width="251" height="35">     
                    <tr> 
                        <td width="60">Guia:</td>  
                        <td width="179"><input type="text" id="txtGuia" value = "Guia" onclick = "value = ''" size="17"/><div id="autoGuia"></div></td> 
                    </tr> 
                </table> 
            </fieldset>  
            <fieldset> 
                <legend>Planificaci&oacute;n / Folio </legend> 
                <table width="251" height="35">     
                    <tr> 
                        <td width="118">Planificaci&oacute;n / Folio:</td>  
                        <td width="121">
                        	<input type="text" id="txtPlanificacion" value = "Vale" onclick = "value = ''" size="10"/>
                        	<div id="autoVale"></div>
                            <input type="hidden" value="" id="hdnVale" name="hdnVale" />
                      </td> 
                    </tr> 
                </table> 
            </fieldset> 
            <fieldset> 
                <legend>Formatos</legend> 
                <table width="251" height="52">   
                    <tr> 
                        <td><input type="radio" id="rdoFormato" name="rdoFormato" value="1" onclick="mostrarArea(0);" checked="checked"/>General</td> 
                        <td><input type="radio" id="rdoFormato" name="rdoFormato" value="2" onclick="mostrarArea(0);"/>Con detalle de Movimientos</td> 
                        <td><img src="imagenes/Spacer.gif" alt="" border="0" width="1px" height="26px"/></td>                                          
                    </tr>
                    <tr> 
                        <td><input type="radio" id="rdoFormato" name="rdoFormato" value="3" onclick="mostrarArea(1);"/>Abierta</td> 
                    </tr>                     
                </table> 
            </fieldset>             
            <fieldset> 
                <legend>Retenci&oacute;n del Iva</legend> 
                <table width="251" height="52">     
                    <tr> 
                        <td width="118">Retencion del Iva:</td>  
                        <td width="121">
                        <input type="text" id="txtValorD" size="7"/>
                        <input type="hidden" id="txthValorD" size="7"/>
                        %</td> 
                    </tr> 
                </table> 
            </fieldset>  
            <fieldset> 
                <legend>Porcentaje Seguro</legend> 
                <table width="251" height="52">     
                    <tr> 
                        <td width="115">Porcentaje Seguro:</td>  
                        <td width="124"><input type="text" id="txtPorcentajeSeguro" size="7" value="2"/>
                        %</td> 
                    </tr> 
                </table> 
            </fieldset>  
            <fieldset> 
           		<legend>Precio del Acuse</legend> 
                <table width="251" height="35">  
                    <tr> 
                        <td width="115">Precio del Acuse:</td>  
                        <td width="124"><input type="text" id="txtPrecioA" size="7" value="50"/></td> 
                    </tr> 
                </table> 
            </fieldset>  
            <fieldset> 
                <legend>Folio de la Factura</legend> 
                <table width="251" height="35"> 
                    <tr> 
                        <td width="115">Folio de la Factura:</td>  
                        <td width="120">
                        	<input type="text" id="txtFolioFactura" name="txtFolioFactura" size="7" />
	                        <div id="autoFactura"></div>
                        </td>
                    </tr> 
                </table> 
            </fieldset>  
            <fieldset> 
                <legend>Tope de la Factura</legend> 
                <table width="251" height="35">
                    <tr> 
                        <td width="117">Tope de la Factura:</td>  
                        <td width="122"><input type="text" id="txtTope" value = "100000" size="15"/></td> 
                    </tr> 
                </table> 
            </fieldset> 
            <fieldset> 
                <legend>Controles</legend> 
                <table height="35">     
                    <tr> 
                        <td>
                        	<input type="hidden" id="txthSeguro" name="txthSeguro" />
                        	<input type="button" id="btnImprimir" name="btnImprimir" value="Imprimir" />
                        </td> 
	                    <td><input type="button" value="Ver Factura" id="btnFactura" name="btnFactura" /></td> 
                        <td><input type="button" value="Guardar" id="btnGuardar" name="btnGuardar" /></td> 
                        <td><input type="button" value="Modificar" id="btnModificar" name="btnModificar" /></td> 
                        <td><input type="button" value="Liberar Guias" id="btnLiberar" name="btnLiberar" /></td> 
                        <td><input type="button" value="Cancelar" id="btnCancelar" name="btnCancelar" /></td> 
                    </tr> 
                </table>
            </fieldset> 
            <fieldset id="fldAbierta" class="fldDatosAbiertaInvisible" >  
                <legend>Datos</legend> 
 				<table>
                  <tr>
                    <td height="61" style="vertical-align:top"><input type="text" name="txtAVale" id="txtAVale" size="10" onkeyup="javascript:InsertaNuevaLinea(this,'txaAVales',event);" />
                      <input type="hidden" name="hdncveFacturas" id="hdncveFacturas" value="<?php echo $cveFactura; ?>" /></td>
                    <td><textarea rows="4" id="txaAVales" name="txaAVales" cols="13"><?php echo $facturaEnviar; ?></textarea></td>
                  </tr>
                </table>
            </fieldset>
			<div align="center" class="oculto" id="datos">            
                <fieldset> 
                    <legend>Env&iacute;os</legend>     
                    <table class="gridView" align="center" id="tablaFormulario"> 
                        <tr> 
                            <th width="15">
                            	<input type="checkbox" checked="true" id="condicion_2" name="condicion_2" onclick="javascript:valoresNulos(this);" />Fecha</th>
                            <th width="43">
                            	<input type="checkbox" checked="true" id="condicion_3" name="condicion_3" onclick="javascript:valoresNulos(this);"/>No. Guia</th>
                            <th width="104">
                            	<input type="checkbox" checked="true" id="condicion_4" name="condicion_4" onclick="javascript:valoresNulos(this);"/>Factura / Remision</th> 
                            <th width="101">
                            	<input type="checkbox" checked="true" id="condicion_5" name="condicion_5" onclick="javascript:valoresNulos(this);"/>Planificacion / Folio</th> 
                            <th width="54">
                            	<input type="checkbox" checked="true" id="condicion_6" name="condicion_6" onclick="javascript:valoresNulos(this);"/>Destino</th>
                            <th width="81">
                            	<input type="checkbox" checked="true" id="condicion_7" name="condicion_7" onclick="javascript:valoresNulos(this);"/>Destinatario</th> 
                            <th width="81">
                            	<input type="checkbox" checked="true" id="condicion_8" name="condicion_8" onclick="javascript:valoresNulos(this);"/>Observacion</th> 
                            <th width="75">
                            	<input type="checkbox" checked="true" id="condicion_9" name="condicion_9" onclick="javascript:valoresNulos(this);"/>V. Declarado</th> 
                            <th width="37">
                            	<input type="checkbox" checked="true" id="condicion_10" name="condicion_10" onclick="javascript:valoresNulos(this);"/>Pzas</th> 
                            <th width="38">
                            	<input type="checkbox" checked="true" id="condicion_11" name="condicion_11" onclick="javascript:valoresNulos(this);"/>Peso</th> 
                            <th width="44">
                            	<input type="checkbox" checked="true" id="condicion_12" name="condicion_12" onclick="javascript:valoresNulos(this);" />Tarifa</th> 
                            <th width="39">
                            	<input type="checkbox" checked="true" id="condicion_13" name="condicion_13" onclick="javascript:valoresNulos(this);"/>Flete</th> 
                            <th width="51">
                            	<input type="checkbox" checked="true" id="condicion_14" name="condicion_14" onclick="javascript:valoresNulos(this);"/>Seguro</th>     
                            <th width="45">
                            	<input type="checkbox" checked="true" id="condicion_15" name="condicion_15" onclick="javascript:valoresNulos(this);"/>Acuse</th> 
                            <th width="58">
                            	<input type="checkbox" checked="true" id="condicion_16" name="condicion_16" onclick="javascript:valoresNulos(this);"/>Importe</th> 
                            <th width="31" id='thIva'>
                            	<input type="checkbox" checked="true" id="condicion_17" name="condicion_17" onclick="javascript:valoresNulos(this);"/>IVA</th> 
                            <th width="59">
                            	<input type="checkbox" checked="true" id="condicion_18" name="condicion_18" onclick="javascript:valoresNulos(this);"/>Subtotal</th> 
                            <th width="81">
                            	<input type="checkbox" checked="true" id="condicion_19" name="condicion_19" onclick="javascript:valoresNulos(this);"/>Retencion IVA</th> 
                            <th width="40">
                            	<input type="checkbox" checked="true" id="condicion_20" name="condicion_20" onclick="javascript:valoresNulos(this);"/>Total</th> 
                            <th width="81">
                            	<input type="checkbox" checked="true" id="condicion_21" name="condicion_21" onclick="javascript:valoresNulos(this);"/>Observacion</th> 
                            <th width="53">Borrar</th> 
                        </tr> 
                    </table>         
                    <table class="gridView" align="right" > 
                        <tr> 
                            <th align="center"></th> 
                            <th align="center">Importe</th> 
                            <th align="center">IVA</th> 
                            <th align="center">Subtotal</th> 
                            <th align="center">Retenci&oacute;n IVA</th> 
                            <th align="center">Total</th> 
                         </tr> 
                         <tr> 
                            <td align="center">Total</td> 
                            <td><input type='text' id='totalImporte' name='totalImporte' class='moneda'/></td> 
                            <td><input type='text' id='totalIva' name='totalIva' class='moneda'/></td> 
                            <td><input type='text' id='totalSubtotal' name='totalSubtotal'class='moneda'/></td> 
                            <td><input type='text' id='totalRetencion' name='totalRetencion' class='moneda'/> </td> 
                            <td><input type='text' id='totalTotal' name='totalTotal' class='moneda'/></td> 
                        </tr>                                                                                                                                             
                    </table>         
                </fieldset> 
            </div> 
            <div align="center" style="width: 950px;" class="oculto"> 
            	<input type="hidden" name="hdnSeguro" id="hdnSeguro" /> 
            </div>     
       </form>
     </div>   
</div>     
</body> 
</html> 
