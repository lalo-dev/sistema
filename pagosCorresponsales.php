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
$anyo=date('Y');
$anyoInicio=$anyo-15;
$anyoFin=$anyo+15;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script type="text/javascript" src="scripts/ajaxPCorresponsales.js"></script>
<script type="text/javascript" src="scripts/prototype.js"></script>
<script type="text/javascript" src="scripts/AjaxLib.js"></script>
<script type="text/javascript" src="scripts/scriptaculous.js?load=builder,effects,controls"></script>
<script type="text/javascript" src="scripts/window.js"> </script>
<link href="themes/default.css" rel="stylesheet" type="text/css"/> <!-- Add this to have a specific theme--> <link href="themes/mac_os_x.css" rel="stylesheet" type="text/css"/> 
<link rel="stylesheet" type="text/css" href="css/mtoForm.css" media="all"/>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>EWebFac - Pagos a Corresponsales</title>
<link rel="stylesheet" href="estilos/calendar.css" type="text/css"/>
<script type="text/javascript" language="JavaScript" src="jscripts/calendar_es.js"></script>
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
                <div id="ruta">Cuentas por Pagar | Facturas Corresponsales</div>
                <h2 id="titulo">Facturas Corresponsales</h2>    
            </div>   
            <br />           
            <div class="izquierda">
                <span id="loading" style="display: none">Por favor espere...</span>
                <span id="aviso" style="display: none">Cargando...</span>
                <span id="status"></span>
            </div>
        </div><br /><br />
   	  	<div id="pnlDatosGenerales"> 
            <form id= "form2" name="form2" >            
           	  <fieldset id="derecho" >
               	<legend>Datos Generales</legend>
                  <table width="468">
                      <tr>
                          <td>A&ntilde;o de Factura:</td>
                          <td>
                            <input type="hidden" id="txthAnyo" name="txthAnyo" />
                          	<select id="sltAnyo" name="sltAnyo">
							<?php for($i=$anyoInicio;$i<=$anyoFin;$i++){
									if($i==$anyo)
									    echo "<option value='".$i."' selected='selected'>".$i."</option>";
									else
	                                    echo "<option value='".$i."'>".$i."</option>";
                                  }
							?>
                            </select>
                          </td>
                      </tr>                   
                      <tr>
                       	  <td width="121">No. Proveedor</td>
                          <td width="335">
                              <input type="text" id="txtCodigo" name="txtCodigo" size="50" maxlength="50" class="mayuscula" />
                              <div id="autoClienteC"></div>
                          </td>
                      </tr>
                      <tr>
                          <td>Raz&oacute;n Social</td>
                          <td>
                              <input type="text" id="txtRazonS" name="txtRazonS" size="50" maxlength="50" class="mayuscula" />
                              <div id="autoCliente"></div>
                              <input type="hidden"  name="txtPorcentajeR" id="txtPorcentajeR" size="10" />            
                          </td>            
                      </tr>                     
                      <tr>
                          <td>Folio de la Factura</td> 
                          <td>
                              <input type="hidden" id="txthFolioFactura" name="txthFolioFactura" />
							  <input type="text" id="txtFolioFactura" name="txtFolioFactura" size="10" onclick = "value = ''"/>		                              
                              <div id="autoFactura"></div>                            
                          </td>
                      </tr>
                      <tr>
                          <td>Fecha de la Factura</td> 
                          <td>
                              <input type="text" id="txtfechaFactura" name="txtfechaFactura" class="calendar" size="12" />
                              <script type="text/javascript" language="JavaScript"> new tcal ({'controlname': 'txtfechaFactura'}); </script>
                          </td>
                      </tr> 
                      <tr>
                          <td>I.V.A</td> 
                          <td>
                              <input type="text" id="txtPorIva" name="txtPorIva" size="10" onchange="calculaTotalesPre(this,0);" onfocus='guardaAnterior(this)' />%
                          </td>
                      </tr> 
                      <tr>
                          <td>Retenci&oacute;n</td> 
                          <td>
                              <input type="text" id="txtPorRetencion" name="txtPorRetencion" size="10" onchange="calculaTotalesPre(this,0);" onfocus='guardaAnterior(this)' />%
                          </td>
                      </tr>   
                      <tr>
                          <td>Gu&iacute;a</td> 
                          <td>
                              <input type="text" id="txtGuia" name="txtGuia" size="10" />
                              <div id="autoGuia"></div>
                          </td>
                      </tr>                                                                                               
                  </table>		
                </fieldset>
                <div id="divGral">
                    <div id="divGuias">
                    </div>
                    <div id="divFac">
                    </div> 
                    <div id="divEstacionGuia">
                    </div>                
                </div>                
            </form>
            <br />
        	<fieldset id="completo">
            	<legend>Guias</legend>
                <table id="guiasCorresponsal" class="gridView">	
                    <tr>
                        <th>Gu&iacute;a</th>
                        <th>Tipo de Entrega</th>
                        <th>Piezas</th>
                        <th>Kilos</th>
                        <th>Tarifa</th>
                        <th>Costo de Entrega</th>
                        <th>Sobrepeso</th>
                        <th>Costo Sobrepeso</th>
                        <th>Distancia</th>
                        <th>Costo Distancia</th>
                        <th>Costo Especial</th>
                        <th>Vi&aacute;ticos</th>
                        <th>Gu&iacute;a A&eacute;rea</th>
                        <th>Extra 1</th>
                        <th>Extra 2</th>
                        <th>Observaciones</th>
                        <th>Total Costo</th>
                        <th>Eliminar</th>                    
                    </tr>
                </table>
        	</fieldset> 
	        <br/><br/>
            <table width="500" align="left" >	 
                <tr>
                    <td width="22"></td>
                    <td width="124">Importe</td>
                    <td width="354">
                        <input type="text" name="txtImporte" id="txtImporte" size="10" class='moneda' onfocus='guardaAnterior(this)' onchange="calculaTotalesPre(this,1)"/>
                    </td>    
                </tr>
                <tr>
	                <td></td>
                    <td>Iva</td>
                    <td>
                        <input type="text" name="txtIva" id="txtIva" size="10" class='moneda' onfocus='guardaAnterior(this)' onchange="calculaTotalesPre(this,1)"/>
                    </td>    
                </tr>
                <tr>
                    <td></td>
                    <td>Retenci&oacute;n</td>
                    <td>
                        <input name="txtRetencion" id="txtRetencion" type="text" size="10" class='moneda' onfocus='guardaAnterior(this)' onchange="calculaTotalesPre(this,1)"/>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>Importe Total</td>
                    <td>
                        <input name="txtTotal" id="txtTotal" type="text" size="10" class='moneda'/>
                    </td>
                </tr>
                <tr>
                	<td colspan="3">&nbsp;</td>
                </tr>
                <tr align="center">
                	<td></td>
                    <td colspan="2" align="left">
                    	<input type="button" value="Imprimir" id="btnImprimir" name="btnImprimir" />
                    	<input type="button" value="Ver Factura" id="btnVerFactura" name="btnVerFactura" />
                        <input type="button" value="Guardar" id="btnGuardar" name="btnGuardar" />
                        <input type="button" value="Modificar" id="btnModificar" name="btnModificar" />
                        <input type="button" value="Cancelar" id="btnCancelar" name="btnCancelar" />
                    </td>
                </tr>										
            </table>
    </div>
</div>
</body>
</html>
