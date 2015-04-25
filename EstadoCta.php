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
$dato=$_GET['dato'];
if($dato=="cliente")
{
	$etqNombre="Cliente";
}else{$etqNombre="Corresponsal";}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<script type="text/javascript" src="scripts/ajaxEstadoCta.js"></script>
    <script type="text/javascript" src="scripts/prototype.js"></script>
    <script type="text/javascript" src="scripts/AjaxLib.js"></script>
    <script type="text/javascript" src="scripts/scriptaculous.js?load=builder,effects,controls"></script>
    <script type="text/javascript" src="scripts/window.js"> </script>
    <link href="themes/default.css" rel="stylesheet" type="text/css"/> 
    <!-- Add this to have a specific theme--> 
    <link href="themes/mac_os_x.css" rel="stylesheet" type="text/css"/> 
    <link rel="stylesheet" type="text/css" href="css/mtoForm.css" media="all"/>
    <link href="estilos/ewebfac1.css" rel="stylesheet" type="text/css" />
    <!--Calendario-->
    <link rel="stylesheet" href="estilos/calendar.css" type="text/css"/>
    <script type="text/javascript" language="JavaScript" src="jscripts/calendar_es.js"></script>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Soporte de Factura</title>
</head>

<body> 
	<div id="contenedor">
		<?php
            if($menu!="")	
                require_once($menu);
        ?>
        <form id= "form2" name="form2" action="reporteprueva.php" method="post">
            <div id="completo">
                <div class="cabecera">
                    <div id="ruta">Cuentas por Cobrar | Estado de Cuenta</div>
                    <h2 id="titulo">Estado de Cuenta</h2>
                </div><br />
                <span id="loading" style="display: none">Por favor espere...</span>
                <span id="aviso" style="display: none">aver aque horas...</span>
                <span id="status"></span>
                <br />
                <br />
                <fieldset>
                  <legend>Datos Generales</legend>
                    <table width="563">
                        <tr>
                            <td width="279" align="right">Codigo del <?php echo $etqNombre; ?>:</td>
                            <td colspan="3" >
                                <input name="txtCodigo" type="text" id="txtCodigo" size="60" />
                                <input type="hidden" name="hdnTabla" id="hdnTabla" value="<?php echo $dato; ?>" />
                                <div id="autoCodigo"></div>
                            </td>
                        </tr>
                        <tr>
                            <td align="right">Razon Soncial:</td>
                            <td colspan="3"  > 
                                <input name="txtRazonSocial" type="text" id="txtRazonSocial" class="mayuscula" size="60"/>  
                                <div id="autoRazon"></div>                                  
                            </td>
                        </tr>
                        <tr>
                            <td align="right">Vigencia del</td>
                            <td width="140">
                                <input name="txtFecha1" id="txtFecha1" type="text" class="input100"/>
                                <script type="text/javascript" language="JavaScript"> new tcal ({'controlname': 'txtFecha1'}); </script>
                            </td>                    
                            <td width="320">al&nbsp;&nbsp;
                                <input name="txtFecha2" id="txtFecha2" type="text" class="input100"/>
                                <script type="text/javascript" language="JavaScript"> new tcal ({'controlname': 'txtFecha2'}); </script>
                            </td>
                        </tr>
                    </table>
                </fieldset>
                <div class="controles">
                    <input type="button" name="btnBuscar" id="btnBuscar" value="Buscar" />
                    <input type="button" name="btnImprimir" id="btnImprimir" value="Imprimir" />
                    <input type="button" name="btnCancelar" id="btnCancelar" value="Cancelar" />
                </div> 
            </div>
        </form>
        <div id="dvdDatos" class="oculto">
            <table width="700">
                <tr>
                    <td colspan="2" align="right" >Saldo al
                        <span id="lblFecha1" ></span>
                        <span id="lblSaldo1" ></span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="background-color:#0066FF; height:2px"></td>
                </tr>
                <tr>
                	<td></td>
                </tr>
                <tr>
                    <td>
                        <div>
                            <table class="gridView" id="gvwDocumentos" width="700" >
                                <tr>
                                    <th >Fecha</th>
                                    <th >Documento</th>
                                    <th >Tipo de Documento</th>
                                    <th >Referencia</th>
                                    <th >Cargos</th><th >Abonos</th>
                                    <th >Saldo</th>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>                
                <tr>
               		<td colspan="2" style="background-color:#0066FF; height:2px"></td>
                </tr>
                <tr>
                    <td colspan="2" align="right">Saldo al 
                        <span id="lblFecha2" align="rigth" ></span>
                        <span id="lblSaldo2" align="rigth" ></span>
                    </td>
                </tr>
            </table>
		</div>		
	</div>
</body>
</html>
