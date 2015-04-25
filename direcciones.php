<?php

/**
 * @author miguel
 * @copyright 2009
 */
session_start();
if (!isset($_SESSION["usuario_valido"]))
if ((!isset($_SESSION["usuario_valido"]))||(($_SESSION["permiso"]!="Administrador") && ($_SESSION["permiso"]!="Usuario")&& ($_SESSION["permiso"]!="Guias")&& ($_SESSION["permiso"]!="Facturacion")))
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
	$lugar="Cliente";
  }
 else
 {
 	$url="corresponsales.php?codigo=".$cveCliente;
	$lugar="Corresponsal";
 }

$permiso=$_GET["nP"];
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
	<input type="hidden" id="txthPer" value="<?php echo $permiso;?>" />
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
                    <div id="ruta">Datos Base | <?php echo $lugar; ?> | Direcciones</div>
                    <h2 id="titulo">Direcciones</h2>
                </div>

                <div>
                <span id="loading" style="display: none">Por favor espere...</span>
<span id="aviso" style="display: none">Cargando...</span>
<span id="status"></span>
                    
              <div id="pnlDatosGenerales">
<fieldset>
<legend>Direcciones</legend>
                        <table width="704" border="0" cellpadding="2" cellspacing="2">
                            <caption style="margin-bottom:20px;"><span id="lblNombreCliente" style="color:YellowGreen;font-size:Large;font-weight:bold;"><?php echo utf8_encode($razon); ?></span></caption>
<input type="hidden" name="cveCliente" id="cveCliente" value="<?php echo $cveCliente; ?>"/>
<input type="hidden" name="cveTabla" id="cveTabla" value="<?php echo $tabla; ?>"/>
                            <tr>
                                <td width="98">*Numero Direccion </td>
<td width="209"><input name="txtNumeroD" type="text" size="25" id="txtNumeroD"  value="<?php echo $cveDireccion; ?>" /><input type="button" name="btnbuscar" id="btnbuscar" value= "Buscar" /><div id="autoDireccion"></div></td>
                             <td width="102" id="tdSucursal">*Sucursal </td>
							 <td width="158">
                             	<select id="slcSucursal"></select>
                                <input type="hidden" id="slchSucursal" name="slchSucursal" value="" />
                             </td>
                            </tr>
                            <tr>
                             <td>*Pa&iacute;s</td>
								<td><select name="slcPaises" id="slcPaises">
							    </select></td>
                                <td>*Estado</td>
                                <td><select name="slcEstados" id="slcEstados" >
                              </select></td>
                            </tr>

                            <tr>
                             	<td>*Municipio / Delegaci&oacute;n</td>
                              <td><select name="slcMunicipios" id="slcMunicipios" >
                              </select></td><td>*Calle</td>
                                <td><input name="txtCalle" type="text" maxlength="60" id="txtCalle" class="mayuscula" style="width:250px;"/></td>
                            </tr>
                            <tr>
                             <td>*Colonia</td>
                                <td><input name="txtColonia" type="text" maxlength="60" id="txtColonia" class="mayuscula" style="width:250px;" /></td>
                                <td>No. interior</td>
                                <td><input name="txtNumeroInterior" type="text" maxlength="8" size="8" id="txtNumeroInterior"  /></td>
                          
                            </tr>
                            <tr>
                             <td>No. exterior</td>
                                <td><input name="txtNumeroExterior" type="text"maxlength="8" size="8" id="txtNumeroExterior"  /></td>
                                <td>*C&oacute;digo Postal</td>
                                <td><input name="txtCodigoPostal" type="text" maxlength="8" size="8" id="txtCodigoPostal" onkeydown="return chk_val(this.id,event);"/></td>
                            
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
<input  type="button" name="btnGuardar" id="btnGuardar" value= "Guardar" />
                <input  type="button" name="btnModificar" id="btnModificar" value= "Modificar" />

                    <input  type="button" name="btnCancelar" value="Cancelar" id="btnCancelar" />
                    <input  type="button" name="btnContactos" id="btnContactos" value= "Contactos" />
                    <input  type="button" name="btnVolver" value="Volver" id="btnVolver" onclick="javascript:Enviar();"/>
</div>
                
</div>
    
 <div id="divContactos" class="oculto" >
                    <div id="pnlDatosGenerales" >
<fieldset class="contacto">
<legend>Contactos</legend>
                        <table border="0" cellpadding="2" cellspacing="2">
                            <tr>
                                <td> <input type="hidden" id="hdncvecontacto" name="hdncvecontacto" />*Nombre</td>
                                <td><input name="txtNombre" type="text" maxlength="40" id="txtNombre" class="mayuscula"/> 
                                <div id="autoContacto"></div>
                                </td>
                             <td align="right"><input type="checkbox" id="cbxFacturacion" name="cbxFacturacion" checked="checked" /></td>
                                <td>Contacto de Facturacion</td>
                            </tr>
                            <tr>
                             <td>*Apellido paterno</td>
                                <td><input name="txtApellidoPaterno" type="text" maxlength="40" id="txtApellidoPaterno" class="mayuscula"/>
                                <td>*Apellido materno</td>
                                <td><input name="txtApellidoMaterno" type="text" maxlength="40" id="txtApellidoMaterno" class="mayuscula"/> </td>
                           </tr>     
                           <tr> 
  <td>Cargo</td>
                                <td><input name="txtCargo" type="text" maxlength="20" id="txtCargo"  class="mayuscula"/></td>    
<td>Departamento</td>
                                <td><input name="txtDepartamento" type="text" maxlength="20" id="txtDepartamento" class="mayuscula" /></td>
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
<input  type="button" name="btnGuardarContacto" id="btnGuardarContacto" value= "Guardar" />

                    <input  type="button" name="btnModificarContacto" value="Modificar" id="btnModificarContacto" />

                    <input  type="button" name="btnBorrarContacto" value="Borrar" id="btnBorrarContacto"  />
                    <input  type="button" name="btnCancelarContacto" value="Cancelar" id="btnCancelarContacto" onclick="deshabilitar2();" />
                   
          </div>
               
                </div>
                
               
            </div>
           
       
        <div class="derecha" style="position:absolute;left:1050px;top:130px;">                    
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

