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
	<script type="text/javascript" src="scripts/ajaxAerolineas.js"></script>
	<script type="text/javascript" src="scripts/prototype.js"></script>
	<script type="text/javascript" src="scripts/AjaxLib.js"></script>
	<script type="text/javascript" src="jscripts/globalscripts.js"></script>
	<script type="text/javascript" src="scripts/scriptaculous.js?load=builder,effects,controls"></script>
	<link rel="stylesheet" type="text/css" href="css/mtoForm.css" media="all"/>
	<script type="text/javascript" src="scripts/window.js"> </script>
	<link href="themes/default.css" rel="stylesheet" type="text/css"/> 
	<!-- Add this to have a specific theme--> 
	<link href="themes/mac_os_x.css" rel="stylesheet" type="text/css"/>     
	<title>EWebFac - Aerol&iacute;neas</title>
</head>
<body>
  <div id="contenedor">
<?php
	if($menu!="")	
		require_once($menu);
?>
     <div id="derecho">
        <div class="cabecera">
            <div id="ruta">Catalogos | Aerol&iacute;neas</div>
            <h2 id="titulo">Aerol&iacute;neas</h2>
        </div><br />            
        <div class="izquierda">
            <span id="loading" style="display: none">Por favor espere...</span>
            <span id="aviso" style="display: none">Cargando...</span>
            <span id="status"></span>
        </div>
      </div>  
      <br />
      <br />
      <div id="pnlDatosGenerales">
	<table width="543">
		<tr align="left">
			<td>
				<img src="imagenes/impresora.png" title="Imprimir Aerol&iacute;neas" onclick="imprimirCatalogo(1,'Aerolineas');" class='imgImp'/>
				Imprimir Cat&aacute;logo
			</td>
		</tr>
	</table>
	<br />
        <form id="form2">
            <fieldset >
              <legend>Datos generales</legend>
                <table width="543" align="center">
                    <tr>
                        <td width="108" >*Clave L&iacute;nea A&eacute;rea</td>
                        <td width="423">
                          <input type="text" id="txtClave" name="txtClave" onblur="this.value=this.value.toUpperCase();" tabindex="1" />
                          <input type="hidden" id="txthClave" name="txthClave"  />
                          <input type="text" id="totalReg" name="totalReg" class="totalReg" readonly="readonly"/>
                          <div id="divDescripcion"></div>
                         </td>
                    </tr>                            
                    <tr>
                      <td>*L&iacute;nea A&eacute;rea</td>
                      <td><input type="text" id="txtDescripcion" name="txtDescripcion" tabindex="2" class="input200"/></td>
                    </tr>
                    <tr>
                        <td>Contacto</td>
                        <td>
                            <input type="text" id="txtContacto" name="txtContacto" onblur="this.value=this.value.toUpperCase();"  tabindex="3" class="input200"/>
                        </td>                               
                    </tr>
                     <tr>
                        <td>Tel&eacute;fono</td>
                        <td>
                            <input type="text" id="txtTelefono" name="txtTelefono"  maxlength="12" tabindex="4" class="input200"/>
                        </td>                               
                    </tr>    
                    <tr id="act_des">
                        <td align="right"><input type="checkbox" id="chkActivado" name="chkActivado" onchange="Cambia(this.checked);" tabindex="5"/></td>
                        <td >
                            <input type="text" id="lblActivado" name="lblActivado" readonly="readonly" style="border:0" value="" />
                        </td>                               
                    </tr>                                    
                </table>
            </fieldset>
            <div class="controles">
                <input type="button" id="btnBuscar"  name="btnBuscar" value= "Buscar" tabindex="6" />
                <input type="button" id="btnGuardar" name="btnGuardar" value="Guardar" tabindex="7" />
                <input type="button" id="btnModificar" name="btnModificar" value="Modificar" tabindex="8"/>
                <input type="button" id="btnCancelar" name="btnCancelar" value="Cancelar"  tabindex="9"/>
            </div>                
		</form>
	</div>
    <br /><br />
    <table width="822" align="center" class="gridView" id="tblLineas" >
        <tr>
            <th width="253" align="center">Empresa</th>
            <th width="104" align="center">Cve. L&iacute;nea &Aacute;erea</th>
            <th width="233" align="center">Descripci&oacute;n</th>
            <th width="212" align="center">Contacto</th>                    
        </tr>
    </table>
 </div>
</body>
</html>
