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
	<script type="text/javascript" src="scripts/ajaxDestinos.js"></script>
	<script type="text/javascript" src="scripts/prototype.js"></script>
	<script type="text/javascript" src="scripts/AjaxLib.js"></script>
	<script type="text/javascript" src="jscripts/globalscripts.js"></script>
	<script type="text/javascript" src="scripts/scriptaculous.js?load=builder,effects,controls"></script>
	<link rel="stylesheet" type="text/css" href="css/mtoForm.css" media="all"/>
	<script type="text/javascript" src="scripts/window.js"> </script>
	<link href="themes/default.css" rel="stylesheet" type="text/css"/> 
	<!-- Add this to have a specific theme--> 
	<link href="themes/mac_os_x.css" rel="stylesheet" type="text/css"/>     
	<title>EWebFac - Destinos</title>
</head>
<body>
 <div id="contenedor">
<?php
	if($menu!="")	
		require_once($menu);
?>
<div id="derecho">
    <div id="derecho">
        <div class="cabecera">
            <div id="ruta">Catalogos | Estaciones</div>
            <h2 id="titulo">Estaciones</h2>
        </div>
     </div>
    <br />
    <br />
     <form id="form2">
        <div class="izquierda">
            <span id="loading" style="display: none">Por favor espere...</span>
                        <span id="aviso" style="display: none">Cargando...</span>
                        <span id="status"></span>
            
            <div id="pnlDatosGenerales">
	      <table width="543">
		      <tr align="left">
			      <td>
				<img src="imagenes/impresora.png" title="Imprimir Estaciones" onclick="imprimirCatalogo(10,'Estaciones');" class='imgImp'/>
				Imprimir Cat&aacute;logo
			      </td>
		      </tr>
	      </table>
	      <br />
              <fieldset >
                <legend>Datos generales</legend>
                  <table width="352" align="center">
                      <tr>                                
                          <td>Estaci&oacute;n</td>
                          <td>
                              <input type="text" id="txtDescripcion" onblur="this.value=this.value.toUpperCase();" class="input200" tabindex="1"/>
                              <div id="divDescripcion" ></div>
                          </td>
                          <td><input type="text" id="totalReg" name="totalReg" class="totalReg" readonly="readonly" /></td>
                      </tr>                                                                
                      <tr>
                          <td >Estado</td>
                          <td><p>
                            <select name="sltNombredo" id="sltNombredo" tabindex="2">
                            </select>
                           </td>
                          <td><input type="hidden" id="hdnClave" /></td>
                      </tr>
                      <tr id="act_des">
                        <td align="right"><input type="checkbox" id="chkActivado" name="chkActivado" onchange="Cambia(this.checked);" tabindex="3"/></td>
                        <td><input type="text" id="lblActivado" name="lblActivado" readonly="readonly" style="border:0" value="" /></td>
                        <td>&nbsp;</td>
                      </tr>                     
   
                  </table>
                </fieldset>
            </div>
            <div class="controles">
	            <input type="button" id="btnBuscar" name="btnBuscar" value= "Buscar" tabindex="4" />
                <input type="button" id="btnGuardar" name="btnGuardar" value="Guardar" tabindex="5" />
                <input type="button" id="btnModificar" name="btnModificar" value="Modificar" tabindex="6" />
                <input type="button" id="btnCancelar" name="btnCancelar" value="Cancelar" tabindex="7" />                    
            </div>
        <br /><br />
        </div>                
    </form>          
    <div>
	    <fieldset id="sucursales_edo" >
            <legend>Lista de Sucursales</legend>
            <div id="lst_sucursales">
                <table id="lista_sucursales">
                	<tr><td></td><td></td></tr>
                </table>
            </div>
    	</fieldset>
    </div>
    <table width="407" align="center" class="gridView" id="tblSucursales" >
        <tr>
            <th width="105" align="center">Clave</th>
            <th width="167" align="center">Descripci&oacute;n</th>
            <th width="119" align="center">Estado</th>
        </tr>
    </table>
</div>
</div>
</body>
</html>
