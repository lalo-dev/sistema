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
<script type="text/javascript" src="scripts/ajaxTarifasDestino.js"></script>
<script type="text/javascript" src="scripts/prototype.js"></script>
<script type="text/javascript" src="scripts/AjaxLib.js"></script>
<script type="text/javascript" src="scripts/scriptaculous.js?load=builder,effects,controls"></script>
<script type="text/javascript" src="jscripts/globalscripts.js"></script>
<link rel="stylesheet" type="text/css" href="css/mtoForm.css" media="all"/>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>EWebFac - Tarifas Destinos</title>
<link href="estilos/ewebfac2.css" rel="stylesheet" type="text/css" />
</head>
<body>
 <div id="contenedor">
<?php
	if($menu!="")	
		require_once($menu);
?>
     <div style="width: 942px;">
        <div class="cabecera">
			<div id="ruta">Datos Base | Tarifas Destinos</div>
            <h2 id="titulo">Tarifas Destinos</h2>
        </div><br />            
        <div class="izquierda">
            <span id="loading" style="display: none">Por favor espere...</span>
            <span id="aviso" style="display: none">Cargando...</span>
            <span id="status"></span>
        </div>
      </div> 
     <form id= "form2" name="form2" action="reportepdf.php" method="post">
        <div align="center">	
            <fieldset  style="width: 530px;">
                <legend>
                    Datos Generales
                </legend>
                <table>             
                    <tr>
                        <td>Primer Rango</td>
                        <td ><input name="txtprimerRango" type="text" maxlength="80" id="txtprimerRango" size="20" class="mayuscula"/>
                        </td>
                        <td>Segundo Rango</td>
                        <td ><input name="txtsegundoRango" type="text" maxlength="80" id="txtsegundoRango" size="20" class="mayuscula"/>
                        </td>
                    </tr>
                    <tr>
                        <td>Tercer Rango</td>
                        <td ><input name="txttercerRango" type="text" maxlength="80" id="txttercerRango" size="20" class="mayuscula"/>
                        </td>
                        <td>Cuarto Rango</td>
                        <td ><input name="txtcuartoRango" type="text" maxlength="80" id="txtcuartoRango" size="20" class="mayuscula"/>
                        </td>
                    </tr>
                    
                    <tr>
                        <td colspan="4" align='center'>
                            <br />
                        </td>
                    </tr>		
                    <tr>
                        <td colspan="4" align='center'>
                            <input type="button" name='btnModificar' id='btnModificar' value='Modificar Rangos' />
                            <input type="button" name='btnCancelar' id='btnCancelar' value='Cancelar' />
                        </td>
                    </tr>						
                </table>								
            </fieldset>
        </div><br /><br />
        <div align="left">
            <fieldset>
                <legend>Agregar Tarifa</legend>	
                <table width="755">
                    <tr>
                        <td width="361">
                            <input type="text" id="txtTarifa" name="txtTarifa" size="60" />
                            <div id="divTarifa"></div>
                        </td>
                        <td width="20"><input type="hidden" value="destino" id="hdnTipoTarifa" name="hdnTipoTarifa"/></td>
                        <td width="358">
                            <input type="button" name="btnGuardar" id="btnGuardar" value="Agregar Tarifa" />
                            <input type="button" name="btnModificarT" id="btnModificarT" value="Modificar Tarifa" />
                            <input type="button" name="btnCancelarT" id="btnCancelarT" value="Cancelar" />
                        </td>
                    </tr>
                    <tr id='rowTarifa'> 
                        <td align="right">
                            <input type="text"   name="txtCantidad" id="txtCantidad" onkeydown="return chk_val(this.id,event);" size="10" />%
                            <select id="slctipoClienteRangos" name="slctipoClienteRangos"></select>
                        </td>
                        <td>&nbsp;</td>
                        <td>
                            <input type="button" name="btnAumentarT" id="btnAumentarT" value=" + " />
                            <input type="button" name="btnReducirT" id="btnReducirT" value=" - " />
                            (Tarifas)
                        </td>
                    </tr>
                    <tr id='rowCargo'>
                        <td align="right">
                            <input type="text"   name="txtCantidadCM" id="txtCantidadCM" onkeydown="return chk_val(this.id,event);" size="10" />
                            %
                            <select id="slctipoClienteCargoMin" name="slctipoClienteCargoMin"></select>
                            <select id="slctipoEnvio" name="slctipoEnvio"></select>
                        </td>
                        <td>&nbsp;</td>
                        <td>
                            <input type="button" name="btnAumentarCM" id="btnAumentarCM" value=" + " />
                            <input type="button" name="btnReducirCM" id="btnReducirCM" value=" - " />
                            (Cargo M&iacute;nimo)
                        </td>
                    </tr>
                </table>
                <table class="gridView" align="left" >
                    <tr>
                        <th colspan="2" align="center">Origen</th>
                        <th colspan="2" align="center">Destino</th>                        
                    </tr>
                    <tr>
                        <th align="center">Estado</th>
                        <th align="center">Zona</th>
                        <th align="center">Estado</th>
                        <th align="center">Zona</th>
                        <th align="center">Tipo de Cliente</th>
                        <th align="center">Tipo de Envio</th>
                        <th align="center">Estatus</th>
                        <th id="thPrimero" align="center"></th>
                        <th id="thSegundo" align="center"></th>
                        <th id="thTercero" align="center"></th>
                        <th id="thCuarto" align="center"></th> 
                        <th align="center">Cargo Minimo</th>
                    </tr>
                    <tr>	
                        <td><select name="slcEstados" id="slcEstados" ></select></td>
                        <td><select name="slcMunicipios" id="slcMunicipios" ></select></td>
                        <td><select name="slcEstadosD" id="slcEstadosD" ></select></td>
                        <td><select name="slcMunicipiosD" id="slcMunicipiosD" ></select></td>
                        <td><select id = "tipoCliente" name="slcTipoC"></select></td>
                        <td><select id = "slcTipoe" name="slcTipoe"></select></td>
                        <td ><input id="cbxEstatus"  type="checkbox" name="cbxEstatus" /><label for="cbxEstatus">Activa</label></td>
                        <td><input type="text" size="5" id="txtTarifa1" name="txtTarifa1" onkeydown="return chk_val(this.id,event);"/></td>
                        <td><input type="text" size="5" id="txtTarifa2" name="txtTarifa2" onkeydown="return chk_val(this.id,event);"/></td>
                        <td><input type="text" size="5" id="txtTarifa3" name="txtTarifa3" onkeydown="return chk_val(this.id,event);"/></td>
                        <td><input type="text" size="5" id="txtTarifa4" name="txtTarifa4" onkeydown="return chk_val(this.id,event);"/></td>
                        <td>
                            <input type="hidden" id="hdncveTarifa" name="hdncveTarifa"/><input type="text" size="6" id="txtTarifaMin" name="txtTarifaMin" onkeydown="return chk_val(this.id,event);"/></td>
                    </tr>										
                </table>
            </fieldset>
       </div><br /><br />
    </form>
    <div align="center" id="visible" class="oculto" style="position:absolute;right:200px;">
		<fieldset>
			<legend>Tarifas Agregadas</legend>	
			<table class="gridView" align="center" id="tablaFormulario" width="900">
                <tr>
                    <th colspan="2" align="center">Origen</th>
                    <th colspan="2" align="center">Destino</th>
                </tr>
                <tr>
                    <th>Estado</th>
                    <th>Zona</th>
                    <th>Estado</th>
                    <th>Zona</th>
                    <th>Tipo de Cliente</th>
                    <th>Tipo de Envio</th>
                    <th >Estatus</th>
                    <th id="thPrimero2"></th>
                    <th id="thSegundo2"></th>
                    <th id="thTercero2"></th>
                    <th id="thCuarto2"></th> 
                    <th>Cargo Minimo</th>
                    <th>Editar</th>
                </tr>	
			</table>
		</fieldset>
   </div>
 </div>
</body>
</html>
