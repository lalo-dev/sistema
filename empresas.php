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
	<script type="text/javascript" src="scripts/ajaxEmpresas.js"></script>
	<script type="text/javascript" src="scripts/prototype.js"></script>
	<script type="text/javascript" src="scripts/AjaxLib.js"></script>
	<script type="text/javascript" src="jscripts/globalscripts.js"></script>
	<script type="text/javascript" src="scripts/scriptaculous.js?load=builder,effects,controls"></script>
	<link rel="stylesheet" type="text/css" href="css/mtoForm.css" media="all"/>
	<script type="text/javascript" src="scripts/window.js"> </script>
	<link href="themes/default.css" rel="stylesheet" type="text/css"/> 
	<!-- Add this to have a specific theme--> 
	<link href="themes/mac_os_x.css" rel="stylesheet" type="text/css"/>   
	<title>EWebFac - Empresas</title>
</head>
<body>
 <div id="contenedor">
<?php
	if($menu!="")	
		require_once($menu);
?>
    
	<div id="derecho">
		<div class="cabecera">
			<div id="ruta">Catalogos | Empresas</div>
			<h2 id="titulo">Empresas</h2>
		</div>
	</div><br /><br />
	<form id="form2">
		<div class="izquierda">
			<span id="loading" style="display: none">Por favor espere...</span>
			<span id="aviso" style="display: none">Cargando...</span>
			<span id="status"></span>
		</div>
		<div id="pnlDatosGenerales">
			<table width="543">
				<tr align="left">
					<td>
					<img src="imagenes/impresora.png" title="Imprimir Empresas" onclick="imprimirCatalogo(2,'Empresas');" class='imgImp'/>
					Imprimir Cat&aacute;logo
					</td>
				</tr>
			</table>
			<br />
		<fieldset>
		<legend>
			Datos Generales
		</legend>
                <table width="677" align="left">
                            <tr>
                                <td width="95" >*Raz&oacute;n Social</td>
                                <td width="570"><input type="text" id="txtrazon" name="txtrazon" size="50" tabindex="1"  />
                            <input type="hidden" id="hdnClave" />
                            <input type="hidden" id="txthrazon"  name="txthrazon"/>
                            <input type="text" id="totalReg" name="totalReg" class="totalReg" readonly="readonly"/>
<div id="divRazon"></div></td>
                               
                            </tr>
                            
                            <tr>
                                <td>*R.F.C</td>
                                <td>
                                    <input type="text" id="txtRfc" tabindex="2"  />
                                    <input type="hidden" id="txthRfc"  name="txthRfc"/>
                                </td>
                               
                            </tr>
                            <tr>
                                <td >Direcci&oacute;n</td>
                                <td ><textarea cols="70" id="txtDireccion" tabindex="3"></textarea>
                                </td>
                               
                            </tr>
                            <tr id="act_des">
                                <td align="right"><input type="checkbox" id="chkActivado" name="chkActivado" onchange="Cambia(this.checked);" tabindex="4"/></td>
                                <td >
                                    <input type="text" id="lblActivado" name="lblActivado" readonly="readonly" style="border:0" value="" />
                                </td>                               
                            </tr>                            
                               
                                                         
                        </table>            
	</fieldset>
</div>
          
                  </div>
                <div class="controles">
                    <input type="button" id="btnBuscar" name="btnBuscar" value= "Buscar" tabindex="5" />
                    <input type="button" id="btnGuardar" value="Guardar" tabindex="6" />
                    <input type="button" id="btnModificar" value="Modificar" tabindex="7"/>
                    <input type="button" id="btnCancelar" value="Cancelar" tabindex="8" />                    
                </div>
                <br /><br />
		</div>    
        </form>
</div>          
</body>
</html>
