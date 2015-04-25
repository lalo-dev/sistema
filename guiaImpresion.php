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
    <script type="text/javascript" src="scripts/prototype.js"></script>
    <script type="text/javascript" src="scripts/AjaxLib.js"></script>
    <script type="text/javascript" src="scripts/scriptaculous.js?load=builder,effects,controls"></script>
    <script type="text/javascript" src="scripts/window.js"> </script>
    <link rel="stylesheet" type="text/css" href="css/mtoForm.css" media="all"/>
    <script type="text/javascript" src="scripts/ajaxGuiaImpresion.js"></script><!-- YOPI -->
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
    <title>Impresi&oacute;n de Guias</title>    
    <link href="themes/default.css" rel="stylesheet" type="text/css"/>    
    <!-- Add this to have a specific theme--> 
    <link href="themes/mac_os_x.css" rel="stylesheet" type="text/css"/>    
    <link href="estilos/ewebfac.css" rel="stylesheet" type="text/css" />
    <link href="estilos/ewebfacd.css" rel="stylesheet" type="text/css" />
    
    <link rel="stylesheet" href="css/guiaImpresion.css" type="text/css"/><!-- YOPI -->
    
</head>

<body>
<div id="contenedor">
	<?php
	if($menu!="")	
		require_once($menu);
    ?>
    <span id="loading" style="display: none"></span>
    <div id="cuerpo" >
    	<div class="form1">
        <br />
        <form id="formImprimirGuias" style="width:1300px;">
            <fieldset>
                <legend>Asignaci&oacute;n y Pre-impresi&oacute;n de Guias</legend>
				<br />
                <table border="0">
                    <tr>
                        <td colspan="3" align="left">
                        	¿Desea imprimir el FOLIO de las Guias?
                            Si<input type="radio" name="rdbImprimir" id="rdbSi" value="1" tabindex="1" onclick="cargarRemitente('si');" />
                            No<input type="radio" name="rdbImprimir" id="rdbNo" value="0" tabindex="2" onclick="cargarRemitente('no');" />
                            <input type="hidden" name="hddImprimirNumGuia" id="hddImprimirNumGuia" value="" />
                            <input type="hidden" name="hddUsuario" id="hddUsuario" value="<?php echo $usuario;?>"  />
                            <input type="hidden" name="hddEmpresa" id="hddEmpresa" value="<?php echo $empresa;?>"  />
                            <input type="hidden" name="hddSucursal" id="hddSucursal" value="<?php echo $sucursal;?>" />
                            <br /><br />
                        </td>
                    </tr>
                	<tr>
                        <td align="left" width="173">Remitente: </td>
                        <td>
                        	<input type="text" name="txtRemitente" id="txtRemitente" disabled="disabled" tabindex="3" style="width:315px;" />
                            <input type="hidden" name="hddCveCliente" id="hddCveCliente" value="0" />
                        </td>
                        <div id="divAutocompletar"></div>
                        <td>RFC</td>
                        <td>
                        	<input type="text" name="txtRfcR" id="txtRfcR" disabled="disabled" tabindex="4"/>
                            <input type="hidden" name="hdncveDireccion" id="hdncveDireccion" value=""/>
                        </td>
                    </tr>
                    <tr>
                        <td>Estado</td>
                        <td>
                            <select name="txtNombredo" id="txtNombredo" disabled="disabled" tabindex="5">
                            <?php $opciones = $bd->get_select('cestados','cveEstado','nombre',0,''); echo $opciones; ?>
                            </select>
                        </td>
                        <td>Municipio / Delegaci&oacute;n</td>
                        <td><select name="txtMunR" id="txtMunR" disabled="disabled" tabindex="6"></select></td>
                    </tr>
                    <tr>
                        <td>Calle </td>
                        <td><input type="text"  name="txtCalleR" id="txtCalleR" disabled="disabled" class="arial10" tabindex="7"/></td>
                        <td>Colonia</td>
                        <td><input type="text" name="txtColR" id="txtColR" disabled="disabled" class="arial10" tabindex="8"/></td>										
                    </tr>
                    <tr>
                        <td>C&oacute;digo Postal</td>
                        <td><input type="text" name="txtCodigoPr" id="txtCodigoPr" disabled="disabled" tabindex="9"/></td>
                        <td>Tel&eacute;fono</td>
                        <td><input type="text" name="txtTelefonoR" id="txtTelefonoR" disabled="disabled" tabindex="10"/></td>
                    </tr>
                </table>
                <div id="divMostrarNo" style="display:block;">
                	<table id="tblMostrarNo">
                    	<tr>
                        	<td colspan="4" align="left"><br />
                            	<!--Folio de Inicio: <input type="text" name="txtNumInicio" id="txtNumInicio" onblur="buscaGuia();" disabled="disabled" tabindex="11" />-->
                                Folio de Inicio: <input type="text" name="txtNumInicio" id="txtNumInicio" onblur="buscaGuia();" disabled="disabled" tabindex="11" />
                                Total de Guias: <input type="text" name="txtTotalGuias" id="txtTotalGuias" onblur="obtenerDisponibles();" disabled="disabled" tabindex="12" />
                                Ultimo Folio: <input type="text" name="txtUltimoFolio" id="txtUltimoFolio" disabled="disabled" tabindex="13" />
                            </td>
                        </tr>
                    </table>
                </div>
                <!--<div id="divMostrarSi" style="display:none;">
                    <table id="tblMostrar">
                        <tr>
                            <td align="left"><br />
                                Total de FOLIOS a imprimir: <input type="text" name="txtTotalFolios" id="txtTotalFolios" disabled="disabled" tabindex="14" />
                            </td>
                        </tr>
                    </table>
                </div>-->
            </fieldset>            
        </form>
        </div>
        <div class="form3">
        <br />
            <fieldset>
                <legend>Controles</legend>
                <br />
                <table>
                    <tr>
                        <td>
                            <!--<input type="button" name="btnImprimirGuias" id="btnImprimirGuias" value= "Imprimir Guias" onclick="imprimirGuias();" disabled="disabled" tabindex="15"/>-->
                            <input type="button" name="btnImprimirGuias" id="btnImprimirGuias" value= "Imprimir Guias" disabled="disabled" tabindex="15"/>
                            <input type="button" name="btnCancelar" id="btnCancelar" value= "Limpiar" onclick="limpiarForm0();" tabindex="16"/>
                        </td>
                    </tr>
                </table>
            </fieldset>
        </div>
	</div><!-- termina divCuerpo -->
</div><!-- termina divContenedor -->
</body>
</html>