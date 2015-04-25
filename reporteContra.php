<?php
/**
 * @author miguel
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
	<script type="text/javascript" src="scripts/ajaxRecibo.js"></script>
    <script type="text/javascript" src="scripts/prototype.js"></script>
    <script type="text/javascript" src="scripts/AjaxLib.js"></script>
    <script type="text/javascript" src="scripts/scriptaculous.js?load=builder,effects,controls"></script>
    <script type="text/javascript" src="scripts/window.js"> </script>
    <link href="themes/default.css" rel="stylesheet" type="text/css"/> <!-- Add this to have a specific theme--> <link href="themes/mac_os_x.css" rel="stylesheet" type="text/css"/> 
    <link rel="stylesheet" type="text/css" href="css/mtoForm.css" media="all"/>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>EWebFac - Pagos</title>
    <link href="estilos/ewebfac1.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="contenedor">
<?php
	if($menu!="")	
		require_once($menu);
?>
    <div id="derecho">
        <div class="cabecera">
            <div id="ruta">Cuentas por Cobrar | Contra Recibo<?php echo $etqRuta; ?>  </div>
            <h2 id="titulo">Contrarecibo</h2>
        </div><br />         
        <div class="izquierda">
            <span id="loading" style="display: none">Por favor espere...</span>
            <span id="aviso" style="display: none">cargando...</span>
            <span id="status"></span>
        </div>
    </div>  
    <br />
  	<form id="form2" name="form2" method="post">
		<fieldset>
            <legend>Datos Generales</legend>
            <table width="514">
                <tr>
                    <td width="107" align="right">No. Contrarecibo:</td>
                    <td width="144">
                        <input type="text" id="txtNumC" name="txtNumC" />
                   	 	<div id="autoContra"></div>
                     </td>
                     <td width="73" align="right">
                         <input type="button" id="btnBuscar" name="btnBuscar" value="Buscar" />
                     </td>
                     <td width="170">                      
                     </td>
                </tr>
                <tr>
                    <td width="107" align="right">No. Factura:</td>
                    <td width="144">
                        <input name="txtFactura" type="text" id="txtFactura"  />
                        <div id="autoFactura"></div>
                     </td>
                     <td width="73" align="right">
                         Importe:
                     </td>
                     <td width="170">
                        <input name="txtImporte" type="text" id="txtImporte"  />
                     </td>
                </tr>
                <tr>
                    <td align="right">Clave del Cliente:</td>
                    <td colspan="3">
                        <input name="txtCodigoCliente" type="text" id="txtCodigoCliente" size="60"  />
                        <div id="autoClienteC"></div>
                    </td>
                </tr>                            
                <tr>
                    <td align="right">Razon Social:</td>
                    <td colspan="3"> 
                        <input name="txtRazonSocial" type="text" id="txtRazonSocial" onkeyup="this.value=this.value.toUpperCase();" size="60" />  
                        <div id="autoClienteR"></div>                              
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td colspan="3">
                        <input type="button" name="btnAgregar" value="Agregar" id="btnAgregar" />
                        <input type="button" name="btnGuardar" value="Guardar" id="btnGuardar" />
                        <input type="button" name="btnModificar" value="Modificar" id="btnModificar" />
                        <input type="button" name="btnImprimir" value="Imprimir" id="btnImprimir" />
                        <input type="button" name="btnCancelar" value="Cancelar" id="btnCancelar" />
                    </td>
                </tr>
            </table>
	    </fieldset>
        <div class="ContraAgregados">
            <table width="417" class="gridView" id="tblFacturas">
                 <tr>
                    <th colspan="6" height="23" align="center">Facturas Agregadas</th>
              </tr>
                <tr>
                    <th width="70" align="center">No. Factura</th>
                    <th width="90" align="center">Fecha</th>
                    <th width="70" align="center">Importe</th>
                    <th width="70" align="center">Estado</th>
                    <th width="70" align="center">Eliminar</th>  
                </tr>
            </table>
   		</div>
        <div class="ContraHistoricos">
            <table class="gridView" id="datosHis">
                <tr>
                    <th colspan="4" height="20" align="center">Facturas Sin Pagar</th>
                </tr>
                <tr>
                    <th width="125" align="center">No. Factura</th>
                    <th width="125" align="center">Fecha</th>
                    <th width="125" align="center">Monto Neto</th>    
                    <th width="125" align="center">Saldo</th>    
                </tr>
            </table>
        </div>
  </form>
</div>
</body>
</html>
