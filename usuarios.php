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
	<script type="text/javascript" src="scripts/ajaxUsuarios.js"></script>
	<script type="text/javascript" src="scripts/prototype.js"></script>
	<script type="text/javascript" src="scripts/AjaxLib.js"></script>
	<script type="text/javascript" src="jscripts/globalscripts.js"></script>
	<script type="text/javascript" src="scripts/scriptaculous.js?load=builder,effects,controls"></script>
	<link rel="stylesheet" type="text/css" href="css/mtoForm.css" media="all"/>
	<link href="estilos/ewebfac1.css" rel="stylesheet" type="text/css" />   
	<script type="text/javascript" src="scripts/window.js"> </script>
	<link href="themes/default.css" rel="stylesheet" type="text/css"/> 
	<!-- Add this to have a specific theme--> 
	<link href="themes/mac_os_x.css" rel="stylesheet" type="text/css"/>
	<title>EWebFac - Usuarios</title>    
</head>
<body>
 <div id="contenedor">
<?php
	if($menu!="")	
		require_once($menu);
?>
  
           
    <div id="derecho">
        <div class="cabecera">
            <div id="ruta">Catalogos | Usuarios</div>
            <h2 id="titulo">Usuarios</h2>
        </div><br />
        <div class="izquierda">
            <span id="loading" style="display: none">Por favor espere...</span>
            <span id="aviso" style="display: none">Cargando...</span>
            <span id="status"></span>
        </div>
    </div><br /><br />
    <form id="form2">
                <div class="izquierda">                
                    <div id="pnlDatosGenerales">
			<table width="543">
				<tr align="left">
					<td>
						<img src="imagenes/impresora.png" title="Imprimir Usuarios" onclick="imprimirCatalogo(7,'Usuarios');" class='imgImp'/>
						Imprimir Cat&aacute;logo
					</td>
				</tr>
			</table>
			<br />
                        <fieldset>
                        <legend>Datos generales</legend>
                            <table width="827" >                        
                            <tr>
                                <td width="150">Codigo:</td>
                                <td colspan="2">
                                	<input type="text" id="txtClave" name="txtClave" size="30" tabindex="1"/>
                                  	<div id="divClave" ></div>
                                </td>
                                <td>
        	                    	<input type="text" id="totalReg" name="totalReg" class="totalReg" readonly="readonly"/>
                                </td>
                            </tr>
                            <tr>
                                <td>*Nombre:</td>
                                <td colspan="3"><input type="text" id="txtNombre" name="txtNombre" tabindex="2" /></td>
                                
                            </tr>
                            <tr>
                                <td>*Apellido paterno:</td>
                                <td width="144"><input type="text" id="txtApellidoPaterno" name="txtApellidoPaterno" tabindex="3"/></td>
                                <td width="162">*Apellido materno:</td>
                                <td width="351"><input type="text" id="txtApellidoMaterno" name="txtApellidoMaterno" tabindex="4"/></td>
                            </tr>
                            <tr>
                                <td>*Nick:</td>
                                <td>
                                	<input type="text" id="txtNick" name="txtNick" tabindex="5"/>
                                   	<input type="hidden" id="txthNick" name="txthNick" tabindex="5"/>
                                </td>
                              <td >*Password: <label class="arial10">(m&iacute;n. 5)</label></td>
                                <td><input type="password" id="txtPassword"  name="txtPassword" tabindex="6"/></td>
                            </tr>
                            <tr>
                                <td>&Aacute;rea:</td>
                                <td><input type="text" id="txtAreas" name="txtAreas" tabindex="7" /></td>
                                <td>Departamento:</td>
                                <td><input type="text" id="txtDepartamento" name="txtDepartamento" tabindex="8" /></td>
                            </tr>
                            <tr>
                                <td>*Perfil:</td>
                                <td><select name="slcPerfil" id="slcPerfil" tabindex="9">
                                            <option value="">Seleccionar...</option>
                                            <option value="Administrador">Administrador</option>
                                            <option value="Corresponsal">Corresponsal</option>
                                            <option value="Cliente">Cliente</option>
                                            <option value="Facturacion">Facturacion</option>
                                            <option value="Guias">Guias</option>
                                            <option value="Usuario">Usuario</option>
                                        </select>
                                </td>
                                <td><label id="lblTexto" name="lblTexto"></label></td>
                                <td>
                                    <input type="hidden" id="txthClave" name="txthClave" />
                                    <select name="slcEstacion" id="slcEstacion" tabindex="10" class="oculto1">
                                    </select>
                                	<input type="text" id="txtcveCliente" name="txtcveCliente" class="oculto1" />
                                    <div id="autoCliente"></div>
                                </td>
                           </tr>
                           <tr>
                                <td>*Empresa:</td>
                                <td><select name="slcEmpresa" id="slcEmpresa" tabindex="11"></select></td>
                                <td>*Sucursal:</td>
                                <td><select name="slcSucursal" id="slcSucursal" tabindex="12"></select></td>
                            </tr>
                            <tr id="act_des">
                                <td align="right">
                                     <input type="checkbox" id="chkActivado" name="chkActivado" onchange="Cambia(this.checked);" tabindex="13"/>
                                </td>
                                <td>
                                    <input type="text" id="lblActivado" name="lblActivado" readonly="readonly" style="border:0" value="" />
                                </td>                               
                            </tr>
                        </table>
                        </fieldset>
                    </div>
                    <div class="controles">
                        <input type="button" id="btnBuscar"  name="btnBuscar" value= "Buscar" tabindex="14" />
                        <input type="button" id="btnGuardar" name="btnGuardar" value="Guardar" tabindex="15"/>
                        <input type="button" id="btnModificar" name="btnModificar" value="Modificar" tabindex="16" />
                        <input type="button" id="btnBorrar" name="btnBorrar" value="Borrar" tabindex="17" />
                        <input type="button" id="btnCancelar" name="btnCancelar" value="Cancelar" tabindex="18"/>
                    </div>
                </div>
                <br /><br />
     </form>
</div>
</body>
</html>

