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
	<script type="text/javascript" src="scripts/ajaxMonedas.js"></script>
	<script type="text/javascript" src="scripts/prototype.js"></script>
	<script type="text/javascript" src="scripts/AjaxLib.js"></script>
	<script type="text/javascript" src="jscripts/globalscripts.js"></script>
	<script type="text/javascript" src="scripts/scriptaculous.js?load=builder,effects,controls"></script>
	<link rel="stylesheet" type="text/css" href="css/mtoForm.css" media="all"/>
	<script type="text/javascript" src="scripts/window.js"> </script>
	<link href="themes/default.css" rel="stylesheet" type="text/css"/> 
	<!-- Add this to have a specific theme--> 
	<link href="themes/mac_os_x.css" rel="stylesheet" type="text/css"/> 
	<title>EWebFac - Monedas</title>
</head>
<body>
 <div id="contenedor">
<?php
	if($menu!="")	
		require_once($menu);
?>
<div id="derecho">
    <div class="cabecera">
        <div id="ruta">Catalogos | Monedas</div>
        <h2 id="titulo">Monedas</h2>
    </div>
</div>
<br /><br />
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
				<img src="imagenes/impresora.png" title="Imprimir Monedas" onclick="imprimirCatalogo(4,'Monedas');" class='imgImp'/>
				Imprimir Cat&aacute;logo
			</td>
		</tr>
	</table>
	<br />
        <fieldset >
        <legend>Datos generales</legend>
            <table width="682" >
                <tr>
                    <td width="99" >*Moneda</td>
                  <td><input type="text" id="txtMoneda" name="txtMoneda" class="input200" tabindex="1"/>
                    <input type="hidden" id="txthClaveMon" name="txthClaveMon"  /></td>
                    <td width="348"><input type="text" id="totalReg" name="totalReg" class="totalReg" readonly="readonly"/></td>
                    <div id="divClave"></div>
                </tr>
                
                <tr>
                    <td >*Descripci&oacute;n</td>
                    <td width="219" >
                        <input type="text" id="txtDescripcion" name="txtDescripcion" class="input300" tabindex="2"/>
                    </td>    
                   <td width="348"></td>                          
              </tr>
                <tr id="act_des">
                    <td align="right"><input type="checkbox" id="chkActivado" name="chkActivado" onchange="Cambia(this.checked);" tabindex="3"/></td>
                    <td >
                        <input type="text" id="lblActivado" name="lblActivado" readonly="readonly" style="border:0" value="" />
                    </td>                               
                    <td width="348"></td>                              
                </tr>                                                                               
             </table>
            <div class="controles">
            <input type="button" id="btnBuscar"  name="btnBuscar" value= "Buscar" tabindex="4" />
                <input type="button" id="btnGuardar" name="btnGuardar" value="Guardar" tabindex="5"/>
                <input type="button" id="btnModificar" name="btnModificar" value="Modificar"  tabindex="6"/>
                <input type="button" id="btnCancelar" name="btnCancelar" value="Cancelar"  tabindex="7"/>
             </div>
       </fieldset>
    </div>
    <div>
        <fieldset>
            <legend>Monedas</legend>
            <table>
                 <tr>
                    <td>
                         <table class="gridView" id="tblMonedas">
                           <th>Moneda</th>
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
