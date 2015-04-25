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
<head><meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<link href="estilos/ewebfac1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="scripts/ajaxMunicipios.js"></script>
<script type="text/javascript" src="scripts/prototype.js"></script>
<script type="text/javascript" src="scripts/AjaxLib.js"></script>
<script type="text/javascript" src="jscripts/globalscripts.js"></script>
<script type="text/javascript" src="scripts/scriptaculous.js?load=builder,effects,controls"></script>
<link rel="stylesheet" type="text/css" href="css/mtoForm.css" media="all"/>
    
    <title>
	EWebFac - Municipios

</title></head>
<?php
	include_once ('scripts/menuGeneral.php');
?>
            <div id="derecho">
                <div class="cabecera">
                    <div id="ruta">Catalogos | Municipios</div>
                    <h2 id="titulo">Municipios</h2>
                </div>
  <form id="form2">
                <div class="izquierda">
                	<span id="loading" style="display: none">Por favor espere...</span>
								<span id="aviso" style="display: none">Cargando...</span>
								<span id="status"></span>
                    <div id="pnlDatosGenerales">
<fieldset >
                    <legend>Datos generales</legend>
                        <table align="center">
                         	 <tr>
                                <td >Estado</td>
                                <td><select name="txtNombredo" id="txtNombredo" ></td></td>
                               <input type="hidden" id="hdnClave" />
                            </tr>
                            
                            <tr>
                                <td >Nombre Municipio</td>
                                <td >
                                    <input type="text" id="txtDescripcion"  />
                                    <div id="divDescripcion"></div>
                                </td>
                               
                            </tr>
                                                
                        </table>
                    </fieldset>
                </div>
                <div class="controles">
                	  <input type="button" id="btnCodigos" value="Codigo Postal" />
                    <input type="button" id="btnGuardar" value="Guardar" />
                    <input type="button" id="btnModificar" value="Modificar"/>
                    <input type="button" id="btnCancelar" value="Cancelar" />                    
                </div>
                <div id="divCodigo" class="oculto">
                <div id="pnlDatosGenerales">
<fieldset >
                    <legend>Datos generales</legend>
                        <table align="center">
                         	<tr>
                                <td >Codigo Postal</td>
                                <td >
                                    <input type="text" id="txtCodigoP"  />
                                    <div id="divCodigo"></div>
                                </td>
                               
                            </tr>   
                                <td >Colonia </td>
                                <td >
                                    <input type="text" id="txtColonia"  />
                                    <div id="divColonia"></div>
                                </td>
                               
                            </tr>                          
                                                         
                        </table>
                    </fieldset>
                </div>
                <div class="controles">
                    <input type="button" id="btnGuardar" value="Guardar" />
                    <input type="button" id="btnModificar" value="Modificar"/>
                    <input type="button" id="btnCancelar" value="Cancelar" />                    
                </div>
                </div>
                </div>
                <br />

                <br />
            </div>
        </div>
    </div>
    </form>
</body>
</html>
