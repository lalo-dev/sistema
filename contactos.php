<?php
/**
 * @author Jose Miguel Pantaleon
 * @copyright 2010
 */
	session_start();
if (!isset($_SESSION["usuario_valido"]))
{
    header("Location: login.php");
}
$usuario=$_SESSION["usuario_valido"];
$empresa=$_SESSION["cveEmpresa"];
$sucursal=$_SESSION["cveSucursal"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="EXPIRES" content="0" /><link href="estilos/ewebfac1.css" rel="stylesheet" type="text/css" /><link href="estilos/ListadoBusquedas.css" rel="stylesheet" type="text/css" />
    <script src="jscripts/General.js" type="text/javascript"></script>
    <script src="jscripts/XmlHttpRequest.js" type="text/javascript"></script>
    <script src="jscripts/Contactos.js" type="text/javascript"></script>
    <title>
	EWebFac - Contactos

</title></head>
<body>
    <form name="form1" method="post"  id="form1">



    <div id="contenedor">
        <div id="encabezado">
            <img id="logotipo" src="imagenes/e-webfac.jpg" alt="e-webfac" />
            <div id="menuayuda">
                | <a id="lnkAyuda" href="javascript:WebForm_DoPostBackWithOptions(new WebForm_PostBackOptions(&quot;lnkAyuda&quot;, &quot;&quot;, false, &quot;&quot;, &quot;#&quot;, false, true))">Ayuda</a> | <a id="lnkSalir" href="javascript:__doPostBack('lnkSalir','')">Salir</a> |
            </div>

            <img id="cabecera" src="imagenes/Cabecera.jpg" alt="cabecera" />
        </div>
        <div id="cuerpo">
            <div id="izquierdo">
                
            </div>
            <div id="derecho">
                <div class="cabecera">
                    <div id="ruta">Datos Base | Clientes | Contactos</div>
                    <h2 id="titulo">Contactos</h2>

                </div>
                <div class="completo">
                    <div id="pnlDatosGenerales" class="fieldset" style="width:400px;">
	<fieldset>
		<legend>
			 
		</legend>
                        <table border="0" cellpadding="2" cellspacing="2">
                            <caption style="margin-bottom:20px;"><span id="lblNombreCliente" style="color:YellowGreen;font-size:Large;font-weight:bold;"></span></caption>
                            <tr>

                                <td><span class="requerido">*</span>Nombre</td>
                                <td>
                                    <div id="divContactos" class="queryContainer"></div>
                                    <input name="txtNombre" type="text" maxlength="40" id="txtNombre" class="queryTextBox" onkeyup="javascript:ConsultarContactos(this,event);" onkeydown="javascript:txtNombre_KeyDown(this,event);" style="width:200px;" />
                                </td>
                            </tr>
                            <tr>
                                <td><span class="requerido">*</span>Apellido paterno</td>

                                <td>
                                    <input name="txtApellidoPaterno" type="text" maxlength="40" id="txtApellidoPaterno" style="width:200px;" />
                                    <input type="hidden" name="hflContacto" id="hflContacto" value="0" />
                                </td>
                            </tr>
                            <tr>
                                <td>Apellido materno</td>
                                <td>

                                    <input name="txtApellidoMaterno" type="text" maxlength="40" id="txtApellidoMaterno" style="width:200px;" />
                                </td>
                            </tr>
                            <tr>
                                <td>Lada</td>
                                <td>
                                    <input name="txtLada" type="text" maxlength="10" id="txtLada" onkeypress="javascript:return SoloNumeros(this);" onchange="javascript:this.value = EliminarTexto(this.value);" style="width:100px;" />
                                </td>

                            </tr>
                            <tr>
                                <td>Tel&eacute;fono</td>
                                <td>
                                    <input name="txtTelefono" type="text" maxlength="20" id="txtTelefono" onkeypress="javascript:return ValidarTelefono(this);" onchange="javascript:this.value = FormatoTelefono(this.value);" style="width:200px;" />
                                </td>
                            </tr>
                            <tr>

                                <td>
                                    Celular
                                </td>
                                <td>
                                    <input name="txtCelular" type="text" maxlength="20" id="txtCelular" onkeypress="javascript:return ValidarTelefono(this);" onchange="javascript:this.value = FormatoTelefono(this.value);" style="width:200px;" />
                                </td>
                            </tr>
                             <tr>
                                <td>

                                    E-Mail
                                </td>
                                <td>
                                    <input name="txtMail" type="text" maxlength="40" id="txtMail" style="width:200px;" />
                                </td>
                            </tr>
                             <tr>
                                <td>
                                    Enlace
                                </td>

                                <td>
                                    <input name="txtEnlace" type="text" maxlength="40" id="txtEnlace" style="width:200px;" />
                                </td>
                            </tr>
                            <tr>
                                <td>Departamento</td>
                                <td>
                                    <input name="txtDepartamento" type="text" maxlength="20" id="txtDepartamento" style="width:200px;" />

                                </td>
                            </tr>
                        </table>
                    
	</fieldset>
</div>
                </div>
                <div class="controles">
					<input type="submit" name="btnGuardar" value="Guardar" id="btnGuardar" disabled="disabled" />
                    <input type="submit" name="btnModificar" value="Modificar" id="btnModificar" disabled="disabled" />

                    <input type="submit" name="btnBorrar" value="Borrar" id="btnBorrar" disabled="disabled" />
                    <input type="submit" name="btnCancelar" value="Cancelar" id="btnCancelar" />
                    <input type="submit" name="btnVolver" value="Volver" id="btnVolver" />
                </div>
            </div>
        </div>
    </div>
    </form>
</body>

</html>
