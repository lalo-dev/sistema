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
	$cveGuia = @$_GET["cveGuia"];
	include("scripts/bd.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

	<script type="text/javascript" src="jscripts/globalscripts.js"></script>
    <script type="text/javascript" src="scripts/ajaxGuiaReImpresion.js"></script>
    <script type="text/javascript" src="scripts/prototype.js"></script>
    <script type="text/javascript" src="scripts/AjaxLib.js"></script>
    <script type="text/javascript" src="scripts/scriptaculous.js?load=builder,effects,controls"></script>
    <script type="text/javascript" src="scripts/window.js"> </script>
    <script type="text/javascript" language="JavaScript" src="jscripts/calendar_es.js"></script>    
    <link rel="stylesheet" href="estilos/calendar.css" type="text/css"/>    
    <link rel="stylesheet" type="text/css" href="css/mtoForm.css" media="all"/>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
    <title>Reimpresi&oacute;n de Guias</title>
    <link href="themes/default.css" rel="stylesheet" type="text/css"/>    
    <link href="themes/mac_os_x.css" rel="stylesheet" type="text/css"/>
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
        <!-- ********************************************************************************************************* -->
    	<form id="form2">
        	<fieldset style="width:800px;">
           	  <legend>Filtro</legend>
                <table width="723" border="0">
                    <tr>
                        <td width="107">
                        	<input type="radio" id="rdbGuia" name="rdbFiltro" checked="checked" onclick="chkFiltro(this,0);"/>
                            Gu&iacute;a
                        </td>
                        <td width="54" align="right">Gu&iacute;a:</td>
                        <td width="210">
	                        <input type="text" id="txtGuia" value ="<?php echo $cveGuia;?>" size="35" maxlength="10" tabindex="1"/>
                            <div id="autoGuia"></div>
                        </td>
                    </tr>
                    <tr>
						<td>
                        	<input type="radio" id="rdbRango" name="rdbFiltro" onclick="chkFiltro(this,1);"/>
                            Rango
                        </td>                    
                        <td align="right">Rango Inicial:</td>
                        <td><input type="text" id="txtRangoInicio" size="15" disabled="disabled" /></td>
                        <td width="61" align="right">Rango Final:</td>
                        <td width="90"><input type="text" id="txtRangoFin" size="15" disabled="disabled"  /></td>
                    </tr>
                    <tr>
                    	<td>&nbsp;</td>
                        <td align="right">Cliente:</td>
                        <td colspan="3">
                        	<input type="text" id="txtCliente" size="45" disabled="disabled" />
                            <div id="autoClienteC"></div>
                            <input type="hidden" id="hddCveCliente" value="0" disabled="disabled" />
                        </td>
                    </tr>
                    	<td>&nbsp;</td>                    
                        <td align="right">Fecha Inicial:</td>
                        <td>
                        	<input type="text" id="txtFechaInicio" name="txtFechaInicio" size="15"  disabled="disabled" class="calendar" />
                            <script type="text/javascript" language="JavaScript"> new tcal ({'controlname': 'txtFechaInicio'}); </script>
                        </td>
                        <td align="right">Fecha Final:</td>
                        <td>
                        	<input type="text" id="txtFechaFin" name="txtFechaFin" size="15"  disabled="disabled" class="calendar" />
							<script type="text/javascript" language="JavaScript"> new tcal ({'controlname': 'txtFechaFin'}); </script>                            
                        </td>
                    </tr>
                </table>
            </fieldset>
        </form>
        <!-- ********************************************************************************************************* -->
        <div id="divsas">
        <form id="form3">
            <div style="width: 1300px;">
                <div class="controlesbtn_XXX" style="position:absolute; margin-top:20px; margin-left:500px;">
                    <fieldset>
                        <legend>Controles</legend>
                        <table>
                            <tr>
                                <td>	                        
                                    <div>	
                                        <span id="status"></span>
                                        <span id="loading" style="display: none">Por favor espere...</span>			
                                    </div>
                                    Imprimir N&uacute;mero de Gu&iacute;a:<br />
                                    <input type="radio" name="rdbImprimir" id="rdbImprimirSi" checked="checked">Si</input>
                                    <input type="radio" name="rdbImprimir" id="rdbImprimirNo">No</input>
                                    <input type="button" name="btnModificar" id="btnModificar" value= "Imprimir" tabindex="46" />
                                    <input type="button" name="btnCancelar" id="btnCancelar" value= "Cancelar" tabindex="47" />
                                </td>
                            </tr>
                        </table>
                    </fieldset>
                </div>
                <fieldset id="izqd" class="fldgenerales">
                    <legend>No. de Gu&iacute;a</legend>
                    <table>
                        <tr>
                            <td width="60">Piezas</td>
                            <td>
                            	<input type="text" name="txtPiezas" id="txtPiezas" size="7" disabled="disabled" tabindex="27"/>
                                KG <input type="text" name="txtKg" id="txtKg" size="7" disabled="disabled" tabindex="28"/>
                            </td>																				
                        </tr>
                        <tr>
                            <td>Volumen</td>
                            <td><input type="text" name="txtVol" id="txtVol" size="7" disabled="disabled" tabindex="29"/></td>
                        </tr>   
                        <tr>
                            <td></td>
                            <td width="275">
                                <div id="autoCliente"></div>
                                <input name="txtCodigoC" type="hidden"  id="txtCodigoC" />
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                              <input type="hidden" id="txthStatus" name="txthStatus" value="" />
                            </td>
                        </tr>
                    </table>		
                </fieldset>
            </div><br />
            <div style="width:1500px;">
                <fieldset class="fldremitente">
                    <legend>Remitente</legend>	
                    <table>
                        <tr>
                            <td>Nombre</td>
                            <td><input type="text" name="txtRemitente" size="35" id="txtRemitente" class="arial10" disabled="disabled" tabindex="10"/></td>
                            <td>RFC</td>
                            <td>
                            	<input type="text" name="txtRfcR" id="txtRfcR" disabled="disabled" tabindex="11"/>
                            	<input type="hidden" name="hdncveDireccion" id="hdncveDireccion"/>
                            </td>
                        </tr>
                        <tr>
                            <td>Estado</td>
                            <td>
                                <select name="txtNombredo" id="txtNombredo" disabled="disabled" tabindex="12">
                                <?php $opciones = $bd->get_select('cestados','cveEstado','nombre',0,''); echo $opciones; ?>
                                </select>
                            </td>
                            <td>Municipio / Delegaci&oacute;n</td>
                            <td><select name="txtMunR" id="txtMunR" disabled="disabled" tabindex="13"></select></td>											
                        </tr>
                        <tr>
                            <td>Calle </td>
                            <td><input type="text"  name="txtCalleR" id="txtCalleR" class="arial10" disabled="disabled" tabindex="14"/></td>
                            <td>Colonia</td>
                            <td><input type="text" name="txtColR" id="txtColR" class="arial10" disabled="disabled" tabindex="15"/></td>										
                        </tr>
                        <tr>
                            <td>C&oacute;digo Postal</td>
                            <td><input type="text" name="txtCodigoPr" id="txtCodigoPr" disabled="disabled" tabindex="16"/></td>
                            <td>Tel&eacute;fono</td>
                            <td><input type="text" name="txtTelefonoR" id="txtTelefonoR" disabled="disabled" tabindex="17"/>
                            </td>							
                        </tr>										
                    </table>
                </fieldset><!--<br /><br />-->
                <fieldset id="derecho2" class="flddestinatario">
                    <legend>Destinatario</legend>	
                    <table>
                        <tr>
                            <td>Estacion Destino 
                            </td>
                            <td>
                                <select id="slcSucursal" name="slcSucursal" disabled="disabled" tabindex="18">
                                </select>                    
                            </td>									
                            <td>Nombre</td>
                            <td>
                                <input type="text" name="txtNombreDes" id="txtNombreDes" size="35" class="arial10" disabled="disabled" tabindex="19"/>
                                <div id="autoDestinatario"></div>
                                <input type="hidden" id="txthNombreDes" name="txthNombreDes" value="0" />
                                <input type="hidden" id="txthNombreDesP" name="txthNombreDesP" value="0" />
                                <input type="hidden" id="txthAlta" name="txthAlta" value="0" />
                            </td>								
                        </tr>
                        <tr>
                            <td>Estado</td>
                            <td>
                                <select name="txtEstadoD" id="txtEstadoD" disabled="disabled" tabindex="23">
				<?php $opciones = $bd->get_select('cestados','cveEstado','nombre',0,''); echo $opciones; ?>
				</select>
                            </td>											
                            <td>Municipio / Delegaci&oacute;n</td>
                            <td>
                                <select name="txtMunicipioD" id="txtMunicipioD" disabled="disabled" tabindex="24"></select>
                            </td>											
                        </tr>
                        <tr>
			    <td>Calle</td>
                            <td><input type="text"  name="txtCalleD" class="arial10" id="txtCalleD" disabled="disabled" tabindex="21"/></td>
                            <td>Colonia</td>
                            <td><input type="text" name="txtColoniaD" class="arial10" id="txtColoniaD" disabled="disabled" tabindex="26"/></td>
                        </tr>
                        <tr>
                            <td>C&oacute;digo Postal</td>
                            <td>
                                <input type="text" name="txtCodigoPD" id="txtCodigoPD" tabindex="20" disabled="disabled" onblur="cargarDesCP()"/>
                                <div id="autoPostal"></div>
                            </td>
                            <td>Tel&eacute;fono</td>
                            <td>
                            	<input type="text" name="txtTelefonoD" id="txtTelefonoD" disabled="disabled" tabindex="22"/>
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
            </div>
            <!--<div style="width:535px;">
            	<fieldset>
                	<legend>Observaciones</legend>
                        Obs
                        <br />
                        <textarea name="txaObservacionesC" id="txaObservacionesC"  rows="2" cols="44" tabindex="45"></textarea>
                        <br />
                        Dice contener
                        <br />
                        <input type="text" id="txtDContener" name="txtDContener" size="80"/> 
                </fieldset>
            </div>-->
            
            <div class="observacionesClienteReImp">
                    <fieldset class="fldobservacionesCliente" style="width:600px;">
                        <legend>Observaciones Cliente</legend>
                        Dice contener
                        <br />
                        <textarea id="txtDContener" name="txtDContener" cols="48" rows="2" disabled="disabled" /></textarea>
                        <br />
                        Obs
                        <br />
                        <textarea name="txaObservacionesC" id="txaObservacionesC" cols="48" rows="4" disabled="disabled" tabindex="45"></textarea>                        
                    </fieldset>
                </div>
            
        </form>
        </div><!-- divsas-->
	</div>
</div>
<script>window.onerror=null</script>
</body>
</html>
