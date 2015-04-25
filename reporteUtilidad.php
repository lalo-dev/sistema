<?php

/**
 * @author Jose Miguel Pantaleon
 * @copyright 2010
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
<script type="text/javascript" src="scripts/ajaxReporteUtilidad.js"></script>
<script type="text/javascript" src="scripts/prototype.js"></script>
<script type="text/javascript" src="scripts/AjaxLib.js"></script>
<script type="text/javascript" src="jscripts/globalscripts.js"></script>
<script type="text/javascript" src="scripts/scriptaculous.js?load=builder,effects,controls"></script>
<script type="text/javascript" language="JavaScript" src="jscripts/calendar_es3.js"></script>
<link rel="stylesheet" href="estilos/calendar.css" type="text/css"/>
<link rel="stylesheet" type="text/css" href="css/mtoForm.css" media="all"/>    
<title>EWebFac - REPORTE DE UTILIDAD BRUTA</title>
</head>
<body>
 <div id="contenedor">
<?php
	if($menu!="")	
		require_once($menu);
?>
     <div id="derecho">
        <div class="cabecera">
            <div id="ruta">Reportes | Reporte de Utilidad Bruta</div>
            <h2 id="titulo">Reporte de Utilidad Bruta</h2>
        </div>    
        <br />          
        <div class="izquierda">
            <span id="loading" style="display: none">Por favor espere...</span>
            <span id="aviso" style="display: none">Cargando...</span>
            <span id="status"></span>
        </div>
        <br />
      </div>  
      <div id="pnlDatosGenerales">
	  	<form id="form2" action="consultaUtilidad.php" onsubmit="return chkForm();" target="_blank" method="post">
                <fieldset>
                  <legend>Par&aacute;metros de B&uacute;squeda</legend>
                    <table width="543" align="center">
                        <tr>
                            <td width="115" align="right">Cliente:</td>
                            <td width="2" ></td>                            
                            <td width="191"  align="left" colspan="2">
                            	<input type="text" id="txtCliente" name="txtCliente" value="Cliente" size="40" tabindex="1"/>
                                <div id="autoClienteC"></div>
                            </td>                           
                        </tr>                            
                        <tr>
                            <td width="115" align="right" >Destino:</td>
                            <td width="2" ></td>                            
                            <td width="191"  align="left">
                            	<select id="sltDestino" name="sltDestino" class="busqueda" tabindex="2">
                                	<option value="0">Elija un Destino</option>
                                </select>
                            </td>                           
                        </tr>   
                        <tr>
                            <td width="115" align="right" >Periodo:</td>
                            <td width="2" ></td>                            
                            <td width="191"  align="left"> 
                            	<input type="text"  id="txtPeriodoD" name="txtPeriodoD" value="Desde" tabindex="3"/>
                                <script type="text/javascript" language="JavaScript"> new tcal ({'controlname': 'txtPeriodoD'}); </script>
                            </td>
                            <td width="207"  align="left">
                            	<input type="text"  id="txtPeriodoH" name="txtPeriodoH" value="Hasta" tabindex="4"/>
                                <script type="text/javascript" language="JavaScript"> new tcal ({'controlname': 'txtPeriodoH'}); </script>
                            </td>                            
                        </tr>
                    </table>
                    <tr height="20">
                        <td></td>
                    </tr>
                </fieldset>
                <table width="590">
                	<tr>
                    <td align="right">
	                    <input type="button" id="btnLimpiar"  name="btnLimpiar" value= "Limpiar" tabindex="5" onclick="limpiar()"/>
	                    <input type="submit" id="btnGenerar"  name="btnGenerar" value= "Generar Reporte" tabindex="6" />
                      </td>
                  </tr>
	            </table>
		</form>  
      </div>
 </div>
</body>
</html>
