<?php

/**
 * @author Edgar Lopez Contreras
 * @copyright 2014
 */

require_once("direccionamiento.php");
if ((!isset($_SESSION["usuario_valido"]))||(($_SESSION["permiso"]!="Administrador") && ($_SESSION["permiso"]!="Usuario") && ($_SESSION["permiso"]!="Facturacion")))
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
<link href="estilos/ewebfac1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="scripts/ajaxBusquedas.js"></script>
<script type="text/javascript" src="scripts/prototype.js"></script>
<script type="text/javascript" src="scripts/AjaxLib.js"></script>
<script type="text/javascript" src="jscripts/globalscripts.js"></script>
<script type="text/javascript" src="scripts/scriptaculous.js?load=builder,effects,controls"></script>
<script type="text/javascript" language="JavaScript" src="jscripts/calendar_es3.js"></script>
<link rel="stylesheet" href="estilos/calendar.css" type="text/css"/>
<link rel="stylesheet" type="text/css" href="css/mtoForm.css" media="all"/>    
<title>EWebFac - B&Uacute;SQUEDA DE GU&Iacute;AS</title>
</head>
<body>
 <div id="contenedor">
<?php
	if($menu!="")	
		require_once($menu);
?>
     <div id="derecho">
        <div class="cabecera">
            <div id="ruta">Reportes | B&uacute;squeda de Gu&iacute;as</div>
            <h2 id="titulo">B&uacute;squeda de Gu&iacute;as</h2>
        </div>        <br />      
        <div class="izquierda">
            <span id="loading" style="display: none">Por favor espere...</span>
            <span id="aviso" style="display: none">Cargando...</span>
            <span id="status"></span>
        </div>
      </div>  <br />
      <div id="pnlDatosGenerales">
	<form id="form2" action="consultaTOTAL.php" onsubmit="return chkForm();" target="_blank" method="post">
                <fieldset>
                  <legend>Par&aacute;metros de B&uacute;squeda</legend>
                    <table width="543" align="center">
                        <tr>
                            <td width="115" align="right" >Gu&iacute;as:</td>
                            <td width="2" ></td>
                            <td width="191"  align="left">
                            	<input type="text" id="txtGuiaD" name="txtGuiaD" value="Desde" />
                            </td>
                            <td width="207"  align="left">
                            	<input type="text" id="txtGuiaH" name="txtGuiaH" value="Hasta"/>
                            </td>                            
                        </tr>                            
                        <tr>
                            <td width="115" align="right">Cliente:</td>
                            <td width="2" ></td>                            
                            <td width="191"  align="left">
                            	<input type="text" id="txtClienteD" name="txtClienteD" value="Desde"/>
                                <div id="autoClienteC"></div>
                            </td>
                            <td width="207"  align="left">
                            	<input type="text" id="txtClienteH" name="txtClienteH" value="Hasta"/>
                                <div id="autoClienteC2"></div>
                            </td>                            
                        </tr>                            
                        <tr>
                            <td width="115" align="right" >Destino:</td>
                            <td width="2" ></td>                            
                            <td width="191"  align="left">
                            	<select id="sltDestinoD" name="sltDestinoD" class="busqueda">
                                	<option value="0">Desde</option>
                                </select>
                            </td>
                            <td width="207"  align="left">
	                            <select id="sltDestinoH" name="sltDestinoH" class="busqueda">
                                	<option value="0">Hasta</option>
                                </select>
                            </td>                            




                        </tr>                            
                        <tr>
                            <td width="115" align="right">Ciudad:</td>
                            <td width="2" ></td>                            
                            <td width="191"  align="left">
                            	<input type="text" id="txtMunicipioD" name="txtMunicipioD" value="Desde"/>
                                <div id="autoMunicipioC"></div>
                            </td>
                            <td width="207"  align="left">
                            	<input type="text" id="txtMunicipioH" name="txtMunicipioH" value="Hasta"/>
                                <div id="autoMunicipioC2"></div>
                            </td>                            



 </tr>                            
                        <tr>


                            <td width="115" align="right">Estado:</td>
                            <td width="2" ></td>                            
                            <td width="191"  align="left">
                            	<input type="text" id="txtEstadoD" name="txtEstadoD" value="Desde"/>
                                <div id="autoEstadoC"></div>
                            </td>
                            <td width="207"  align="left">
                            	<input type="text" id="txtEstadoH" name="txtEstadoH" value="Hasta"/>
                                <div id="autoEstadoC2"></div>
                            </td>                            








                        </tr>                            
                        <tr>
                            <td width="115" align="right" >Destinatario:</td>
                            <td width="2" ></td>                            
                            <td width="191"  align="left">
                            	<input type="text"  id="txtDestinatarioD" name="txtDestinatarioD" value="Desde"/>
				<div id="autoDestinatarioD"></div>
                            </td>
                            <td width="207"  align="left">
                            	<input type="text"  id="txtDestinatarioH" name="txtDestinatarioH" value="Hasta"//>
				<div id="autoDestinatarioH"></div>
                            </td>                            
                        </tr>                            
                        <tr>
                            <td width="115" align="right" >Status:</td>
                            <td width="2" ></td>                            
                            <td width="191"  align="left">
								<select id="sltStatusD" name="sltStatusD" class="busqueda">
                                	<option value="0" class="bg1">Desde</option>
                                </select>
                            </td>
                            <td width="207"  align="left">
								<select id="sltStatusH" name="sltStatusH" class="busqueda">
                                	<option value="0">Hasta</option>
                                </select>
                            </td>                            
                        </tr>   
                        <tr>
                            <td width="115" align="right" >Recepci&oacute;n CyE:</td>
                            <td width="2" ></td>                            
                            <td width="191"  align="left"> 
                            	<input type="text"  id="txtRecepcionD" name="txtRecepcionD" value="Desde"/>
                                <script type="text/javascript" language="JavaScript"> new tcal ({'controlname': 'txtRecepcionD'}); </script>
                            </td>
                            <td width="207"  align="left">
                            	<input type="text"  id="txtRecepcionH" name="txtRecepcionH" value="Hasta"//>
                                <script type="text/javascript" language="JavaScript"> new tcal ({'controlname': 'txtRecepcionH'}); </script>
                            </td>                            
                        </tr>
                        <tr>
                            <td width="115" align="right" >Fecha de Entrega:</td>
                            <td width="2" ></td>                            
                            <td width="191"  align="left">
                            	<input type="text"  id="txtFechaEntregaD" name="txtFechaEntregaD" value="Desde"/>
								<script type="text/javascript" language="JavaScript"> new tcal ({'controlname': 'txtFechaEntregaD'}); </script>                                
                            </td>
                            <td width="207"  align="left">
                            	<input type="text"  id="txtFechaEntregaH" name="txtFechaEntregaH" value="Hasta"//>
								<script type="text/javascript" language="JavaScript"> new tcal ({'controlname': 'txtFechaEntregaH'}); </script>                                
                            </td>                            
                        </tr>                                                                         
                    </table>
 			<tr height="20">
                        	<td></td>
                        </tr>
                        <tr>
                            <td width="115" align="right" >Elija Reporte:</td>
                            <td width="2" ></td>                            
                            <td align="left" colspan="2">
								<input type="radio" id="rdbtnFactura1" name="rdbtnFactura" value="0" checked="checked"/>Reporte General
   								<input type="radio" id="rdbtnFactura2" name="rdbtnFactura" value="1"/>Reporte para Factura  
                            </td>                           
                        </tr>                                                                                               
                    </table>
                </fieldset>
                <table width="590">
                	<tr>
                    <td align="right">
	                    <input type="button" id="btnLimpiar"  name="btnLimpiar" value= "Limpiar" tabindex="15" onclick="limpiar()"/>
	                    <input type="submit" id="btnBuscar"  name="btnBuscar" value= "Buscar Gu&iacute;as" tabindex="16" />
                      </td>
                  </tr>
	            </table>
 
		</form>  
      </div>
 </div>
</body>
</html>
