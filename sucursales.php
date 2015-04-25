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
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link href="estilos/ewebfac1.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="scripts/ajaxSucursales.js"></script>
	<script type="text/javascript" src="scripts/prototype.js"></script>
	<script type="text/javascript" src="scripts/AjaxLib.js"></script>
	<script type="text/javascript" src="jscripts/globalscripts.js"></script>
	<script type="text/javascript" src="scripts/scriptaculous.js?load=builder,effects,controls"></script>
	<link rel="stylesheet" type="text/css" href="css/mtoForm.css" media="all"/>
	<script type="text/javascript" src="scripts/window.js"> </script>
	<link href="themes/default.css" rel="stylesheet" type="text/css"/> 
	<!-- Add this to have a specific theme--> 
	<link href="themes/mac_os_x.css" rel="stylesheet" type="text/css"/>     
	<title>EWebFac - Sucursales</title>
</head>
<body>
 <div id="contenedor">
<?php
	if($menu!="")	
		require_once($menu);
?>

<div id="derecho">
    <div class="cabecera">
         <div id="ruta">Catalogos | Sucursales</div>
        <h2 id="titulo">Sucursales</h2>
    </div><br />
    <div class="izquierda">
        <span id="loading" style="display: none">Por favor espere...</span>
        <span id="aviso" style="display: none">Cargando...</span>
        <span id="status"></span>
    </div>
</div>  <br / ><br />
<div id="pnlDatosGenerales">
	<table width="543">
		<tr align="left">
			<td>
				<img src="imagenes/impresora.png" title="Imprimir Sucursales" onclick="imprimirCatalogo(3,'Sucursales');" class='imgImp'/>
				Imprimir Cat&aacute;logo
			</td>
		</tr>
	</table>
	<br />
<form id="form2">
	<fieldset>
		<legend>Datos Generales</legend>
             <table align="center">
                <tr>
                    <td width="46" >Nombre</td>
                    <td width="356"><input type="text" id="txtrazon" name="txtrazon" class="input350" tabindex="1"/>
                        <input type="hidden" id="hdnClave" name="hdnClave" />
                        <input type="hidden" id="hdnClaveEm" name="hdnClaveEm" />
                        <input type="hidden" id="txthrazon" name="txthrazon" />
<div id="divRazon"></div>
                    </td>
                    <td width="212"><input type="text" id="totalReg" name="totalReg" class="totalReg" readonly="readonly"/></td>
                </tr>
                <tr>
                    <td>Empresa</td>
                    <td>
                        <select name="slcEmpresa" id="slcEmpresa" Class="input350" tabindex="2"></select></td>                                    
                    </td>
                </tr>
               <tr id="act_des">
                    <td align="right"><input type="checkbox" id="chkActivado" name="chkActivado" onchange="Cambia(this.checked);" tabindex="3"/></td>
                    <td>
                        <input type="text" id="lblActivado" name="lblActivado" readonly="readonly" style="border:0" value="" />
                    </td>    
                    <td></td>                           
                </tr>                      
           </table>            
	</fieldset>
    <div class="controles">
        <input type="button" id="btnBuscar"  name="btnBuscar" value= "Buscar" tabindex="4" />
        <input type="button" id="btnGuardar" name="btnGuardar" value="Guardar" tabindex="5"/>
        <input type="button" id="btnModificar" name="btnModificar" value="Modificar" tabindex="6"/>
        <input type="button" id="btnCancelar" name="btnCancelar" value="Cancelar"  tabindex="7"/>                    
    </div>
</form><br /><br />
<table width="938" align="center" class="gridView" id="tblSucursales" >
          <tr>
            <th width="81" align="center">Cve. Sucursal</th>
            <th width="267" align="center">Nombre Sucursal</th>
            <th width="87" align="center">Cve. Empresa</th>
            <th width="263" align="center">Nombre Empresa</th>
            <th width="100" align="center">Estatus</th>                    
            <th width="100" align="center">Editar</th>                    
          </tr>
</table>
</div>
</div>
</body>
</html>
