<?php

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
	<script type="text/javascript" src="scripts/ajaxBancos.js"></script>
	<script type="text/javascript" src="scripts/prototype.js"></script>
	<script type="text/javascript" src="scripts/AjaxLib.js"></script>
	<script type="text/javascript" src="jscripts/globalscripts.js"></script>
	<script type="text/javascript" src="scripts/scriptaculous.js?load=builder,effects,controls"></script>
	<link rel="stylesheet" type="text/css" href="css/mtoForm.css" media="all"/>
	<script type="text/javascript" src="scripts/window.js"> </script>
	<link href="themes/default.css" rel="stylesheet" type="text/css"/> 
	<!-- Add this to have a specific theme--> 
	<link href="themes/mac_os_x.css" rel="stylesheet" type="text/css"/> 
	<title>EWebFac - BANCOS</title>
</head>
<body>
  <div id="contenedor">
<?php
	include_once ($menu);
?>
     <div id="derecho">
        <div class="cabecera">
            <div id="ruta">Catalogos | Bancos</div>
            <h2 id="titulo">Bancos</h2>
        </div>  <br />             
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
				<img src="imagenes/impresora.png" title="Imprimir Bancos" onclick="imprimirCatalogo(9,'Bancos');" class='imgImp'/>
				Imprimir Cat&aacute;logo
			</td>
		</tr>
	</table>
	<br />
        <form id="form2">
            <fieldset >
              <legend>Datos generales</legend>
                <table width="461" align="center">
                    <tr>
                        <td width="46" >Clave</td>
                        <td width="403"> 
                          <input type="text" id="txtClave" name="txtClave" tabindex="1" />
                          <input type="text" id="totalReg" name="totalReg" class="totalReg" readonly="readonly"/>
                          <div id="divDescripcion"></div>
                         </td>
                    </tr>                            
                    <tr>
                      <td>*Nombre</td>
                      <td>
                      	<input type="text" id="txtNombre" name="txtNombre" tabindex="2" class="input200"/>
                        <input type="hidden" id="txthNombre" name="txthNombre" />
                      </td>
                    </tr>    
                    <tr id="act_des">
                        <td align="right"><input type="checkbox" id="chkActivado" name="chkActivado" onchange="Cambia(this.checked);" tabindex="5"/></td>
                        <td >
                            <input type="text" id="lblActivado" name="lblActivado" readonly="readonly" style="border:0" value="" /></td>                               
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
    <table width="547" align="center" class="gridView" id="tblBancos" >
        <tr>
            <th width="124" align="center">Cve. Banco</th>
            <th width="275" align="center">Descripci&oacute;n</th>
            <th width="132" align="center">Estatus</th>                    
        </tr>
    </table>
 </div>
</body>
</html>
