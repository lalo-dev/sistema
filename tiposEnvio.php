<?php

/**
 * @author Jose Miguel Pantaleon
 * @copyright 2010
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
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	<link href="estilos/ewebfac1.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="scripts/ajaxTiposE.js"></script>
	<script type="text/javascript" src="scripts/prototype.js"></script>
	<script type="text/javascript" src="scripts/AjaxLib.js"></script>
	<script type="text/javascript" src="jscripts/globalscripts.js"></script>
	<script type="text/javascript" src="scripts/scriptaculous.js?load=builder,effects,controls"></script>
	<link rel="stylesheet" type="text/css" href="css/mtoForm.css" media="all"/>
	<script type="text/javascript" src="scripts/window.js"> </script>
	<link href="themes/default.css" rel="stylesheet" type="text/css"/> 
	<!-- Add this to have a specific theme--> 
	<link href="themes/mac_os_x.css" rel="stylesheet" type="text/css"/>     
	<title>EWebFac - Tipos de Env&iacute;o</title>
</head>
<body>
 <div id="contenedor">
<?php
	if($menu!="")	
		require_once($menu);
?>
<div id="derecho">
    <div class="cabecera">
        <div id="ruta">Catalogos | Tipos de Env&iacute;o</div>

        <h2 id="titulo">Tipos de Env&iacute;o</h2>
    </div>
</div>
<form id="form2"><br />
     <div class="izquierda">
        <span id="loading" style="display: none">Por favor espere...</span>
        <span id="aviso" style="display: none">Cargando...</span>
        <span id="status"></span>
      </div><br /><br />
     <div id="pnlDatosGenerales">
	<table width="543">
		<tr align="left">
			<td>
				<img src="imagenes/impresora.png" title="Imprimir Env&iacute;os" onclick="imprimirCatalogo(5,'Envios');" class='imgImp'/>
				Imprimir Cat&aacute;logo
			</td>
		</tr>
	</table>
	<br />
        <fieldset >
        <legend>Datos generales</legend>
            <table width="662" >
                <tr>
                    <td width="110" >*Tipo de Env&iacute;o</td>
                    <td width="540" colspan ="3">

                      <input type="text" id="txtEnvio" name="txtEnvio" tabindex="1" />
                      <input type="hidden" id="txthClaveEnvio" name="txthClaveEnvio"  tabindex="2"/>
                      <input type="text" id="totalReg" name="totalReg" class="totalReg" readonly="readonly"/></td>
                    <div id="divClave"></div>
                </tr>
                
                <tr>
                    <td >*Descripci&oacute;n</td>
                    <td >

                        <input type="text" id="txtDescripcion" name="txtDescripcion" size="80" tabindex="3" class="input510" />
                    </td> 
                </tr>    
                <tr id="act_des">
                    <td align="right"><input type="checkbox" id="chkActivado" name="chkActivado" onchange="Cambia(this.checked);" tabindex="4"/></td>
                    <td>
                        <input type="text" id="lblActivado" name="lblActivado" readonly="readonly" style="border:0" value="" />
                    </td>                               
                </tr>                                                   
            </table>
            <div class="controles">

        <input type="button" id="btnBuscar"  name="btnBuscar" value= "Buscar" tabindex="5" />
                <input type="button"  id="btnGuardar" name="btnGuardar" value="Guardar" tabindex="6"/>
                <input type="button"  id="btnModificar" name="btnModificar" value="Modificar" tabindex="7"/>
                <input type="button"  id="btnCancelar" name="btnCancelar" value="Cancelar" tabindex="8"/>
            </div>
        </fieldset>
    </div>
    <div>
        <fieldset>
            <legend>Tipos de Envio</legend>
            <table>
                <tr>
                    <td>
                         <table class="gridView" id="tblEnvios">
                           <th>Tipo de Env&iacute;o</th>
                           <th>Descripci&oacute;n</th>
                           <th>Editar</th>    
                        </table>
                    </td>
                 </tr>      
            </table>
        </fieldset>
    </div>
</form>
</div>
</body>
</html>
