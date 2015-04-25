<?php

require_once("direccionamiento.php");
if ((!isset($_SESSION["usuario_valido"]))||(($_SESSION["permiso"]!="Administrador") && ($_SESSION["permiso"]!="Usuario")))
{
    header("Location: login.php");
}
$usuario=$_SESSION["usuario_valido"];
$empresa=$_SESSION["cveEmpresa"];
$sucursal=$_SESSION["cveSucursal"];
$guia='';
if(isset($_GET["guia"]))
	$guia=$_GET["guia"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<link href="estilos/ewebfac1.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="scripts/ajaxReporteIncidentes.js"></script>
	<script type="text/javascript" src="scripts/prototype.js"></script>
	<script type="text/javascript" src="scripts/AjaxLib.js"></script>
	<script type="text/javascript" src="jscripts/globalscripts.js"></script>
	<script type="text/javascript" src="scripts/scriptaculous.js?load=builder,effects,controls"></script>
	<link rel="stylesheet" type="text/css" href="css/mtoForm.css" media="all"/>
	<script type="text/javascript" src="scripts/window.js"> </script>
	<link href="themes/default.css" rel="stylesheet" type="text/css"/> 
	<!-- Add this to have a specific theme--> 
	<link href="themes/mac_os_x.css" rel="stylesheet" type="text/css"/>     
	<!--Calendario-->
    <link rel="stylesheet" href="estilos/calendar.css" type="text/css"/>
    <script type="text/javascript" language="JavaScript" src="jscripts/calendar_es.js"></script>
	<title>EWebFac - Reporte de Incidencias</title>
</head>
<body>
  <div id="contenedor">
<?php
	if($menu!="")	
		require_once($menu);
?>
     <div id="derecho">
        <div class="cabecera">
            <div id="ruta">Reportes | Reporte de incidentes en la entrega</div>
            <h2 id="titulo">Reporte de incidentes en la entrega</h2>
        </div><br />            
        <div class="izquierda">
            <span id="loading" style="display: none">Por favor espere...</span>
            <span id="aviso" style="display: none">Cargando...</span>
            <span id="status"></span>
        </div>
      </div>  
      <br />
      <br />
      <div id="pnlDatosGenerales" class="divIncidencias">
        <form id="form2">
            <fieldset>
              <legend>Datos del Reporte</legend>
                <table align="center" border="0">
                    <tr>
                        <td width="140" >*Gu&iacute;a:</td>
                        <td width="215">
                          <input type="text" id="txtGuia" name="txtGuia" class="input200" tabindex="1" value="<?php echo $guia;?>" />
                          <div id="autoGuia"></div>
                         </td>
                    </tr>
                    <tr>
                        <td width="140" >*Folio Reporte:</td>
                        <td width="215">
                          <input type="text" id="txtReporte" name="txtReporte" class="input200" tabindex="1" />
                          <div id="autoReporte"></div>
                         </td>
                    </tr>
                    <tr>
                      <td>*Tipo de Incidente:</td>
                      <td colspan="2">
                      		<input type="radio"  id="rdBExtemporanea" name="rBbTipoIncidente" value="0" checked="checked" onclick="controlChks(0);"/>Entrega Extempor&aacute;nea
                            <input type="radio" id="rdBFaltantes" name="rBbTipoIncidente" value="1" onclick="controlChks(1);"/>Da&ntilde;os y Faltantes
                      </td>
                    </tr>
                    <tr>
                        <td>Fecha de reporte:</td>
                        <td>
                            <input type="text" id="txtFechaReporte" name="txtFechaReporte" onblur="this.value=this.value.toUpperCase();"  tabindex="3" class="input100"/>
							<script type="text/javascript" language="JavaScript"> new tcal ({'controlname': 'txtFechaReporte'});</script>
                        </td>
                    </tr>
                    <tr>
                        <td>Gu&iacute;a House:</td>
                        <td>
                       		<input type="text" id="txtNGuia" name="txtNGuia"  class="input200" tabindex="4" disabled="disabled"/>
                        </td>
                        <td>Destino:</td>
                        <td>
                       		<input type="text" id="txtDestino" name="txtDestino"  class="input200" tabindex="4" disabled="disabled"/>
                        </td>
						<td>Municipio/Delegaci&oacute;n:</td>
                        <td>
                       		<input type="text" id="txtMunicipio" name="txtMunicipio"  class="input200" tabindex="4" disabled="disabled"/>
                        </td>
                    </tr>
                    <tr>
                        <td>Remitente:</td>
                        <td colspan="3">
                       		<input type="text" id="txtRemitente" name="txtRemitente"  class="input568" tabindex="4" disabled="disabled"/>
                        </td>
                    </tr>
                    <tr>
                        <td>Consignado a:</td>
                        <td colspan="3">
                       		<input type="text" id="txtConsignado" name="txtConsignado"  class="input568" tabindex="4"/>
                        </td>
                    </tr>
                    <tr>
                        <td>L&iacute;nea A&eacute;rea:</td>
                        <td>
                       		<input type="text" id="txtLineaA" name="txtLineaA"  class="input200" tabindex="4" disabled="disabled"/>
                        </td>
						<td width="145">Gu&iacute;a A&eacute;rea:</td>
                        <td width="215">
                       		<input type="text" id="txtGuiaA" name="txtGuiaA"  class="input200" tabindex="4" disabled="disabled"/>
                        </td>
						<td>No De Vuelo:</td>
                        <td>
                       		<input type="text" id="txtVueloA" name="txtVueloA" class="input200" tabindex="4" disabled="disabled"/>
                        </td>
                    </tr>
                    <tr>
                        <td>No De piezas env&iacute;adas:</td>
                        <td>
                       		<input type="text" id="txtPzasEnviadas" name="txtPzasEnviadas"  class="inputRemarcado" tabindex="4" disabled="disabled">
                        </td>
						<td>No De piezas Entregadas:</td>
                        <td>
                       		<input type="text" id="txtPzasEntregadas" name="txtPzasEntregadas"  tabindex="4" class="inputRemarcado"/>
                        </td>
                    </tr>
                    <tr>
                        <td>Kilos Env&iacute;ados:</td>
                        <td>
                       		<input type="text" id="txtKgEnviados" name="txtKgEnviados"  class="inputRemarcado" tabindex="4" disabled="disabled"/>
                        </td>
                        <td>Kilos Entregados:</td>
                        <td>
                       		<input type="text" id="txtKgEntregados" name="txtKgEntregados"  tabindex="4" class="inputRemarcado"/>
                        </td>
                    </tr>
					<tr><td colspan="6" id="tdGrande">Incidente</td></tr>
					<tr>
	                    <td>&nbsp;</td>
                    	<td colspan="4" align="center">                                                     
                        	<input type="checkbox" id="chkExtemporaneo" name="chkFalla"/>Extempor&aacute;neo
                             &nbsp;
                             &nbsp;
                        	<input type="checkbox" id="chkMalEstado" name="chkFalla"/>Mal Estado
                            &nbsp;
                        	<input type="checkbox" id="chkRoto" name="chkFalla"/>Roto
                            &nbsp;
                            &nbsp;
                        	<input type="checkbox" id="chkMojado" name="chkFalla"/>Mojado
                            &nbsp;
                            &nbsp;
                        	<input type="checkbox" id="chkRobado" name="chkFalla"/>Robado
                            &nbsp;
                            &nbsp;
                        	<input type="checkbox" id="chkAbierto" name="chkFalla"/>Abierto
                            &nbsp;
                            &nbsp;
                        	<input type="checkbox" id="chkSucio" name="chkFalla"/>Sucio
                            &nbsp;
                            &nbsp;
                            <input type="checkbox" id="chkOtro" name="chkFalla" disabled="disabled"/>Otro
                        </td>
	                    <td><span id="error"></span></td>
                    </tr>
                    <tr>
                    	<td class="alinVT">Descripci&oacute;n:</td>
						<td colspan="5">
                       		<textarea id="txtaDescripcion" name="txtaDescripcion" rows="5" cols="130"></textarea>
                        </td>
                    </tr>
                    <tr>
                    	<td>Elabor&oacute;:</td>
                        <td colspan="2"><input type="text" id="txtElaboro" name="txtElaboro" class="input350" /></td>
                   </tr>
                   <tr>
                    	<td>Corrobor&oacute;:</td>
                        <td colspan="2"><input type="text" id="txtCorroboro" name="txtCorroboro" class="input350" /></td>
                    </tr>                    
                </table>
                <table border="0" width="1054">
	                <caption id="capPreguntas">Soluci&oacute;n al problema</caption>
                    <tr>
	                    <td width="220" class="alinVT">Descripci&oacute;n del problema detectado:</td>
	                    <td><textarea id="txtaDesProblemaSol" name="txtaDesProblemaSol" rows="5" cols="130"></textarea></td>
                    </tr>
                    <tr>
	                    <td>Fecha de detecci&oacute;n del problema:</td>
	                    <td>
                        	<input id="txtFechaDet" name="txtFechaDet" type="text" class="input100"/>
							<script type="text/javascript" language="JavaScript"> new tcal ({'controlname': 'txtFechaDet'});</script>                            
                        </td>
                    </tr>
                    <tr>
	                    <td>Qui&eacute;n detecta el problema:</td>
	                    <td><input id="txtPersonaDet" name="txtPersonaDet" type="text" class="input450"/></td>
                    </tr>
                    <tr>
	                    <td>T&eacute;cnica utilizada en la soluci&oacute;n:</td>
	                    <td><input id="txtTecnica" name="txtTecnica" type="text" class="input450"/></td>
                    </tr>
                    <tr>
	                    <td>Fecha de soluci&oacute;n:</td>
	                    <td>
                        	<input id="txtFechaSol" name="txtFechaSol" type="text" class="input100"/>
							<script type="text/javascript" language="JavaScript"> new tcal ({'controlname': 'txtFechaSol'});</script>                            
                        </td>
                    </tr>
                    <tr>
	                    <td>Qui&eacute;n soluciona el problema:</td>
	                    <td><input id="txtPersonaSol" name="txtPersonaSol" type="text" class="input450"/></td>
                    </tr>
                    <tr>
	                    <td class="alinVT">Descripci&oacute;n de la soluci&oacute;n:</td>
	                    <td><textarea id="txtaDesSol" name="txtaDesSol" rows="5" cols="130"></textarea></td>
                    </tr>
                </table>
            </fieldset>
            <div class="controles">
                <input type="button" id="btnImprimir"  name="btnImprimir" value= "Imprimir" tabindex="6" onclick="imprimir();"/>
                <input type="button" id="btnGuardar" name="btnGuardar" value="Guardar" tabindex="7" onclick="insertar();"/>
                <input type="button" id="btnModificar" name="btnModificar" value="Modificar" tabindex="8" onclick="actualizar();"/>
                <input type="button" id="btnCancelar" name="btnCancelar" value="Cancelar"  tabindex="9" onclick="limpiar();"/>
            </div>
		</form>
	</div>
    <br /><br />
    <table width="1000" align="center" class="gridView" id="tblReporte">
        <tr>
            <th width="63" align="center">Folio de Reporte</th>
            <th width="63" align="center">Fecha de Reporte</th>            
            <th width="124" align="center">Tipo de Incidente</th>
            <th width="168" align="center">Destino</th>
            <th width="169" align="center">Municipio/Delegaci&oacute;n</th>
			<th width="96" align="center">L&iacute;nea &Aacute;erea</th>
            <th width="74" align="center">Gu&iacute;a &Aacute;erea</th>
            <th width="74" align="center">No Vuelo</th>                        
            <th width="119" align="center">Descripci&oacute;n</th>                                    
            <th width="55" align="center">Editar</th>
        </tr>
    </table>
 </div>
</body>
</html>
