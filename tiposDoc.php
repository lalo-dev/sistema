<?php

/**
 * @author Jose Miguel Pantaleon
 * @copyright 2010
 */
require_once("direccionamiento.php");
if ((!isset($_SESSION["usuario_valido"]))||(($_SESSION["permiso"]!="Administrador") && ($_SESSION["permiso"]!="Usuario")&& ($_SESSION["permiso"]!="Facturacion")))
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
	<script type="text/javascript" src="scripts/ajaxDocumentos.js"></script>
	<script type="text/javascript" src="scripts/prototype.js"></script>
	<script type="text/javascript" src="scripts/AjaxLib.js"></script>
	<script type="text/javascript" src="jscripts/globalscripts.js"></script>
	<script type="text/javascript" src="scripts/scriptaculous.js?load=builder,effects,controls"></script>
	<link rel="stylesheet" type="text/css" href="css/mtoForm.css" media="all"/>
	<script type="text/javascript" src="scripts/window.js"> </script>
	<link href="themes/default.css" rel="stylesheet" type="text/css"/> 
	<!-- Add this to have a specific theme--> 
	<link href="themes/mac_os_x.css" rel="stylesheet" type="text/css"/>     	<title>EWebFac - Tipos de Documentos</title>
</head>
<body>
 <div id="contenedor">
<?php
	if($menu!="")	
		require_once($menu);
?>
<div id="derecho">
    <div class="cabecera">
        <div id="ruta">Catalogos | Tipos de Documentos</div>
        <h2 id="titulo">Tipos de Documentos</h2>
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
				<img src="imagenes/impresora.png" title="Imprimir Documentos" onclick="imprimirCatalogo(6,'Documentos');" class='imgImp'/>
				Imprimir Cat&aacute;logo
			</td>
		</tr>
	</table>
	<br />
        <fieldset>
        <legend>Datos generales</legend>
            <table align="center">
                <tr>
                    <td width="117">Clave Documento</td>
                   <td width="162"><input type="text" id="txtTipoDoc" name="txtTipoDoc" tabindex="1" /><div id="divClave" ></div>
	                   <input type="hidden" id="hdnClave" name="hdnClave" />
                       <input type="hidden" id="hdnTipo"  name="hdnTipo"/>
                  </td>
                    <td width="288"><input type="text" id="totalReg" name="totalReg" class="totalReg" readonly="readonly"/></td>
                </tr>
                
                <tr class="verticalalign_t">
                  <td>*Tipo de Documento</td>
                  <td>
               	      <select id="sltTipoDoc" name="sltTipoDoc" tabindex="2">
           	          </select>
               	   </td>
                  <td>
                  	<input type="text" id="txtOtro" name="txtOtro" tabindex="3" class="ocultoOtro" />
                  </td>
                </tr>
                <tr>
                    <td>*Siglas</td>
                    <td>
                        <input type="text" id="txtDescripcion" name="txtDescripcion" tabindex="4" />
                    </td>
                    <td></td>                   
                </tr>
                 
                <tr>
                    <td  >Folio</td>
                    <td >
                        <input type="text" id="txtFolio" name="txtFolio"  tabindex="5" onkeydown="return chk_val(this.id,event);"/> 
                    </td> 
                    <td></td>
               </tr>
               <tr id="act_des">
                    <td align="right"><input type="checkbox" id="chkActivado" name="chkActivado" onchange="Cambia(this.checked);" tabindex="6"/></td>
                    <td >
                        <input type="text" id="lblActivado" name="lblActivado" readonly="readonly" style="border:0" value="" />
                    </td>
                    <td></td>                   
                </tr>  
                                             
            </table>
        </fieldset>
    </div>
    <div class="controles">
	    <input type="button" id="btnBuscar"  name="btnBuscar" value= "Buscar" tabindex="7" />
        <input type="button" id="btnGuardar" name="btnGuardar" value="Guardar" tabindex="8" />
        <input type="button" id="btnModificar" name="btnModificar" value="Modificar" tabindex="9"/>
        <input type="button" id="btnCancelar" name="btnCancelar" value="Cancelar" tabindex="10"/>                    
    </div>
    <table width="470" align="center" class="gridView" id="tblDocumentos" >
        <tr>
            <th width="128" align="center">Tipo de Documento</th>
            <th width="104" align="center">Siglas</th>
            <th width="106" align="center">Folio</th>
            <th width="112" align="center">Estatus</th>                    
        </tr>
    </table>
</form>
</div>
</body>
</html>
