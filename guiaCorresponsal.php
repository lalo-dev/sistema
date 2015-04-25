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
	$usuario=trim($_SESSION["usuario_valido"]);
	$empresa=trim($_SESSION["cveEmpresa"]);
	$sucursal=trim($_SESSION["cveSucursal"]);
	$cveGuia =trim($_GET["cveGuia"]);
	

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" /> 
    <title>Gu&iacute;as Corresponsal</title>
    <script type="text/javascript" src="scripts/ajaxDatosCorresponsales.js"></script>
    <script type="text/javascript" src="scripts/prototype.js"></script>
    <script type="text/javascript" src="scripts/AjaxLib.js"></script>
    <script type="text/javascript" src="scripts/scriptaculous.js?load=builder,effects,controls"></script>
    <script type="text/javascript" language="JavaScript" src="jscripts/calendar_es.js"></script>
    <link rel="stylesheet" type="text/css" href="css/mtoForm.css" media="all"/>
    <link rel="stylesheet" href="estilos/calendar.css" type="text/css"/>
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

  <div id="encabezado">
		<br />
        <img id="logotipo" src="imagenes/e-webfac.jpg" alt="e-webfac" />
  		<div id="menuayuda"><a href='logout.php'>Salir</a></div>
  </div>
  <p>&nbsp;</p>
  <p><img src="imagenes/Cabecera.jpg" alt="cabecera" width="1339" height="22" id="cabecera" align="middle"/></p>
  </div>
  <div id="contenedor">
        <div id="cuerpo">       
            <form id= "form2" name="form2" >
            <div style="width:1000px;">
            	<div class="unoFieldset">
                    <fieldset class="fldgenerales2">
                            <legend>Generales</legend>
                            <table width="488">
                                <tr>
                                    <td width="50">Guia </td>
                                    <td width="426">
                                        <input type="text" id="txtGuia" tabindex="1" size="45" maxlength="10" value ="<?php echo $cveGuia;?>"/>
                                        <input type="button" name="btnbuscar" id="btnbuscar" value= "Buscar" tabindex="2" />
                                        <input type="hidden" id="txthEstacion" name="txthEstacion" value="" />
                                    </td>
                                </tr>
                                <tr>
                                    <td>	
                                        <div id="autoGuia"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Cliente</td>
                                    <td>
                                        <input type="text" id="txtRazonSocial" name="txtRazonSocial"  size="60" readonly="readonly"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Estatus</td>
                                    <td>
                                        <select id = "slcStatus" name="slcStatus" tabindex="3"> 
                                        </select>
                                   		<input type="hidden" id="txthStatus" name="txthStatus" value=""/></td>
                                </tr>
                            </table>		
                    </fieldset>                
                </div>
                <br />
            	<div class="dosFieldset">
                    <fieldset class="flddestinatario2">
                      <legend>Destinatario</legend>
                      <table width="751">
                            <tr>
                                <td width="57">Sucursal Destino</td>
                                <td width="330"><input type="text" id="slcSucursal" class="disabled" readonly="readonly"></td>									
                                <td width="166">Nombre</td>
                                <td width="174"><input type="text" name="txtNombreDes" id="txtNombreDes" class="disabled" readonly="readonly" /> </td>                            
                            </tr>
                            <tr>
                                <td>Estado </td>
                                <td><input type="text" name="txtEstadoD" id="txtEstadoD" class="disabled" readonly="readonly" size="55" /></td>
                                <td>Municipio / Delegaci&oacute;n</td>
                                <td><input type="text" name="txtMunicipioD" id="txtMunicipioD" class="disabled" readonly="readonly" /></td>
                            </tr>
                            <tr>
                                <td>Colonia </td>
                                <td><input type="text" name="txtColoniaD" id="txtColoniaD" class="disabled" readonly="readonly" size="55"/></td>
                                <td>C&oacute;digo Postal</td>
                                <td><input type="text" name="txtCodigoPD" id="txtCodigoPD" class="disabled" readonly="readonly" /></td>                                        
                            </tr>
                            <tr>
                                <td>Calle</td>
                                <td><input type="text"  name="txtCalleD" id="txtCalleD" class="disabled" readonly="readonly" size="55"/></td>                                        
                                <td>Tel&eacute;fono </td>
                                <td><input type="text" name="txtTelefonoD" id="txtTelefonoD" class="disabled" readonly="readonly" /></td>
                            </tr>                        
                      </table>		
                    </fieldset>
                </div>                
            </div>
            <div style="width:1000px;">
                <div class="tresFieldset">
                    <fieldset class="fldremitente2">
                         <legend>Remitente</legend>
                         <table width="752">
                                <tr>
                                    <td width="58">Nombre</td>
                                    <td width="331"><input type="text" name="txtRemitente" id="txtRemitente" class="disabled" readonly="readonly" size="55"/></td>
                                    <td width="166">RFC</td>
                                    <td width="177"><input type="text" name="txtRfcR" id="txtRfcR" class="disabled" readonly="readonly" /></td>
                                </tr>
                                <tr>
                                    <td>Estado</td>
                                    <td><input type="text" name="txtNombredo" id="txtNombredo" class="disabled" readonly="readonly" size="55"/></td>
                                    <td>Municipio / Delegaci&oacute;n </td>
                                    <td><input type="text" name="txtMunR" id="txtMunR" class="disabled" readonly="readonly" /></td>
                                </tr>
                                <tr>
                                    <td>Colonia </td>
                                    <td><input type="text" name="txtColR" id="txtColR" class="disabled" readonly="readonly" size="55"/></td>
                                    <td>C&oacute;digo Postal</td>
                                    <td><input type="text" name="txtCodigoPr" id="txtCodigoPr" class="disabled" readonly="readonly" /></td>                                        
                                </tr>
                                <tr>
                                    <td>Calle</td>
                                    <td><input type="text"  name="txtCalleR" id="txtCalleR" class="disabled" readonly="readonly" size="55"/></td>
                                    <td>Tel&eacute;fono </td>
                                    <td><input type="text" name="txtTelefonoR" id="txtTelefonoR" class="disabled" readonly="readonly" /></td>
                                </tr>
                         </table>		
                    </fieldset>
                </div>
              <div class="spacer">
                        &nbsp;
                </div>
                <div class="dosFieldset2">
                	<fieldset class="fldentrega2">
                     		<legend>Entrega </legend>
                            <table>
                                <tr>
                                    <td>Fecha</td>
                                    <td>
                                        <input name="txtFechaEntrega" type="text" id="txtFechaEntrega" class="calendar" size="10" tabindex="4"/>
                                        <script type="text/javascript" language="JavaScript"> new tcal ({'controlname': 'txtFechaEntrega'}); </script>
                                    </td>                    
                                </tr>
                                <tr>
                                    <td>Recibio</td>
                                    <td colspan="3"><input type="text" size="50" name="txtRecibio" id="txtRecibio" tabindex="5"/></td>
                                </tr>	
                                <tr align="justify" >
                                    <td colspan="3">
                                        <input type="checkbox" value="1" id="chkSello" tabindex="6"/>Sello
                                        <input type="checkbox" value="1" id="chkFirma" tabindex="7"/>Firma
                                        <input type="checkbox" value="1" id="chkRespaldo" tabindex="8"/>Respaldos
                                        <span id="error"></span> 
                                    </td>                    
                                </tr>	
                            </table>
                    </fieldset>	
                </div>
            </div>
              <div class="dosFieldset3">
              <div align="center">
                            <input type="button" name="btnModificar" id="btnModificar" value= "Modificar" tabindex="9" />
                            <input type="button" name="btnCancelar" id="btnCancelar" value= "Cancelar" tabindex="10"/> 
                            <span id="status"></span>
                </div>
                <div class="centro">
                    <span id="loading" style="display:none;">Por favor espere...</span>
                    <span id="aviso" style="display:none;">Cargando...</span>             
                </div>
                <input type="hidden" size="50" name="hdnUsuario" id="hdnUsuario" tabindex="5" value="<?php echo $usuario; ?>"/>
                <input type="hidden" size="50" name="hdnEmpresaS" id="hdnEmpresaS" tabindex="5" value="<?php echo $empresa; ?>"/>
                <input type="hidden" size="50" name="hdnSucursal" id="hdnSucursal" tabindex="5" value="<?php echo $sucursal; ?>"/>
            </div>
            </form>
		</div>
</div>
</body>
</html>
