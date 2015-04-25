<?php

/**
 * @author Jose Miguel Pantaleon
 * @copyright 2010
 */
 
require_once("direccionamiento.php");
if ((!isset($_SESSION["usuario_valido"]))||(($_SESSION["permiso"]!="Administrador") && ($_SESSION["permiso"]!="Usuario")))
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
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link href="estilos/ewebfac1.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="scripts/ajaxReporte.js"></script>
    <script type="text/javascript" src="scripts/prototype.js"></script>
    <script type="text/javascript" src="scripts/AjaxLib.js"></script>
    <link rel="stylesheet" type="text/css" href="css/mtoForm.css" media="all"/>
    <link rel="stylesheet" href="estilos/calendar.css" type="text/css"/>
    <script type="text/javascript" language="JavaScript" src="jscripts/calendar_es.js"></script>
    <!--Para abrir el reporte-->
    <script type="text/javascript" src="scripts/window.js"> </script>
    <link href="themes/default.css" rel="stylesheet" type="text/css"/> 
    <!-- Add this to have a specific theme--> 
    <link href="themes/mac_os_x.css" rel="stylesheet" type="text/css"/> 
    <title>EWebFac - Reporte de Ventas</title>
</head>
<body>
<div id='contenedor'>
<?php
	if($menu!="")	
		require_once($menu);
?>

	<div id="derecho">
        <div class="cabecera">
            <div id="ruta">Reportes | Ventas por Destino</div>
            <h2 id="titulo">Ventas por Destino</h2>
        </div>
        <br />
        <form id="form2">
        	<input type="hidden" id="txthAccion" name="txthAccion" value="1" />
            <div class="izquierda">
                <span id="loading" style="display: none">Por favor espere...</span>
                <span id="aviso" style="display: none">Cargando...</span>
                <span id="status"></span>
            </div>
	   <br />
            <div id="pnlDatosGenerales">
                <fieldset>
                    <legend>Datos Generales</legend>
                    <table>
                        <tr>
                            <td>Fecha Inicial</td>
                            <td>
                                <input name="txtFechaI" type="text" id="txtFechaI" class="calendar" size="10" />
                                <script type="text/javascript" language="JavaScript"> new tcal ({'controlname': 'txtFechaI'}); </script>
                            </td>
                        </tr>
                        <tr>
                            <td>Fecha Final</td>
                            <td>
                                <input name="txtFechaF" type="text" id="txtFechaF" class="calendar" size="10" />
                                <script type="text/javascript" language="JavaScript"> new tcal ({'controlname': 'txtFechaF'}); </script>
                            </td>
                        </tr>
                        <tr>
                            <td><input type="button" id="btnEnviar" value="Generar Reporte" onclick="imprimir();"/></td>
                        </tr>
                    </table>        
                </fieldset>
            </div>        
        </form>
     </div> </div>
</body>
</html>
