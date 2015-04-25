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
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<script type="text/javascript" src="scripts/ajaxNotasC.js"></script>
<script type="text/javascript" src="scripts/prototype.js"></script>
<script type="text/javascript" src="scripts/AjaxLib.js"></script>
<script type="text/javascript" src="scripts/scriptaculous.js?load=builder,effects,controls"></script>
<script type="text/javascript" src="scripts/window.js"> </script>
<link href="themes/default.css" rel="stylesheet" type="text/css"/> 
<link rel="stylesheet" type="text/css" href="css/mtoForm.css" media="all"/>
<title>EWebFac - Notas de Cr&eacute;dito</title>
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
			<div id="ruta">Cuentas por Pagar | Notas de Cr&eacute;dito</div>
            <h2 id="titulo">Notas de Cr&eacute;dito</h2>
        </div><br />          
        <div class="izquierda">
            <span id="loading" style="display: none">Por favor espere...</span>
            <span id="aviso" style="display: none">Cargando...</span>
			<span id="status"></span>            
        </div>
     </div>  
     <br />
     <br />
     <form id= "form2" name="form2"  method="post">
         <fieldset id="fldNotas">
           <legend>Datos Generales</legend>
            <table>
	            <tr>
                	<td>&nbsp;</td>
                </tr>
                <tr>
                    <td align="right">No. Nota de Cr&eacute;dito:</td>
                    <td>
                    	<input type="text" id="txtNotaC" name="txtNotaC"/>
                        <div id="autoNota"></div>
                        <input type="button" id="btnBuscar" name="btnBuscar" value="Buscar"/>
                    </td>
                </tr>
                <tr>
                    <td align="right">Razon Social:</td>
                    <td>
                    	<input type="text" id="txtRazonS" name="txtRazonS" size="50" maxlength="50" class="mayuscula"/>
                        <div id="autoCliente"></div>
                    	<input type="hidden" name="hdncveCliente" id="hdncveCliente" />
                    </td>                    
                </tr>
                <tr>
                    <td align="right">Folio de la Factura:</td> 
                    <td>
                   	  <input type="text" id="txtFolioFactura" name="txtFolioFactura" size="10" />
                        <div id="autoFactura"></div>
                    </td>
                </tr>
                <tr>
                    <td align="right">Saldo:</td> 
                    <td>
 	                	<input type="hidden" name="hdnSaldo" id="hdnSaldo" />
                    	<input type="text" id="txtSaldo" name="txtSaldo" size="10" />
                    </td>                    
                </tr>
            </table>		
		</fieldset>
	    <fieldset id="fldNotasDes">
            <legend>Descripci&oacute;n</legend>
            <table>	
                <tr>
                	<td><textarea rows="10" id="txaFacturas" name="txaFacturas" cols="60"></textarea></td>
                </tr>
            </table>
        </fieldset> 
        <br />
        <br /> 
        <table width="493" align="lefth" >	 
            <tr>
                <td width="138" align="right">Importe:</td>
                <td width="355">
                	<input type="text" name="txtImporte" id="txtImporte" size="10" class="ahCentro"/>
                    <input type="hidden" name="txthImporteAnterior" id="txthImporteAnterior"/>
                    <input type="hidden" name="txthImporteDisponible" id="txthImporteDisponible" />
					<input type="button" id="btnCalcular" name="btnCalcular" value="Calcular"/>
				</td>                                                                                        
            </tr>
            <tr>
                <td align="right">Importe de Factura:</td>
                <td>
                	<input name="txtTotalBruto" type="text" id="txtTotalBruto" size="10" class="moneda"/>
	                <input type="hidden" name="hdnTotalBruto" id="hdnTotalBruto" />
                </td>
            </tr>              
           <tr>
                <td align="right">Iva:</td>
                <td>
                	<input type="text" name="txtIva" id="txtIva" size="10" class="moneda"/>
	                <input type="hidden" name="hdncveIva" id="hdncveIva"/>
                </td>
                                                    
            </tr>
            <tr>
                <td align="right">Retenci&oacute;n:</td>
                <td>
                	<input name="txtRetencion" type="text" id="txtRetencion" size="10" class="moneda"/>
	                <input type="hidden" name="hdncveRetencion" id="hdncveRetencion" />
                </td>
             </tr>
            <tr>
                <td align="right">Importe Total:</td>
                <td>
                	<input name="txtTotal" type="text" id="txtTotal" size="10" class="moneda"/>
	                <input type="hidden" name="hdnTotal" id="hdnTotal" />
                </td>
            </tr>           
           <tr>
                <td colspan="2" ><br /></td>                
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>
                  <input type="button" name="btnGuardar" id="btnGuardar" value="Guardar" />
                  <input type="button" name="btnModificar" id="btnModificar" value="Modificar" />                  
                  <input type="button" name="btnCancelar" id="btnCancelar" value="Cancelar" />
                </td>
            </tr>
        </table>
	</form>
</div>
</body>
</html>
