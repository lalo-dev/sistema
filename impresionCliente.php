<?php
		/**
	 * @author miguel
	 * @copyright 2009
	 */
		session_start();
	if ((!isset($_SESSION["usuario_valido"]))||($_SESSION["permiso"]!="Cliente"))
	{
		header("Location: login.php");
	}
	$usuario=$_SESSION["usuario_valido"];
	$empresa=$_SESSION["cveEmpresa"];
	$sucursal=$_SESSION["cveSucursal"];
	$cliente =$_SESSION["cvecliente"];
	$cveGuia = @$_GET["cveGuia"];
	include("scripts/bd.php");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<script type="text/javascript" src="jscripts/globalscripts.js"></script>
    <script type="text/javascript" src="scripts/ajaxImpresionCliente.js"></script>
    <script type="text/javascript" src="scripts/prototype.js"></script>
    <script type="text/javascript" src="scripts/AjaxLib.js"></script>
    <script type="text/javascript" src="scripts/scriptaculous.js?load=builder,effects,controls"></script>
    <script type="text/javascript" src="scripts/window.js"> </script>
    <link rel="stylesheet" type="text/css" href="css/mtoForm.css" media="all"/>
    <link rel="stylesheet" href="estilos/calendar.css" type="text/css"/>
    <script type="text/javascript" language="JavaScript" src="jscripts/calendar_es.js"></script>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" /> 
    <title>Guias</title>
    <link href="themes/default.css" rel="stylesheet" type="text/css"/>    
    <!-- Add this to have a specific theme--> 
    <link href="themes/mac_os_x.css" rel="stylesheet" type="text/css"/>
    <link href="estilos/ewebfac1.css" rel="stylesheet" type="text/css" />
    <link href="estilos/ewebfacd.css" rel="stylesheet" type="text/css" />
</head>

<body>
 <div id="contenedor">
        <div id="encabezado">
            <img id="logotipo" src="imagenes/e-webfac.jpg" alt="e-webfac" height="45" width="120"/>
            <div id="menuayuda">
              <a href='logout.php' title="Salir" style="cursor:pointer">Salir</a>
      </div>
            <img id="cabecera" src="imagenes/Cabecera.jpg" alt="cabecera" height="15" width="944"/>
        </div>
        <div id="derecho">
    <div id="cuerpo">
    <div class="cabecera">
                    <div id="ruta">
                    	Impresi&oacute;n  de Guias
                    	<a href="consultaGuias.php"><input type="button" value="Consulta Gu&iacute;as" /></a>
                    </div>
                    <h2 id="titulo">Impresi&oacute;n  de Guias</h2>
                </div>
        <form id="form2">
            <input type="hidden" id="hdnCliente" value="<?php echo $cliente; ?>" />       
            <div style="width:310px; position:absolute;">
                <fieldset>
                    <legend>Generales</legend>
                    <table border="0">
                        <tr>
                            <td width="30" align="right">Gu&iacute;a: </td>
                            <td>
                                <input type="text" id="txtGuia" value ="<?php echo $cveGuia;?>" size="35" maxlength="10" tabindex="1"/>
                                <div id="autoGuia"></div>
                                <input type="hidden" name="txtRazonSocial" id="txtRazonSocial" value="Numero Cliente" />
                                <input name="txtCodigoC" type="hidden"  id="txtCodigoC" />
                                <input type="hidden" id="slcStatus" name="slcStatus" value="" />
                                <input type="hidden" id="txthStatus" name="txthStatus" value="" />
                            </td>
                        </tr>
                    </table>		
                </fieldset>
            </div>
            <br />
            <div style="width:800px; position:absolute; margin-top:85px; margin-left:0px;">	
                <fieldset class="fldremitente">
                    <legend>Remitente</legend>	
                    <table>
                        <tr>
                            <td>Nombre</td>
                            <td><input type="text" name="txtRemitente" size="35" id="txtRemitente" class="arial10" disabled="disabled" /></td>
                            <td>RFC</td>
                            <td><input type="text" name="txtRfcR" id="txtRfcR" disabled="disabled" /><input type="hidden" name="hdncveDireccion" id="hdncveDireccion"/></td>
                        </tr>
                        <tr>
                            <td>Estado</td>
                            <td>
                                <select name="txtNombredo" id="txtNombredo" disabled="disabled" >
                                <?php $opciones = $bd->get_select('cestados','cveEstado','nombre',0,''); echo $opciones; ?>
                                </select>
                            </td>
                            <td>Municipio / Delegaci&oacute;n</td>
                            <td><select name="txtMunR" id="txtMunR" disabled="disabled" ></select></td>											
                        </tr>
                        <tr>
                            <td>Calle </td>
                            <td><input type="text"  name="txtCalleR" id="txtCalleR" class="arial10" disabled="disabled" /></td>
                            <td>Colonia</td>
                            <td><input type="text" name="txtColR" id="txtColR" class="arial10" disabled="disabled" /></td>										
                        </tr>
                        <tr>
                            <td>C&oacute;digo Postal</td>
                            <td><input type="text" name="txtCodigoPr" id="txtCodigoPr" disabled="disabled" /></td>
                            <td>Tel&eacute;fono</td>
                            <td><input type="text" name="txtTelefonoR" id="txtTelefonoR" disabled="disabled" />
                            </td>							
                        </tr>										
                    </table>
                </fieldset>
                <br/><br />
                <fieldset id="derecho2" class="flddestinatario">
                    <legend>Destinatario</legend>	
                    <table>
                    	<!--<tr>
                        	<td colspan="2" height="35">
                            	Persona<input type="radio" name="rdGrupo" id="rdbPersona" onclick="cambiaGrupo();" />
                                Empresa<input type="radio" name="rdGrupo" id="rdbEmpresa" onclick="cambiaGrupo();" />
                            </td>
                        </tr>-->
                        <tr>
                            <td>Estacion Destino
                            </td>
                            <td>
                                <select id="slcSucursal" name="slcSucursal" tabindex="18">
                                </select>                    
                            </td>									
                            <td>Nombre</td>
                            <td>
                                <!--<input type="text" name="txtNombreDes" id="txtNombreDes" onblur="desSiguiente();" disabled="disabled" size="35" class="arial10" tabindex="19"/>-->
                                <input type="text" name="txtNombreDes" id="txtNombreDes" disabled="disabled" size="35" class="arial10" tabindex="19"/>
                                <div id="autoDestinatario"></div>
                                <input type="hidden" id="txthNombreDes" name="txthNombreDes" value="0" />
                                <input type="hidden" id="txthNombreDesP" name="txthNombreDesP" value="0" />
                                <input type="hidden" id="txthAlta" name="txthAlta" value="0" />
                            </td>								
                        </tr>
                        <tr>
                            <td>Estado</td>
                            <td>
                                <select name="txtEstadoD" id="txtEstadoD" disabled="disabled" onblur="desMunicipio();" tabindex="23">
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
                                <!--<input type="text" name="txtCodigoPD" id="txtCodigoPD" tabindex="20" onblur="cargarDesCP()"/>-->
                                <input type="text" name="txtCodigoPD" id="txtCodigoPD" disabled="disabled" onblur="registrara();" tabindex="20" />
                                <div id="autoPostal"></div>
                            </td>
                            <td>Tel&eacute;fono</td>
                            <td>
                            	<input type="text" name="txtTelefonoD" id="txtTelefonoD" disabled="disabled" tabindex="22"/>
	                            <input type="button" name="btnLimpiar" id="btnLimpiar" value="Nuevo" tabindex="25"/>
                            </td>
                        </tr>
                    </table>
                </fieldset>            
            </div>
            <div style="width:600px;">
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
            <br />
            <div style="width: 500px; position:absolute; margin-top:460px; margin-left:0px;">            
                <fieldset>
                    <legend>Datos del Envio</legend> 
                    <table>	 
                        <tr>
                            <td>Piezas</td>
                            <td><input type="text" name="txtPiezas" id="txtPiezas" size="7" disabled="disabled" tabindex="27"/></td>
                            <td>KG <input type="text" name="txtKg" id="txtKg" size="7" disabled="disabled" tabindex="28"/></td>
                            <td><!--<input type="text" name="txtKg" id="txtKg" size="7" tabindex="28"/>--></td>																				
                        </tr>
                        <tr>
                            <td>Volumen</td>
                            <td><input type="text" name="txtVol" id="txtVol" size="7" disabled="disabled" tabindex="29"/></td>
                            <td>Que dice contener <input type="text" name="txtContenido" id="txtContenido" size="10" disabled="disabled" tabindex="30" /></td>
                    </table>
                </fieldset>		            
            </div>
            <br />
            <div style="position:absolute; width:auto; margin-top:560px; margin-left:0px;">
                <fieldset class="fldobservaciones">
                    <legend>Observaciones de Carga</legend>
                    <table border="0" width="100%">
                    	<tr>
                        	<td width="50%">
                                Dice Contener:
                                <select id="slcDiceContener" name="slcDiceContener" onchange="llenaDiceContener();">
                                </select><br />
                                <textarea id="txtDContener" name="txtDContener" readonly="readonly" cols="60" rows="2" onkeydown="validarTamanio(this,100);" onkeyup="validarTamanio(this,100);" /></textarea><br />
	    	                </td>
                            <td>
                                Observaciones:<br />
                                <textarea name="txaObservaciones" id="txaObservaciones" disabled="disabled" rows="4" cols="60" onkeydown="validarTamanio(this,200);" onkeyup="validarTamanio(this,200);" tabindex="45"><?php echo $observacion;?></textarea>
                            </td>
                        </tr>
                    </table>
                </fieldset>
            </div>
            <div style="position:absolute; margin-top:450px; margin-left:540px;">
                <fieldset>
                    <legend>Controles</legend>
                    <table border="0">
                        <tr>
                            <td>
                                <div>
                                    <span id="status"></span>
                                    <span id="loading" style="display: none">Por favor espere...</span>			
                                </div>
                                <input type="button" name="btnGuardar" id="btnGuardar" value= "Imprimir" onclick="actualizarGuia();" disabled="disabled" tabindex="45"/>
                                <!--<input type="button" name="btnModificar" id="btnModificar" value= "Modificar" tabindex="46"/>-->
                                <input type="button" name="btnCancelar" id="btnCancelar" value= "Cancelar" tabindex="47"/>
                                <!--<input type="button" name="btnReporte" id="btnReporte" value= "Reporte" tabindex="48" onclick="irReporte();"/>-->
                            </td>
                        </tr>
                    </table>
                </fieldset>
            </div></tr>
        </form></div>
	</div>
</div>	
</body>
</html>
