<?php

/**
 * @author miguel
 * @copyright 2009
 */
require_once("direccionamiento.php");
if ((!isset($_SESSION["usuario_valido"]))||(($_SESSION["permiso"]!="Administrador") && ($_SESSION["permiso"]!="Usuario")&& ($_SESSION["permiso"]!="Guias")))
{
    header("Location: login.php");
}
$usuario=$_SESSION["usuario_valido"];
$empresa=$_SESSION["cveEmpresa"];
$sucursal=$_SESSION["cveSucursal"];
$razon = $_GET["razon"];
$corresponsal = $_GET["codigo"];
if($corresponsal==""){$corresponsal=0;}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head><meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<link href="estilos/ewebfac1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="scripts/ajaxCorresponsales.js"></script>
<script type="text/javascript" src="scripts/prototype.js"></script>
<script type="text/javascript" src="jscripts/globalscripts.js"></script>
<script type="text/javascript" src="scripts/AjaxLib.js"></script>
<script type="text/javascript" src="scripts/scriptaculous.js?load=builder,effects,controls"></script>
<script type="text/javascript" src="scripts/window.js"> </script>
<link href="themes/default.css" rel="stylesheet" type="text/css"/> 
<!-- Add this to have a specific theme--> 
<link href="themes/mac_os_x.css" rel="stylesheet" type="text/css"/> 


<script type="text/javascript">
function Enviar(){
	var numP=document.getElementById("txthPer").value;
	var cveCorr= document.getElementById("txtCodigo").value;
	var razon= document.getElementById("txtRazonSocial").value;		    	
        location = "direcciones.php?nP="+numP+"&razon=" + razon + "&cveCliente=" + cveCorr+"&tabla=corresponsal" ;
}
</script>
<link rel="stylesheet" type="text/css" href="css/mtoForm.css" media="all"/>
<title>
	EWebFac - Corresponsales

</title></head>
<body>
 <div id="contenedor">
<?php
	if($menu!="")	
		require_once($menu);
?>
<body>
            <div id="derecho">
                <div class="cabecera">
                    <div id="ruta">Datos Base | Corresponsales</div>
                    <h2 id="titulo">Corresponsales</h2>
                </div>
	    </div>
		<br /><br />
               <form id="form2" name="form2">
                <div>
                	<span id="loading" style="display: none">Por favor espere...</span>
								<span id="aviso" style="display: none">Cargando...</span>
								<span id="status"></span>
		<table width="670">
		   <tr>
			 <td align="right">
				Imp. Consignatarios<img src="imagenes/impresora.png" title="Imprimir Consignatarios" onclick="imprimirCorresponsales();" class='imgImp'/>
			    </td>
			</tr>
		    </table> 
                    <div id="pnlDatosGenerales">
	<fieldset>
		<legend>
			Datos Generales
		</legend>
<table width="620" border="0" cellpadding="2" cellspacing="2">
                           
                            <tr>
                                <td width="120">C&oacute;digo
						<input type="hidden" id="hdnCorresponsal" value="<?php echo $corresponsal; ?>" />
				</td>
                                <td colspan="3">
                                    <div id="autoClienteC"></div>
                                    <input name="txtCodigo" type="text" maxlength="20" id="txtCodigo" size="65" />
                                </td>
                            </tr>
                            <tr>
                                <td>*Raz&oacute;n social</td>

                                <td colspan="3">
								<div id="autoCliente"></div>
								<input name="txtRazonSocial" type="text" maxlength="80" id="txtRazonSocial" value = "<?php echo $razon; ?>"  size="65" onblur="this.value=this.value.toUpperCase();"/>
                                </td>
                            </tr>
                            <tr>
                                <td>Nombre comercial</td>
                                <td colspan="3">
                                    <input name="txtNombreComenrcial" type="text" maxlength="80" id="txtNombreComenrcial" size="65" onblur="this.value=this.value.toUpperCase();"/>

                                </td>
                            </tr>
                            <tr>
                                <td>R.F.C.</td>
                                <td width="145">
                                    <input name="txtRfc" type="text" maxlength="20" id="txtRfc"  />
                                </td>
                                <td width="108">C.U.R.P.</td>
                                <td width="397">
                                    <input name="txtCurp" type="text" maxlength="30" id="txtCurp" />

                                </td>
                            </tr>
                            <tr>
                                
                                <td>Lada</td>

                                <td>
                                    <input name="txtLada" type="text" maxlength="10" id="txtLada"  />
                                </td>
                                <td>Telefono</td>
                                <td>
                                    <input name="txtTelefono" type="text" maxlength="20" id="txtTelefono" />
                                </td>
                            </tr>
                            <tr>
                                <td>Fax</td>
                                <td>
                                    <input name="txtFax" type="text" maxlength="20" id="txtFax"  />
                                </td>
                            </tr>
                            <tr>
                                <td >P&aacute;gina WEB</td>
								<td><input name="txtPaginaWeb" type="text" maxlength="60" id="txtPaginaWeb"  /></td>
                            </tr>
                            <tr>
                                <td>*Impuesto:</td>
                                <td><input name="txtImpuesto" type="text" maxlength="60" id="txtImpuesto"  /></td>
                                <td colspan="2"> </td>
                            </tr>
                            <tr>
                                <td >*Condiciones de pago</td>
                                <td>   <input name="txtCondicionesP" type="text" maxlength="60" id="txtCondicionesP"  />
                                </td>
                                <td>*Tipo de moneda</td>		
                                <td>    <select name="slcMonedas" id="slcMonedas">
																					</select>
                                </td>
                            </tr>
                            <tr id="act_des">
                                <td align="right"><input type="checkbox" id="chkActivado" name="chkActivado" onchange="Cambia(this.checked);" /></td>
                                <td >
                                    <input type="text" id="lblActivado" name="lblActivado" readonly="readonly" style="border:0" value="" />
                                </td>
                                <td></td>
                                <td></td>                               
                            </tr> 
                        </table>

                    
	</fieldset>
</div>

<div class="derecha" style="position:absolute;left:1130px;top:137px;">
                    <div id="pnlTipoCliente">
						<fieldset style="width:auto">
					
							<legend>
								Tipo de Cliente
							</legend>
					                        <input id="rdoMoral" type="radio" name="TipoCliente" value="1" checked="checked"/><label for="rdoMoral">F&iacute;sica</label>
					                        <span class="requerido">*</span><br />
					                        <input id="rdoMoral" type="radio" name="TipoCliente" value="2" /><label for="rdoMoral">Moral</label><br />
					                    	<input id="rdoMoral" type="radio" name="TipoCliente" value="3" /><label for="rdoMoral">F&iacute;sica y Moral</label>
						</fieldset>
				</div>
                </div>
          </div>                
                <div class="controles">
					<input  type="button" name="btnbuscar" id="btnbuscar" value= "Buscar" />	
                    <input  type="button" name="btnGuardar" id="btnGuardar" value="Guardar"/>
                    <input  type="button" name="btnModificar" id="btnModificar" value="Modificar" id="btnModificar" />
                    <input  type="button" name="btnCancelar" id="btnCancelar" value="Cancelar" id="btnCancelar" />                    
                    <input  type="button" name="btnDirecciones" id="btnDirecciones" value="Direcciones" onclick="javascript:Enviar();" />
                </div>
                <div class="controles">
				<div style="position:absolute;left:1130px;top: 228px;">
					<table class="gridView" id="tablaFormulario">
					<caption>Contactos</caption>
					<tr>	
						<th>Nombre</th>
						<th>Sucursal</th>
						<th>Telefono</th>
						<th>Direcciones</th>
					</tr>
					</table>
				</div>
				<br />
				<div id="divDirecciones" class="oculto">
						<table class="gridView" id="tblDirecciones">
							<caption>Direcciones</caption>
							<tr>	
								<th width="130">Tipo</th>
								<th width="130">Calle</th>
								<th width="130">Colonia</th>
								<th width="130">Estado</th>
								<th width="130">Municipio / Del</th>
								<th width="130">C.P.</th>
								<th width="130">Editar</th>
							</tr>
					</table>
					</div>
                </div>
                <br />

                <br />
            </div>
        </div>
    </div>
    </form>
</div>
</body>
</html>
