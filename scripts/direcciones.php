<?php

/**
 * @author miguel
 * @copyright 2009
 */
 	session_start();
if (!isset($_SESSION["usuario_valido"]))
{
    header("Location: login.php");
}
$usuario=$_SESSION["usuario_valido"];
$empresa=$_SESSION["cveEmpresa"];
$sucursal=$_SESSION["cveSucursal"];
$razon = $_GET["razon"];

$cveCliente = $_GET["cveCliente"];
$cveDireccion = $_GET["cveDireccion"];

$tabla=$_GET["tabla"];
if($tabla=="cliente")
 {
 	$url="clientes.php?codigo=".$cveCliente;
  }
 else
 {
 	$url="corresponsales.php?razon=".$razon;
 	 }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="estilos/ewebfac1.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="css/mtoForm.css" media="all"/>
<script type="text/javascript" src="scripts/ajaxDirecciones.js"></script>
<script type="text/javascript" src="scripts/prototype.js"></script>
<script type="text/javascript" src="scripts/AjaxLib.js"></script>
<script type="text/javascript" src="jscripts/globalscripts.js"></script>
<script type="text/javascript" src="scripts/scriptaculous.js?load=builder,effects,controls"></script>
 <script language="javascript" type="text/javascript">
    function Enviar(){		         	    	
        location = "<?php echo $url ?>";
    }
</script>
    <title>
	EWebFac - Direcciones
</title>
    
</head>
<body>

    <form name="form2" id="form2">
<div>
<input type="hidden" id="hdnUsuario" value="<?php echo $usuario;?>" />
         <input type="hidden" id="hdnEmpresaS" value="<?php echo "-".$empresa."-,-".$sucursal."-";?>" />

    <div id="contenedor">
         <div id="encabezado">
            <img id="logotipo" src="imagenes/e-webfac.jpg" alt="e-webfac" height="45" width="120"/>
            <div id="menuayuda">
                | <a href="">Ayuda</a> | <a href='logout.php'>Salir</a> |
            </div>
            <img id="cabecera" src="imagenes/Cabecera.jpg" alt="cabecera" height="15" width="944"/>
        </div>
        <div id="cuerpo">
            
            <div id="derecho">
             <div class="cabecera">
                    <div id="ruta">Datos Base | Clientes | Direcciones</div>
                    <h2 id="titulo">Direcciones</h2>
                </div>

                <div>
                <span id="loading" style="display: none">Por favor espere...</span>
<span id="aviso" style="display: none">Cargando...</span>
<span id="status"></span>
                    
              <div id="pnlDatosGenerales">
<fieldset>
<legend>Direcciones</legend>
                        <table width="593" border="0" cellpadding="2" cellspacing="2">
                            <caption style="margin-bottom:20px;"><span id="lblNombreCliente" style="color:YellowGreen;font-size:Large;font-weight:bold;"><?php echo $razon; ?></span></caption>
<input type="hidden" name="cveCliente" id="cveCliente" value="<?php echo $cveCliente; ?>"/>
<input type="hidden" name="cveTabla" id="cveTabla" value="<?php echo $tabla; ?>"/>
                            <tr>
                                <td width="98">*Numero Direccion </td>
<td width="209"><input name="txtNumeroD" type="text" size="25" id="txtNumeroD"  value="<?php echo $cveDireccion; ?>" /><input type="button" name="btnbuscar" id="btnbuscar" value= "Buscar" /><div id="autoDireccion"></div></td>
                             <td width="102" id="tdSucursal">Sucursal </td>
<td width="158"><select id="slcSucursal"></select></td>
                            </tr>
                            <tr>
                             <td>*Calle</td>
<td><input name="txtCalle" type="text" maxlength="60" id="txtCalle" onkeyup="this.value=this.value.toUpperCase();" />
                                </td>
                                <td>No. exterior</td>
                                <td><input name="txtNumeroExterior" type="text" maxlength="20" id="txtNumeroExterior"  />
                                </td>
                            
                                
                            </tr>

                            <tr>
                             <td>No. interior</td>
                                <td><input name="txtNumeroInterior" type="text" maxlength="20" id="txtNumeroInterior"  /></td>
                                <td>Colonia</td>
                                <td><input name="txtColonia" type="text" maxlength="60" id="txtColonia" onkeyup="this.value=this.value.toUpperCase();" />
                                </td>
                            
                            </tr>
                            <tr>
                             <td>*C&oacute;digo Postal</td>
                                <td><input name="txtCodigoPostal" type="text" maxlength="20" id="txtCodigoPostal" onkeydown="return chk_val(this.id,event);"/>
                                </td>
                                <td>*Pais</td>
                                <td><select id="slcPaises"></select>
                                </td>
                          
                            </tr>
                            <tr>
                             <td>*Estado</td>
                                <td>
                                    <select name="slcEstados" id="slcEstados" ></select>
                                </td>
                                <td>*Municipio / Delegaci&oacute;n</td>
                                <td><select name="slcMunicipios" id="slcMunicipios" ></select>
                                </td>
                            
                            </tr>
                            <tr>
                             <td>Tipo direcci&oacute;n</td>
                                <td><select name="slcTiposDireccion" id="slcTiposDireccion" >
<option value="Comercial">Comercial</option>
<option value="Fiscal">Fiscal</option>
</select>
                                </td>
                                <td>*Telefono</td>
                                <td><input name="txtTelefono" type="text" maxlength="60" id="txtTelefono"  />
                                </td>
                                
                            </tr>
                            <tr id="act_des">
                                <td align="right"><input type="checkbox" id="chkActivado" name="chkActivado" onchange="Cambia(this.checked);" /></td>
                                <td >
                                    <input type="text" id="lblActivado" name="lblActivado" readonly="readonly" style="border:0" value="Activado" />
                                </td>                               
                            </tr> 
                        </table>
                    
</fieldset>
<div class="controles">
<input class = "button" type="button" name="btnGuardar" id="btnGuardar" value= "Guardar" />
                <input class = "button" type="button" name="btnModificar" id="btnModificar" value= "Modificar" />

                    <input class = "button" type="button" name="btnCancelar" value="Cancelar" id="btnCancelar" />
                    <input class = "button" type="button" name="btnContactos" id="btnContactos" value= "Contactos" />
                    <input class = "button" type="button" name="btnVolver" value="Volver" id="btnVolver" onclick="javascript:Enviar();"/>
</div>
                
</div>
    
 <div id="divContactos" class="oculto" >
                    <div id="pnlDatosGenerales" >
<fieldset class="contacto">
<legend>Contactos</legend>
                        <table border="0" cellpadding="2" cellspacing="2">
                            <tr>
                                <td> <input type="hidden" id="hdncvecontacto" name="hdncvecontacto" />*Nombre</td>
                                <td><input name="txtNombre" type="text" maxlength="40" id="txtNombre" onkeyup="this.value=this.value.toUpperCase();"/> 
                                <div id="autoContacto"></div>
                                </td>
                             <td align="right"><input type="checkbox" id="cbxFacturacion" name="cbxFacturacion" /></td>
                                <td>Contacto de Facturacion</td>
                            </tr>
                            <tr>
                             <td>*Apellido paterno</td>
                                <td><input name="txtApellidoPaterno" type="text" maxlength="40" id="txtApellidoPaterno" onkeyup="this.value=this.value.toUpperCase();"/>
                                <td>*Apellido materno</td>
                                <td><input name="txtApellidoMaterno" type="text" maxlength="40" id="txtApellidoMaterno" onkeyup="this.value=this.value.toUpperCase();" /> </td>
                           </tr>     
                           <tr> 
  <td>Cargo</td>
                                <td><input name="txtCargo" type="text" maxlength="20" id="txtCargo"  onkeyup="this.value=this.value.toUpperCase();"/></td>    
<td>Departamento</td>
                                <td><input name="txtDepartamento" type="text" maxlength="20" id="txtDepartamento" onkeyup="this.value=this.value.toUpperCase();" /></td>
                          </tr>
                            <tr>
                                <td>Lada</td>
                                <td><input name="txtLada" type="text" maxlength="10" id="txtLada" /></td>
<td>*Tel&eacute;fono</td>
                                <td><input name="txtTelefonoContacto" type="text" maxlength="20" id="txtTelefonoContacto" />
                                </td>
                            </tr>
                            <tr>
<td>Celular</td>
                                <td><input name="txtCelular" type="text" maxlength="20" id="txtCelular" /></td>
                                <td>E-Mail</td>
                                <td><input name="txtMail" type="text" maxlength="40" id="txtMail"  /> </td>
                            </tr>
                             
                        </table>
                    
</fieldset>
</div>
<div class="controles">
<input class = "button" type="button" name="btnGuardarContacto" id="btnGuardarContacto" value= "Guardar" />

                    <input class = "button" type="button" name="btnModificarContacto" value="Modificar" id="btnModificarContacto" />

                    <input class = "button" type="button" name="btnBorrarContacto" value="Borrar" id="btnBorrarContacto"  />
                    <input class = "button" type="button" name="btnCancelarContacto" value="Cancelar" id="btnCancelarContacto" onclick="deshabilitar2();" />
                   
          </div>
               
                </div>
                
               
            </div>
           
       
        <div class="derecha" style="position:absolute; left: 991px; top: 135px;">
                    
<div>
<fieldset>
<legend>Contactos</legend>
<table class="gridView" id="tablaFormulario">
<caption>Contactos</caption>
<tr>
<th>Nombre</th>
<th>Editar</th>
</tr>
</table>
</fieldset>
</div>
                </div>
             </div>   
    </div>
    </form>
</body>

</html>

