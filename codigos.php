<?php


require_once("direccionamiento.php");
if ((!isset($_SESSION["usuario_valido"]))||(($_SESSION["permiso"]!="Administrador") && ($_SESSION["permiso"]!="Usuario"))&& ($_SESSION["permiso"]!="Guias"))
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
	<script type="text/javascript" src="scripts/ajaxCodigos.js"></script>
	<script type="text/javascript" src="scripts/prototype.js"></script>
	<script type="text/javascript" src="scripts/AjaxLib.js"></script>
	<script type="text/javascript" src="jscripts/globalscripts.js"></script>
	<script type="text/javascript" src="scripts/scriptaculous.js?load=builder,effects,controls"></script>
	<link rel="stylesheet" type="text/css" href="css/mtoForm.css" media="all"/>
	<script type="text/javascript" src="scripts/window.js"> </script>
	<link href="themes/default.css" rel="stylesheet" type="text/css"/>
	<!-- Add this to have a specific theme-->
	<link href="themes/mac_os_x.css" rel="stylesheet" type="text/css"/>
	<title>EWebFac - C&Oacute;DIGOS POSTAL</title>
</head>
<body>
 <div id="contenedor">
<?php
	if($menu!="")	
		require_once($menu);
?>
            <div id="derecho">
                <div class="cabecera">
                    <div id="ruta">Catalogos | C&oacute;digos Postal</div>
                    <h2 id="titulo">C&oacute;digos Postal</h2>
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
						<img src="imagenes/impresora.png" title="Imprimir C.P." onclick="imprimirCatalogo(8,'C.P.');" class='imgImp'/>
						Imprimir Cat&aacute;logo
					</td>
				</tr>
			</table>
			<br />
			<fieldset>
                     <legend>Datos generales</legend>
                        <table align="center">
                            <tr>
                                <td >*C.P.</td>
                                <td>
                                	<input type="hidden" id="txthCveCP" name="txthCveCP" value="" />
                                    <input type="hidden" id="txthCP" name="txthCP" value="" />
                                	<input type="text" id="txtCP" name="txtCP" size="10"  class="sltEstadosMuni" tabindex="1" onkeydown="return chk_val(this.id,event);"/>
                                    <input type="text" id="totalReg" name="totalReg" class="totalReg" readonly="readonly"/>
                              <div id="divCodigo"></div></td>
                               
                            </tr>
                            
                            <tr>
                                <td >*Estado</td>
                                <td>
                                    <input type="hidden" id="slchEstado" name="slchEstado" value="" />
                                    <select name="slcEstado" id="slcEstado" class="sltEstadosMuni" tabindex="2">
                                    </select>
                                </td>
                               
                            </tr>
                            <tr>
                                <td >*Municipio</td>
                                <td >
	                                <input type="hidden" id="slchMunicipios" name="slchMunicipios" value="" />
                                    <select name="slcMunicipios" id="slcMunicipios" class="sltEstadosMuni" tabindex="3">
	                                    <option value="">Seleccione</option>
                                    </select>
                                    <input type="text" id="txtMunicipio"  name="txtMunicipio" tabindex="4" class="sltEstadosMuni2"/>
                                </td>
                               
                            </tr>
                             <tr>
                                <td>*Colonia</td>
                                <td>
	                                <input type="hidden" id="txthColonia" name="txthColonia" value="" />
                                    <input type="text" id="txtColonia"  name="txtColonia"  class="sltEstadosMuni" tabindex="5"/>
                                </td>
                            </tr>                                      
                        </table>
                    </fieldset>
                </div>
                <div class="controles" align="center"> 
                    <input type="button" id="btnGuardar" value="Guardar" tabindex="6" />
                    <input type="button" id="btnModificar" value="Modificar" tabindex="7"/>
                    <input type="button" id="btnModificarN" value="Guardar Nuevo" tabindex="8"/>
                    <input type="button" id="btnBorrar" value="Borrar" tabindex="9"/>
                    <input type="button" id="btnCancelar" value="Cancelar"  tabindex="10"/>
                </div>
                <br />
                <br />
    </form><br /><br /><br />
    <table width="822" align="center" class="gridView" id="tblCp" >
      <tr>
        <th width="253" align="center">Estado</th>
        <th width="104" align="center">Municipio</th>
        <th width="233" align="center">Colonia</th>
        <th width="212" align="center">C.P.</th>                    
        <th width="212" align="center">Clave de C.P.</th>                    
      </tr>
      <tr>
        <td width="253" align="center">
        	<select name="slcEstado2" id="slcEstado2" class="sltEstadosMuni" tabindex="11"></select>
        </td>
        <td width="104" align="center">
        	<select name="slcMunicipios2" id="slcMunicipios2" class="sltEstadosMuni" tabindex="12">
                <option value="">Seleccione</option>
            </select>
        </th>
        <td width="233" align="center"></td>
        <td width="212" align="center"></td>                    
        <td width="212" align="center"></td>                    
      </tr>
    </table>
</div>
</body>
</html>
